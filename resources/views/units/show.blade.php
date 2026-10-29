<x-wms-layout title="Unit Details">
    <x-page-header title="{{ $unit->name }}" subtitle="{{ $unit->code }}">
        <x-slot:actions>
            <a href="{{ route('units.edit', $unit) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Edit</a>

            @if ($unit->documentItems->count() == 0 && $unit->products->count() == 0) 
                <form action="{{ route('units.destroy', $unit) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-300 bg-red-50 px-4 py-2.5 text-sm font-medium text-red-700 hover:bg-red-100" onclick="return confirm('Delete this unit? This cannot be undone.');">
                        Delete
                    </button>
                </form>
            @endif

        </x-slot:actions>
    </x-page-header>

    <x-panel title="Overview">
        <dl class="space-y-4 text-sm text-slate-700">
            <div class="flex justify-between gap-4 border-b border-slate-200 pb-3"><dt class="text-slate-500">Code</dt><dd class="font-medium text-slate-900">{{ $unit->code }}</dd></div>
            <div class="flex justify-between gap-4 border-b border-slate-200 pb-3"><dt class="text-slate-500">Name</dt><dd class="font-medium text-slate-900">{{ $unit->name }}</dd></div>
            <div class="flex justify-between gap-4 border-b border-slate-200 pb-3"><dt class="text-slate-500">Symbol</dt><dd class="font-medium text-slate-900">{{ $unit->symbol ?: '—' }}</dd></div>
        </dl>
    </x-panel>
</x-wms-layout>
