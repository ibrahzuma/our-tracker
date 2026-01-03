<?php

namespace App\Http\Controllers;

use App\Models\MoneyRequest;
use App\Models\User;
use App\Notifications\MoneyRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MoneyRequestController extends Controller
{
    public function index()
    {
        $sentRequests = Auth::user()->moneyRequestsSent()->latest()->get();
        $receivedRequests = Auth::user()->moneyRequestsReceived()->where('status', '!=', 'completed')->latest()->get();
        $completedReceivedRequests = Auth::user()->moneyRequestsReceived()->where('status', 'completed')->latest()->get();

        return view('requests.index', compact('sentRequests', 'receivedRequests', 'completedReceivedRequests'));
    }

    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('requests.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'approver_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:255',
        ]);

        $moneyRequest = MoneyRequest::create([
            'requester_id' => Auth::id(),
            'approver_id' => $request->approver_id,
            'amount' => $request->amount,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        $approver = User::find($request->approver_id);
        $approver->notify(new MoneyRequestNotification(
            Auth::user()->name . " requested $" . number_format($request->amount, 2),
            route('requests.index')
        ));

        return redirect()->route('requests.index')->with('success', 'Request sent successfully.');
    }

    public function updateStatus(Request $request, MoneyRequest $moneyRequest)
    {
        if (Auth::id() !== $moneyRequest->approver_id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $moneyRequest->update(['status' => $request->status]);

        $moneyRequest->requester->notify(new MoneyRequestNotification(
            "Your request for $" . number_format($moneyRequest->amount, 2) . " was " . $request->status,
            route('requests.index')
        ));

        return redirect()->route('requests.index')->with('success', 'Request status updated.');
    }

    public function uploadProof(Request $request, MoneyRequest $moneyRequest)
    {
        if (Auth::id() !== $moneyRequest->requester_id) {
            abort(403);
        }

        $request->validate([
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $path = $request->file('receipt')->store('receipts', 'public');

        $moneyRequest->update([
            'receipt_path' => $path,
            'status' => 'completed',
        ]);

        $moneyRequest->approver->notify(new MoneyRequestNotification(
            "Receipt uploaded for $" . number_format($moneyRequest->amount, 2),
            route('requests.index')
        ));

        return redirect()->route('requests.index')->with('success', 'Proof uploaded and request completed.');
    }
}
