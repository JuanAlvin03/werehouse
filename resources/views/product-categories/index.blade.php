<x-wms-layout title="Categories">
    <x-page-header title="Product categories" subtitle="Organize products by type and group.">
        <x-slot:actions>
            <a href="{{ route('product-categories.create') }}" class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-700">New category</a>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        @if ($categories->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-xs uppercase tracking-[0.12em] text-slate-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">Code</th>
                            <th class="px-4 py-3 font-medium">Name</th>
                            <th class="px-4 py-3 font-medium">Parent</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($categories as $category)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $category->code }}</td>
                                <td class="px-4 py-3">{{ $category->name }}</td>
                                <td class="px-4 py-3">{{ $category->parent?->name ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <x-status-badge :value="$category->is_active ? 'Active' : 'Inactive'" :tone="$category->is_active ? 'success' : 'default'" />
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('product-categories.show', $category) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">View</a>
                                        <a href="{{ route('product-categories.edit', $category) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        @else
            <p class="text-sm text-slate-500">No categories found.</p>
        @endif
    </x-panel>
</x-wms-layout>
