@extends('layouts.app')
@section('title', 'Data Sales')

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

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Data Sales</h2>
        <p class="text-sm text-slate-400 mt-0.5">Manajemen tim sales & kasir</p>
    </div>
    <div class="flex items-center gap-3">
        {{-- Filter Tanggal --}}
        <form method="GET" class="flex items-center gap-2">
            <div class="flex items-center gap-2">
                <label class="text-xs text-slate-500 font-medium whitespace-nowrap">Dari</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-xs text-slate-500 font-medium whitespace-nowrap">Sampai</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
            </div>
            <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700 transition">
                Tampilkan
            </button>
        </form>
        <button onclick="document.getElementById('create-sales-modal').classList.remove('hidden')"
            class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Tambah Sales
        </button>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-5">
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Total Sales</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $staffs->count() }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
            </svg>
        </div>
    </div>
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Total Transaksi</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $staffs->sum('transactions_count') }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#d97706" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 21Z"/>
            </svg>
        </div>
    </div>
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Total Penjualan</p>
            <p class="text-lg font-bold text-emerald-600 mt-1">Rp {{ number_format($staffs->sum('transactions_sum_total'), 0, ',', '.') }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#059669" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
            </svg>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-100">
            <tr>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Kode</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Nama</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Jumlah Transaksi</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Total Penjualan</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Status</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($staffs as $staff)
            <tr class="hover:bg-blue-50/20 transition">
                <td class="py-3 px-5">
                    <code class="text-xs bg-slate-100 px-2 py-1 rounded-lg text-blue-600 font-mono font-bold">{{ $staff->code }}</code>
                </td>
                <td class="py-3 px-5">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm flex-shrink-0">
                            {{ substr($staff->name, 0, 1) }}
                        </div>
                        <span class="font-semibold text-slate-700">{{ $staff->name }}</span>
                    </div>
                </td>
                <td class="py-3 px-5 font-bold text-slate-700">{{ $staff->transactions_count }}x</td>
                <td class="py-3 px-5 font-bold text-emerald-600">Rp {{ number_format($staff->transactions_sum_total ?? 0, 0, ',', '.') }}</td>
                <td class="py-3 px-5">
                    <span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $staff->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                        {{ $staff->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="py-3 px-5">
                    <div class="flex items-center gap-1.5">
                        {{-- Edit --}}
                        <button onclick="document.getElementById('edit-sales-modal-{{ $staff->id }}').classList.remove('hidden')"
                            class="p-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                            </svg>
                        </button>
                        {{-- Hapus --}}
                        <form method="POST" action="{{ route('admin.sales-staff.destroy', $staff) }}"
                            onsubmit="return confirm('Yakin hapus sales ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 transition" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>

            {{-- Edit Modal --}}
            <div id="edit-sales-modal-{{ $staff->id }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl w-full max-w-sm p-6 relative">
                    <button onclick="document.getElementById('edit-sales-modal-{{ $staff->id }}').classList.add('hidden')"
                        class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <h2 class="text-lg font-bold text-slate-800 mb-5">Edit Sales</h2>
                    <form action="{{ route('admin.sales-staff.update', $staff) }}" method="POST" class="space-y-4">
                        @csrf @method('PUT')
                        <div>
                            <label class="label">Nama</label>
                            <input type="text" name="name" value="{{ $staff->name }}" required class="input-field">
                        </div>
                        <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>

            @empty
            <tr>
                <td colspan="6" class="py-14 text-center text-slate-300">
                    <p class="text-sm">Belum ada data sales</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Create Modal --}}
<div id="create-sales-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-sm p-6 relative">
        <button onclick="document.getElementById('create-sales-modal').classList.add('hidden')"
            class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
        <h2 class="text-lg font-bold text-slate-800 mb-5">Tambah Sales</h2>
        <form action="{{ route('admin.sales-staff.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="label">Nama</label>
                <input type="text" name="name" required class="input-field" placeholder="Nama sales">
            </div>
            <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition">
                Tambah Sales
            </button>
        </form>
    </div>
</div>

@endsection
