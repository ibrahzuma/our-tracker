@extends('layout')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('loans.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700 flex items-center mb-2">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Loans
            </a>
            <h1 class="text-3xl font-bold text-gray-900">{{ isset($loan) ? 'Edit Loan Record' : 'Add New Loan Record' }}</h1>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form action="{{ isset($loan) ? route('loans.update', $loan) : route('loans.store') }}" method="POST">
                @csrf
                @if(isset($loan)) @method('PUT') @endif

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Name</label>
                        <input type="text" name="contact_name" value="{{ $loan->contact_name ?? '' }}" placeholder="Who is this with?" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Loan Type</label>
                            <select name="type" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all" required>
                                <option value="lent" {{ (isset($loan) && $loan->type == 'lent') ? 'selected' : '' }}>I Lent Money</option>
                                <option value="borrowed" {{ (isset($loan) && $loan->type == 'borrowed') ? 'selected' : '' }}>I Borrowed Money</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Original Amount (TSH)</label>
                            <input type="number" name="amount" value="{{ $loan->amount ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Current Balance (TSH)</label>
                            <input type="number" name="balance" value="{{ $loan->balance ?? '' }}" placeholder="Remaining amount" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all" {{ isset($loan) ? 'required' : 'readonly' }}>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all" required>
                                <option value="pending" {{ (isset($loan) && $loan->status == 'pending') ? 'selected' : '' }}>Pending</option>
                                <option value="partially_paid" {{ (isset($loan) && $loan->status == 'partially_paid') ? 'selected' : '' }}>Partially Paid</option>
                                <option value="paid" {{ (isset($loan) && $loan->status == 'paid') ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Due Date (Optional)</label>
                        <input type="date" name="due_date" value="{{ $loan->due_date ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all">
                    </div>

                    <button type="submit" class="w-full py-4 bg-brand-600 text-white rounded-xl font-bold hover:bg-brand-700 shadow-lg shadow-brand-500/30 transition-all flex items-center justify-center">
                        <i class="fa-solid fa-hand-holding-dollar mr-2"></i> {{ isset($loan) ? 'Update Record' : 'Save Record' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
