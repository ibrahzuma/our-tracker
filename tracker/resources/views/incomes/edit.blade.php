@extends('layout')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Edit Income</h2>
        <a href="{{ route('incomes.index') }}" class="text-blue-500 hover:underline">Back to List</a>
    </div>

    <form action="{{ route('incomes.update', $income->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-gray-700">Date</label>
            <input type="date" name="date" value="{{ old('date', $income->date->format('Y-m-d')) }}" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="block text-gray-700">Source</label>
            <select name="source" class="w-full border p-2 rounded" required>
                <option value="cash" {{ $income->source == 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="mobile_money" {{ $income->source == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                <option value="bank" {{ $income->source == 'bank' ? 'selected' : '' }}>Bank</option>
            </select>
        </div>

        <div>
            <label class="block text-gray-700">Amount</label>
            <input type="number" step="0.01" name="amount" value="{{ old('amount', $income->amount) }}" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="block text-gray-700">Description</label>
            <textarea name="description" class="w-full border p-2 rounded">{{ old('description', $income->description) }}</textarea>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-600">Update Income</button>
    </form>
@endsection
