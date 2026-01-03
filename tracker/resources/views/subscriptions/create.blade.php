@extends('layout')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('subscriptions.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700 flex items-center mb-2">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Subscriptions
            </a>
            <h1 class="text-3xl font-bold text-gray-900">{{ isset($subscription) ? 'Edit Subscription' : 'Add New Subscription' }}</h1>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form action="{{ isset($subscription) ? route('subscriptions.update', $subscription) : route('subscriptions.store') }}" method="POST">
                @csrf
                @if(isset($subscription)) @method('PUT') @endif

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Service Name</label>
                        <input type="text" name="name" value="{{ $subscription->name ?? '' }}" placeholder="e.g. Netflix, Spotify, Gym" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all" required>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                         <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Currency</label>
                            <input type="text" name="currency" value="{{ $subscription->currency ?? 'TZS' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all" required>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Amount</label>
                            <input type="number" name="amount" value="{{ $subscription->amount ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Frequency</label>
                            <select name="frequency" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all" required>
                                <option value="monthly" {{ (isset($subscription) && $subscription->frequency == 'monthly') ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ (isset($subscription) && $subscription->frequency == 'yearly') ? 'selected' : '' }}>Yearly</option>
                                <option value="weekly" {{ (isset($subscription) && $subscription->frequency == 'weekly') ? 'selected' : '' }}>Weekly</option>
                            </select>
                        </div>
                         <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all" required>
                                <option value="active" {{ (isset($subscription) && $subscription->status == 'active') ? 'selected' : '' }}>Active</option>
                                <option value="paused" {{ (isset($subscription) && $subscription->status == 'paused') ? 'selected' : '' }}>Paused</option>
                                <option value="cancelled" {{ (isset($subscription) && $subscription->status == 'cancelled') ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Next Billing Date</label>
                        <input type="date" name="next_billing_date" value="{{ $subscription->next_billing_date ?? date('Y-m-d') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all" required>
                    </div>

                    <button type="submit" class="w-full py-4 bg-brand-600 text-white rounded-xl font-bold hover:bg-brand-700 shadow-lg shadow-brand-500/30 transition-all flex items-center justify-center">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> {{ isset($subscription) ? 'Update Subscription' : 'Save Subscription' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
