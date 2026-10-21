<x-wms-layout title="Warehouse Transfers">
    <x-page-header title="Warehouse Transfers" subtitle="Goods transferred between warehouses.">
        <x-slot:actions>
            <a href="{{ route('transfers.create') }}" class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-green-700">New Warehouse Transfer</a>
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
                            <th class="px-4 py-3 font-medium">Source Warehouse</th>
                            <th class="px-4 py-3 font-medium">Destination Warehouse</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">Items</th>
                            <th class="px-4 py-3 font-medium">Created By</th>
                            <th class="px-4 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($documents as $tranfer)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $tranfer->document_no }}</td>
                                <td class="px-4 py-3">{{ $tranfer->document_date->format('M d, Y') }}</td>
                                <td class="px-4 py-3">{{ $tranfer->fromWarehouse?->code }}</td>
                                <td class="px-4 py-3">{{ $tranfer->toWarehouse?->code }}</td>
                                <td class="px-4 py-3">
                                    <x-status-badge :value="$tranfer->status" :tone="$tranfer->status === 'POSTED' ? 'success' : ($tranfer->status === 'CANCELLED' ? 'warning' : 'default')" />
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-slate-600">{{ $tranfer->items->count() }} item{{ $tranfer->items->count() !== 1 ? 's' : '' }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm">{{ $tranfer->createdBy?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('documents.show', $tranfer) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">View</a>
                                        @if ($tranfer->isDraft())
                                            <a href="{{ route('transfers.edit', $tranfer) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">Edit</a>
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
                <p class="text-sm text-slate-500 mb-4">No warehouse transfers found. Create your first warehouse transfer.</p>
                <a href="{{ route('transfers.create') }}" class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-green-700">Create Warehouse Transfer</a>
            </div>
        @endif
    </x-panel>
</x-wms-layout>
