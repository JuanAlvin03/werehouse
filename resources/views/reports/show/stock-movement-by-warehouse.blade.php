<x-wms-layout title="Stock Movement Report">
    <x-page-header title="{{ $warehouse->name }} Warehouse" subtitle="{{ $start_date }} - {{ $end_date }}" />

    <x-panel title="Items">
        @if ($stock_movements->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 font-medium text-xs text-slate-500">SKU</th>
                            <th class="px-4 py-3 font-medium text-xs text-slate-500">Product</th>
                            <th class="px-4 py-3 font-medium text-xs text-slate-500">Unit</th>
                            <th class="px-4 py-3 font-medium text-xs text-slate-500 text-right">Qty In</th>
                            <th class="px-4 py-3 font-medium text-xs text-slate-500 text-right">Qty Out</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($stock_movements as $sm)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $sm->product?->sku }}</td>
                                <td class="px-4 py-3">{{ $sm->product?->name }}</td>
                                <td class="px-4 py-3">{{ $sm->product?->unit?->code }}</td>
                                <td class="px-4 py-3 text-right font-medium">{{ number_format($sm->qty_in, 3) }}</td>
                                <td class="px-4 py-3 text-right font-medium">{{ number_format($sm->qty_out, 3) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-slate-500">No stock movement for this warehouse in the specified time period.</p>
        @endif
    </x-panel>
</x-wms-layout>
