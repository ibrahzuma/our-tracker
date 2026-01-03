@extends('layout')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('assets.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700 flex items-center mb-2">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Assets
            </a>
            <h1 class="text-3xl font-bold text-gray-900">{{ isset($asset) ? 'Edit Asset' : 'Add New Asset' }}</h1>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form action="{{ isset($asset) ? route('assets.update', $asset) : route('assets.store') }}" method="POST">
                @csrf
                @if(isset($asset)) @method('PUT') @endif

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Asset Name</label>
                        <input type="text" name="name" value="{{ $asset->name ?? '' }}" placeholder="e.g. Plot in Kigamboni, Stock Portfolio" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Asset Type</label>
                            <select name="type" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all" required>
                                <option value="Cash" {{ (isset($asset) && $asset->type == 'Cash') ? 'selected' : '' }}>Cash/Savings</option>
                                <option value="Stock" {{ (isset($asset) && $asset->type == 'Stock') ? 'selected' : '' }}>Stock/Equity</option>
                                <option value="Crypto" {{ (isset($asset) && $asset->type == 'Crypto') ? 'selected' : '' }}>Crypto</option>
                                <option value="Real Estate" {{ (isset($asset) && $asset->type == 'Real Estate') ? 'selected' : '' }}>Real Estate</option>
                                <option value="Vehicle" {{ (isset($asset) && $asset->type == 'Vehicle') ? 'selected' : '' }}>Vehicle</option>
                                <option value="Other" {{ (isset($asset) && $asset->type == 'Other') ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Current Value (TSH)</label>
                            <input type="number" name="value" value="{{ $asset->value ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Valuation Date</label>
                        <input type="date" name="valuation_date" value="{{ $asset->valuation_date ?? date('Y-m-d') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea name="notes" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all">{{ $asset->notes ?? '' }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-4 bg-brand-600 text-white rounded-xl font-bold hover:bg-brand-700 shadow-lg shadow-brand-500/30 transition-all flex items-center justify-center">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> {{ isset($asset) ? 'Update Asset' : 'Save Asset' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
