@extends('layout')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Budgets for {{ \Carbon\Carbon::parse($currentMonth)->format('F Y') }}</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Set Budget Form -->
        <div class="bg-white p-6 rounded shadow">
            <h3 class="font-semibold mb-4">Set Monthly Limit</h3>
            <form action="{{ route('budgets.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm text-gray-700">Category</label>
                    <select name="category_id" class="w-full border p-2 rounded" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Amount Limit ($)</label>
                    <input type="number" name="amount" class="w-full border p-2 rounded" required step="0.01">
                </div>
                <button type="submit" class="bg-purple-600 text-white w-full py-2 rounded hover:bg-purple-700">Save Budget</button>
            </form>
        </div>

        <!-- Current Budgets List -->
        <div class="bg-white p-6 rounded shadow">
            <h3 class="font-semibold mb-4">Active Budgets</h3>
            @if($budgets->isEmpty())
                <p class="text-gray-500">No budgets set for this month.</p>
            @else
                <ul class="space-y-4">
                    @foreach($budgets as $budget)
                        <li class="flex justify-between items-center border-b pb-2">
                            <div>
                                <span class="block font-bold">{{ $budget->category->name }}</span>
                                <span class="text-xs text-gray-500">Limit: ${{ number_format($budget->amount, 0) }}</span>
                            </div>
                            <span class="bg-gray-200 px-3 py-1 rounded text-sm">${{ number_format($budget->amount, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection
