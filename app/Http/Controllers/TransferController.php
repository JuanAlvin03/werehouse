<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Warehouse;
use App\Models\Product;
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

        return view('transfers.index', [
            'documents' => $documents,
        ]);
    }

    public function create(): View
    {
        $warehouses = Warehouse::active()->get();
        $products = Product::active()->get();

        return view('transfers.create', [
            'warehouses' => $warehouses,
            'products' => $products,
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

    public function edit(Document $document): View
    {
        if ($document->document_type !== Document::TYPE_TRANSFER || !$document->isDraft()) {
            abort(403, 'Cannot edit non-draft transfer documents.');
        }

        $warehouses = Warehouse::active()->get();
        $products = Product::active()->get();

        return view('transfers.edit', [
            'document' => $document->load('items'),
            'warehouses' => $warehouses,
            'products' => $products,
        ]);
    }

    public function update(UpdateTransferRequest $request, Document $document): RedirectResponse
    {
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
