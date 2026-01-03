<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Subscription;
use App\Models\Bill;
use App\Models\Loan;
use App\Models\Budget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch all user IDs in the same family (or just the user ID if not in a family)
        $userIds = Auth::user()->familyMembers()->pluck('id');

        // Data for Pie Charts
        $incomeBySource = Income::whereIn('user_id', $userIds)
            ->select('source', DB::raw('sum(amount) as total'))
            ->groupBy('source')
            ->pluck('total', 'source');

        $expenseByCategory = Expense::whereIn('user_id', $userIds)
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->select('categories.name', 'categories.color', DB::raw('sum(expenses.amount) as total'))
            ->groupBy('categories.name', 'categories.color')
            ->get();

        // Data for Bar Chart (Monthly) -- Last 6 months
        $months = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');
            $year = $date->year;
            $month = $date->month;

            $months[] = $monthName;

            $incomeData[] = Income::whereIn('user_id', $userIds)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->sum('amount');

            $expenseData[] = Expense::whereIn('user_id', $userIds)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->sum('amount');
        }

        // Budget Logic for Current Month (Aggregated)
        $currentMonth = now()->format('Y-m');
        
        // Sum budget limits per category for the family
        $budgets = Budget::whereIn('user_id', $userIds)
            ->where('month', $currentMonth)
            ->with('category')
            ->get()
            ->groupBy('category_id');
        
        $budgetData = [];
        foreach ($budgets as $categoryId => $categoryBudgets) {
            $category = $categoryBudgets->first()->category;
            $totalLimit = $categoryBudgets->sum('amount');
            
            $actual = Expense::whereIn('user_id', $userIds)
                ->where('category_id', $categoryId)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('amount');
            
            $budgetData[] = [
                'category' => $category->name,
                'limit' => $totalLimit,
                'actual' => $actual,
                'percentage' => $totalLimit > 0 ? min(100, ($actual / $totalLimit) * 100) : 100,
                'color' => $category->color
            ];
        }

        // NEW MODULES DATA
        $assets = Asset::whereIn('user_id', $userIds)->get();
        $loans = Loan::whereIn('user_id', $userIds)->get();
        $bills = Bill::whereIn('user_id', $userIds)->get();
        $subs = Subscription::whereIn('user_id', $userIds)->get();

        $totalWealth = $assets->sum('value');
        $totalDebt = $loans->where('type', 'borrowed')->sum('balance');
        $netWorth = $totalWealth - $totalDebt;
        
        $pendingBillsCount = $bills->where('is_paid', false)->count();
        $activeSubsMonthly = $subs->where('status', 'active')->sum(fn($s) => $s->frequency == 'yearly' ? $s->amount/12 : $s->amount);

        // Financial Health Score Calculation
        $healthScore = $this->calculateHealthScore($incomeData, $expenseData, $budgetData, $netWorth, $totalDebt);

        return view('dashboard.index', compact(
            'incomeBySource', 
            'expenseByCategory', 
            'months', 
            'incomeData', 
            'expenseData',
            'budgetData',
            'netWorth',
            'totalDebt',
            'pendingBillsCount',
            'activeSubsMonthly',
            'healthScore'
        ));
    }

    private function calculateHealthScore($incomeData, $expenseData, $budgetData, $netWorth, $totalDebt)
    {
        $score = 0;
        $count = count($incomeData);
        $avgIncome = array_sum($incomeData) / $count;
        $avgExpense = array_sum($expenseData) / $count;

        // 1. Savings Rate (30%)
        if ($avgIncome > 0) {
            $savingsRate = ($avgIncome - $avgExpense) / $avgIncome;
            $score += min(30, max(0, $savingsRate * 5 * 30)); // 20% savings rate = full 30 points
        }

        // 2. Budget Adherence (30%)
        $overspentCount = collect($budgetData)->where('actual', '>', 'limit')->count();
        $budgetCount = count($budgetData);
        if ($budgetCount > 0) {
            $score += (30 * ($budgetCount - $overspentCount) / $budgetCount);
        } else {
            $score += 15; // Neutral if no budgets set
        }

        // 3. Debt-to-Asset Ratio (20%)
        if ($netWorth + $totalDebt > 0) {
            $debtRatio = $totalDebt / ($netWorth + $totalDebt);
            $score += max(0, 20 * (1 - $debtRatio));
        } else {
            $score += 20;
        }

        // 4. Emergency Buffer (20%)
        if ($avgExpense > 0) {
            $monthsBuffer = $netWorth / $avgExpense;
            $score += min(20, $monthsBuffer * (20/6)); // 6 months = full 20 points
        }

        return round($score);
    }
}
