<?php

namespace App\Http\Controllers;

use App\Models\SavingsGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavingsGoalController extends Controller
{
    public function index()
    {
        $userIds = Auth::user()->familyMembers()->pluck('id');
        $goals = SavingsGoal::whereIn('user_id', $userIds)->get();
        return view('savings.index', compact('goals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'deadline' => 'nullable|date',
            'color' => 'nullable|string',
        ]);

        SavingsGoal::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            'deadline' => $request->deadline,
            'color' => $request->color ?? '#4ade80',
        ]);

        return redirect()->route('savings.index')->with('success', 'Goal created successfully.');
    }

    public function contribute(Request $request, SavingsGoal $savingsGoal)
    {
        $request->validate(['amount' => 'required|numeric|min:1']);
        
        $savingsGoal->increment('current_amount', $request->amount);
        
        return back()->with('success', 'Contribution added!');
    }
    
    public function destroy(SavingsGoal $savingsGoal)
    {
        if (!Auth::user()->familyMembers()->pluck('id')->contains($savingsGoal->user_id)) {
            abort(403);
        }
        $savingsGoal->delete();
        return back()->with('success', 'Goal deleted.');
    }
}
