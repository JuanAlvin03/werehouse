<x-wms-layout title="Stock Movement Report">
    <x-page-header title="Stock Movement Report" subtitle="List of stock movements in a warehouse" />

    <x-panel>
        @foreach ($stock_movements as $sm)
            {{ $sm->product->name }} - {{ $sm->qty_in }} - {{ $sm->qty_out }} <br>
        @endforeach
    </x-panel>
</x-wms-layout>
