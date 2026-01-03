@extends('layout')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Loans & Debts</h1>
            <p class="text-gray-500 mt-1">Track money you've lent to or borrowed from others.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('loans.create') }}" class="flex items-center px-4 py-2 bg-brand-600 text-white rounded-lg hover:bg-brand-700 transition-colors shadow-lg shadow-brand-500/30 font-medium">
                <i class="fa-solid fa-plus mr-2"></i> Add Record
            </a>
        </div>
    </div>

    <!-- Loan Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Others Owe You</p>
                <p class="text-3xl font-bold text-green-600 mt-1">TSH {{ number_format($totalLent, 0) }}</p>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-green-600 text-xl">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total You Owe Others</p>
                <p class="text-3xl font-bold text-red-600 mt-1">TSH {{ number_format($totalBorrowed, 0) }}</p>
            </div>
            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-red-600 text-xl">
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50/50">
                    <tr>
                        <th scope="col" class="px-6 py-4">Contact</th>
                        <th scope="col" class="px-6 py-4">Type</th>
                        <th scope="col" class="px-6 py-4 text-right">Original</th>
                        <th scope="col" class="px-6 py-4 text-right">Balance</th>
                        <th scope="col" class="px-6 py-4">Due Date</th>
                        <th scope="col" class="px-6 py-4">Status</th>
                        <th scope="col" class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($loans as $loan)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $loan->contact_name }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold uppercase {{ $loan->type == 'lent' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $loan->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-gray-400">
                                {{ number_format($loan->amount, 0) }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900 whitespace-nowrap">
                                TSH {{ number_format($loan->balance, 0) }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('M d, Y') : 'No date' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $loan->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <a href="{{ route('loans.edit', $loan) }}" class="text-gray-400 hover:text-brand-600 transition-colors">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('loans.destroy', $loan) }}" method="POST" onsubmit="return confirm('Delete this loan record?');">
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
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400 italic">
                                No loan records found. Good for you!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
