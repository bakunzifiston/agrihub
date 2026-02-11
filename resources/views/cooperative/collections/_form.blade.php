@props(['collection' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="member_id" value="Member (farmer)" />
        <select id="member_id" name="member_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            <option value="">— Or enter name below —</option>
            @foreach ($members as $m)
                <option value="{{ $m->id }}" @selected(old('member_id', $collection?->member_id) == $m->id)>{{ $m->display_name }}{{ $m->membership_number ? ' (' . $m->membership_number . ')' : '' }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('member_id')" />
    </div>
    <div>
        <x-input-label for="contributor_name" value="Or enter farmer name manually" />
        <x-text-input id="contributor_name" name="contributor_name" type="text" class="mt-1 block w-full" :value="old('contributor_name', $collection ? $collection->getRawOriginal('contributor_name') : '')" placeholder="Type name if not selecting a member" />
        <p class="text-xs text-gray-500 mt-1">Select a member above or enter the farmer name here.</p>
        <x-input-error class="mt-2" :messages="$errors->get('contributor_name')" />
    </div>
    <div>
        <x-input-label for="product_name" value="Product Name *" />
        <x-text-input id="product_name" name="product_name" type="text" class="mt-1 block w-full" :value="old('product_name', $collection?->product_name)" required />
        <x-input-error class="mt-2" :messages="$errors->get('product_name')" />
    </div>
    <div>
        <x-input-label for="collection_date" value="Collection Date *" />
        <x-text-input id="collection_date" name="collection_date" type="date" class="mt-1 block w-full" :value="old('collection_date', $collection?->collection_date?->format('Y-m-d'))" required />
        <x-input-error class="mt-2" :messages="$errors->get('collection_date')" />
    </div>
    <div>
        <x-input-label for="quantity_collected" value="Quantity Collected *" />
        <x-text-input id="quantity_collected" name="quantity_collected" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('quantity_collected', $collection?->quantity_collected)" required />
        <x-input-error class="mt-2" :messages="$errors->get('quantity_collected')" />
    </div>
    <div>
        <x-input-label for="unit" value="Unit *" />
        <x-text-input id="unit" name="unit" type="text" class="mt-1 block w-full" :value="old('unit', $collection?->unit)" placeholder="e.g. kg, tons" required />
        <x-input-error class="mt-2" :messages="$errors->get('unit')" />
    </div>
    <div>
        <x-input-label for="quality_grade" value="Quality Grade" />
        <x-text-input id="quality_grade" name="quality_grade" type="text" class="mt-1 block w-full" :value="old('quality_grade', $collection?->quality_grade)" placeholder="e.g. A, B, C" />
        <x-input-error class="mt-2" :messages="$errors->get('quality_grade')" />
    </div>
    <div>
        <x-input-label for="collection_point" value="Collection Point" />
        <x-text-input id="collection_point" name="collection_point" type="text" class="mt-1 block w-full" :value="old('collection_point', $collection?->collection_point)" />
        <x-input-error class="mt-2" :messages="$errors->get('collection_point')" />
    </div>
    <div>
        <x-input-label for="price_per_unit" value="Price per Unit" />
        <x-text-input id="price_per_unit" name="price_per_unit" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price_per_unit', $collection?->price_per_unit)" />
        <p class="text-xs text-gray-500 mt-1">Total value will be calculated automatically</p>
        <x-input-error class="mt-2" :messages="$errors->get('price_per_unit')" />
    </div>
</div>
