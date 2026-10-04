<x-wms-layout title="Dashboard">
    <div class="space-y-6">
        <x-page-header title="Overview" subtitle="Warehouse activity and operational status." />

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-stat-card label="Products" value="{{ \App\Models\Product::count() }}" tone="default" icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5v-9zm8 3.5 8-4.5M12 11v10" /></svg>' />

            <x-stat-card label="Warehouses" value="{{ \App\Models\Warehouse::count() }}" tone="default" icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.5 20V8.5L12 4l8.5 4.5V20M7 10h10M7 14h10M7 18h10" /></svg>' />

            <x-stat-card label="Documents" value="{{ \App\Models\Document::count() }}" tone="success" icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 7.5h6M9 12h6m-6 4.5h6M5.5 4.5h13a1.5 1.5 0 0 1 1.5 1.5v12a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18V6a1.5 1.5 0 0 1 1.5-1.5z" /></svg>' />

            <x-stat-card label="Stock Movements" value="{{ \App\Models\StockMovement::count() }}" tone="warning" icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 17 10 11l4 4 6-8M18 7h2v2" /></svg>' />
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.4fr_0.8fr]">
            <x-panel title="Recent documents" subtitle="Latest warehouse and stock movements">
                @php
                    $documents = \App\Models\Document::with(['warehouse', 'businessPartner', 'createdBy'])->latest()->limit(6)->get();
                @endphp

                @if ($documents->isNotEmpty())
                    <div class="space-y-3">
                        @foreach ($documents as $document)
                            <div class="flex items-center justify-between rounded-lg border border-slate-200 px-3 py-3">
                                <div>
                                    <p class="text-sm font-medium text-slate-800">{{ $document->document_no ?? 'Document' }}</p>
                                    <p class="text-xs text-slate-500">{{ $document->warehouse?->name ?? 'Warehouse' }} · {{ $document->created_at?->format('d M Y') }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <x-status-badge :value="$document->status" :tone="match($document->status) { 'POSTED' => 'success', 'DRAFT' => 'info', 'CANCELLED' => 'danger', default => 'default' }" />
                                    <a href="{{ route('documents.show', $document) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">View</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500">No transactions yet.</p>
                @endif
            </x-panel>

            <x-panel title="Quick actions" subtitle="Common tasks">
                <div class="space-y-2">
                    <a href="{{ route('products.create') }}" class="flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        <span>Create product</span>
                        <span>→</span>
                    </a>
                    <a href="{{ route('warehouses.create') }}" class="flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        <span>Add warehouse</span>
                        <span>→</span>
                    </a>
                    <a href="{{ route('receipts.create') }}" class="flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        <span>New receipt</span>
                        <span>→</span>
                    </a>
                    <a href="{{ route('transfers.create') }}" class="flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        <span>New transfer</span>
                        <span>→</span>
                    </a>
                </div>
            </x-panel>
        </div>
    </div>
</x-wms-layout>
