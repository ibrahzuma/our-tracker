@extends('layout')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Upcoming Bills</h1>
            <p class="text-gray-500 mt-1">Track one-time or recurring household bills.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('bills.create') }}" class="flex items-center px-4 py-2 bg-brand-600 text-white rounded-lg hover:bg-brand-700 transition-colors shadow-lg shadow-brand-500/30 font-medium">
                <i class="fa-solid fa-plus mr-2"></i> Add Bill
            </a>
        </div>
    </div>

    <!-- Outstanding Bills Card -->
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between mb-8">
        <div>
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Pending Amount</p>
            <p class="text-4xl font-black {{ $totalPending > 0 ? 'text-red-600' : 'text-green-600' }} mt-1">TSH {{ number_format($totalPending, 0) }}</p>
        </div>
        <div class="mt-4 md:mt-0 px-6 py-3 bg-gray-50 rounded-xl text-gray-700 font-bold border border-gray-100">
             @if($totalPending > 0)
                <i class="fa-solid fa-clock mr-2 text-red-500"></i> Attention Required
             @else
                <i class="fa-solid fa-check-double mr-2 text-green-500"></i> All clear!
             @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50/50">
                    <tr>
                        <th scope="col" class="px-6 py-4">Bill Name</th>
                        <th scope="col" class="px-6 py-4">Frequency</th>
                        <th scope="col" class="px-6 py-4 text-right">Amount</th>
                        <th scope="col" class="px-6 py-4">Due Date</th>
                        <th scope="col" class="px-6 py-4">Status</th>
                        <th scope="col" class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bills as $bill)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $bill->name }}
                            </td>
                            <td class="px-6 py-4 capitalize text-xs">
                                {{ $bill->frequency ?? 'One-time' }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900 whitespace-nowrap">
                                TSH {{ number_format($bill->amount, 0) }}
                            </td>
                            <td class="px-6 py-4 {{ (!$bill->is_paid && \Carbon\Carbon::parse($bill->due_date)->isPast()) ? 'text-red-500 font-black' : 'text-gray-500' }}">
                                {{ \Carbon\Carbon::parse($bill->due_date)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $bill->is_paid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $bill->is_paid ? 'Paid' : 'Unpaid' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <a href="{{ route('bills.edit', $bill) }}" class="text-gray-400 hover:text-brand-600 transition-colors">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('bills.destroy', $bill) }}" method="POST" onsubmit="return confirm('Delete this bill?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">
                                No bills recorded. Stay on top of your payments!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
