<x-wms-layout title="Category Details">
    <x-page-header title="{{ $category->name }}" subtitle="{{ $category->code }}">
        <x-slot:actions>
            <a href="{{ route('product-categories.edit', $category) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Edit</a>
        </x-slot:actions>
    </x-page-header>

    <div class="grid gap-6 lg:grid-cols-2">
        <x-panel title="Overview">
            <dl class="space-y-4 text-sm text-slate-700">
                <div class="flex justify-between gap-4 border-b border-slate-200 pb-3"><dt class="text-slate-500">Code</dt><dd class="font-medium text-slate-900">{{ $category->code }}</dd></div>
                <div class="flex justify-between gap-4 border-b border-slate-200 pb-3"><dt class="text-slate-500">Parent</dt><dd class="font-medium text-slate-900">{{ $category->parent?->name ?? '—' }}</dd></div>
                <div class="flex justify-between gap-4 border-b border-slate-200 pb-3"><dt class="text-slate-500">Status</dt><dd><x-status-badge :value="$category->is_active ? 'Active' : 'Inactive'" :tone="$category->is_active ? 'success' : 'default'" /></dd></div>
            </dl>
        </x-panel>

        <x-panel title="Description">
            <p class="text-sm leading-6 text-slate-600">
                {{ $category->description ?: 'No description provided for this category.' }}
            </p>
        </x-panel>
    </div>
</x-wms-layout>
