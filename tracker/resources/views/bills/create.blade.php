@extends('layout')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('bills.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700 flex items-center mb-2">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Bills
            </a>
            <h1 class="text-3xl font-bold text-gray-900">{{ isset($bill) ? 'Edit Bill' : 'Add New Bill' }}</h1>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form action="{{ isset($bill) ? route('bills.update', $bill) : route('bills.store') }}" method="POST">
                @csrf
                @if(isset($bill)) @method('PUT') @endif

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bill Name</label>
                        <input type="text" name="name" value="{{ $bill->name ?? '' }}" placeholder="e.g. Rent, Electricity, Internet" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Amount (TSH)</label>
                            <input type="number" name="amount" value="{{ $bill->amount ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Due Date</label>
                            <input type="date" name="due_date" value="{{ $bill->due_date ?? date('Y-m-d') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Frequency (Optional)</label>
                        <select name="frequency" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all">
                            <option value="">One-time</option>
                            <option value="monthly" {{ (isset($bill) && $bill->frequency == 'monthly') ? 'selected' : '' }}>Monthly</option>
                            <option value="quarterly" {{ (isset($bill) && $bill->frequency == 'quarterly') ? 'selected' : '' }}>Quarterly</option>
                            <option value="yearly" {{ (isset($bill) && $bill->frequency == 'yearly') ? 'selected' : '' }}>Yearly</option>
                        </select>
                    </div>

                    @if(isset($bill))
                    <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                        <input type="checkbox" name="is_paid" value="1" id="is_paid" class="w-5 h-5 text-brand-600 border-gray-300 rounded focus:ring-brand-500" {{ $bill->is_paid ? 'checked' : '' }}>
                        <label for="is_paid" class="ml-3 text-sm font-semibold text-gray-700">Mark as Paid</label>
                    </div>
                    @endif

                    <button type="submit" class="w-full py-4 bg-brand-600 text-white rounded-xl font-bold hover:bg-brand-700 shadow-lg shadow-brand-500/30 transition-all flex items-center justify-center">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> {{ isset($bill) ? 'Update Bill' : 'Save Bill' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
