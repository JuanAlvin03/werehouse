<x-wms-layout title="Product Details">
    <x-page-header title="{{ $product->name }}" subtitle="{{ $product->sku }}">
        <x-slot:actions>
            <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Edit</a>
            <a href="{{ route('products.destroy', $product->id) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-red-500 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Delete</a>
        </x-slot:actions>
    </x-page-header>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4 mb-6">
        <x-stat-card label="Total Available" value="{{ $product->inventories->sum('quantity_on_hand') }}" tone="success" icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5v-9zm8 3.5 8-4.5M12 11v10" /></svg>' />
        <x-stat-card label="Total Reserved" value="{{ $product->inventories->sum('quantity_reserved') }}" tone="warning" icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.5 20V8.5L12 4l8.5 4.5V20M7 10h10M7 14h10M7 18h10" /></svg>' />
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <x-panel title="Overview">
            <dl class="space-y-4 text-sm text-slate-700">
                <div class="flex justify-between gap-4 border-b border-slate-200 pb-3"><dt class="text-slate-500">SKU</dt><dd class="font-medium text-slate-900">{{ $product->sku }}</dd></div>
                <div class="flex justify-between gap-4 border-b border-slate-200 pb-3"><dt class="text-slate-500">Barcode</dt><dd class="font-medium text-slate-900">{{ $product->barcode ?? '—' }}</dd></div>
                <div class="flex justify-between gap-4 border-b border-slate-200 pb-3"><dt class="text-slate-500">Category</dt><dd class="font-medium text-slate-900">{{ $product->category?->name ?? '—' }}</dd></div>
                <div class="flex justify-between gap-4 border-b border-slate-200 pb-3"><dt class="text-slate-500">Unit</dt><dd class="font-medium text-slate-900">{{ $product->unit?->name ?? '—' }}</dd></div>
                <div class="flex justify-between gap-4 border-b border-slate-200 pb-3"><dt class="text-slate-500">Status</dt><dd><x-status-badge :value="$product->is_active ? 'Active' : 'Inactive'" :tone="$product->is_active ? 'success' : 'default'" /></dd></div>
            </dl>
        </x-panel>

        <x-panel title="Description">
            <p class="text-sm leading-6 text-slate-600">
                {{ $product->description ?: 'No description provided for this product.' }}
            </p>
        </x-panel>
    </div>

    <div class="grid gap-6 mt-6">
        <!-- Inventories -->
        <x-panel title="Inventories">
            @if ($product->inventories->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 font-medium text-xs text-slate-500">Warehouse</th>
                                <th class="px-4 py-3 font-medium text-xs text-slate-500 text-right">Qty On Hand</th>
                                <th class="px-4 py-3 font-medium text-xs text-slate-500 text-right">Qty Reserved</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($product->inventories as $item)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-slate-800">{{ $item->warehouse?->name }}</td>
                                    <td class="px-4 py-3 text-right font-medium">{{ number_format($item->quantity_on_hand, 3) }}</td>
                                    <td class="px-4 py-3 text-right font-medium">{{ number_format($item->quantity_reserved, 3) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-slate-500">There is no stock for this product.</p>
            @endif
        </x-panel>
    </div>
</x-wms-layout>
