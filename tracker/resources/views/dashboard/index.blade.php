@extends('layout')

@section('content')
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-500 mt-1">Welcome back, {{ auth()->user()->name }}! Here's your financial overview.</p>
        </div>
        <div class="text-sm text-gray-500">
            {{ now()->format('l, F j, Y') }}
        </div>
    </div>

    <!-- Health & Net Worth Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Health Score -->
        <div class="lg:col-span-1 bg-gradient-to-br from-indigo-600 to-brand-600 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl hover:scale-[1.02] transition-transform cursor-pointer">
             <div class="relative z-10">
                <div class="flex justify-between items-start">
                    <h3 class="text-indigo-100 font-semibold mb-2">Financial Health Score</h3>
                    <i class="fa-solid fa-shield-heart text-2xl text-white/50"></i>
                </div>
                <div class="flex items-end gap-3 mt-4">
                    <span class="text-7xl font-black">{{ $healthScore }}</span>
                    <span class="text-2xl font-bold opacity-60 mb-2">/100</span>
                </div>
                <div class="mt-6 flex items-center gap-2">
                    <div class="flex-1 bg-white/20 h-2 rounded-full overflow-hidden">
                        <div class="bg-white h-full rounded-full" style="width: {{ $healthScore }}%"></div>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-widest text-indigo-100">
                        @if($healthScore >= 80) Excellent
                        @elseif($healthScore >= 50) Good
                        @else Warning @endif
                    </span>
                </div>
                <p class="mt-6 text-indigo-100/80 text-sm leading-relaxed">
                    @if($healthScore >= 80) "You're a financial ninja! Your savings rate and buffer are exceptional."
                    @elseif($healthScore >= 50) "You're on the right track. Consider increasing your emergency fund."
                    @else "Financial warning: Your debt levels or spending habits need urgent attention." @endif
                </p>
             </div>
             <!-- Decorative background circle -->
             <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Net Worth & Debt Metrics -->
        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Net Worth Card -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between group hover:border-brand-300 transition-colors">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-400 font-bold text-xs uppercase tracking-widest">Total Net Worth</span>
                        <div class="w-10 h-10 bg-brand-50 rounded-2xl flex items-center justify-center text-brand-600 group-hover:bg-brand-500 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-gem"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-gray-900">TSH {{ number_format($netWorth, 0) }}</p>
                    <p class="text-xs text-gray-400 mt-2">Consolidated family assets minus liabilities</p>
                </div>
                <a href="{{ route('assets.index') }}" class="mt-6 text-sm font-bold text-brand-600 hover:text-brand-700 flex items-center">
                    Manage Portfolio <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>

            <!-- Debt & Bills Row -->
            <div class="grid grid-rows-2 gap-6">
                <!-- Active Debt -->
                <div class="bg-white px-6 py-5 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-4 hover:border-red-200 transition-colors">
                    <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-600 flex-shrink-0">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Active Debt</p>
                        <p class="text-xl font-black text-red-600">TSH {{ number_format($totalDebt, 0) }}</p>
                    </div>
                    <a href="{{ route('loans.index') }}" class="ml-auto text-gray-300 hover:text-gray-500">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>

                <!-- Bills & Subscriptions -->
                <div class="flex gap-4">
                    <div class="flex-1 bg-white px-5 py-5 rounded-3xl shadow-sm border border-gray-100 text-center hover:border-yellow-200 transition-colors">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Unpaid Bills</p>
                        <p class="text-2xl font-black text-gray-800">{{ $pendingBillsCount }}</p>
                        <a href="{{ route('bills.index') }}" class="text-[10px] font-bold text-brand-600 hover:underline">Pay Now</a>
                    </div>
                    <div class="flex-1 bg-white px-5 py-5 rounded-3xl shadow-sm border border-gray-100 text-center hover:border-indigo-200 transition-colors">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Monthly Subs</p>
                        <p class="text-2xl font-black text-gray-800">{{ number_format($activeSubsMonthly / 1000, 1) }}k</p>
                        <a href="{{ route('subscriptions.index') }}" class="text-[10px] font-bold text-brand-600 hover:underline">Manage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section Header -->
    <div class="flex items-center gap-4 mb-6">
        <h2 class="text-xl font-bold text-gray-800">Cash Flow Analysis</h2>
        <div class="h-px bg-gray-200 flex-1"></div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Main Trend Chart -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-border col-span-1 lg:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg text-gray-800">Financial Performance</h3>
                <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2 py-1 rounded">Last 6 Months</span>
            </div>
            <div class="h-72">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Breakdown Charts -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-lg text-gray-800 mb-6">Income Sources</h3>
            <div class="h-64 flex justify-center">
                <canvas id="incomeChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-lg text-gray-800 mb-6">Top Expense Categories</h3>
            <div class="h-64 flex justify-center">
                <canvas id="expenseChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Budgets Row -->
    @if(!empty($budgetData))
    <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white p-8 rounded-3xl shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-xl">Monthly Budgets</h3>
            <a href="{{ route('budgets.index') }}" class="text-sm text-gray-300 hover:text-white underline">Manage Budgets</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
            @foreach($budgetData as $budget)
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-medium text-gray-300">{{ $budget['category'] }}</span>
                        <span class="font-bold tracking-wide">TSH {{ number_format($budget['actual'], 0) }} <span class="text-gray-500 font-normal">/ TSH {{ number_format($budget['limit'], 0) }}</span></span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-3 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-1000 ease-out" 
                             style="width: {{ $budget['percentage'] }}%; background-color: {{ $budget['color'] }}"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Common Options
        Chart.defaults.font.family = "'Outfit', sans-serif";
        Chart.defaults.color = '#9ca3af';
        
        // Income Chart
        new Chart(document.getElementById('incomeChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($incomeBySource->keys()) !!},
                datasets: [{
                    data: {!! json_encode($incomeBySource->values()) !!},
                    backgroundColor: ['#4ade80', '#60a5fa', '#facc15'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: { cutout: '70%', plugins: { legend: { position: 'right' } } }
        });

        // Expense Chart
        new Chart(document.getElementById('expenseChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($expenseByCategory->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($expenseByCategory->pluck('total')) !!},
                    backgroundColor: {!! json_encode($expenseByCategory->pluck('color')) !!},
                    borderWidth: 0
                }]
            },
            options: { plugins: { legend: { position: 'right' } } }
        });

        // Trend Chart
        new Chart(document.getElementById('trendChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [
                    {
                        label: 'Income',
                        data: {!! json_encode($incomeData) !!},
                        backgroundColor: '#0ea5e9',
                        borderRadius: 6,
                    },
                    {
                        label: 'Expense',
                        data: {!! json_encode($expenseData) !!},
                        backgroundColor: '#f43f5e',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { grid: { borderDash: [2, 4], borderColor: 'transparent' }, beginAtZero: true },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
@endsection
