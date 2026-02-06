<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Farmer Payments</h2>
            <a href="{{ route('cooperative.payments.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Payment</a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        @if ($payments->isEmpty())
            <p class="text-gray-600">No payments yet.</p>
            <a href="{{ route('cooperative.payments.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Payment</a>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Farmer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Method</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($payments as $p)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $p->farmer?->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($p->amount_paid, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $p->payment_method ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $p->reference_number ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $p->payment_date->format('M d, Y') }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $p->payment_status === 'completed' ? 'bg-primary-100 text-primary' : ($p->payment_status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-700') }}">{{ ucfirst($p->payment_status ?? '-') }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('cooperative.payments.edit', $p) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                    <form method="POST" action="{{ route('cooperative.payments.destroy', $p) }}" class="inline" onsubmit="return confirm('Remove this payment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-tenant-layout>
