<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class HouseholdController extends Controller
{
    public function index()
    {
        $members = Auth::user()->familyMembers();
        return view('household.index', compact('members'));
    }

    public function link(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $targetUser = User::where('email', $request->email)->first();

        if (!$targetUser) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        if ($targetUser->id === Auth::id()) {
             return back()->withErrors(['email' => 'You cannot link yourself.']);
        }

        // Logic:
        // 1. If neither has family_id, generate new one for both.
        // 2. If Auth user has family_id, add Target user to it.
        // 3. If Target user has family_id, join theirs (security risk? assuming trust for now).
        // Let's implement: Auth user invites Target. Target joins Auth's family.
        
        $familyId = Auth::user()->family_id ?? (string) Str::uuid();
        
        Auth::user()->update(['family_id' => $familyId]);
        $targetUser->update(['family_id' => $familyId]);

        return back()->with('success', 'Account linked successfully! You are now sharing a household.');
    }
    
    public function leave()
    {
        Auth::user()->update(['family_id' => null]);
        return back()->with('success', 'You have left the household.');
    }
}
