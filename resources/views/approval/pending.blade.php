<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Account Pending Approval
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-amber-50 border border-amber-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <div class="flex items-start gap-4">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-amber-200 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-amber-900 mb-2">Your account is pending approval</h3>
                            <p class="text-amber-800 mb-4">
                                Thank you for registering! Your account has been submitted for review by our administrator. 
                                You will be notified once your account has been approved and you can access the dashboard.
                            </p>
                            <p class="text-sm text-amber-700">
                                This usually takes 1-2 business days. If you have any questions, please contact support.
                            </p>
                            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                                @csrf
                                <button type="submit" class="text-sm text-amber-700 hover:text-amber-900 underline">
                                    Log out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
