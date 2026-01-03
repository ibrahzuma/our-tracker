<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class IncomeController extends Controller
{
    private $categories = ['Salary', 'Business', 'Loan', 'Gift', 'Allowance', 'Other'];
    private $providers = [
        'bank' => ['CRDB', 'NMB', 'NBC', 'Exim', 'DTB', 'Stanbic', 'Absa', 'KCB', 'Equity', 'Standard Chartered'],
        'mobile_money' => ['M-Pesa', 'Tigo Pesa', 'Airtel Money', 'Halo Pesa', 'T-Pesa'],
        'cash' => ['Cash'],
    ];

    public function index(Request $request)
    {
        $query = Income::where('user_id', auth()->id())->orderBy('date', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $incomes = $query->get();

        // Calculations
        $totalIncome = $incomes->sum('amount');
        
        $currentMonthIncome = Income::where('user_id', auth()->id())
                                    ->whereMonth('date', Carbon::now()->month)
                                    ->whereYear('date', Carbon::now()->year)
                                    ->sum('amount');

        return view('incomes.index', compact('incomes', 'totalIncome', 'currentMonthIncome'));
    }

    public function create()
    {
        $categories = $this->categories;
        $providers = $this->providers;
        return view('incomes.create', compact('categories', 'providers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'source' => 'required|in:cash,mobile_money,bank',
            'category' => 'required|string',
            'provider' => 'nullable|string',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        Income::create([
            'user_id' => auth()->id(),
            'amount' => $request->amount,
            'source' => $request->source,
            'category' => $request->category,
            'provider' => $request->provider,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return redirect()->route('incomes.index')->with('success', 'Income added successfully.');
    }

    public function edit(Income $income)
    {
        $categories = $this->categories;
        $providers = $this->providers;
        return view('incomes.edit', compact('income', 'categories', 'providers'));
    }

    public function update(Request $request, Income $income)
    {
         $request->validate([
            'amount' => 'required|numeric|min:0',
            'source' => 'required|in:cash,mobile_money,bank',
            'category' => 'required|string',
            'provider' => 'nullable|string',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $income->update($request->all());

        return redirect()->route('incomes.index')->with('success', 'Income updated successfully.');
    }

    public function destroy(Income $income)
    {
        $income->delete();
        return redirect()->route('incomes.index')->with('success', 'Income deleted successfully.');
    }

    public function export()
    {
        $incomes = Income::where('user_id', auth()->id())->get();
        $csvData = "Date,Category,Source,Provider,Amount,Description\n";

        foreach ($incomes as $income) {
            $csvData .= "{$income->date},{$income->category},{$income->source},{$income->provider},{$income->amount},\"{$income->description}\"\n";
        }

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="incomes.csv"',
        ]);
    }

    public function duplicate(Income $income)
    {
        $newIncome = $income->replicate();
        $newIncome->date = now(); // Set to today
        $newIncome->created_at = now();
        $newIncome->updated_at = now();
        $newIncome->save();

        return redirect()->route('incomes.index')->with('success', 'Income duplicated successfully (Recurring).');
    }
}
