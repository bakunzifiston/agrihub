<x-tenant-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Employee</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('farmer.employees.update', $employee) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('farmer.employees._form', ['employee' => $employee])
                </form>
            </div>
        </div>
    </div>
</x-tenant-layout>
