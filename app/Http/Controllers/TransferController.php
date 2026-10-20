<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Unit;
use App\Http\Requests\StoreTransferRequest;
use App\Http\Requests\UpdateTransferRequest;
use App\Services\DocumentNumberGenerator;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TransferController extends Controller
{
    public function index(): View
    {
        $documents = Document::byType(Document::TYPE_TRANSFER)
            ->with('fromWarehouse', 'toWarehouse', 'createdBy')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('documents.transfers.index', [
            'documents' => $documents,
        ]);
    }

    public function create(): View
    {
        $warehouses = Warehouse::active()->get();
        $products = Product::active()->get();
        $units = Unit::all();

        return view('documents.transfers.create', [
            'warehouses' => $warehouses,
            'products' => $products,
            'units' => $units,
            'documentNo' => DocumentNumberGenerator::generate(Document::TYPE_TRANSFER),
        ]);
    }

    public function store(StoreTransferRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['document_no'] = DocumentNumberGenerator::generate(Document::TYPE_TRANSFER);
        $data['document_type'] = Document::TYPE_TRANSFER;
        $data['status'] = Document::STATUS_DRAFT;
        $data['document_date'] = now()->toDateString();
        $data['created_by'] = auth()->id();

        $document = Document::create($data);

        // Create document items
        foreach ($request->items as $item) {
            $document->items()->create($item);
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Warehouse transfer created successfully.');
    }

    public function edit($id): View
    {
        $document = Document::findOrFail($id);
        $units = Unit::all();
        if ($document->document_type !== Document::TYPE_TRANSFER || !$document->isDraft()) {
            abort(403, 'Cannot edit non-draft transfer documents.');
        }

        $warehouses = Warehouse::active()->get();
        $products = Product::active()->get();

        return view('documents.transfers.edit', [
            'document' => $document->load('items'),
            'warehouses' => $warehouses,
            'products' => $products,
            'units' => $units,
        ]);
    }

    public function update(UpdateTransferRequest $request, $id): RedirectResponse
    {
        $document = Document::findOrFail($id);

        if ($document->document_type !== Document::TYPE_TRANSFER || !$document->isDraft()) {
            abort(403, 'Cannot edit non-draft transfer documents.');
        }

        $document->update($request->validated());

        // Update items
        $document->items()->delete();
        foreach ($request->items as $item) {
            $document->items()->create($item);
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Warehouse transfer updated successfully.');
    }
}
