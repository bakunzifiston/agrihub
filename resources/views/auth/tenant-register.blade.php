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

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
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

        {{-- Agribusiness: business name, business type, location --}}
        @if ($tenantType === 'agribusiness')
            <div class="mt-4">
                <x-input-label for="business_name" value="Business Name" />
                <x-text-input id="business_name" class="block mt-1 w-full" type="text" name="business_name" :value="old('business_name')" required />
                <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="business_type" value="Business Type" />
                <select id="business_type" name="business_type" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                    <option value="">Select</option>
                    <option value="Buyer" @selected(old('business_type') === 'Buyer')>Buyer</option>
                    <option value="Processor" @selected(old('business_type') === 'Processor')>Processor</option>
                    <option value="Exporter" @selected(old('business_type') === 'Exporter')>Exporter</option>
                    <option value="Retailer" @selected(old('business_type') === 'Retailer')>Retailer</option>
                </select>
                <x-input-error :messages="$errors->get('business_type')" class="mt-2" />
            </div>
        @endif

        {{-- Common: location, country, district (all tenant types) --}}
        <div class="mt-4">
            <x-input-label for="location" value="Location / Address" />
            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location')" placeholder="e.g. Village, Sector" />
            <x-input-error :messages="$errors->get('location')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="country" value="Country" />
            <x-text-input id="country" class="block mt-1 w-full" type="text" name="country" :value="old('country')" placeholder="e.g. Rwanda" />
            <x-input-error :messages="$errors->get('country')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="district" value="District" />
            <x-text-input id="district" class="block mt-1 w-full" type="text" name="district" :value="old('district')" />
            <x-input-error :messages="$errors->get('district')" class="mt-2" />
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
</x-guest-layout>
