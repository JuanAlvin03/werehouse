<x-wms-layout title="Warehouse Details">
    <x-page-header title="{{ $warehouse->name }}" subtitle="{{ $warehouse->code }}">
        <x-slot:actions>
            <a href="{{ route('warehouses.edit', $warehouse) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Edit</a>
        </x-slot:actions>
    </x-page-header>

    <div class="grid gap-6 lg:grid-cols-2">
        <x-panel title="Overview">
            <dl class="space-y-4 text-sm text-slate-700">
                <div class="flex justify-between gap-4 border-b border-slate-200 pb-3"><dt class="text-slate-500">Code</dt><dd class="font-medium text-slate-900">{{ $warehouse->code }}</dd></div>
                <div class="flex justify-between gap-4 border-b border-slate-200 pb-3"><dt class="text-slate-500">Status</dt><dd><x-status-badge :value="$warehouse->is_active ? 'Active' : 'Inactive'" :tone="$warehouse->is_active ? 'success' : 'default'" /></dd></div>
                <div class="flex justify-between gap-4 border-b border-slate-200 pb-3"><dt class="text-slate-500">Address</dt><dd class="font-medium text-slate-900">{{ $warehouse->address ?: '—' }}</dd></div>
            </dl>
        </x-panel>

        <x-panel title="Locations">
            @if ($warehouse->locations->count())
                <ul class="space-y-3 text-sm text-slate-600">
                    @foreach ($warehouse->locations as $location)
                        <li class="rounded-lg border border-slate-200 px-3 py-2">
                            <div class="flex items-center justify-between gap-3">
                                <span class="font-medium text-slate-800">{{ $location->code }}</span>
                                <x-status-badge :value="$location->is_active ? 'Active' : 'Inactive'" :tone="$location->is_active ? 'success' : 'default'" />
                            </div>
                            <p class="mt-1 text-slate-500">{{ $location->name }}</p>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-slate-500">No warehouse locations assigned yet.</p>
            @endif
        </x-panel>
    </div>
</x-wms-layout>
