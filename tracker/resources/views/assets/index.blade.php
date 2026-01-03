@extends('layout')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Assets & Net Worth</h1>
            <p class="text-gray-500 mt-1">Track your investments and long-term holdings.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('assets.create') }}" class="flex items-center px-4 py-2 bg-brand-600 text-white rounded-lg hover:bg-brand-700 transition-colors shadow-lg shadow-brand-500/30 font-medium">
                <i class="fa-solid fa-plus mr-2"></i> Add Asset
            </a>
        </div>
    </div>

    <!-- Wealth Card -->
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between mb-8">
        <div>
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Estimated Wealth</p>
            <p class="text-4xl font-black text-gray-900 mt-1">TSH {{ number_format($totalWealth, 0) }}</p>
        </div>
        <div class="mt-4 md:mt-0 px-6 py-3 bg-brand-50 rounded-xl text-brand-700 font-bold border border-brand-100 italic">
            "Your net worth is a snapshot of your financial freedom."
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50/50">
                    <tr>
                        <th scope="col" class="px-6 py-4">Asset Name</th>
                        <th scope="col" class="px-6 py-4">Type</th>
                        <th scope="col" class="px-6 py-4 text-right">Value</th>
                        <th scope="col" class="px-6 py-4">Last Valuation</th>
                        <th scope="col" class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($assets as $asset)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $asset->name }}
                                @if($asset->notes)
                                    <div class="text-xs font-normal text-gray-400 mt-0.5">{{ $asset->notes }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-700 text-xs px-2.5 py-1 rounded inline-flex items-center">
                                    <i class="fa-solid fa-tag mr-1.5 opacity-50"></i> {{ $asset->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900 whitespace-nowrap">
                                TSH {{ number_format($asset->value, 0) }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ \Carbon\Carbon::parse($asset->valuation_date)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <a href="{{ route('assets.edit', $asset) }}" class="text-gray-400 hover:text-brand-600 transition-colors">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this asset?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">
                                No assets tracked yet. Start building your portfolio!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
