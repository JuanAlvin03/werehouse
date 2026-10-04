<x-wms-layout title="Unit Details">
    <x-page-header title="{{ $unit->name }}" subtitle="{{ $unit->code }}">
        <x-slot:actions>
            <a href="{{ route('units.edit', $unit) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Edit</a>
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
