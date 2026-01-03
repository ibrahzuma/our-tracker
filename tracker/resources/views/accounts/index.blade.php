@extends('layout')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Accounts & Balances</h1>
            <p class="text-gray-500 mt-1">Snapshot of your current financial standing by account.</p>
        </div>
        <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium">
            <i class="fa-solid fa-circle-info mr-2"></i> Calculated from your transaction history
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($accounts as $account)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl font-bold
                                {{ $account['source'] == 'bank' ? 'bg-blue-100 text-blue-600' : '' }}
                                {{ $account['source'] == 'mobile_money' ? 'bg-yellow-100 text-yellow-600' : '' }}
                                {{ $account['source'] == 'cash' ? 'bg-green-100 text-green-600' : '' }}">
                                @if($account['source'] == 'bank') <i class="fa-solid fa-building-columns"></i>
                                @elseif($account['source'] == 'mobile_money') <i class="fa-solid fa-mobile-screen"></i>
                                @else <i class="fa-solid fa-wallet"></i>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">
                                    {{ $account['provider'] ?: 'Cash Wallet' }}
                                </h3>
                                <div class="text-xs text-uppercase font-bold tracking-wider text-gray-400">
                                    {{ str_replace('_', ' ', $account['source']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <p class="text-sm text-gray-500 mb-1">Current Balance</p>
                        <p class="text-3xl font-bold {{ $account['balance'] >= 0 ? 'text-gray-900' : 'text-red-500' }}">
                            TSH {{ number_format($account['balance']) }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-t border-gray-100 pt-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold">Total In</p>
                            <p class="text-sm font-bold text-green-600">+{{ number_format($account['income']) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400 uppercase font-semibold">Total Out</p>
                            <p class="text-sm font-bold text-red-600">-{{ number_format($account['expense']) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 text-2xl">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">No accounts found</h3>
                <p class="text-gray-500">Add incomes or expenses to see your account breakdowns here.</p>
                <a href="{{ route('incomes.create') }}" class="inline-block mt-4 px-6 py-2 bg-brand-600 text-white rounded-lg hover:bg-brand-700 transition-colors font-medium">Add Income</a>
            </div>
        @endforelse
    </div>
@endsection
