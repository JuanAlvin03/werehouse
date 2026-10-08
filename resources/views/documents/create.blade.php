<x-wms-layout title="Create Document">
    <x-page-header title="Create Document" subtitle="Add a new document to the system." />

    <x-panel>
        <form action="{{ route('documents.store') }}" method="POST" class="space-y-5">
            @csrf
            {{--  
            <div class="grid gap-5 md:grid-cols-2">
                <x-form-field label="SKU" for="sku" :required="true">
                    <input id="sku" name="sku" value="{{ old('sku') }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" required>
                    @error('sku') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>

                <x-form-field label="Barcode" for="barcode">
                    <input id="barcode" name="barcode" value="{{ old('barcode') }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                    @error('barcode') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>

                <x-form-field label="Product name" for="name" :required="true">
                    <input id="name" name="name" value="{{ old('name') }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" required>
                    @error('name') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>

                <x-form-field label="Unit" for="unit_id" :required="true">
                    <select id="unit_id" name="unit_id" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" required>
                        <option value="">Select unit</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->code }} - {{ $unit->name }}</option>
                        @endforeach
                    </select>
                    @error('unit_id') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>

                <x-form-field label="Category" for="category_id">
                    <select id="category_id" name="category_id" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                        <option value="">Select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>

                <x-form-field label="Status" for="is_active">
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-300">
                        Active product
                    </label>
                </x-form-field>
            </div>

            <x-form-field label="Description" for="description">
                <textarea id="description" name="description" rows="4" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">{{ old('description') }}</textarea>
                @error('description') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
            </x-form-field>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
                <x-primary-button>Save product</x-primary-button>
            </div>--}}
        </form>
    </x-panel>
</x-wms-layout>
