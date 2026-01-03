<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index()
    {
        $currentMonth = now()->format('Y-m');
        $budgets = Budget::where('user_id', auth()->id())
            ->where('month', $currentMonth)
            ->with('category')
            ->get();
        
        $categories = Category::where('type', 'expense')->get();

        return view('budgets.index', compact('budgets', 'categories', 'currentMonth'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'amount' => 'required|numeric|min:0',
        ]);

        $month = now()->format('Y-m');

        Budget::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'category_id' => $request->category_id,
                'month' => $month
            ],
            ['amount' => $request->amount]
        );

        return redirect()->route('budgets.index')->with('success', 'Budget set successfully.');
    }
}
