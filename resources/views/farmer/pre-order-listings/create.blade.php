<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Pre-order Listing</h2>
            <a href="{{ route('farmer.pre-order-listings.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Back to Listings</a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 max-w-2xl">
        <form method="POST" action="{{ route('farmer.pre-order-listings.store') }}" id="listing-form">
            @csrf
            <div class="space-y-4">
                <div>
                    <x-input-label for="source" value="List from *" />
                    <select id="source" name="source" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                        <option value="crop" @selected(old('source') === 'crop')>Crop (planted / growing)</option>
                        <option value="output" @selected(old('source') === 'output')>Harvest output (available stock)</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('source')" />
                </div>

                <div id="crop-field" class="source-field">
                    <x-input-label for="crop_id" value="Select crop *" />
                    <select id="crop_id" name="crop_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        <option value="">— Select crop —</option>
                        @foreach ($crops as $c)
                            <option value="{{ $c->id }}" data-name="{{ e($c->crop_name . ($c->crop_type ? " ({$c->crop_type})" : '')) }}" data-unit="{{ e($c->yield_unit ?? 'kg') }}" data-harvest="{{ $c->expected_harvest_date?->format('Y-m-d') ?? '' }}" @selected(old('crop_id') == $c->id)>{{ $c->crop_name }}{{ $c->crop_type ? " — {$c->crop_type}" : '' }} — Harvest: {{ $c->expected_harvest_date?->format('M Y') ?? '—' }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('crop_id')" />
                </div>

                <div id="output-field" class="source-field" style="display: none;">
                    <x-input-label for="farm_output_id" value="Select harvest output *" />
                    <select id="farm_output_id" name="farm_output_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        <option value="">— Select output —</option>
                        @foreach ($outputs as $o)
                            <option value="{{ $o->id }}" data-name="{{ e($o->product_name) }}" data-qty="{{ $o->quantity_available }}" data-unit="{{ e($o->unit) }}" data-harvest="{{ $o->harvest_date?->format('Y-m-d') ?? '' }}" @selected(old('farm_output_id') == $o->id)>{{ $o->product_name }} — {{ number_format($o->quantity_available, 2) }} {{ $o->unit }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('farm_output_id')" />
                </div>

                <div>
                    <x-input-label for="title" value="Listing title *" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" placeholder="e.g. Maize (Yellow) — Pre-order" required />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="quantity_available" value="Quantity available *" />
                        <x-text-input id="quantity_available" name="quantity_available" type="number" step="0.01" min="0.01" class="mt-1 block w-full" :value="old('quantity_available')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('quantity_available')" />
                    </div>
                    <div>
                        <x-input-label for="unit" value="Unit *" />
                        <x-text-input id="unit" name="unit" type="text" class="mt-1 block w-full" :value="old('unit', 'kg')" placeholder="e.g. kg, bags" required />
                        <x-input-error class="mt-2" :messages="$errors->get('unit')" />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="price_per_unit" value="Price per unit (optional)" />
                        <x-text-input id="price_per_unit" name="price_per_unit" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price_per_unit')" />
                        <x-input-error class="mt-2" :messages="$errors->get('price_per_unit')" />
                    </div>
                    <div>
                        <x-input-label for="expected_harvest_date" value="Expected harvest date" />
                        <x-text-input id="expected_harvest_date" name="expected_harvest_date" type="date" class="mt-1 block w-full" :value="old('expected_harvest_date')" />
                        <x-input-error class="mt-2" :messages="$errors->get('expected_harvest_date')" />
                    </div>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Create Listing</button>
                <a href="{{ route('farmer.pre-order-listings.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 text-sm font-medium">Cancel</a>
            </div>
        </form>
    </div>

    <script>
    document.getElementById('source').addEventListener('change', function () {
        var cropField = document.getElementById('crop-field');
        var outputField = document.getElementById('output-field');
        var cropId = document.getElementById('crop_id');
        var outputId = document.getElementById('farm_output_id');
        if (this.value === 'crop') {
            cropField.style.display = 'block';
            outputField.style.display = 'none';
            cropId.required = true;
            outputId.required = false;
            outputId.value = '';
        } else {
            cropField.style.display = 'none';
            outputField.style.display = 'block';
            cropId.required = false;
            outputId.required = true;
            cropId.value = '';
        }
    });
    document.getElementById('crop_id').addEventListener('change', function () {
        var opt = this.options[this.selectedIndex];
        if (!opt || !opt.value) return;
        document.getElementById('title').value = opt.getAttribute('data-name') || document.getElementById('title').value;
        document.getElementById('unit').value = opt.getAttribute('data-unit') || 'kg';
        document.getElementById('expected_harvest_date').value = opt.getAttribute('data-harvest') || '';
    });
    document.getElementById('farm_output_id').addEventListener('change', function () {
        var opt = this.options[this.selectedIndex];
        if (!opt || !opt.value) return;
        document.getElementById('title').value = opt.getAttribute('data-name') || document.getElementById('title').value;
        document.getElementById('quantity_available').value = opt.getAttribute('data-qty') || '';
        document.getElementById('unit').value = opt.getAttribute('data-unit') || 'kg';
        document.getElementById('expected_harvest_date').value = opt.getAttribute('data-harvest') || '';
    });
    if (document.getElementById('source').value === 'output') {
        document.getElementById('crop-field').style.display = 'none';
        document.getElementById('output-field').style.display = 'block';
    }
    </script>
</x-tenant-layout>
