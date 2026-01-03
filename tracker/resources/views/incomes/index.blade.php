@extends('layout')

@section('content')
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Income</h1>
            <p class="text-gray-500 mt-1">Manage and track your earnings.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('incomes.export') }}" class="flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors shadow-sm font-medium">
                <i class="fa-solid fa-download mr-2"></i> Export
            </a>
            <a href="{{ route('incomes.create') }}" class="flex items-center px-4 py-2 bg-brand-600 text-white rounded-lg hover:bg-brand-700 transition-colors shadow-lg shadow-brand-500/30 font-medium">
                <i class="fa-solid fa-plus mr-2"></i> Add Income
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Income</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">TSH {{ number_format($totalIncome, 0) }}</p>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-green-600 text-xl">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">This Month</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">TSH {{ number_format($currentMonthIncome, 0) }}</p>
            </div>
            <div class="w-12 h-12 bg-brand-50 rounded-full flex items-center justify-center text-brand-600 text-xl">
                <i class="fa-solid fa-calendar-day"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- Filter Bar -->
        <div class="p-4 border-b border-gray-100 bg-gray-50/50">
            <form method="GET" action="{{ route('incomes.index') }}" class="flex flex-col md:flex-row gap-4 items-end md:items-center">
                <div class="flex-1 w-full md:w-auto grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full bg-white border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-2.5">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full bg-white border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-2.5">
                    </div>
                </div>
                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit" class="flex-1 md:flex-none px-5 py-2.5 text-sm font-medium text-white bg-gray-800 rounded-lg hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300">
                        Filter
                    </button>
                    <a href="{{ route('incomes.index') }}" class="flex-1 md:flex-none px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-gray-200 text-center">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50/50">
                    <tr>
                        <th scope="col" class="px-6 py-4">Date</th>
                        <th scope="col" class="px-6 py-4">Category</th>
                        <th scope="col" class="px-6 py-4">Channel</th>
                        <th scope="col" class="px-6 py-4 text-right">Amount</th>
                        <th scope="col" class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($incomes as $income)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $income->date->format('M j, Y') }}
                                <div class="text-xs text-gray-500">{{ $income->description }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-200">
                                    {{ $income->category ?? 'General' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="capitalize text-xs font-bold mr-2
                                        {{ $income->source == 'cash' ? 'text-green-600' : '' }}
                                        {{ $income->source == 'bank' ? 'text-blue-600' : '' }}
                                        {{ $income->source == 'mobile_money' ? 'text-yellow-600' : '' }}">
                                        {{ str_replace('_', ' ', $income->source) }}
                                    </span>
                                    @if($income->provider)
                                        <span class="bg-indigo-50 text-indigo-700 text-xs px-2 py-0.5 rounded-full">{{ $income->provider }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900">
                                TSH {{ number_format($income->amount, 0) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <form action="{{ route('incomes.duplicate', $income) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-gray-400 hover:text-brand-600 transition-colors" title="Duplicate">
                                            <i class="fa-regular fa-copy"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('incomes.edit', $income) }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('incomes.destroy', $income) }}" method="POST" onsubmit="return confirm('Are you sure?');">
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
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-inbox text-4xl text-gray-300 mb-3"></i>
                                    <p>No income records found for this period.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
