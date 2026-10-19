<x-wms-layout title="Supplier Returns">
    <x-page-header title="Supplier Returns" subtitle="Goods returned to suppliers.">
        <x-slot:actions>
            <a href="{{ route('supplier-returns.create') }}" class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-green-700">New Supplier Return</a>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        @if ($documents->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-xs uppercase tracking-[0.12em] text-slate-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">Document No.</th>
                            <th class="px-4 py-3 font-medium">Date</th>
                            <th class="px-4 py-3 font-medium">Warehouse</th>
                            <th class="px-4 py-3 font-medium">Supplier</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">Items</th>
                            <th class="px-4 py-3 font-medium">Created By</th>
                            <th class="px-4 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($documents as $supplier_return)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $supplier_return->document_no }}</td>
                                <td class="px-4 py-3">{{ $supplier_return->document_date->format('M d, Y') }}</td>
                                <td class="px-4 py-3">{{ $supplier_return->warehouse?->code }}</td>
                                <td class="px-4 py-3">{{ $supplier_return->businessPartner?->name ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <x-status-badge :value="$supplier_return->status" :tone="$supplier_return->status === 'POSTED' ? 'success' : ($supplier_return->status === 'CANCELLED' ? 'warning' : 'default')" />
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-slate-600">{{ $supplier_return->items->count() }} item{{ $supplier_return->items->count() !== 1 ? 's' : '' }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm">{{ $supplier_return->createdBy?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('documents.show', $supplier_return) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">View</a>
                                        @if ($supplier_return->isDraft())
                                            <a href="{{ route('supplier_returns.edit', $supplier_return) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">Edit</a>
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
                <p class="text-sm text-slate-500 mb-4">No supplier returns found. Create your first supplier return.</p>
                <a href="{{ route('supplier-returns.create') }}" class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-green-700">Create Supplier Return</a>
            </div>
        @endif
    </x-panel>
</x-wms-layout>
