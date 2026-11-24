<x-wms-layout title="Create Stock Movement Report">
    <x-page-header title="Create Stock Movement Report" subtitle="List of stock movements in a warehouse" />

    <x-panel>
        <form action="{{ route('reports.stock-movement-by-warehouse.show') }}" method="POST" class="space-y-5" id="myForm">
            @csrf

            <div class="grid gap-5 md:grid-cols-2">
                {{-- make this a select for the warehouses --}}
                <x-form-field label="Warehouse" for="warehouse" :required="true">
                    <input id="warehouse" name="warehouse" value="{{ old('warehouse') }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" required>
                    @error('warehouse') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>

                <x-form-field label="Start date" for="start_date" :required="true">
                    <input id="start_date" name="start_date" value="{{ old('start_date') }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" required>
                    @error('start_date') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>

                <x-form-field label="End date" for="end_date" :required="true" class="md:col-span-2">
                    <input id="end_date" name="end_date" value="{{ old('end_date') }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" required>
                    @error('end_date') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
                <x-primary-button>Generate Report</x-primary-button>
            </div>
        </form>
    </x-panel>
</x-wms-layout>
