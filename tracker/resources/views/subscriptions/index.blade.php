@extends('layout')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Subscriptions</h1>
            <p class="text-gray-500 mt-1">Manage recurring digital services and memberships.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('subscriptions.create') }}" class="flex items-center px-4 py-2 bg-brand-600 text-white rounded-lg hover:bg-brand-700 transition-colors shadow-lg shadow-brand-500/30 font-medium">
                <i class="fa-solid fa-plus mr-2"></i> Add Subscription
            </a>
        </div>
    </div>

    <!-- Monthly Burn Card -->
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between mb-8">
        <div>
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Estimated Monthly Cost</p>
            <p class="text-4xl font-black text-gray-900 mt-1">TSH {{ number_format($monthlyCost, 0) }}</p>
        </div>
        <div class="mt-4 md:mt-0 px-6 py-3 bg-red-50 rounded-xl text-red-700 font-bold border border-red-100">
            <i class="fa-solid fa-fire-burner mr-2"></i> Monthly Burn Rate
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50/50">
                    <tr>
                        <th scope="col" class="px-6 py-4">Service</th>
                        <th scope="col" class="px-6 py-4">Billing</th>
                        <th scope="col" class="px-6 py-4 text-right">Amount</th>
                        <th scope="col" class="px-6 py-4">Status</th>
                        <th scope="col" class="px-6 py-4">Next Payment</th>
                        <th scope="col" class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($subscriptions as $sub)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $sub->name }}
                            </td>
                            <td class="px-6 py-4 capitalize">
                                {{ $sub->frequency }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900 whitespace-nowrap">
                                {{ $sub->currency }} {{ number_format($sub->amount, 0) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $sub->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($sub->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 {{ \Carbon\Carbon::parse($sub->next_billing_date)->isPast() ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                                {{ \Carbon\Carbon::parse($sub->next_billing_date)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <a href="{{ route('subscriptions.edit', $sub) }}" class="text-gray-400 hover:text-brand-600 transition-colors">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('subscriptions.destroy', $sub) }}" method="POST" onsubmit="return confirm('Delete this subscription?');">
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
                                No active subscriptions. Track your digital life here!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
