@extends('layout')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Household Settings</h1>
        <p class="text-gray-500 mb-8">Link accounts to view aggregated dashboard data.</p>
        
        <!-- Link Form -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 mb-8">
            <h2 class="font-bold text-xl text-gray-800 mb-4">Link a Partner</h2>
            <p class="text-sm text-gray-600 mb-6 bg-blue-50 p-4 rounded-lg">
                <i class="fa-solid fa-circle-info text-blue-500 mr-2"></i>
                Enter the email of the person you want to link with. Once linked, you will both see combined totals on your Dashboards.
            </p>

            <form action="{{ route('household.link') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Partner's Email</label>
                    <input type="email" name="email" class="w-full p-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all" placeholder="partner@example.com" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full py-3 bg-brand-600 text-white rounded-lg font-bold hover:bg-brand-700 shadow-lg shadow-brand-500/30">
                    Send Link Request
                </button>
            </form>
        </div>

        <!-- Members List -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="font-bold text-xl text-gray-800 mb-6">Current Household Members</h2>
            
            <div class="space-y-4">
                @foreach($members as $member)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center text-brand-600 font-bold">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $member->name }} {{ $member->id === auth()->id() ? '(You)' : '' }}</h3>
                                <p class="text-xs text-gray-500">{{ $member->email }}</p>
                            </div>
                        </div>
                        @if($member->id === auth()->id() && $members->count() > 1)
                            <form action="{{ route('household.leave') }}" method="POST" onsubmit="return confirm('Are you sure you want to leave this household?');">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Leave</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
