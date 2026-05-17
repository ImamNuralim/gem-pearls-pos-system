@extends('layouts.app')
@section('title', 'Detail Member')

@section('content')
<style>
    .card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
    .input-field { width:100%; padding:9px 14px; border-radius:10px; border:1.5px solid #e2e8f0; font-size:13px; outline:none; transition:border-color 0.2s, box-shadow 0.2s; background:#f8fafc; color:#1e293b; font-family:'Poppins',sans-serif; }
    .input-field:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.1); background:#fff; }
    .label { display:block; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; color:#64748b; margin-bottom:6px; }
    .section-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:#94a3b8; }
</style>

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-semibold flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
    </svg>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-semibold flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
    </svg>
    {{ session('error') }}
</div>
@endif

{{-- Header --}}
<div class="flex items-center gap-3 mb-5">
    <a href="{{ route('admin.members.index') }}"
        class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-600">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
        </svg>
    </a>
    <div class="flex-1">
        <h2 class="text-xl font-bold text-slate-800">{{ $member->name }}</h2>
        <p class="text-sm text-slate-400">{{ $member->phone }} · Terdaftar {{ $member->registered_at->format('d/m/y') }}</p>
    </div>
    <a href="{{ route('admin.members.edit', $member) }}"
        class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
        </svg>
        Edit
    </a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-4 gap-4 mb-5">
    <div class="card p-5 border-amber-200 col-span-1">
        <p class="section-label">Saldo Poin</p>
        <p class="text-3xl font-bold text-amber-500 mt-1">{{ number_format($member->points_balance, 0, ',', '.') }}</p>
        <p class="text-xs text-slate-400 mt-1">≈ Rp {{ number_format($member->points_balance * 100, 0, ',', '.') }}</p>
    </div>
    <div class="card p-5">
        <p class="section-label">Total Poin Didapat</p>
        <p class="text-2xl font-bold text-emerald-600 mt-1">{{ number_format($totalEarned, 0, ',', '.') }}</p>
    </div>
    <div class="card p-5">
        <p class="section-label">Total Poin Diredeem</p>
        <p class="text-2xl font-bold text-red-500 mt-1">{{ number_format($totalRedeemed, 0, ',', '.') }}</p>
    </div>
    <div class="card p-5">
        <p class="section-label">Total Belanja</p>
        <p class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
        <p class="text-xs text-slate-400 mt-1">{{ $totalTransactions }} transaksi</p>
    </div>
</div>

<div class="grid grid-cols-3 gap-4">

    {{-- Riwayat Poin --}}
    <div class="col-span-2 card overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <p class="font-bold text-slate-800">Riwayat Poin</p>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Tanggal</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Keterangan</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Poin</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($pointLogs as $log)
                <tr class="hover:bg-blue-50/20 transition">
                    <td class="py-3 px-5 text-xs text-slate-500">{{ $log->created_at->format('d/m/y H:i') }}</td>
                    <td class="py-3 px-5">
                        <p class="text-xs text-slate-600">{{ $log->note }}</p>
                        @if($log->transaction)
                            <code class="text-xs text-slate-400">{{ $log->transaction->invoice_number }}</code>
                        @endif
                    </td>
                    <td class="py-3 px-5">
                        <span class="font-bold text-sm {{ $log->type === 'earn' ? 'text-emerald-600' : 'text-red-500' }}">
                            {{ $log->type === 'earn' ? '+' : '-' }}{{ number_format($log->points, 0, ',', '.') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center py-10 text-slate-300 text-sm">Belum ada riwayat poin</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($pointLogs->hasPages())
        <div class="px-5 py-3 border-t border-slate-100">{{ $pointLogs->links() }}</div>
        @endif
    </div>

    {{-- Adjust Poin --}}
    <div class="card p-5 h-fit">
        <p class="font-bold text-slate-800 mb-4">Adjust Poin Manual</p>
        <form method="POST" action="{{ route('admin.members.adjust-points', $member) }}" class="space-y-3">
            @csrf
            <div>
                <label class="label">Tipe</label>
                <select name="type" class="input-field">
                    <option value="earn">Tambah Poin</option>
                    <option value="redeem">Kurangi Poin</option>
                </select>
            </div>
            <div>
                <label class="label">Jumlah Poin</label>
                <input type="number" name="points" min="1" required class="input-field" placeholder="Jumlah poin">
            </div>
            <div>
                <label class="label">Keterangan</label>
                <input type="text" name="note" required class="input-field" placeholder="Alasan adjustment">
            </div>
            <button type="submit"
                class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition mt-1">
                Simpan
            </button>
        </form>
    </div>

</div>
@endsection
