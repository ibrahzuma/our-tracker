@extends('layout')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Edit Expense</h2>
        <a href="{{ route('expenses.index') }}" class="text-blue-500 hover:underline">Back to List</a>
    </div>

    <form action="{{ route('expenses.update', $expense) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-gray-700">Date</label>
            <input type="date" name="date" value="{{ old('date', $expense->date->format('Y-m-d')) }}" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="block text-gray-700">Category</label>
            <select name="category_id" class="w-full border p-2 rounded" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $expense->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-gray-700">Amount</label>
            <input type="number" step="0.01" name="amount" value="{{ old('amount', $expense->amount) }}" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="block text-gray-700">Description</label>
            <textarea name="description" class="w-full border p-2 rounded">{{ old('description', $expense->description) }}</textarea>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-600">Update Expense</button>
    </form>
@endsection
