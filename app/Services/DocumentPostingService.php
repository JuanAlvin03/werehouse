<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentItem;
use App\Models\Inventory;
use App\Models\StockMovement;
use App\Models\WarehouseLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DocumentPostingService
{
    /**
     * Post a document: validate, create stock movements, and update inventory.
     */
    public function post(Document $document): bool
    {
        return DB::transaction(function () use ($document) {
            // Validate document can be posted
            if (!$document->isDraft()) {
                throw new \Exception("Document {$document->document_no} is not in DRAFT status");
            }

            // Validate document has items
            if ($document->items()->count() === 0) {
                throw new \Exception("Document {$document->document_no} has no items");
            }

            // Validate based on document type
            $this->validateDocumentByType($document);

            // Create stock movements and update inventory
            $this->processStockMovements($document);

            // Update document status
            $document->update([
                'status' => Document::STATUS_POSTED,
                'posted_by' => auth()->id(),
                'posted_at' => now(),
            ]);

            Log::info("Document posted successfully", [
                'document_id' => $document->id,
                'document_no' => $document->document_no,
                'posted_by' => auth()->id(),
            ]);

            return true;
        });
    }

    /**
     * Validate document before posting.
     */
    private function validateDocumentByType(Document $document): void
    {
        $type = $document->document_type;

        // Basic validations for all types
        if (!$document->warehouse_id && !$document->isTransfer()) {
            throw new \Exception("Warehouse is required for {$type} documents");
        }

        if ($document->isTransfer()) {
            if (!$document->from_warehouse_id || !$document->to_warehouse_id) {
                throw new \Exception("Both source and destination warehouses are required for TRANSFER documents");
            }

            if ($document->from_warehouse_id === $document->to_warehouse_id) {
                throw new \Exception("Source and destination warehouses cannot be the same");
            }
        }

        // Validate products and quantities
        foreach ($document->items as $item) {
            if (!$item->product || !$item->product->is_active) {
                throw new \Exception("Product not found or inactive for item in {$document->document_no}");
            }

            if ($item->quantity <= 0) {
                throw new \Exception("Quantity must be greater than 0 for item in {$document->document_no}");
            }
        }

        // Type-specific validation
        match ($type) {
            Document::TYPE_RECEIPT => $this->validateReceipt($document),
            Document::TYPE_ISSUE => $this->validateIssue($document),
            Document::TYPE_ADJUSTMENT => $this->validateAdjustment($document),
            Document::TYPE_CUSTOMER_RETURN => $this->validateCustomerReturn($document),
            Document::TYPE_SUPPLIER_RETURN => $this->validateSupplierReturn($document),
            Document::TYPE_TRANSFER => $this->validateTransfer($document),
            default => throw new \Exception("Unknown document type: {$type}"),
        };
    }

    private function validateReceipt(Document $document): void
    {
        // Receipt can have optional supplier
        if ($document->business_partner_id) {
            $partner = $document->businessPartner;
            if (!$partner || !$partner->is_active) {
                throw new \Exception("Invalid or inactive supplier");
            }
        }
    }

    private function validateIssue(Document $document): void
    {
        // Check if warehouse has sufficient stock for all items
        $warehouseId = $document->warehouse_id;

        foreach ($document->items as $item) {
            $onHand = Inventory::where('warehouse_id', $warehouseId)
                ->where('product_id', $item->product_id)
                ->sum('quantity_on_hand');

            if ($onHand < $item->quantity) {
                throw new \Exception(
                    "Insufficient stock for {$item->product->sku}. Available: {$onHand}, Requested: {$item->quantity}"
                );
            }
        }
    }

    private function validateAdjustment(Document $document): void
    {
        // Adjustment must have a reason document
        if (!$document->notes) {
            throw new \Exception("Adjustment reason is required");
        }
    }

    private function validateCustomerReturn(Document $document): void
    {
        // Customer return can have optional customer
        if ($document->business_partner_id) {
            $partner = $document->businessPartner;
            if (!$partner || !$partner->is_active) {
                throw new \Exception("Invalid or inactive customer");
            }
        }
    }

    private function validateSupplierReturn(Document $document): void
    {
        // Supplier return can have optional supplier
        if ($document->business_partner_id) {
            $partner = $document->businessPartner;
            if (!$partner || !$partner->is_active) {
                throw new \Exception("Invalid or inactive supplier");
            }
        }

        // Validate sufficient stock for return
        $warehouseId = $document->warehouse_id;

        foreach ($document->items as $item) {
            $onHand = Inventory::where('warehouse_id', $warehouseId)
                ->where('product_id', $item->product_id)
                ->sum('quantity_on_hand');

            if ($onHand < $item->quantity) {
                throw new \Exception(
                    "Insufficient stock for {$item->product->sku} to return. Available: {$onHand}, Return quantity: {$item->quantity}"
                );
            }
        }
    }

    private function validateTransfer(Document $document): void
    {
        // Check if source warehouse has sufficient stock
        $sourceWarehouseId = $document->from_warehouse_id;

        foreach ($document->items as $item) {
            $onHand = Inventory::where('warehouse_id', $sourceWarehouseId)
                ->where('product_id', $item->product_id)
                ->sum('quantity_on_hand');

            if ($onHand < $item->quantity) {
                throw new \Exception(
                    "Insufficient stock at source warehouse for {$item->product->sku}. Available: {$onHand}, Transfer quantity: {$item->quantity}"
                );
            }
        }
    }

    /**
     * Create stock movements and update inventory.
     */
    private function processStockMovements(Document $document): void
    {
        $type = $document->document_type;

        foreach ($document->items as $item) {
            match ($type) {
                Document::TYPE_RECEIPT => $this->processReceipt($document, $item),
                Document::TYPE_ISSUE => $this->processIssue($document, $item),
                Document::TYPE_ADJUSTMENT => $this->processAdjustment($document, $item),
                Document::TYPE_CUSTOMER_RETURN => $this->processCustomerReturn($document, $item),
                Document::TYPE_SUPPLIER_RETURN => $this->processSupplierReturn($document, $item),
                Document::TYPE_TRANSFER => $this->processTransfer($document, $item),
            };
        }
    }

    private function processReceipt(Document $document, DocumentItem $item): void
    {
        $warehouseId = $document->warehouse_id;
        $locationId = $this->getDefaultLocation($warehouseId)->id ?? null;

        // Create incoming movement
        StockMovement::create([
            'document_id' => $document->id,
            'document_item_id' => $item->id,
            'warehouse_id' => $warehouseId,
            'location_id' => $locationId,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'movement_date' => now(),
        ]);

        // Update inventory
        $this->updateInventory($warehouseId, $locationId, $item->product_id, $item->quantity);
    }

    private function processIssue(Document $document, DocumentItem $item): void
    {
        $warehouseId = $document->warehouse_id;
        $locationId = $this->getDefaultLocation($warehouseId)->id ?? null;

        // Create outgoing movement
        StockMovement::create([
            'document_id' => $document->id,
            'document_item_id' => $item->id,
            'warehouse_id' => $warehouseId,
            'location_id' => $locationId,
            'product_id' => $item->product_id,
            'quantity' => -$item->quantity,
            'movement_date' => now(),
        ]);

        // Update inventory
        $this->updateInventory($warehouseId, $locationId, $item->product_id, -$item->quantity);
    }

    private function processAdjustment(Document $document, DocumentItem $item): void
    {
        $warehouseId = $document->warehouse_id;
        $locationId = $this->getDefaultLocation($warehouseId)->id ?? null;

        // Determine direction from notes or assume decrease
        // For now, we'll parse from the document structure
        // The controller/form will need to specify increase/decrease
        $direction = $document->notes; // This should be parsed differently

        // For adjustment, we'll use the sign to determine direction
        // This will be refined in the controller
        $quantity = $item->quantity; // This should be signed in the request

        // Create movement
        StockMovement::create([
            'document_id' => $document->id,
            'document_item_id' => $item->id,
            'warehouse_id' => $warehouseId,
            'location_id' => $locationId,
            'product_id' => $item->product_id,
            'quantity' => $quantity,
            'movement_date' => now(),
        ]);

        // Update inventory
        $this->updateInventory($warehouseId, $locationId, $item->product_id, $quantity);
    }

    private function processCustomerReturn(Document $document, DocumentItem $item): void
    {
        $warehouseId = $document->warehouse_id;
        $locationId = $this->getDefaultLocation($warehouseId)->id ?? null;

        // Create incoming movement (positive)
        StockMovement::create([
            'document_id' => $document->id,
            'document_item_id' => $item->id,
            'warehouse_id' => $warehouseId,
            'location_id' => $locationId,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'movement_date' => now(),
        ]);

        // Update inventory
        $this->updateInventory($warehouseId, $locationId, $item->product_id, $item->quantity);
    }

    private function processSupplierReturn(Document $document, DocumentItem $item): void
    {
        $warehouseId = $document->warehouse_id;
        $locationId = $this->getDefaultLocation($warehouseId)->id ?? null;

        // Create outgoing movement (negative)
        StockMovement::create([
            'document_id' => $document->id,
            'document_item_id' => $item->id,
            'warehouse_id' => $warehouseId,
            'location_id' => $locationId,
            'product_id' => $item->product_id,
            'quantity' => -$item->quantity,
            'movement_date' => now(),
        ]);

        // Update inventory
        $this->updateInventory($warehouseId, $locationId, $item->product_id, -$item->quantity);
    }

    private function processTransfer(Document $document, DocumentItem $item): void
    {
        $sourceWarehouseId = $document->from_warehouse_id;
        $destWarehouseId = $document->to_warehouse_id;
        $sourceLocationId = $this->getDefaultLocation($sourceWarehouseId)->id ?? null;
        $destLocationId = $this->getDefaultLocation($destWarehouseId)->id ?? null;

        // Create outgoing movement at source
        StockMovement::create([
            'document_id' => $document->id,
            'document_item_id' => $item->id,
            'warehouse_id' => $sourceWarehouseId,
            'location_id' => $sourceLocationId,
            'product_id' => $item->product_id,
            'quantity' => -$item->quantity,
            'movement_date' => now(),
        ]);

        // Create incoming movement at destination
        StockMovement::create([
            'document_id' => $document->id,
            'document_item_id' => $item->id,
            'warehouse_id' => $destWarehouseId,
            'location_id' => $destLocationId,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'movement_date' => now(),
        ]);

        // Update inventory at both locations
        $this->updateInventory($sourceWarehouseId, $sourceLocationId, $item->product_id, -$item->quantity);
        $this->updateInventory($destWarehouseId, $destLocationId, $item->product_id, $item->quantity);
    }

    /**
     * Update or create inventory record.
     */
    private function updateInventory($warehouseId, $locationId, $productId, $quantityChange): void
    {
        $inventory = Inventory::where('warehouse_id', $warehouseId)
            ->where('location_id', $locationId)
            ->where('product_id', $productId)
            ->first();

        if ($inventory) {
            $inventory->update([
                'quantity_on_hand' => $inventory->quantity_on_hand + $quantityChange,
            ]);
        } else {
            Inventory::create([
                'warehouse_id' => $warehouseId,
                'location_id' => $locationId,
                'product_id' => $productId,
                'quantity_on_hand' => max(0, $quantityChange),
                'quantity_reserved' => 0,
            ]);
        }
    }

    /**
     * Get or create default location for a warehouse.
     */
    private function getDefaultLocation($warehouseId)
    {
        return WarehouseLocation::where('warehouse_id', $warehouseId)
            ->where('code', 'DEFAULT')
            ->first() ?? WarehouseLocation::create([
            'warehouse_id' => $warehouseId,
            'code' => 'DEFAULT',
            'name' => 'Default Location',
            'is_active' => true,
        ]);
    }

    /**
     * Cancel a posted document with reversal.
     */
    public function cancel(Document $document): bool
    {
        return DB::transaction(function () use ($document) {
            if (!$document->isPosted()) {
                throw new \Exception("Only posted documents can be cancelled");
            }

            // Create reversal document
            $reversal = Document::create([
                'document_no' => 'REV-' . $document->document_no,
                'document_type' => $document->document_type,
                'status' => Document::STATUS_POSTED,
                'document_date' => now()->toDateString(),
                'warehouse_id' => $document->warehouse_id,
                'from_warehouse_id' => $document->from_warehouse_id,
                'to_warehouse_id' => $document->to_warehouse_id,
                'business_partner_id' => $document->business_partner_id,
                'notes' => "Reversal of {$document->document_no}",
                'created_by' => auth()->id(),
                'posted_by' => auth()->id(),
                'posted_at' => now(),
            ]);

            // Copy items and reverse quantities
            foreach ($document->items as $item) {
                $reversalItem = DocumentItem::create([
                    'document_id' => $reversal->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_id' => $item->unit_id,
                    'notes' => "Reversal of original item",
                ]);
            }

            // Create reversed stock movements and update inventory
            $this->processStockMovements($reversal);

            // Update original document status
            $document->update([
                'status' => Document::STATUS_CANCELLED,
                'cancelled_at' => now(),
            ]);

            Log::info("Document cancelled with reversal", [
                'original_document' => $document->document_no,
                'reversal_document' => $reversal->document_no,
                'cancelled_by' => auth()->id(),
            ]);

            return true;
        });
    }
}
