<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        $userIds = Auth::user()->familyMembers()->pluck('id');
        $subscriptions = Subscription::whereIn('user_id', $userIds)->orderBy('next_billing_date', 'asc')->get();
        $monthlyCost = $subscriptions->sum(function($sub) {
            return $sub->frequency == 'yearly' ? $sub->amount / 12 : $sub->amount;
        });

        return view('subscriptions.index', compact('subscriptions', 'monthlyCost'));
    }

    public function create()
    {
        return view('subscriptions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'frequency' => 'required|string',
            'next_billing_date' => 'required|date',
            'status' => 'required|string',
        ]);

        Auth::user()->subscriptions()->create($validated);

        return redirect()->route('subscriptions.index')->with('success', 'Subscription added successfully.');
    }

    public function edit(Subscription $subscription)
    {
        return view('subscriptions.edit', compact('subscription'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'frequency' => 'required|string',
            'next_billing_date' => 'required|date',
            'status' => 'required|string',
        ]);

        $subscription->update($validated);

        return redirect()->route('subscriptions.index')->with('success', 'Subscription updated successfully.');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('subscriptions.index')->with('success', 'Subscription deleted successfully.');
    }
}
