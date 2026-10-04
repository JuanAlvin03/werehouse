<x-wms-layout title="Business Partners">
    <x-page-header title="Business partners" subtitle="Suppliers, customers, and both-type partners.">
        <x-slot:actions>
            <a href="{{ route('business-partners.create') }}" class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-700">New partner</a>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        @if ($partners->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-xs uppercase tracking-[0.12em] text-slate-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">Code</th>
                            <th class="px-4 py-3 font-medium">Name</th>
                            <th class="px-4 py-3 font-medium">Type</th>
                            <th class="px-4 py-3 font-medium">Email</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($partners as $partner)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $partner->code }}</td>
                                <td class="px-4 py-3">{{ $partner->name }}</td>
                                <td class="px-4 py-3">{{ $partner->partner_type }}</td>
                                <td class="px-4 py-3">{{ $partner->email ?: '—' }}</td>
                                <td class="px-4 py-3">
                                    <x-status-badge :value="$partner->is_active ? 'Active' : 'Inactive'" :tone="$partner->is_active ? 'success' : 'default'" />
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('business-partners.show', $partner) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">View</a>
                                        <a href="{{ route('business-partners.edit', $partner) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $partners->links() }}
            </div>
        @else
            <p class="text-sm text-slate-500">No business partners found.</p>
        @endif
    </x-panel>
</x-wms-layout>
