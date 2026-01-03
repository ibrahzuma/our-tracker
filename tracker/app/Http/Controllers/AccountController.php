<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function index()
    {
        $userIds = Auth::user()->familyMembers()->pluck('id');

        // Fetch Incomes Grouped
        $incomes = Income::whereIn('user_id', $userIds)
            ->select('source', 'provider', DB::raw('sum(amount) as total'))
            ->groupBy('source', 'provider')
            ->get();

        // Fetch Expenses Grouped
        $expenses = Expense::whereIn('user_id', $userIds)
            ->select('source', 'provider', DB::raw('sum(amount) as total'))
            ->groupBy('source', 'provider')
            ->get();

        $accounts = [];

        // Helper to generate a key
        $getKey = fn($source, $provider) => $source . '|' . ($provider ?? 'General');

        // Process Incomes
        foreach ($incomes as $inc) {
            $key = $getKey($inc->source, $inc->provider);
            if (!isset($accounts[$key])) {
                $accounts[$key] = [
                    'source' => $inc->source,
                    'provider' => $inc->provider,
                    'income' => 0,
                    'expense' => 0,
                    'balance' => 0,
                ];
            }
            $accounts[$key]['income'] += $inc->total;
            $accounts[$key]['balance'] += $inc->total;
        }

        // Process Expenses
        foreach ($expenses as $exp) {
            $key = $getKey($exp->source, $exp->provider);
            if (!isset($accounts[$key])) {
                $accounts[$key] = [
                    'source' => $exp->source,
                    'provider' => $exp->provider,
                    'income' => 0,
                    'expense' => 0,
                    'balance' => 0,
                ];
            }
            $accounts[$key]['expense'] += $exp->total;
            $accounts[$key]['balance'] -= $exp->total;
        }

        // Sort by balance desc
        $accounts = collect($accounts)->sortByDesc('balance');

        return view('accounts.index', compact('accounts'));
    }
}
