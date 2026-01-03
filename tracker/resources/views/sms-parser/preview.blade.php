@extends('layout')

@section('content')
    <div class="max-w-xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Confirm Import</h1>
        <p class="text-gray-500 mb-8">Please review the extracted details.</p>
        
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200 text-xs text-gray-500 italic break-words">
                "{{ $text }}"
            </div>

            @if($data['amount'])
                <form action="{{ $data['type'] == 'income' ? route('incomes.store') : route('expenses.store') }}" method="POST">
                    @csrf
                    
                    <input type="hidden" name="source" value="{{ $data['source'] }}">
                    
                    <!-- Hidden fields for logic, displayed for user adjustment -->
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-bold uppercase {{ $data['type'] == 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $data['type'] }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount (TSH)</label>
                        <input type="number" name="amount" value="{{ $data['amount'] }}" class="w-full p-2 border border-gray-200 rounded font-bold text-lg" required>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                         <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" name="date" value="{{ $data['date'] }}" class="w-full p-2 border border-gray-200 rounded" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Provider</label>
                            <input type="text" name="provider" value="{{ $data['provider'] }}" class="w-full p-2 border border-gray-200 rounded">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <input type="text" name="description" value="{{ $data['description'] }}" class="w-full p-2 border border-gray-200 rounded">
                    </div>

                    <!-- Variable Fields based on Type -->
                    @if($data['type'] == 'expense')
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category_id" class="w-full p-2 border border-gray-200 rounded" required>
                                @foreach(\App\Models\Category::all() as $cat)
                                    <option value="{{ $cat->id }}" {{ $cat->name == 'Other' ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                         <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                             <select name="category" class="w-full p-2 border border-gray-200 rounded" required>
                                <option value="Salary" {{ $data['category'] == 'Salary' ? 'selected' : '' }}>Salary</option>
                                <option value="Business" {{ $data['category'] == 'Business' ? 'selected' : '' }}>Business</option>
                                <option value="Loan">Loan</option>
                                <option value="Gift">Gift</option>
                                <option value="Allowance">Allowance</option>
                                <option value="Other" {{ $data['category'] == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    @endif

                    <button type="submit" class="w-full py-3 bg-brand-600 text-white rounded-lg font-bold hover:bg-brand-700 shadow-lg">
                        Confirm & Save
                    </button>
                </form>
            @else
                <div class="text-center p-6 text-red-500">
                    <i class="fa-solid fa-circle-exclamation text-3xl mb-2"></i>
                    <p class="font-bold">Could not parse message.</p>
                    <p class="text-sm mt-2">Please ensure it follows a standard M-Pesa or Tigo Pesa format.</p>
                    <a href="{{ route('sms-parser.index') }}" class="inline-block mt-4 text-gray-600 underline">Try Again</a>
                </div>
            @endif
        </div>
    </div>
@endsection
