@extends('layouts.app')
@section('title', 'Manajemen Mitra')

@section('content')
<style>
    .card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
    .input-field { width:100%; padding:9px 14px; border-radius:10px; border:1.5px solid #e2e8f0; font-size:12px; outline:none; transition:border-color 0.2s; background:#f8fafc; color:#1e293b; font-family:'Poppins',sans-serif; }
    .input-field:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.1); background:#fff; }
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
        <h2 class="text-xl font-bold text-slate-800">Manajemen Mitra</h2>
        <p class="text-sm text-slate-400 mt-0.5">Travel Agent & Freelance Guide</p>
    </div>
    <a href="{{ route('admin.partners.create') }}"
        class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Tambah Mitra
    </a>
</div>

{{-- Stats --}}
@php
    $totalMitra  = \App\Models\Partner::count();
    $travelAgent = \App\Models\Partner::where('type', 'travel_agent')->count();
    $freelance   = \App\Models\Partner::where('type', 'freelance_guide')->count();
    $unpaidTotal = \App\Models\Commission::where('status', 'unpaid')->sum('commission_amount');
@endphp
<div class="grid grid-cols-4 gap-4 mb-5">
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Total Mitra</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalMitra }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>
            </svg>
        </div>
    </div>
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Travel Agent</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $travelAgent }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
            </svg>
        </div>
    </div>
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Freelance Guide</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $freelance }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#059669" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.159.69.159 1.006 0Z"/>
            </svg>
        </div>
    </div>
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Komisi Belum Dibayar</p>
            <p class="text-xl font-bold text-red-500 mt-1">Rp {{ number_format($unpaidTotal, 0, ',', '.') }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ef4444" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.036 0-1.875.84-1.875 1.875S10.964 12 12 12s1.875.84 1.875 1.875S13.036 15.75 12 15.75m0-7.5c1.036 0 1.875.84 1.875 1.875M12 15.75v1.5m-7.5-6h15"/>
            </svg>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card p-4 mb-4">
    <form method="GET" class="flex gap-2">
        <div class="flex-1 relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama atau kode mitra..."
                class="input-field" style="padding-left:36px;">
        </div>
        <select name="type" class="input-field" style="width:auto; padding:9px 12px;">
            <option value="">Semua Tipe</option>
            <option value="travel_agent" {{ request('type') === 'travel_agent' ? 'selected' : '' }}>Travel Agent</option>
            <option value="freelance_guide" {{ request('type') === 'freelance_guide' ? 'selected' : '' }}>Freelance Guide</option>
        </select>
        <select name="status" class="input-field" style="width:auto; padding:9px 12px;">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <button type="submit"
            class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition">
            Filter
        </button>
        @if(request()->hasAny(['search', 'type', 'status']))
            <a href="{{ route('admin.partners.index') }}"
                class="px-4 py-2 rounded-xl border border-slate-200 text-sm text-slate-500 hover:bg-slate-50 transition font-medium">
                Reset
            </a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-100">
            <tr>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Mitra</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Kode</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Tipe</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Total Kunjungan</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Status</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($partners as $partner)
            <tr class="hover:bg-blue-50/20 transition">
                <td class="py-3 px-5">
                    <p class="font-semibold text-slate-700">{{ $partner->name }}</p>
                    <p class="text-xs text-slate-400">{{ $partner->phone ?? '-' }}</p>
                </td>
                <td class="py-3 px-5">
                    <code class="text-xs bg-slate-100 px-2 py-1 rounded-lg text-slate-600 font-mono">{{ $partner->code }}</code>
                </td>
                <td class="py-3 px-5">
                    <span class="text-xs px-2 py-1 rounded-lg font-semibold
                        {{ $partner->type === 'travel_agent' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                        {{ $partner->type === 'travel_agent' ? 'Travel Agent' : 'Freelance Guide' }}
                    </span>
                </td>
                <td class="py-3 px-5">
                    <span class="font-bold text-slate-700">{{ $partner->visits_count ?? \App\Models\PartnerVisit::where('partner_id', $partner->id)->count() }}</span>
                    <span class="text-xs text-slate-400 ml-1">kunjungan</span>
                </td>
                <td class="py-3 px-5">
                    <span class="text-xs px-2 py-1 rounded-lg font-semibold
                        {{ $partner->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                        {{ $partner->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="py-3 px-5">
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.partners.show', $partner) }}"
                            class="p-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 transition" title="Detail">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                        </a>
                        <a href="{{ route('admin.partners.edit', $partner) }}"
                            class="p-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}"
                            onsubmit="return confirm('Yakin hapus mitra ini?')">
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
            @empty
            <tr>
                <td colspan="6" class="text-center py-14">
                    <div class="flex flex-col items-center gap-2 text-slate-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>
                        </svg>
                        <p class="text-sm">Belum ada mitra</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($partners->hasPages())
    <div class="px-5 py-3 border-t border-slate-100">
        {{ $partners->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection
