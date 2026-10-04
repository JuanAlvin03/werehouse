<x-wms-layout title="Create Warehouse">
    <x-page-header title="Create warehouse" subtitle="Add a new storage site or stock location." />

    <x-panel>
        <form action="{{ route('warehouses.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid gap-5 md:grid-cols-2">
                <x-form-field label="Warehouse code" for="code" :required="true">
                    <input id="code" name="code" value="{{ old('code') }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" required>
                    @error('code') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>

                <x-form-field label="Status" for="is_active">
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-300">
                        Active warehouse
                    </label>
                </x-form-field>

                <x-form-field label="Warehouse name" for="name" :required="true" class="md:col-span-2">
                    <input id="name" name="name" value="{{ old('name') }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" required>
                    @error('name') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>

                <x-form-field label="Address" for="address" class="md:col-span-2">
                    <textarea id="address" name="address" rows="4" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">{{ old('address') }}</textarea>
                    @error('address') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('warehouses.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
                <x-primary-button>Save warehouse</x-primary-button>
            </div>
        </form>
    </x-panel>
</x-wms-layout>
