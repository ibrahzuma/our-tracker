@extends('layout')

@section('content')
    <div class="max-w-lg mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Add Income</h1>
        
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('incomes.store') }}" method="POST">
                @csrf
                
                <!-- Category -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category" class="w-full p-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all bg-white" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Source Type (Channel) -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Channel</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="source" value="bank" class="peer sr-only" onchange="updateProviders()" required>
                            <div class="p-3 text-center border rounded-lg peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-700 hover:bg-gray-50 transition-all">
                                <i class="fa-solid fa-building-columns mb-1 block text-lg"></i>
                                <span class="text-xs font-semibold">Bank</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="source" value="mobile_money" class="peer sr-only" onchange="updateProviders()">
                            <div class="p-3 text-center border rounded-lg peer-checked:bg-yellow-50 peer-checked:border-yellow-500 peer-checked:text-yellow-700 hover:bg-gray-50 transition-all">
                                <i class="fa-solid fa-mobile-screen mb-1 block text-lg"></i>
                                <span class="text-xs font-semibold">Mobile</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="source" value="cash" class="peer sr-only" onchange="updateProviders()">
                            <div class="p-3 text-center border rounded-lg peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 hover:bg-gray-50 transition-all">
                                <i class="fa-solid fa-money-bill-wave mb-1 block text-lg"></i>
                                <span class="text-xs font-semibold">Cash</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Provider (Dynamic) -->
                <div class="mb-6 hidden" id="provider-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Provider / Bank</label>
                    <select name="provider" id="provider-select" class="w-full p-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all bg-white">
                        <option value="">Select Provider...</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount (TSH)</label>
                    <input type="number" step="0.01" name="amount" class="w-full p-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all text-lg font-bold" placeholder="0.00" required>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full p-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all" required>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                    <textarea name="description" rows="2" class="w-full p-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all" placeholder="e.g., January Salary"></textarea>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('incomes.index') }}" class="flex-1 py-3 px-4 text-center text-gray-700 bg-white border border-gray-300 rounded-lg font-medium hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="flex-1 py-3 px-4 bg-brand-600 text-white rounded-lg font-bold hover:bg-brand-700 shadow-lg shadow-brand-500/30">Save Income</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const providers = @json($providers);

        function updateProviders() {
            const sourceType = document.querySelector('input[name="source"]:checked').value;
            const providerContainer = document.getElementById('provider-container');
            const providerSelect = document.getElementById('provider-select');
            
            providerSelect.innerHTML = '<option value="">Select Provider...</option>';

            if (sourceType === 'cash') {
                providerContainer.classList.add('hidden');
                providerSelect.required = false;
            } else {
                providerContainer.classList.remove('hidden');
                providerSelect.required = true;
                
                const list = providers[sourceType] || [];
                list.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item;
                    option.textContent = item;
                    providerSelect.appendChild(option);
                });
            }
        }
    </script>
@endsection
