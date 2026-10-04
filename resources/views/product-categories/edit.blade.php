<x-wms-layout title="Edit Category">
    <x-page-header title="Edit category" subtitle="Update category details." />

    <x-panel>
        <form action="{{ route('product-categories.update', $category) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid gap-5 md:grid-cols-2">
                <x-form-field label="Category code" for="code" :required="true">
                    <input id="code" name="code" value="{{ old('code', $category->code) }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" required>
                    @error('code') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>

                <x-form-field label="Status" for="is_active">
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-300">
                        Active category
                    </label>
                </x-form-field>

                <x-form-field label="Parent category" for="parent_id">
                    <select id="parent_id" name="parent_id" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                        <option value="">No parent</option>
                        @foreach ($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                    @error('parent_id') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>

                <x-form-field label="Category name" for="name" :required="true">
                    <input id="name" name="name" value="{{ old('name', $category->name) }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" required>
                    @error('name') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>

                <x-form-field label="Description" for="description" class="md:col-span-2">
                    <textarea id="description" name="description" rows="4" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">{{ old('description', $category->description) }}</textarea>
                    @error('description') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('product-categories.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
                <x-primary-button>Update category</x-primary-button>
            </div>
        </form>
    </x-panel>
</x-wms-layout>
