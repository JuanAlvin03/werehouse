<x-wms-layout :title="'Document - ' . $document->document_no">
    <x-page-header :title="$document->document_no" :subtitle="str_replace('_', ' ', $document->document_type) . ' - ' . $document->document_date->format('M d, Y')">
        <x-slot:actions>
            @if ($document->isDraft())
                <div class="flex items-center gap-2">
                    <form action="{{ route('documents.post', $document) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-green-700" onclick="return confirm('Post this document? This action cannot be undone.');">
                            Post
                        </button>
                    </form>
                    <a href="{{ route('documents.edit-by-type', ['document' => $document, 'type' => strtolower(str_replace('_', '-', $document->document_type))]) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Edit</a>
                    <form action="{{ route('documents.destroy', $document) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-300 bg-red-50 px-4 py-2.5 text-sm font-medium text-red-700 hover:bg-red-100" onclick="return confirm('Delete this document? This cannot be undone.');">
                            Delete
                        </button>
                    </form>
                </div>
            @elseif ($document->isPosted())
                <form action="{{ route('documents.cancel', $document) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50" onclick="return confirm('Cancel this document? A reversal document will be created.');">
                        Cancel
                    </button>
                </form>
            @endif
        </x-slot:actions>
    </x-page-header>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Document Header -->
            <x-panel title="Document Details">
                <dl class="space-y-4 text-sm text-slate-700">
                    <div class="flex justify-between gap-4 border-b border-slate-200 pb-3">
                        <dt class="text-slate-500">Document No.</dt>
                        <dd class="font-medium text-slate-900">{{ $document->document_no }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-b border-slate-200 pb-3">
                        <dt class="text-slate-500">Type</dt>
                        <dd class="font-medium text-slate-900">{{ str_replace('_', ' ', $document->document_type) }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-b border-slate-200 pb-3">
                        <dt class="text-slate-500">Status</dt>
                        <dd>
                            @php
                                $statusTones = [
                                    'DRAFT' => 'default',
                                    'POSTED' => 'success',
                                    'CANCELLED' => 'warning',
                                ];
                            @endphp
                            <x-status-badge :value="$document->status" :tone="$statusTones[$document->status] ?? 'default'" />
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4 border-b border-slate-200 pb-3">
                        <dt class="text-slate-500">Document Date</dt>
                        <dd class="font-medium text-slate-900">{{ $document->document_date->format('M d, Y') }}</dd>
                    </div>
                    @if ($document->document_type === 'TRANSFER')
                        <div class="flex justify-between gap-4 border-b border-slate-200 pb-3">
                            <dt class="text-slate-500">From Warehouse</dt>
                            <dd class="font-medium text-slate-900">{{ $document->fromWarehouse?->name }} ({{ $document->fromWarehouse?->code }})</dd>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-slate-200 pb-3">
                            <dt class="text-slate-500">To Warehouse</dt>
                            <dd class="font-medium text-slate-900">{{ $document->toWarehouse?->name }} ({{ $document->toWarehouse?->code }})</dd>
                        </div>
                    @else
                        <div class="flex justify-between gap-4 border-b border-slate-200 pb-3">
                            <dt class="text-slate-500">Warehouse</dt>
                            <dd class="font-medium text-slate-900">{{ $document->warehouse?->name }} ({{ $document->warehouse?->code }})</dd>
                        </div>
                    @endif
                    @if ($document->businessPartner)
                        <div class="flex justify-between gap-4 border-b border-slate-200 pb-3">
                            <dt class="text-slate-500">Business Partner</dt>
                            <dd class="font-medium text-slate-900">{{ $document->businessPartner->name }}</dd>
                        </div>
                    @endif
                    @if ($document->external_reference)
                        <div class="flex justify-between gap-4 border-b border-slate-200 pb-3">
                            <dt class="text-slate-500">External Reference</dt>
                            <dd class="font-medium text-slate-900">{{ $document->external_reference }}</dd>
                        </div>
                    @endif
                    @if ($document->notes)
                        <div class="border-b border-slate-200 pb-3">
                            <dt class="text-slate-500 mb-2">Notes</dt>
                            <dd class="font-medium text-slate-900 bg-slate-50 rounded p-2">{{ $document->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </x-panel>

            <!-- Document Items -->
            <x-panel title="Items">
                @if ($document->items->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 font-medium text-xs text-slate-500">SKU</th>
                                    <th class="px-4 py-3 font-medium text-xs text-slate-500">Product</th>
                                    <th class="px-4 py-3 font-medium text-xs text-slate-500 text-right">Qty</th>
                                    <th class="px-4 py-3 font-medium text-xs text-slate-500">Unit</th>
                                    <th class="px-4 py-3 font-medium text-xs text-slate-500">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach ($document->items as $item)
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-slate-800">{{ $item->product?->sku }}</td>
                                        <td class="px-4 py-3">{{ $item->product?->name }}</td>
                                        <td class="px-4 py-3 text-right font-medium">{{ number_format($item->quantity, 3) }}</td>
                                        <td class="px-4 py-3">{{ $item->unit?->code }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $item->notes ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-slate-500">No items in this document.</p>
                @endif
            </x-panel>

            <!-- Stock Movements (if posted) -->
            @if ($document->isPosted() && $document->stockMovements->count())
                <x-panel title="Stock Movements">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 font-medium text-xs text-slate-500">Product</th>
                                    <th class="px-4 py-3 font-medium text-xs text-slate-500">Warehouse</th>
                                    <th class="px-4 py-3 font-medium text-xs text-slate-500">Location</th>
                                    <th class="px-4 py-3 font-medium text-xs text-slate-500 text-right">Qty</th>
                                    <th class="px-4 py-3 font-medium text-xs text-slate-500">Type</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach ($document->stockMovements as $movement)
                                    <tr>
                                        <td class="px-4 py-3">{{ $movement->product?->name }}</td>
                                        <td class="px-4 py-3">{{ $movement->warehouse?->code }}</td>
                                        <td class="px-4 py-3">{{ $movement->location?->code ?? '—' }}</td>
                                        <td class="px-4 py-3 text-right font-medium">
                                            <span class="{{ $movement->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $movement->quantity > 0 ? '+' : '' }}{{ number_format($movement->quantity, 3) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-block rounded px-2 py-1 text-xs font-medium {{ $movement->quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $movement->quantity > 0 ? 'IN' : 'OUT' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-panel>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Metadata -->
            <x-panel title="Metadata">
                <dl class="space-y-3 text-sm text-slate-700">
                    <div class="border-b border-slate-200 pb-3">
                        <dt class="text-xs text-slate-500 mb-1">Created By</dt>
                        <dd class="font-medium text-slate-900">{{ $document->createdBy?->name }}</dd>
                    </div>
                    <div class="border-b border-slate-200 pb-3">
                        <dt class="text-xs text-slate-500 mb-1">Created At</dt>
                        <dd class="font-medium text-slate-900">{{ $document->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    @if ($document->isPosted())
                        <div class="border-b border-slate-200 pb-3">
                            <dt class="text-xs text-slate-500 mb-1">Posted By</dt>
                            <dd class="font-medium text-slate-900">{{ $document->postedBy?->name }}</dd>
                        </div>
                        <div class="border-b border-slate-200 pb-3">
                            <dt class="text-xs text-slate-500 mb-1">Posted At</dt>
                            <dd class="font-medium text-slate-900">{{ $document->posted_at->format('M d, Y H:i') }}</dd>
                        </div>
                    @endif
                    @if ($document->isCancelled())
                        <div class="border-b border-slate-200 pb-3">
                            <dt class="text-xs text-slate-500 mb-1">Cancelled At</dt>
                            <dd class="font-medium text-slate-900">{{ $document->cancelled_at->format('M d, Y H:i') }}</dd>
                        </div>
                    @endif
                </dl>
            </x-panel>

            <!-- Status Info -->
            <x-panel title="Status Information">
                <div class="space-y-3 text-sm">
                    @if ($document->isDraft())
                        <div class="rounded-lg bg-blue-50 p-3 text-blue-800">
                            <p class="font-medium mb-1">Draft Document</p>
                            <p class="text-xs leading-relaxed">This document is in draft status. You can edit or delete it. Post it when ready to create stock movements.</p>
                        </div>
                    @elseif ($document->isPosted())
                        <div class="rounded-lg bg-green-50 p-3 text-green-800">
                            <p class="font-medium mb-1">Posted Document</p>
                            <p class="text-xs leading-relaxed">This document has been posted. Stock movements have been created. You can cancel it to create a reversal.</p>
                        </div>
                    @elseif ($document->isCancelled())
                        <div class="rounded-lg bg-orange-50 p-3 text-orange-800">
                            <p class="font-medium mb-1">Cancelled Document</p>
                            <p class="text-xs leading-relaxed">This document has been cancelled. A reversal document was created to maintain audit trail.</p>
                        </div>
                    @endif
                </div>
            </x-panel>
        </div>
    </div>
</x-wms-layout>
