<x-wms-layout title="Stock Issues">
    <x-page-header title="Stock issues" subtitle="Goods issued from warehouse.">
        <x-slot:actions>
            <a href="{{ route('issues.create') }}" class="inline-flex items-center justify-center rounded-lg bg-orange-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-orange-700">New Issue</a>
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
                        @foreach ($documents as $issue)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $issue->document_no }}</td>
                                <td class="px-4 py-3">{{ $issue->document_date->format('M d, Y') }}</td>
                                <td class="px-4 py-3">{{ $issue->warehouse?->code }}</td>
                                <td class="px-4 py-3">{{ $issue->businessPartner?->name ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <x-status-badge :value="$issue->status" :tone="$issue->status === 'POSTED' ? 'success' : ($issue->status === 'CANCELLED' ? 'warning' : 'default')" />
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-slate-600">{{ $issue->items->count() }} item{{ $issue->items->count() !== 1 ? 's' : '' }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm">{{ $issue->createdBy?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('documents.show', $issue) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">View</a>
                                        @if ($issue->isDraft())
                                            <a href="{{ route('issues.edit', $issue) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">Edit</a>
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
                <p class="text-sm text-slate-500 mb-4">No stock issues found. Create your first issue document.</p>
                <a href="{{ route('issues.create') }}" class="inline-flex items-center justify-center rounded-lg bg-orange-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-orange-700">Create Issue</a>
            </div>
        @endif
    </x-panel>
</x-wms-layout>
