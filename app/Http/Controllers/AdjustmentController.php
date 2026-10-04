<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\StockAdjustmentReason;
use App\Http\Requests\StoreAdjustmentRequest;
use App\Http\Requests\UpdateAdjustmentRequest;
use App\Services\DocumentNumberGenerator;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdjustmentController extends Controller
{
    public function index(): View
    {
        $documents = Document::byType(Document::TYPE_ADJUSTMENT)
            ->with('warehouse', 'createdBy')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('adjustments.index', [
            'documents' => $documents,
        ]);
    }

    public function create(): View
    {
        $warehouses = Warehouse::active()->get();
        $reasons = StockAdjustmentReason::active()->get();
        $products = Product::active()->get();

        return view('adjustments.create', [
            'warehouses' => $warehouses,
            'reasons' => $reasons,
            'products' => $products,
            'documentNo' => DocumentNumberGenerator::generate(Document::TYPE_ADJUSTMENT),
        ]);
    }

    public function store(StoreAdjustmentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['document_no'] = DocumentNumberGenerator::generate(Document::TYPE_ADJUSTMENT);
        $data['document_type'] = Document::TYPE_ADJUSTMENT;
        $data['status'] = Document::STATUS_DRAFT;
        $data['document_date'] = now()->toDateString();
        $data['created_by'] = auth()->id();

        $document = Document::create($data);

        // Create document items with signed quantity based on direction
        foreach ($request->items as $item) {
            $quantity = $item['quantity'];
            
            // Sign the quantity based on direction in item
            if (isset($item['direction']) && $item['direction'] === 'DECREASE') {
                $quantity = -abs($quantity);
            } else {
                $quantity = abs($quantity);
            }

            $document->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => abs($item['quantity']),
                'unit_id' => $item['unit_id'],
                'notes' => $item['notes'] ?? null,
            ]);
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Stock adjustment created successfully.');
    }

    public function edit(Document $document): View
    {
        if ($document->document_type !== Document::TYPE_ADJUSTMENT || !$document->isDraft()) {
            abort(403, 'Cannot edit non-draft adjustment documents.');
        }

        $warehouses = Warehouse::active()->get();
        $reasons = StockAdjustmentReason::active()->get();
        $products = Product::active()->get();

        return view('adjustments.edit', [
            'document' => $document->load('items'),
            'warehouses' => $warehouses,
            'reasons' => $reasons,
            'products' => $products,
        ]);
    }

    public function update(UpdateAdjustmentRequest $request, Document $document): RedirectResponse
    {
        if ($document->document_type !== Document::TYPE_ADJUSTMENT || !$document->isDraft()) {
            abort(403, 'Cannot edit non-draft adjustment documents.');
        }

        $document->update($request->validated());

        // Update items
        $document->items()->delete();
        foreach ($request->items as $item) {
            $document->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => abs($item['quantity']),
                'unit_id' => $item['unit_id'],
                'notes' => $item['notes'] ?? null,
            ]);
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Stock adjustment updated successfully.');
    }
}
