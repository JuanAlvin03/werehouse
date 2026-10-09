<x-wms-layout title="Receipts">
    <x-page-header title="Receipts" subtitle="Receipt documents in the system." />
        <x-slot:actions>
            <a href="{{ route('receipts.create') }}" class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-700">New receipt</a>
        </x-slot:actions>
    </x-page-header>

    {{--  
    <x-panel>
        @if ($products->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-xs uppercase tracking-[0.12em] text-slate-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">SKU</th>
                            <th class="px-4 py-3 font-medium">Name</th>
                            <th class="px-4 py-3 font-medium">Category</th>
                            <th class="px-4 py-3 font-medium">Unit</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($products as $product)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $product->sku }}</td>
                                <td class="px-4 py-3">{{ $product->name }}</td>
                                <td class="px-4 py-3">{{ $product->category?->name ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $product->unit?->code ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <x-status-badge :value="$product->is_active ? 'Active' : 'Inactive'" :tone="$product->is_active ? 'success' : 'default'" />
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('products.show', $product) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">View</a>
                                        <a href="{{ route('products.edit', $product) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @else
            <p class="text-sm text-slate-500">No products found.</p>
        @endif
    </x-panel>
    --}}
</x-wms-layout>
