@extends('layout')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Money Requests</h1>
            <p class="text-gray-500 mt-1">Status of your funding requests.</p>
        </div>
        <a href="{{ route('requests.create') }}" class="flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/30 font-medium">
            <i class="fa-solid fa-paper-plane mr-2"></i> New Request
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- RECEIVED REQUESTS (For me to approve) -->
        <div class="space-y-6">
            <h2 class="font-bold text-xl text-gray-800 border-b border-gray-200 pb-2">Needs Your Approval</h2>
            @forelse($receivedRequests as $req)
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold">
                                {{ substr($req->requester->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $req->requester->name }} requests TSH {{ number_format($req->amount, 2) }}</h3>
                                <p class="text-sm text-gray-500">{{ $req->reason }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 rounded text-xs font-bold uppercase
                            {{ $req->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $req->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $req->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                        ">{{ $req->status }}</span>
                    </div>

                    @if($req->status == 'pending')
                        <div class="flex gap-2">
                            <form action="{{ route('requests.update-status', $req) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="w-full py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm font-medium">Approve</button>
                            </form>
                            <form action="{{ route('requests.update-status', $req) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="w-full py-2 bg-red-100 text-red-600 rounded hover:bg-red-200 text-sm font-medium">Reject</button>
                            </form>
                        </div>
                    @elseif($req->status == 'approved')
                        <p class="text-sm text-gray-500 italic"><i class="fa-solid fa-clock-rotate-left"></i> Waiting for requester to upload proof...</p>
                    @endif
                </div>
            @empty
                <div class="bg-gray-50 p-6 rounded-2xl text-center text-gray-400">
                    <p>No pending requests.</p>
                </div>
            @endforelse

            <h2 class="font-bold text-xl text-gray-800 border-b border-gray-200 pb-2 pt-4">Completed History</h2>
            @foreach($completedReceivedRequests as $req)
                 <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 opacity-75 hover:opacity-100 transition-opacity">
                    <div class="flex justify-between">
                         <span class="font-medium text-gray-700">{{ $req->requester->name }}</span>
                         <span class="font-bold text-gray-900">TSH {{ number_format($req->amount, 2) }}</span>
                    </div>
                    <div class="mt-2 text-sm">
                        <a href="{{ asset('storage/'.$req->receipt_path) }}" target="_blank" class="text-indigo-600 hover:underline">
                            <i class="fa-solid fa-paperclip"></i> View Receipt
                        </a>
                    </div>
                 </div>
            @endforeach
        </div>

        <!-- SENT REQUESTS (My requests) -->
        <div class="space-y-6">
            <h2 class="font-bold text-xl text-gray-800 border-b border-gray-200 pb-2">My Requests</h2>
            @forelse($sentRequests as $req)
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-500">To: <span class="font-medium text-gray-800">{{ $req->approver->name }}</span></span>
                        <span class="text-xs text-gray-400">{{ $req->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-2xl font-bold text-gray-900">TSH {{ number_format($req->amount, 2) }}</h3>
                        <span class="px-2 py-1 rounded text-xs font-bold uppercase
                            {{ $req->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $req->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $req->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $req->status == 'completed' ? 'bg-gray-100 text-gray-600' : '' }}
                        ">{{ $req->status }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-4 bg-gray-50 p-2 rounded">{{ $req->reason }}</p>

                    @if($req->status == 'approved')
                        <form action="{{ route('requests.upload-proof', $req) }}" method="POST" enctype="multipart/form-data" class="bg-indigo-50 p-4 rounded border border-indigo-100">
                            @csrf
                            <label class="block text-sm font-medium text-indigo-900 mb-2">Upload Receipt/Statement</label>
                            <input type="file" name="receipt" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200 mb-2" required>
                            <button type="submit" class="w-full py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm font-medium">Upload & Complete</button>
                        </form>
                    @elseif($req->status == 'completed')
                         <div class="text-sm text-green-600 flex items-center">
                            <i class="fa-solid fa-check-circle mr-1"></i> Completed & Verified
                         </div>
                    @endif
                </div>
            @empty
                <div class="bg-gray-50 p-6 rounded-2xl text-center text-gray-400">
                    <p>You haven't sent any requests.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
