<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function index()
    {
        $userIds = Auth::user()->familyMembers()->pluck('id');
        $loans = Loan::whereIn('user_id', $userIds)->orderBy('due_date', 'asc')->get();
        
        $totalLent = $loans->where('type', 'lent')->sum('balance');
        $totalBorrowed = $loans->where('type', 'borrowed')->sum('balance');

        return view('loans.index', compact('loans', 'totalLent', 'totalBorrowed'));
    }

    public function create()
    {
        return view('loans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_name' => 'required|string|max:255',
            'type' => 'required|in:lent,borrowed',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'status' => 'required|string',
        ]);

        $validated['balance'] = $request->amount; // Initial balance is the full amount

        Auth::user()->loans()->create($validated);

        return redirect()->route('loans.index')->with('success', 'Loan record added successfully.');
    }

    public function edit(Loan $loan)
    {
        return view('loans.edit', compact('loan'));
    }

    public function update(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'contact_name' => 'required|string|max:255',
            'type' => 'required|in:lent,borrowed',
            'amount' => 'required|numeric|min:0',
            'balance' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'status' => 'required|string',
        ]);

        $loan->update($validated);

        return redirect()->route('loans.index')->with('success', 'Loan record updated successfully.');
    }

    public function destroy(Loan $loan)
    {
        $loan->delete();
        return redirect()->route('loans.index')->with('success', 'Loan record deleted successfully.');
    }
}
