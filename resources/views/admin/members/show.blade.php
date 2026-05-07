@extends('layouts.app')
@section('title', 'Detail Member')

@section('content')

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">✅ {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">❌ {{ session('error') }}</div>
@endif

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.members.index') }}" class="text-gray-400 hover:text-gray-600 transition">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
    </a>
    <div class="flex-1">
        <h2 class="text-xl font-bold text-gray-800">{{ $member->name }}</h2>
        <p class="text-sm text-gray-400">{{ $member->phone }} • Terdaftar {{ $member->registered_at->format('d/m/Y') }}</p>
    </div>
    <a href="{{ route('admin.members.edit', $member) }}"
        class="bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">Edit</a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-amber-100 col-span-1">
        <p class="text-xs text-gray-400">Saldo Poin</p>
        <p class="text-3xl font-bold text-amber-600 mt-1">{{ number_format($member->points_balance, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-1">≈ Rp {{ number_format($member->points_balance * 100, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400">Total Poin Didapat</p>
        <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($totalEarned, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400">Total Poin Diredeem</p>
        <p class="text-2xl font-bold text-red-500 mt-1">{{ number_format($totalRedeemed, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400">Total Belanja</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $totalTransactions }} transaksi</p>
    </div>
</div>

<div class="grid grid-cols-3 gap-6">
    {{-- Riwayat Poin --}}
    <div class="col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-700">Riwayat Poin</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Tanggal</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Keterangan</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Poin</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pointLogs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 text-xs text-gray-500">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td class="py-3 px-4">
                        <p class="text-xs text-gray-600">{{ $log->note }}</p>
                        @if($log->transaction)
                        <code class="text-xs text-gray-400">{{ $log->transaction->invoice_number }}</code>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        <span class="font-bold {{ $log->type === 'earn' ? 'text-green-600' : 'text-red-500' }}">
                            {{ $log->type === 'earn' ? '+' : '-' }}{{ number_format($log->points, 0, ',', '.') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center py-8 text-gray-300 text-sm">Belum ada riwayat poin</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($pointLogs->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $pointLogs->links() }}</div>
        @endif
    </div>

    {{-- Adjust Poin Manual --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit">
        <h3 class="font-semibold text-gray-700 mb-4">Adjust Poin Manual</h3>
        <form method="POST" action="{{ route('admin.members.adjust-points', $member) }}">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Tipe</label>
                <select name="type" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                    <option value="earn">Tambah Poin</option>
                    <option value="redeem">Kurangi Poin</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Jumlah Poin</label>
                <input type="number" name="points" min="1" required
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm"
                    placeholder="Jumlah poin">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Keterangan</label>
                <input type="text" name="note" required
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm"
                    placeholder="Alasan adjustment">
            </div>
            <button type="submit"
                class="w-full py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold transition">
                Simpan
            </button>
        </form>
    </div>
</div>
@endsection
