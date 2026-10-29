<x-wms-layout title="Partner Details">
    <x-page-header title="{{ $partner->name }}" subtitle="{{ $partner->code }}">
        <x-slot:actions>
            <a href="{{ route('business-partners.edit', $partner) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Edit</a>
        
            @if ($partner->documents()->count() == 0) 
                <form action="{{ route('business-partners.destroy', $partner) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-300 bg-red-50 px-4 py-2.5 text-sm font-medium text-red-700 hover:bg-red-100" onclick="return confirm('Delete this category? This cannot be undone.');">
                        Delete
                    </button>
                </form>
            @endif
        
        </x-slot:actions>
    </x-page-header>

    <div class="grid gap-6 lg:grid-cols-2">
        <x-panel title="Overview">
            <dl class="space-y-4 text-sm text-slate-700">
                <div class="flex justify-between gap-4 border-b border-slate-200 pb-3"><dt class="text-slate-500">Code</dt><dd class="font-medium text-slate-900">{{ $partner->code }}</dd></div>
                <div class="flex justify-between gap-4 border-b border-slate-200 pb-3"><dt class="text-slate-500">Type</dt><dd class="font-medium text-slate-900">{{ $partner->partner_type }}</dd></div>
                <div class="flex justify-between gap-4 border-b border-slate-200 pb-3"><dt class="text-slate-500">Phone</dt><dd class="font-medium text-slate-900">{{ $partner->phone ?: '—' }}</dd></div>
                <div class="flex justify-between gap-4 border-b border-slate-200 pb-3"><dt class="text-slate-500">Email</dt><dd class="font-medium text-slate-900">{{ $partner->email ?: '—' }}</dd></div>
                <div class="flex justify-between gap-4 border-b border-slate-200 pb-3"><dt class="text-slate-500">Status</dt><dd><x-status-badge :value="$partner->is_active ? 'Active' : 'Inactive'" :tone="$partner->is_active ? 'success' : 'default'" /></dd></div>
            </dl>
        </x-panel>

        <x-panel title="Address">
            <p class="text-sm leading-6 text-slate-600">
                {{ $partner->address ?: 'No address provided.' }}
            </p>
        </x-panel>
    </div>
</x-wms-layout>
