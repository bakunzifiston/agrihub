<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Production Record</h2>
            <a href="{{ route('farmer.production-records.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Back</a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 max-w-2xl">
        <form method="POST" action="{{ route('farmer.production-records.update', $productionRecord) }}">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="product_type" value="Product Type *" />
                    <select id="product_type" name="product_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                        <option value="crop" @selected($productionRecord->product_type === 'crop')>Crop</option>
                        <option value="livestock" @selected($productionRecord->product_type === 'livestock')>Livestock</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('product_type')" />
                </div>
                <div>
                    <x-input-label for="product_name" value="Product Name *" />
                    <x-text-input id="product_name" name="product_name" type="text" class="mt-1 block w-full" :value="old('product_name', $productionRecord->product_name)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('product_name')" />
                </div>
                <div>
                    <x-input-label for="production_date" value="Production Date *" />
                    <x-text-input id="production_date" name="production_date" type="date" class="mt-1 block w-full" :value="old('production_date', $productionRecord->production_date->format('Y-m-d'))" required />
                    <x-input-error class="mt-2" :messages="$errors->get('production_date')" />
                </div>
                <div>
                    <x-input-label for="quantity_produced" value="Quantity Produced *" />
                    <x-text-input id="quantity_produced" name="quantity_produced" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('quantity_produced', $productionRecord->quantity_produced)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('quantity_produced')" />
                </div>
                <div>
                    <x-input-label for="quantity_unit" value="Unit *" />
                    <x-text-input id="quantity_unit" name="quantity_unit" type="text" class="mt-1 block w-full" :value="old('quantity_unit', $productionRecord->quantity_unit)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('quantity_unit')" />
                </div>
                <div>
                    <x-input-label for="quality_grade" value="Quality Grade" />
                    <x-text-input id="quality_grade" name="quality_grade" type="text" class="mt-1 block w-full" :value="old('quality_grade', $productionRecord->quality_grade)" />
                    <x-input-error class="mt-2" :messages="$errors->get('quality_grade')" />
                </div>
                <div>
                    <x-input-label for="losses_quantity" value="Losses" />
                    <x-text-input id="losses_quantity" name="losses_quantity" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('losses_quantity', $productionRecord->losses_quantity)" />
                    <x-input-error class="mt-2" :messages="$errors->get('losses_quantity')" />
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="remarks" value="Remarks" />
                    <textarea id="remarks" name="remarks" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">{{ old('remarks', $productionRecord->remarks) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('remarks')" />
                </div>
            </div>
            <div class="mt-6 flex gap-4">
                <x-primary-button>Update Record</x-primary-button>
                <a href="{{ route('farmer.production-records.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Cancel</a>
            </div>
        </form>
    </div>
</x-tenant-layout>
