@extends('layout')

@section('content')
    <div class="max-w-xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Import from SMS</h1>
        
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-gray-500 mb-6">Paste your M-Pesa, Tigo Pesa, or Bank SMS transaction message below. We'll automatically extract the details for you.</p>

            <form action="{{ route('sms-parser.parse') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message Text</label>
                    <textarea name="sms_text" rows="6" class="w-full p-4 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all font-mono text-sm" placeholder="e.g. Confirmed. Tsh 10,000 sent to..." required></textarea>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('dashboard') }}" class="flex-1 py-3 px-4 text-center text-gray-700 bg-white border border-gray-300 rounded-lg font-medium hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="flex-1 py-3 px-4 bg-gray-900 text-white rounded-lg font-bold hover:bg-gray-800 shadow-lg">
                        <i class="fa-solid fa-magic-wand-sparkles mr-2"></i> Parse Message
                    </button>
                </div>
            </form>
        </div>

         <div class="mt-8 bg-blue-50 p-6 rounded-xl border border-blue-100">
            <h3 class="font-bold text-blue-900 mb-2">Supported Formats</h3>
            <ul class="text-sm text-blue-700 space-y-2">
                <li><i class="fa-solid fa-check mr-2"></i> M-Pesa Sent ("Confirmed. Tsh X sent to Y...")</li>
                <li><i class="fa-solid fa-check mr-2"></i> M-Pesa Received ("You have received Tsh X from Y...")</li>
                <li><i class="fa-solid fa-check mr-2"></i> Basic "Paid Tsh X to Y" formats</li>
            </ul>
        </div>
    </div>
@endsection
