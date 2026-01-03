<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::where('user_id', auth()->id())->with('category')->orderBy('date', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $expenses = $query->get();
        $totalExpenses = $expenses->sum('amount');
        
        $currentMonthExpenses = Expense::where('user_id', auth()->id())
                                    ->whereMonth('date', Carbon::now()->month)
                                    ->whereYear('date', Carbon::now()->year)
                                    ->sum('amount');

        return view('expenses.index', compact('expenses', 'totalExpenses', 'currentMonthExpenses'));
    }

    public function create()
    {
        $categories = Category::where('type', 'expense')->get();
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        Expense::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully.');
    }

    public function edit(Expense $expense)
    {
        $categories = Category::where('type', 'expense')->get();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

    public function duplicate(Expense $expense)
    {
        $newExpense = $expense->replicate();
        $newExpense->date = now();
        $newExpense->created_at = now();
        $newExpense->updated_at = now();
        $newExpense->save();

        return redirect()->route('expenses.index')->with('success', 'Expense duplicated successfully (Recurring).');
    }
}
