<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    public function index()
    {
        $userIds = Auth::user()->familyMembers()->pluck('id');
        $assets = Asset::whereIn('user_id', $userIds)->orderBy('valuation_date', 'desc')->get();
        $totalWealth = $assets->sum('value');

        return view('assets.index', compact('assets', 'totalWealth'));
    }

    public function create()
    {
        return view('assets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'value' => 'required|numeric|min:0',
            'valuation_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        Auth::user()->assets()->create($validated);

        return redirect()->route('assets.index')->with('success', 'Asset added successfully.');
    }

    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'value' => 'required|numeric|min:0',
            'valuation_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $asset->update($validated);

        return redirect()->route('assets.index')->with('success', 'Asset updated successfully.');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully.');
    }
}
