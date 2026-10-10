<x-wms-layout title="Documents">
    <x-page-header title="Documents" subtitle="View all warehouse transactions and documents.">
        <x-slot:actions>
            <div class="flex items-center gap-2">
                <a href="{{ route('documents.receipts.create') }}" class="inline-flex items-center justify-center rounded-lg bg-green-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-green-700" title="Create goods receipt">Receipt</a>
                <a href="{{ route('documents.issues.create') }}" class="inline-flex items-center justify-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-orange-700" title="Create stock issue">Issue</a>
                <a href="{{ route('documents.adjustments.create') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-blue-700" title="Create adjustment">Adjustment</a>
                <a href="{{ route('documents.customer-returns.create') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-indigo-700" title="Customer return">Cust. Return</a>
                <a href="{{ route('documents.supplier-returns.create') }}" class="inline-flex items-center justify-center rounded-lg bg-purple-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-purple-700" title="Supplier return">Supp. Return</a>
                <a href="{{ route('documents.transfers.create') }}" class="inline-flex items-center justify-center rounded-lg bg-slate-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-slate-700" title="Transfer between warehouses">Transfer</a>
            </div>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        @if ($documents->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-xs uppercase tracking-[0.12em] text-slate-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">Document No.</th>
                            <th class="px-4 py-3 font-medium">Type</th>
                            <th class="px-4 py-3 font-medium">Date</th>
                            <th class="px-4 py-3 font-medium">Warehouse</th>
                            <th class="px-4 py-3 font-medium">Partner</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">Created By</th>
                            <th class="px-4 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($documents as $document)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $document->document_no }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $typeColors = [
                                            'RECEIPT' => 'bg-green-100 text-green-800',
                                            'ISSUE' => 'bg-orange-100 text-orange-800',
                                            'ADJUSTMENT' => 'bg-blue-100 text-blue-800',
                                            'CUSTOMER_RETURN' => 'bg-indigo-100 text-indigo-800',
                                            'SUPPLIER_RETURN' => 'bg-purple-100 text-purple-800',
                                            'TRANSFER' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $color = $typeColors[$document->document_type] ?? 'bg-slate-100 text-slate-800';
                                    @endphp
                                    <span class="inline-block rounded px-2 py-1 text-xs font-medium {{ $color }}">
                                        {{ str_replace('_', ' ', $document->document_type) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ $document->document_date->format('M d, Y') }}</td>
                                <td class="px-4 py-3">
                                    @if ($document->document_type === 'TRANSFER')
                                        {{ $document->fromWarehouse?->code }} → {{ $document->toWarehouse?->code }}
                                    @else
                                        {{ $document->warehouse?->code }}
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $document->businessPartner?->name ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusTones = [
                                            'DRAFT' => 'default',
                                            'POSTED' => 'success',
                                            'CANCELLED' => 'warning',
                                        ];
                                    @endphp
                                    <x-status-badge :value="$document->status" :tone="$statusTones[$document->status] ?? 'default'" />
                                </td>
                                <td class="px-4 py-3 text-sm">{{ $document->createdBy?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('documents.show', $document) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">View</a>
                                        @if ($document->isDraft())
                                            <a href="{{ route('documents.edit-by-type', ['document' => $document, 'type' => strtolower(str_replace('_', '-', $document->document_type))]) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">Edit</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $documents->links() }}
            </div>
        @else
            <div class="py-12 text-center">
                <p class="text-sm text-slate-500">No documents found. Create your first document using the buttons above.</p>
            </div>
        @endif
    </x-panel>
</x-wms-layout>
