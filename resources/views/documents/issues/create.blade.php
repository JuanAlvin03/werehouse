<x-wms-layout title="Create Stock Issue">
    <x-page-header title="Create stock issue" subtitle="Record goods issued to customer." />

    <x-panel>
        <form action="{{ route('issues.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Document Header -->
            <div>
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Document Information</h3>
                <div class="grid gap-5 md:grid-cols-2">
                    <x-form-field label="Document No." for="document_no">
                        <input id="document_no" name="document_no" type="text" value="{{ $documentNo }}" class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-600 cursor-not-allowed" disabled>
                        <p class="text-xs text-slate-500 mt-1">Auto-generated</p>
                    </x-form-field>

                    <x-form-field label="Warehouse" for="warehouse_id" :required="true">
                        <select id="warehouse_id" name="warehouse_id" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" required>
                            <option value="">Select warehouse</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->code }} - {{ $warehouse->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('warehouse_id') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                    </x-form-field>

                    <x-form-field label="Customer" for="business_partner_id">
                        <select id="business_partner_id" name="business_partner_id" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                            <option value="">No customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('business_partner_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->code }} - {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('business_partner_id') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                    </x-form-field>

                    <x-form-field label="External Reference" for="external_reference">
                        <input id="external_reference" name="external_reference" type="text" value="{{ old('external_reference') }}" placeholder="e.g., SO-2026-001" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                        @error('external_reference') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                    </x-form-field>
                </div>

                <x-form-field label="Notes" for="notes" class="md:col-span-2">
                    <textarea id="notes" name="notes" rows="3" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                    @error('notes') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>
            </div>

            <!-- Items Section -->
            <div>
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Issue Items</h3>
                
                <div id="items-container" class="space-y-3 mb-4">
                    <div class="item-row grid gap-3 md:grid-cols-5 items-end">
                        <x-form-field label="Product" for="items.0.product_id" :required="true">
                            <select name="items[0][product_id]" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200 product-select" required>
                                <option value="">Select product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-unit-id="{{ $product->unit_id }}">
                                        {{ $product->sku }} - {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </x-form-field>

                        <x-form-field label="Quantity" for="items.0.quantity" :required="true">
                            <input type="number" name="items[0][quantity]" step="0.001" min="0.001" value="{{ old('items.0.quantity') }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="0.000" required>
                        </x-form-field>

                        <x-form-field label="Unit" for="items.0.unit_id" :required="true">
                            <select name="items[0][unit_id]" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200 unit-select" required>
                                <option value="">Select unit</option>
                            </select>
                        </x-form-field>

                        <x-form-field label="Notes" for="items.0.notes">
                            <input type="text" name="items[0][notes]" value="{{ old('items.0.notes') }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="Item notes">
                        </x-form-field>

                        <button type="button" class="remove-item-btn px-3 py-2.5 rounded-lg border border-red-300 bg-red-50 text-red-700 text-sm font-medium hover:bg-red-100" style="display: none;">Remove</button>
                    </div>
                </div>

                <button type="button" id="add-item-btn" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    + Add Item
                </button>

                @error('items') <div class="text-sm text-rose-600 mt-2">{{ $message }}</div> @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('issues.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
                <x-primary-button>Create Issue</x-primary-button>
            </div>
        </form>
    </x-panel>
</x-wms-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addBtn = document.getElementById('add-item-btn');
    const container = document.getElementById('items-container');
    let itemCount = 1;

    // Populate units for initial row
    populateUnits(0);

    addBtn.addEventListener('click', function() {
        const template = container.querySelector('.item-row').cloneNode(true);
        
        // Update all indices
        template.querySelectorAll('input, select, button').forEach(el => {
            let name = el.getAttribute('name') || el.getAttribute('for') || el.id || '';
            name = name.replace(/\[\d+\]/g, `[${itemCount}]`);
            if (el.getAttribute('name')) el.setAttribute('name', name);
            if (el.getAttribute('for')) el.setAttribute('for', name);
            if (el.id) el.id = name;
            
            // Reset values
            if (el.tagName === 'INPUT') el.value = '';
            if (el.tagName === 'SELECT') el.value = '';
        });

        // Show remove button
        template.querySelector('.remove-item-btn').style.display = 'block';
        template.querySelector('.remove-item-btn').addEventListener('click', function() {
            template.remove();
        });

        // Set up product change listener
        template.querySelector('.product-select').addEventListener('change', function() {
            populateUnits(itemCount);
        });

        container.appendChild(template);
        itemCount++;
    });

    // Product change listeners for dynamic unit population
    container.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            const match = e.target.name.match(/\[(\d+)\]/);
            if (match) {
                populateUnits(parseInt(match[1]));
            }
        }
    });

    function populateUnits(index) {
        const select = document.querySelector(`select[name="items[${index}][product_id]"]`);
        const unitSelect = document.querySelector(`select[name="items[${index}][unit_id]"]`);
        
        if (select && select.value) {
            const option = select.querySelector(`option[value="${select.value}"]`);
            const unitId = option?.dataset.unitId;
            
            if (unitId) {
                unitSelect.value = unitId;
            }
        }
    }
});
</script>
