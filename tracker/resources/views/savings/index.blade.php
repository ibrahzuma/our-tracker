@extends('layout')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Savings Goals</h1>
            <p class="text-gray-500 mt-1">Track your progress towards your dreams.</p>
        </div>
        <button onclick="document.getElementById('newGoalModal').classList.remove('hidden')" class="flex items-center px-4 py-2 bg-brand-600 text-white rounded-lg hover:bg-brand-700 transition-colors shadow-lg shadow-brand-500/30 font-medium">
            <i class="fa-solid fa-plus mr-2"></i> New Goal
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($goals as $goal)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                <div class="p-6 flex-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-white text-xl font-bold" style="background-color: {{ $goal->color }}">
                            <i class="fa-solid fa-piggy-bank"></i>
                        </div>
                        <form action="{{ route('savings.destroy', $goal) }}" method="POST" onsubmit="return confirm('Delete this goal?');">
                            @csrf
                            @method('DELETE')
                            <button class="text-gray-300 hover:text-red-500 transition-colors"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $goal->name }}</h3>
                    <p class="text-sm text-gray-500 mb-4">{{ $goal->deadline ? 'Deadline: '.$goal->deadline->format('M j, Y') : 'No deadline' }}</p>
                    
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-2xl font-bold text-gray-900">TSH {{ number_format($goal->current_amount) }}</span>
                        <span class="text-xs font-medium text-gray-400">of TSH {{ number_format($goal->target_amount) }}</span>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-100 rounded-full h-2.5 mb-6">
                        @php $percent = $goal->target_amount > 0 ? min(100, ($goal->current_amount / $goal->target_amount) * 100) : 0; @endphp
                        <div class="h-2.5 rounded-full transition-all duration-1000" style="width: {{ $percent }}%; background-color: {{ $goal->color }}"></div>
                    </div>
                    
                    <form action="{{ route('savings.contribute', $goal) }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="number" name="amount" placeholder="Add amount..." class="flex-1 bg-gray-50 border border-gray-200 text-sm rounded-lg p-2.5 focus:ring-brand-500 focus:border-brand-500" required>
                        <button type="submit" class="p-2.5 text-brand-600 bg-brand-50 rounded-lg hover:bg-brand-100 transition-colors">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 text-2xl">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">No goals yet</h3>
                <p class="text-gray-500">Create a savings goal to start tracking.</p>
            </div>
        @endforelse
    </div>

    <!-- Create Modal -->
    <div id="newGoalModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Create Savings Goal</h2>
            <form action="{{ route('savings.store') }}" method="POST">
                @csrf
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Goal Name</label>
                        <input type="text" name="name" class="w-full p-2.5 border border-gray-200 rounded-lg" placeholder="e.g. New Laptop" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Target Amount (TSH)</label>
                        <input type="number" name="target_amount" class="w-full p-2.5 border border-gray-200 rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Target Date (Optional)</label>
                        <input type="date" name="deadline" class="w-full p-2.5 border border-gray-200 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                        <div class="flex gap-2">
                             @foreach(['#ef4444', '#f97316', '#f59e0b', '#84cc16', '#10b981', '#06b6d4', '#3b82f6', '#8b5cf6', '#d946ef', '#f43f5e'] as $color)
                                <label class="cursor-pointer">
                                    <input type="radio" name="color" value="{{ $color }}" class="peer sr-only" {{ $loop->first ? 'checked' : '' }}>
                                    <div class="w-6 h-6 rounded-full bg-gray-200 peer-checked:ring-2 ring-offset-2 ring-gray-400" style="background-color: {{ $color }}"></div>
                                </label>
                             @endforeach
                        </div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('newGoalModal').classList.add('hidden')" class="flex-1 py-2.5 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 font-medium">Cancel</button>
                    <button type="submit" class="flex-1 py-2.5 text-white bg-brand-600 rounded-lg hover:bg-brand-700 font-medium">Create Goal</button>
                </div>
            </form>
        </div>
    </div>
@endsection
