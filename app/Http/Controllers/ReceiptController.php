<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\BusinessPartner;
use App\Http\Requests\StoreReceiptRequest;
use App\Http\Requests\UpdateReceiptRequest;
use App\Services\DocumentNumberGenerator;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ReceiptController extends Controller
{
    public function index(): View
    {
        $documents = Document::byType(Document::TYPE_RECEIPT)
            ->with('warehouse', 'businessPartner', 'createdBy')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('receipts.index', [
            'documents' => $documents,
        ]);
    }

    public function create(): View
    {
        $warehouses = Warehouse::active()->get();
        $suppliers = BusinessPartner::active()->suppliers()->get();
        $products = Product::active()->get();

        return view('receipts.create', [
            'warehouses' => $warehouses,
            'suppliers' => $suppliers,
            'products' => $products,
            'documentNo' => DocumentNumberGenerator::generate(Document::TYPE_RECEIPT),
        ]);
    }

    public function store(StoreReceiptRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['document_no'] = DocumentNumberGenerator::generate(Document::TYPE_RECEIPT);
        $data['document_type'] = Document::TYPE_RECEIPT;
        $data['status'] = Document::STATUS_DRAFT;
        $data['document_date'] = now()->toDateString();
        $data['created_by'] = auth()->id();

        $document = Document::create($data);

        // Create document items
        foreach ($request->items as $item) {
            $document->items()->create($item);
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Receipt created successfully.');
    }

    public function edit(Document $document): View
    {
        if ($document->document_type !== Document::TYPE_RECEIPT || !$document->isDraft()) {
            abort(403, 'Cannot edit non-draft receipt documents.');
        }

        $warehouses = Warehouse::active()->get();
        $suppliers = BusinessPartner::active()->suppliers()->get();
        $products = Product::active()->get();

        return view('receipts.edit', [
            'document' => $document->load('items'),
            'warehouses' => $warehouses,
            'suppliers' => $suppliers,
            'products' => $products,
        ]);
    }

    public function update(UpdateReceiptRequest $request, Document $document): RedirectResponse
    {
        if ($document->document_type !== Document::TYPE_RECEIPT || !$document->isDraft()) {
            abort(403, 'Cannot edit non-draft receipt documents.');
        }

        $document->update($request->validated());

        // Update items
        $document->items()->delete();
        foreach ($request->items as $item) {
            $document->items()->create($item);
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Receipt updated successfully.');
    }

    public function show(Document $document): View
    {
        if ($document->document_type !== Document::TYPE_RECEIPT) {
            abort(404);
        }

        return redirect()->route('documents.show', $document);
    }
}
