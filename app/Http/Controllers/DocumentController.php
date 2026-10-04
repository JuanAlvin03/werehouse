<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Warehouse;
use App\Models\BusinessPartner;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Http\Requests\PostDocumentRequest;
use App\Services\DocumentPostingService;
use App\Services\DocumentNumberGenerator;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DocumentController extends Controller
{
    protected DocumentPostingService $postingService;

    public function __construct(DocumentPostingService $postingService)
    {
        $this->postingService = $postingService;
    }

    public function index(): View
    {
        $documents = Document::with('warehouse', 'businessPartner', 'createdBy')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('documents.index', [
            'documents' => $documents,
        ]);
    }

    public function show(Document $document): View
    {
        return view('documents.show', [
            'document' => $document->load(
                'warehouse',
                'fromWarehouse',
                'toWarehouse',
                'businessPartner',
                'items.product',
                'items.unit',
                'createdBy',
                'postedBy',
                'stockMovements'
            ),
        ]);
    }

    public function post(PostDocumentRequest $request, Document $document): RedirectResponse
    {
        try {
            $this->postingService->post($document);

            return redirect()->route('documents.show', $document)
                ->with('success', "Document {$document->document_no} posted successfully.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Failed to post document: {$e->getMessage()}");
        }
    }

    public function cancel(Document $document): RedirectResponse
    {
        try {
            $this->postingService->cancel($document);

            return redirect()->route('documents.show', $document)
                ->with('success', "Document {$document->document_no} cancelled successfully with reversal.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Failed to cancel document: {$e->getMessage()}");
        }
    }

    public function destroy(Document $document): RedirectResponse
    {
        // Only allow deletion of draft documents
        if (!$document->isDraft()) {
            return redirect()->back()
                ->with('error', 'Only draft documents can be deleted.');
        }

        $document->items()->delete();
        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document deleted successfully.');
    }
}
