<x-wms-layout title="Reports">
    <x-page-header title="Reports" subtitle="Create Reports">
        {{--  
        <x-slot:actions>
            <a href="{{ route('units.create') }}" class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-700">New unit</a>
        </x-slot:actions>
        --}}
    </x-page-header>

    <x-panel title="Create Reports">
        {{-- 
        link based on what Reports like: inventory stock, movement, documents, etc.
        then link to the report page with filters and export options
        tables / list to a page with filters and export options
        - Stock Movement of a warehouse across a date range
        - Stock Movement of a product across a date range
        --}}
        <div class="flex items-center justify-between rounded-lg border border-slate-200 px-3 py-3">
            <div>
                <p class="text-sm font-medium text-slate-800">Warehouse Stock Movement</p>
                {{-- <p class="text-xs text-slate-500">{{ $document->warehouse?->name ?? 'Warehouse' }} · {{ $document->created_at?->format('d M Y') }}</p>--}}
            </div>
            <div class="flex items-center gap-3">
                {{--<x-status-badge :value="$document->status" :tone="match($document->status) { 'POSTED' => 'success', 'DRAFT' => 'info', 'CANCELLED' => 'danger', default => 'default' }" /> --}}
                <a href="{{ route('reports.stock-movement-by-warehouse.create') }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">Create Report</a>
            </div>
        </div>
    </x-panel>
</x-wms-layout>
