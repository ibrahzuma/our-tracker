@extends('layout')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Bill & Income Calendar</h1>
            <p class="text-gray-500 mt-1">Visualize your cash flow for {{ now()->format('F Y') }}.</p>
        </div>
        <div class="flex gap-2">
            <span class="flex items-center text-xs font-medium text-gray-500"><div class="w-3 h-3 bg-red-500 rounded-full mr-1"></div> Expense</span>
            <span class="flex items-center text-xs font-medium text-gray-500"><div class="w-3 h-3 bg-green-500 rounded-full mr-1"></div> Income</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Calendar Grid Head -->
        <div class="grid grid-cols-7 border-b border-gray-100 bg-gray-50">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="py-3 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $day }}</div>
            @endforeach
        </div>

        <!-- Calendar Body -->
        <div class="grid grid-cols-7 auto-rows-fr bg-gray-200 gap-px">
            @php
                $startOfMonth = now()->startOfMonth();
                $endOfMonth = now()->endOfMonth();
                $startDay = $startOfMonth->dayOfWeek; // 0 (Sun) - 6 (Sat)
                $daysInMonth = $startOfMonth->daysInMonth;
                $currentDay = 1;
            @endphp

            <!-- Empty cells before start -->
            @for($i = 0; $i < $startDay; $i++)
                <div class="bg-white min-h-[100px] p-2"></div>
            @endfor

            <!-- Days -->
            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $dateStr = $startOfMonth->copy()->day($day)->format('Y-m-d');
                    $daysEvents = $events->where('date', $dateStr);
                    $isToday = $dateStr === now()->format('Y-m-d');
                @endphp
                <div class="bg-white min-h-[100px] p-2 transition-colors hover:bg-gray-50 group">
                    <div class="flex justify-between items-start">
                        <span class="text-sm font-medium {{ $isToday ? 'bg-brand-600 text-white w-6 h-6 rounded-full flex items-center justify-center' : 'text-gray-900' }}">
                            {{ $day }}
                        </span>
                    </div>
                    
                    <div class="mt-2 space-y-1">
                        @foreach($daysEvents as $event)
                            <div class="text-xs px-1.5 py-1 rounded truncate {{ $event['color'] == 'green' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}" title="{{ $event['desc'] }}">
                                <span class="font-bold">{{ $event['title'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endfor

            <!-- Fill remaining cells (optional, simpler to leave generic bg-gray-200 gap) -->
        </div>
    </div>
@endsection
