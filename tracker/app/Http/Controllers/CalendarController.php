<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function index()
    {
        $userIds = Auth::user()->familyMembers()->pluck('id');
        
        // 1. Actual Incomes
        $incomes = Income::whereIn('user_id', $userIds)
            ->whereMonth('date', now()->month)
            ->get()->map(function($item) {
                return [
                    'title' => '+' . number_format($item->amount),
                    'date' => $item->date->format('Y-m-d'),
                    'color' => 'green',
                    'desc' => $item->provider . ' - ' . $item->description
                ];
            });
        
        // 2. Actual Expenses
        $expenses = \App\Models\Expense::whereIn('user_id', $userIds)
            ->whereMonth('date', now()->month)
            ->get()->map(function($item) {
                 return [
                    'title' => '-' . number_format($item->amount),
                    'date' => $item->date->format('Y-m-d'),
                    'color' => 'red',
                    'desc' => $item->description
                ];
            });

        // 3. Upcoming Bills
        $bills = \App\Models\Bill::whereIn('user_id', $userIds)
            ->where('is_paid', false)
            ->get()->map(function($item) {
                 return [
                    'title' => 'BILL: ' . $item->name,
                    'date' => \Carbon\Carbon::parse($item->due_date)->format('Y-m-d'),
                    'color' => 'yellow',
                    'desc' => 'TSH ' . number_format($item->amount)
                ];
            });

        // 4. Subscriptions
        $subs = \App\Models\Subscription::whereIn('user_id', $userIds)
            ->where('status', 'active')
            ->get()->map(function($item) {
                 return [
                    'title' => 'SUB: ' . $item->name,
                    'date' => \Carbon\Carbon::parse($item->next_billing_date)->format('Y-m-d'),
                    'color' => 'indigo',
                    'desc' => $item->currency . ' ' . number_format($item->amount)
                ];
            });

        $events = $incomes->merge($expenses)->merge($bills)->merge($subs);

        return view('calendar.index', compact('events'));
    }
}
