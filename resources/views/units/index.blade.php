<x-wms-layout title="Units">
    <x-page-header title="Units" subtitle="Measurement units used for stock and product data.">
        <x-slot:actions>
            <a href="{{ route('units.create') }}" class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-700">New unit</a>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        @if ($units->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-xs uppercase tracking-[0.12em] text-slate-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">Code</th>
                            <th class="px-4 py-3 font-medium">Name</th>
                            <th class="px-4 py-3 font-medium">Symbol</th>
                            <th class="px-4 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($units as $unit)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $unit->code }}</td>
                                <td class="px-4 py-3">{{ $unit->name }}</td>
                                <td class="px-4 py-3">{{ $unit->symbol ?: '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('units.show', $unit) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">View</a>
                                        <a href="{{ route('units.edit', $unit) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $units->links() }}
            </div>
        @else
            <p class="text-sm text-slate-500">No units found.</p>
        @endif
    </x-panel>
</x-wms-layout>
