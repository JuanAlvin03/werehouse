<x-wms-layout title="Edit Business Partner">
    <x-page-header title="Edit partner" subtitle="Update supplier or customer details." />

    <x-panel>
        <form action="{{ route('business-partners.update', $partner) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid gap-5 md:grid-cols-2">
                <x-form-field label="Partner code" for="code" :required="true">
                    <input id="code" name="code" value="{{ old('code', $partner->code) }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" required>
                    @error('code') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>

                <x-form-field label="Status" for="is_active">
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $partner->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-300">
                        Active partner
                    </label>
                </x-form-field>

                <x-form-field label="Partner name" for="name" :required="true">
                    <input id="name" name="name" value="{{ old('name', $partner->name) }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" required>
                    @error('name') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>

                <x-form-field label="Partner type" for="partner_type" :required="true">
                    <select id="partner_type" name="partner_type" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" required>
                        <option value="">Select type</option>
                        <option value="SUPPLIER" {{ old('partner_type', $partner->partner_type) == 'SUPPLIER' ? 'selected' : '' }}>Supplier</option>
                        <option value="CUSTOMER" {{ old('partner_type', $partner->partner_type) == 'CUSTOMER' ? 'selected' : '' }}>Customer</option>
                        <option value="BOTH" {{ old('partner_type', $partner->partner_type) == 'BOTH' ? 'selected' : '' }}>Both</option>
                    </select>
                    @error('partner_type') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>

                <x-form-field label="Phone" for="phone">
                    <input id="phone" name="phone" value="{{ old('phone', $partner->phone) }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                    @error('phone') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>

                <x-form-field label="Email" for="email">
                    <input id="email" name="email" type="email" value="{{ old('email', $partner->email) }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                    @error('email') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>

                <x-form-field label="Address" for="address" class="md:col-span-2">
                    <textarea id="address" name="address" rows="4" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">{{ old('address', $partner->address) }}</textarea>
                    @error('address') <span class="text-sm text-rose-600">{{ $message }}</span> @enderror
                </x-form-field>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('business-partners.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
                <x-primary-button>Update partner</x-primary-button>
            </div>
        </form>
    </x-panel>
</x-wms-layout>
