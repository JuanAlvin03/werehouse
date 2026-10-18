<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\StockAdjustmentReason;
use App\Models\Unit;
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

        return view('documents.adjustments.index', [
            'documents' => $documents,
        ]);
    }

    public function create(): View
    {
        $warehouses = Warehouse::active()->get();
        $reasons = StockAdjustmentReason::active()->get();
        $products = Product::active()->get();
        $units = Unit::all();

        return view('documents.adjustments.create', [
            'warehouses' => $warehouses,
            'reasons' => $reasons,
            'products' => $products,
            'units' => $units,
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
            /*
            $quantity = $item['quantity'];
            
            // Sign the quantity based on direction in item
            if (isset($item['direction']) && $item['direction'] === 'DECREASE') {
                $quantity = -abs($quantity);
            } else {
                $quantity = abs($quantity);
            }
            */

            $document->items()->create([
                'product_id' => $item['product_id'],
                //'quantity' => $quantity,
                'quantity' => $item['quantity'],
                'unit_id' => $item['unit_id'],
                'notes' => $item['notes'] ?? null,
                'direction' => $item['direction'] ?? null,
                //'direction' => 'INCREASE',
            ]);
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Stock adjustment created successfully.');
    }

    public function edit($id): View
    {
        $document = Document::findOrFail($id);

        if ($document->document_type !== Document::TYPE_ADJUSTMENT || !$document->isDraft()) {
            abort(403, 'Cannot edit non-draft adjustment documents.');
        }

        $warehouses = Warehouse::active()->get();
        $reasons = StockAdjustmentReason::active()->get();
        $products = Product::active()->get();
        $units = Unit::all();

        return view('documents.adjustments.edit', [
            'document' => $document->load('items'),
            'warehouses' => $warehouses,
            'reasons' => $reasons,
            'products' => $products,
            'units' => $units,
        ]);
    }

    public function update(UpdateAdjustmentRequest $request, $id): RedirectResponse
    {

        $document = Document::findOrFail($id);

        if ($document->document_type !== Document::TYPE_ADJUSTMENT || !$document->isDraft()) {
            abort(403, 'Cannot edit non-draft adjustment documents.');
        }

        $document->update($request->validated());

        // Update items
        $document->items()->delete();
        foreach ($request->items as $item) {

            $document->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_id' => $item['unit_id'],
                'notes' => $item['notes'] ?? null,
                'direction' => $item['direction'] ?? null,
                //'direction' => 'INCREASE',
            ]);
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Stock adjustment updated successfully.');
    }
}
