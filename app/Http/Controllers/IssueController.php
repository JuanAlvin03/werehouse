<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\BusinessPartner;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Services\DocumentNumberGenerator;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class IssueController extends Controller
{
    public function index(): View
    {
        $documents = Document::byType(Document::TYPE_ISSUE)
            ->with('warehouse', 'businessPartner', 'createdBy')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('issues.index', [
            'documents' => $documents,
        ]);
    }

    public function create(): View
    {
        $warehouses = Warehouse::active()->get();
        $customers = BusinessPartner::active()->customers()->get();
        $products = Product::active()->get();

        return view('issues.create', [
            'warehouses' => $warehouses,
            'customers' => $customers,
            'products' => $products,
            'documentNo' => DocumentNumberGenerator::generate(Document::TYPE_ISSUE),
        ]);
    }

    public function store(StoreIssueRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['document_no'] = DocumentNumberGenerator::generate(Document::TYPE_ISSUE);
        $data['document_type'] = Document::TYPE_ISSUE;
        $data['status'] = Document::STATUS_DRAFT;
        $data['document_date'] = now()->toDateString();
        $data['created_by'] = auth()->id();

        $document = Document::create($data);

        // Create document items
        foreach ($request->items as $item) {
            $document->items()->create($item);
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Stock issue created successfully.');
    }

    public function edit(Document $document): View
    {
        if ($document->document_type !== Document::TYPE_ISSUE || !$document->isDraft()) {
            abort(403, 'Cannot edit non-draft issue documents.');
        }

        $warehouses = Warehouse::active()->get();
        $customers = BusinessPartner::active()->customers()->get();
        $products = Product::active()->get();

        return view('issues.edit', [
            'document' => $document->load('items'),
            'warehouses' => $warehouses,
            'customers' => $customers,
            'products' => $products,
        ]);
    }

    public function update(UpdateIssueRequest $request, Document $document): RedirectResponse
    {
        if ($document->document_type !== Document::TYPE_ISSUE || !$document->isDraft()) {
            abort(403, 'Cannot edit non-draft issue documents.');
        }

        $document->update($request->validated());

        // Update items
        $document->items()->delete();
        foreach ($request->items as $item) {
            $document->items()->create($item);
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Stock issue updated successfully.');
    }
}
