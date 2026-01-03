@extends('layout')

@section('content')
    <div class="max-w-lg mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Request Money</h1>
        
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('requests.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Who are you requesting from?</label>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($users as $user)
                            <label class="relative flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 hover:border-indigo-300 transition-all">
                                <input type="radio" name="approver_id" value="{{ $user->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" required>
                                <div class="ml-3 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $user->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount (TSH)</label>
                    <input type="number" step="0.01" name="amount" class="w-full p-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-lg font-bold" placeholder="0.00" required>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                    <textarea name="reason" rows="3" class="w-full p-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="e.g., Grocery shopping" required></textarea>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('requests.index') }}" class="flex-1 py-3 px-4 text-center text-gray-700 bg-white border border-gray-300 rounded-lg font-medium hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="flex-1 py-3 px-4 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-500/30">Send Request</button>
                </div>
            </form>
        </div>
    </div>
@endsection
