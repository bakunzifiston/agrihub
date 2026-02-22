<x-guest-layout>
    <div class="mb-6">
        <a href="{{ url('/') }}" class="text-sm text-stone-500 hover:text-stone-700 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to portal selection
        </a>
    </div>

    <h2 class="text-xl font-semibold text-stone-800 mb-1">{{ $tenantLabel }} Registration</h2>
    <p class="text-sm text-stone-500 mb-6">Create your account</p>

    @auth
        <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-800">
            You are currently logged in. Registering a new account will log you out and sign you in as the new user.
        </div>
    @endauth

    <form method="POST" action="{{ route($tenantType . '.register.post') }}">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="given-name" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="last_name" :value="__('Last Name')" />
                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Farmer: farm name, location, country, district, farm type --}}
        @if ($tenantType === 'farmer')
            <div class="mt-4">
                <x-input-label for="farm_name" value="Farm Name" />
                <x-text-input id="farm_name" class="block mt-1 w-full" type="text" name="farm_name" :value="old('farm_name')" required />
                <x-input-error :messages="$errors->get('farm_name')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="farm_type" value="Farm Type" />
                <select id="farm_type" name="farm_type" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                    <option value="">Select</option>
                    <option value="Crop" @selected(old('farm_type') === 'Crop')>Crop</option>
                    <option value="Livestock" @selected(old('farm_type') === 'Livestock')>Livestock</option>
                    <option value="Mixed" @selected(old('farm_type') === 'Mixed')>Mixed</option>
                </select>
                <x-input-error :messages="$errors->get('farm_type')" class="mt-2" />
            </div>
        @endif

        {{-- Cooperative: cooperative name, location, country, district, focus, members range --}}
        @if ($tenantType === 'cooperative')
            <div class="mt-4">
                <x-input-label for="cooperative_name" value="Cooperative Name" />
                <x-text-input id="cooperative_name" class="block mt-1 w-full" type="text" name="cooperative_name" :value="old('cooperative_name')" required />
                <x-input-error :messages="$errors->get('cooperative_name')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="cooperative_focus" value="Cooperative Focus" />
                <select id="cooperative_focus" name="cooperative_focus" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                    <option value="">Select</option>
                    <option value="Crops" @selected(old('cooperative_focus') === 'Crops')>Crops</option>
                    <option value="Livestock" @selected(old('cooperative_focus') === 'Livestock')>Livestock</option>
                    <option value="Mixed" @selected(old('cooperative_focus') === 'Mixed')>Mixed</option>
                </select>
                <x-input-error :messages="$errors->get('cooperative_focus')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="members_range" value="Number of Members (range)" />
                <select id="members_range" name="members_range" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                    <option value="">Select</option>
                    <option value="1-50" @selected(old('members_range') === '1-50')>1 - 50</option>
                    <option value="51-200" @selected(old('members_range') === '51-200')>51 - 200</option>
                    <option value="201-500" @selected(old('members_range') === '201-500')>201 - 500</option>
                    <option value="501-1000" @selected(old('members_range') === '501-1000')>501 - 1000</option>
                    <option value="1000+" @selected(old('members_range') === '1000+')>1000+</option>
                </select>
                <x-input-error :messages="$errors->get('members_range')" class="mt-2" />
            </div>
        @endif

        {{-- Agribusiness: business name --}}
        @if ($tenantType === 'agribusiness')
            <div class="mt-4">
                <x-input-label for="business_name" value="Business Name" />
                <x-text-input id="business_name" class="block mt-1 w-full" type="text" name="business_name" :value="old('business_name')" required />
                <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
            </div>
        @endif

        {{-- Common: cascading location selects (all tenant types) --}}
        <div class="mt-4">
            <x-input-label for="country" value="Country *" />
            <select id="country" name="country" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                <option value="">Select country</option>
            </select>
            <x-input-error :messages="$errors->get('country')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="district" value="District *" />
            <select id="district" name="district" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required disabled>
                <option value="">Select district</option>
            </select>
            <x-input-error :messages="$errors->get('district')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="sector" value="Sector" />
            <select id="sector" name="sector" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" disabled>
                <option value="">Select sector</option>
            </select>
            <x-input-error :messages="$errors->get('sector')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="cell" value="Cell" />
            <select id="cell" name="cell" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" disabled>
                <option value="">Select cell</option>
            </select>
            <x-input-error :messages="$errors->get('cell')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="village" value="Village" />
            <select id="village" name="village" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" disabled>
                <option value="">Select village</option>
            </select>
            <x-input-error :messages="$errors->get('village')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="location" value="Address / Location details" />
            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location')" placeholder="e.g. Street, landmark" />
            <x-input-error :messages="$errors->get('location')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-6 gap-4">
            <a class="underline text-sm text-stone-600 hover:text-stone-900" href="{{ route($tenantType . '.login') }}">
                Already have an account? Log in
            </a>
            <x-primary-button class="w-full sm:w-auto">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const countrySelect = document.getElementById('country');
        const districtSelect = document.getElementById('district');
        const sectorSelect = document.getElementById('sector');
        const cellSelect = document.getElementById('cell');
        const villageSelect = document.getElementById('village');

        const apiBase = '{{ url('/api/locations') }}';

        function resetSelect(select, placeholder, disable = true) {
            select.innerHTML = '<option value="">' + placeholder + '</option>';
            select.disabled = disable;
        }

        function populateSelect(select, data, placeholder, disabled = false) {
            select.innerHTML = '<option value="">' + placeholder + '</option>';
            data.forEach(function(item) {
                const opt = document.createElement('option');
                opt.value = item.name;
                opt.textContent = item.name;
                opt.dataset.id = item.id;
                select.appendChild(opt);
            });
            select.disabled = disabled || data.length === 0;
        }

        function getSelectedId(select) {
            const selectedOpt = select.options[select.selectedIndex];
            return selectedOpt ? selectedOpt.dataset.id : null;
        }

        fetch(apiBase + '/countries')
            .then(r => r.json())
            .then(data => {
                populateSelect(countrySelect, data, 'Select country', false);
                @if(old('country'))
                countrySelect.value = '{{ old('country') }}';
                if (countrySelect.value) countrySelect.dispatchEvent(new Event('change'));
                @endif
            });

        countrySelect.addEventListener('change', function() {
            resetSelect(districtSelect, 'Select district');
            resetSelect(sectorSelect, 'Select sector');
            resetSelect(cellSelect, 'Select cell');
            resetSelect(villageSelect, 'Select village');

            const id = getSelectedId(this);
            if (!id) return;

            fetch(apiBase + '/districts?country_id=' + id)
                .then(r => r.json())
                .then(data => {
                    populateSelect(districtSelect, data, 'Select district', false);
                    @if(old('district'))
                    districtSelect.value = '{{ old('district') }}';
                    if (districtSelect.value) districtSelect.dispatchEvent(new Event('change'));
                    @endif
                });
        });

        districtSelect.addEventListener('change', function() {
            resetSelect(sectorSelect, 'Select sector');
            resetSelect(cellSelect, 'Select cell');
            resetSelect(villageSelect, 'Select village');

            const id = getSelectedId(this);
            if (!id) return;

            fetch(apiBase + '/sectors?district_id=' + id)
                .then(r => r.json())
                .then(data => {
                    populateSelect(sectorSelect, data, 'Select sector', false);
                    @if(old('sector'))
                    sectorSelect.value = '{{ old('sector') }}';
                    if (sectorSelect.value) sectorSelect.dispatchEvent(new Event('change'));
                    @endif
                });
        });

        sectorSelect.addEventListener('change', function() {
            resetSelect(cellSelect, 'Select cell');
            resetSelect(villageSelect, 'Select village');

            const id = getSelectedId(this);
            if (!id) return;

            fetch(apiBase + '/cells?sector_id=' + id)
                .then(r => r.json())
                .then(data => {
                    populateSelect(cellSelect, data, 'Select cell', false);
                    @if(old('cell'))
                    cellSelect.value = '{{ old('cell') }}';
                    if (cellSelect.value) cellSelect.dispatchEvent(new Event('change'));
                    @endif
                });
        });

        cellSelect.addEventListener('change', function() {
            resetSelect(villageSelect, 'Select village');

            const id = getSelectedId(this);
            if (!id) return;

            fetch(apiBase + '/villages?cell_id=' + id)
                .then(r => r.json())
                .then(data => {
                    populateSelect(villageSelect, data, 'Select village', false);
                    @if(old('village'))
                    villageSelect.value = '{{ old('village') }}';
                    @endif
                });
        });
    });
    </script>
</x-guest-layout>
