<x-wms-layout title="Customer Returns">
    <x-page-header title="Customer Returns" subtitle="Goods returned by customers.">
        <x-slot:actions>
            <a href="{{ route('customer-returns.create') }}" class="inline-flex items-center justify-center rounded-lg bg-orange-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-orange-700">New Return</a>
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
                            <th class="px-4 py-3 font-medium">Customer</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">Items</th>
                            <th class="px-4 py-3 font-medium">Created By</th>
                            <th class="px-4 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($documents as $customer_return)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $customer_return->document_no }}</td>
                                <td class="px-4 py-3">{{ $customer_return->document_date->format('M d, Y') }}</td>
                                <td class="px-4 py-3">{{ $customer_return->warehouse?->code }}</td>
                                <td class="px-4 py-3">{{ $customer_return->businessPartner?->name ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <x-status-badge :value="$customer_return->status" :tone="$customer_return->status === 'POSTED' ? 'success' : ($customer_return->status === 'CANCELLED' ? 'warning' : 'default')" />
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-slate-600">{{ $customer_return->items->count() }} item{{ $customer_return->items->count() !== 1 ? 's' : '' }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm">{{ $customer_return->createdBy?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('documents.show', $customer_return) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">View</a>
                                        @if ($customer_return->isDraft())
                                            <a href="{{ route('customer-returns.edit', $customer_return) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">Edit</a>
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
                <p class="text-sm text-slate-500 mb-4">No customer returns found. Create your first return.</p>
                <a href="{{ route('customer-returns.create') }}" class="inline-flex items-center justify-center rounded-lg bg-orange-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-orange-700">Create Return</a>
            </div>
        @endif
    </x-panel>
</x-wms-layout>
