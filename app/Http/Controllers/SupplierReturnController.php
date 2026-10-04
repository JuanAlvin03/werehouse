<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\BusinessPartner;
use App\Http\Requests\StoreSupplierReturnRequest;
use App\Http\Requests\UpdateSupplierReturnRequest;
use App\Services\DocumentNumberGenerator;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SupplierReturnController extends Controller
{
    public function index(): View
    {
        $documents = Document::byType(Document::TYPE_SUPPLIER_RETURN)
            ->with('warehouse', 'businessPartner', 'createdBy')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('supplier-returns.index', [
            'documents' => $documents,
        ]);
    }

    public function create(): View
    {
        $warehouses = Warehouse::active()->get();
        $suppliers = BusinessPartner::active()->suppliers()->get();
        $products = Product::active()->get();

        return view('supplier-returns.create', [
            'warehouses' => $warehouses,
            'suppliers' => $suppliers,
            'products' => $products,
            'documentNo' => DocumentNumberGenerator::generate(Document::TYPE_SUPPLIER_RETURN),
        ]);
    }

    public function store(StoreSupplierReturnRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['document_no'] = DocumentNumberGenerator::generate(Document::TYPE_SUPPLIER_RETURN);
        $data['document_type'] = Document::TYPE_SUPPLIER_RETURN;
        $data['status'] = Document::STATUS_DRAFT;
        $data['document_date'] = now()->toDateString();
        $data['created_by'] = auth()->id();

        $document = Document::create($data);

        // Create document items
        foreach ($request->items as $item) {
            $document->items()->create($item);
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Supplier return created successfully.');
    }

    public function edit(Document $document): View
    {
        if ($document->document_type !== Document::TYPE_SUPPLIER_RETURN || !$document->isDraft()) {
            abort(403, 'Cannot edit non-draft supplier return documents.');
        }

        $warehouses = Warehouse::active()->get();
        $suppliers = BusinessPartner::active()->suppliers()->get();
        $products = Product::active()->get();

        return view('supplier-returns.edit', [
            'document' => $document->load('items'),
            'warehouses' => $warehouses,
            'suppliers' => $suppliers,
            'products' => $products,
        ]);
    }

    public function update(UpdateSupplierReturnRequest $request, Document $document): RedirectResponse
    {
        if ($document->document_type !== Document::TYPE_SUPPLIER_RETURN || !$document->isDraft()) {
            abort(403, 'Cannot edit non-draft supplier return documents.');
        }

        $document->update($request->validated());

        // Update items
        $document->items()->delete();
        foreach ($request->items as $item) {
            $document->items()->create($item);
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Supplier return updated successfully.');
    }
}
