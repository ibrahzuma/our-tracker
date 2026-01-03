<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    public function index()
    {
        $userIds = Auth::user()->familyMembers()->pluck('id');
        $bills = Bill::whereIn('user_id', $userIds)->orderBy('due_date', 'asc')->get();
        $totalPending = $bills->where('is_paid', false)->sum('amount');

        return view('bills.index', compact('bills', 'totalPending'));
    }

    public function create()
    {
        return view('bills.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'frequency' => 'nullable|string',
        ]);

        Auth::user()->bills()->create($validated);

        return redirect()->route('bills.index')->with('success', 'Bill added successfully.');
    }

    public function edit(Bill $bill)
    {
        return view('bills.edit', compact('bill'));
    }

    public function update(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'frequency' => 'nullable|string',
            'is_paid' => 'nullable|boolean',
        ]);

        $validated['is_paid'] = $request->has('is_paid');

        $bill->update($validated);

        return redirect()->route('bills.index')->with('success', 'Bill updated successfully.');
    }

    public function destroy(Bill $bill)
    {
        $bill->delete();
        return redirect()->route('bills.index')->with('success', 'Bill deleted successfully.');
    }
}
