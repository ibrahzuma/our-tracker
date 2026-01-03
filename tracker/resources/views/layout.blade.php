<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoneyTracker Pro</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9', // Sky blue
                            600: '#0284c7',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
        }
        .nav-item.active {
            background: linear-gradient(90deg, #f0f9ff 0%, #ffffff 100%);
            border-right: 4px solid #0ea5e9;
            color: #0284c7;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col z-10 shadow-lg">
        <!-- Logo -->
        <div class="h-16 flex items-center px-8 border-b border-gray-100">
            <div class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-brand-600 to-indigo-600">
                <i class="fa-solid fa-wallet mr-2 text-brand-500"></i> Tracker
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            @auth
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu</p>
                
                <a href="{{ route('dashboard') }}" class="nav-item flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-50 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie w-6"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <a href="{{ route('incomes.index') }}" class="nav-item flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-50 transition-all duration-200 {{ request()->routeIs('incomes.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-money-bill-trend-up w-6"></i>
                    <span class="font-medium">Incomes</span>
                </a>

                <a href="{{ route('expenses.index') }}" class="nav-item flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-50 transition-all duration-200 {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-credit-card w-6"></i>
                    <span class="font-medium">Expenses</span>
                </a>

                <a href="{{ route('budgets.index') }}" class="nav-item flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-50 transition-all duration-200 {{ request()->routeIs('budgets.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie w-6"></i>
                    <span class="font-medium">Budgets</span>
                </a>

                <a href="{{ route('savings.index') }}" class="nav-item flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-50 transition-all duration-200 {{ request()->routeIs('savings.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-piggy-bank w-6"></i>
                    <span class="font-medium">Goals</span>
                </a>

                <a href="{{ route('calendar.index') }}" class="nav-item flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-50 transition-all duration-200 {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-days w-6"></i>
                    <span class="font-medium">Calendar</span>
                </a>

                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-4">Planning</p>

                <a href="{{ route('assets.index') }}" class="nav-item flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-50 transition-all duration-200 {{ request()->routeIs('assets.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-gem w-6"></i>
                    <span class="font-medium">Assets</span>
                </a>

                <a href="{{ route('subscriptions.index') }}" class="nav-item flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-50 transition-all duration-200 {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-rotate w-6"></i>
                    <span class="font-medium">Subscriptions</span>
                </a>

                <a href="{{ route('bills.index') }}" class="nav-item flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-50 transition-all duration-200 {{ request()->routeIs('bills.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-invoice-dollar w-6"></i>
                    <span class="font-medium">Bills</span>
                </a>

                <a href="{{ route('loans.index') }}" class="nav-item flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-50 transition-all duration-200 {{ request()->routeIs('loans.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-hand-holding-dollar w-6"></i>
                    <span class="font-medium">Loans</span>
                </a>

                <a href="{{ route('requests.index') }}" class="nav-item flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-50 transition-all duration-200 {{ request()->routeIs('requests.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-paper-plane w-6"></i>
                    <span class="font-medium">Requests</span>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
            @else
                <a href="{{ route('login') }}" class="nav-item flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-50 transition-all duration-200 {{ request()->routeIs('login') ? 'active' : '' }}">
                    <i class="fa-solid fa-right-to-bracket w-6"></i>
                    <span class="font-medium">Login</span>
                </a>
                <a href="{{ route('register') }}" class="nav-item flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-50 transition-all duration-200 {{ request()->routeIs('register') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-plus w-6"></i>
                    <span class="font-medium">Register</span>
                </a>
            @endauth
        </nav>

        <!-- User Profile -->
        @auth
        <div class="border-t border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center text-brand-600 font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
        @endauth
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-hidden relative">
        <!-- Top Header (Mobile & Desktop) -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-20">
            <!-- Mobile Menu / Title -->
            <div class="flex items-center gap-4">
                <button class="md:hidden text-gray-600"><i class="fa-solid fa-bars"></i></button>
                <div class="md:hidden font-bold text-brand-600">MoneyTracker</div>
                <!-- Desktop Title/Breadcrumb -->
                <h2 class="hidden md:block text-xl font-bold text-gray-800">
                    @if(request()->routeIs('dashboard')) Dashboard
                    @elseif(request()->routeIs('incomes.*')) Incomes
                    @elseif(request()->routeIs('expenses.*')) Expenses
                    @elseif(request()->routeIs('budgets.*')) Budgets
                    @elseif(request()->routeIs('savings.*')) Savings Goals
                    @elseif(request()->routeIs('calendar.*')) Calendar
                    @elseif(request()->routeIs('requests.*')) Money Requests
                    @elseif(request()->routeIs('household.*')) Household
                    @elseif(request()->routeIs('sms-parser.*')) SMS Parser
                    @elseif(request()->routeIs('accounts.*')) Accounts
                    @elseif(request()->routeIs('assets.*')) Assets
                    @elseif(request()->routeIs('subscriptions.*')) Subscriptions
                    @elseif(request()->routeIs('bills.*')) Bills
                    @elseif(request()->routeIs('loans.*')) Loans
                    @else MoneyTracker @endif
                </h2>
            </div>

            <!-- Right Controls -->
             <div class="flex items-center gap-4">
                <a href="{{ route('accounts.index') }}" class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                    <i class="fa-solid fa-wallet"></i>
                    <span>Accounts</span>
                </a>
                @auth
                    <!-- Notifications -->
                    <form action="{{ route('notifications.read') }}" method="POST" title="Mark all read">
                        @csrf
                        <div class="relative">
                            <button type="submit" class="text-gray-400 hover:text-brand-500 transition-colors {{ auth()->user()->unreadNotifications->count() > 0 ? 'text-brand-500' : '' }}">
                                <i class="fa-solid fa-bell text-xl"></i>
                            </button>
                             @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                </span>
                            @endif
                        </div>
                    </form>

                    <!-- SMS Parser -->
                    <a href="{{ route('sms-parser.index') }}" class="text-gray-400 hover:text-brand-600 transition-colors" title="Quick Import (SMS)">
                         <i class="fa-solid fa-magic-wand-sparkles text-xl"></i>
                    </a>

                    <!-- Household -->
                    <a href="{{ route('household.index') }}" class="text-gray-400 hover:text-brand-600 transition-colors" title="Household Settings">
                        <i class="fa-solid fa-users-gear text-xl"></i>
                    </a>

                    <!-- Separator -->
                    <div class="h-6 w-px bg-gray-200 mx-1"></div>

                    <!-- Logout -->
                    <form action="{{ route('logout') }}" method="POST" title="Logout">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 text-gray-500 hover:text-red-500 transition-colors font-medium">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span class="hidden md:inline">Logout</span>
                        </button>
                    </form>
                @endauth
            </div>
        </header>

        <!-- Scroll Area -->
        <div class="flex-1 overflow-auto bg-gray-50 p-6 md:p-10">
            <div class="max-w-7xl mx-auto space-y-6">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm animate-pulse">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-circle-check text-green-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>

</body>
</html>
