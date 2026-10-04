<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\BusinessPartner;
use App\Http\Requests\StoreCustomerReturnRequest;
use App\Http\Requests\UpdateCustomerReturnRequest;
use App\Services\DocumentNumberGenerator;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CustomerReturnController extends Controller
{
    public function index(): View
    {
        $documents = Document::byType(Document::TYPE_CUSTOMER_RETURN)
            ->with('warehouse', 'businessPartner', 'createdBy')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('customer-returns.index', [
            'documents' => $documents,
        ]);
    }

    public function create(): View
    {
        $warehouses = Warehouse::active()->get();
        $customers = BusinessPartner::active()->customers()->get();
        $products = Product::active()->get();

        return view('customer-returns.create', [
            'warehouses' => $warehouses,
            'customers' => $customers,
            'products' => $products,
            'documentNo' => DocumentNumberGenerator::generate(Document::TYPE_CUSTOMER_RETURN),
        ]);
    }

    public function store(StoreCustomerReturnRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['document_no'] = DocumentNumberGenerator::generate(Document::TYPE_CUSTOMER_RETURN);
        $data['document_type'] = Document::TYPE_CUSTOMER_RETURN;
        $data['status'] = Document::STATUS_DRAFT;
        $data['document_date'] = now()->toDateString();
        $data['created_by'] = auth()->id();

        $document = Document::create($data);

        // Create document items
        foreach ($request->items as $item) {
            $document->items()->create($item);
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Customer return created successfully.');
    }

    public function edit(Document $document): View
    {
        if ($document->document_type !== Document::TYPE_CUSTOMER_RETURN || !$document->isDraft()) {
            abort(403, 'Cannot edit non-draft customer return documents.');
        }

        $warehouses = Warehouse::active()->get();
        $customers = BusinessPartner::active()->customers()->get();
        $products = Product::active()->get();

        return view('customer-returns.edit', [
            'document' => $document->load('items'),
            'warehouses' => $warehouses,
            'customers' => $customers,
            'products' => $products,
        ]);
    }

    public function update(UpdateCustomerReturnRequest $request, Document $document): RedirectResponse
    {
        if ($document->document_type !== Document::TYPE_CUSTOMER_RETURN || !$document->isDraft()) {
            abort(403, 'Cannot edit non-draft customer return documents.');
        }

        $document->update($request->validated());

        // Update items
        $document->items()->delete();
        foreach ($request->items as $item) {
            $document->items()->create($item);
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Customer return updated successfully.');
    }
}
