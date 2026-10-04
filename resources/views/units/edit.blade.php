<x-wms-layout title="Edit Unit">
    <x-page-header title="Edit unit" subtitle="Update unit metadata." />

    <x-panel>
        <form action="{{ route('units.update', $unit) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid gap-5 md:grid-cols-2">
                <x-form-field label="Unit code" for="code" :required="true">
                    <input id="code" name="code" value="{{ old('code', $unit->code) }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" required>
                    @error('code') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>

                <x-form-field label="Symbol" for="symbol">
                    <input id="symbol" name="symbol" value="{{ old('symbol', $unit->symbol) }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                    @error('symbol') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>

                <x-form-field label="Unit name" for="name" :required="true" class="md:col-span-2">
                    <input id="name" name="name" value="{{ old('name', $unit->name) }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" required>
                    @error('name') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('units.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
                <x-primary-button>Update unit</x-primary-button>
            </div>
        </form>
    </x-panel>
</x-wms-layout>
