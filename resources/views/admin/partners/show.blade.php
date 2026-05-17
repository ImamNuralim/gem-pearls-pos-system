@extends('layouts.app')
@section('title', 'Detail Mitra')

@section('content')
<style>
    .card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
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
<div class="flex items-center gap-3 mb-5">
    <a href="{{ route('admin.partners.index') }}"
        class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-600">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
        </svg>
    </a>
    <div class="flex-1">
        <h2 class="text-xl font-bold text-slate-800">{{ $partner->name }}</h2>
        <div class="flex items-center gap-2 mt-0.5">
            <code class="text-xs bg-slate-100 px-2 py-0.5 rounded-lg text-slate-500 font-mono">{{ $partner->code }}</code>
            <span class="text-xs px-2 py-0.5 rounded-lg font-semibold
                {{ $partner->type === 'travel_agent' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                {{ $partner->type === 'travel_agent' ? 'Travel Agent' : 'Freelance Guide' }}
            </span>
            <span class="text-xs px-2 py-0.5 rounded-lg font-semibold
                {{ $partner->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                {{ $partner->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>
    </div>
    <a href="{{ route('admin.partners.edit', $partner) }}"
        class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
        </svg>
        Edit
    </a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-5">
    <div class="card p-5 flex items-center justify-between">
        <div>
            <p class="section-label">Total Komisi</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($totalCommission, 0, ',', '.') }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.036 0-1.875.84-1.875 1.875S10.964 12 12 12s1.875.84 1.875 1.875S13.036 15.75 12 15.75m0-7.5c1.036 0 1.875.84 1.875 1.875M12 15.75v1.5m-7.5-6h15"/>
            </svg>
        </div>
    </div>
    <div class="card p-5 flex items-center justify-between">
        <div>
            <p class="section-label">Belum Dibayar</p>
            <p class="text-2xl font-bold text-red-500 mt-1">Rp {{ number_format($unpaidCommission, 0, ',', '.') }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ef4444" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
            </svg>
        </div>
    </div>
    <div class="card p-5 flex items-center justify-between">
        <div>
            <p class="section-label">Sudah Dibayar</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($paidCommission, 0, ',', '.') }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#059669" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </div>
    </div>
</div>

{{-- Riwayat Kunjungan & Komisi --}}
<div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
        <div>
            <p class="font-bold text-slate-800">Riwayat Kunjungan & Komisi</p>
            <p class="section-label mt-0.5">Data kunjungan partner</p>
        </div>
        <a href="{{ route('admin.commissions.index') }}"
            class="text-xs text-blue-500 hover:text-blue-700 font-semibold">
            Kelola Komisi →
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Kode Visit</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Tanggal</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Rombongan</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Total Belanja</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Komisi</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Status Visit</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Status Komisi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($visits as $visit)
                @php $commission = $visit->commissions->first() @endphp
                <tr class="hover:bg-blue-50/20 transition">
                    <td class="py-3 px-5">
                        <span class="font-bold text-blue-600 text-xs">{{ $visit->visit_code }}</span>
                    </td>
                    <td class="py-3 px-5 text-xs text-slate-500">
                        {{ $visit->visit_date?->format('d/m/y') }}
                    </td>
                    <td class="py-3 px-5 text-xs text-slate-600">
                        {{ $visit->group_description ?? '-' }}
                    </td>
                    <td class="py-3 px-5 font-semibold text-slate-700">
                        Rp {{ number_format($visit->total_sales, 0, ',', '.') }}
                    </td>
                    <td class="py-3 px-5">
                        @if($commission)
                            <div class="font-bold text-emerald-600">Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}</div>
                            <div class="text-xs text-slate-400">{{ $commission->commission_rate }}%</div>
                        @else
                            <span class="text-xs text-slate-300">Belum ada komisi</span>
                        @endif
                    </td>
                    <td class="py-3 px-5">
                        @if($visit->status === 'pending')
                            <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-500 text-xs font-semibold">Pending</span>
                        @else
                            <span class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-600 text-xs font-semibold">Completed</span>
                        @endif
                    </td>
                    <td class="py-3 px-5">
                        @if($commission)
                            @if($commission->status === 'paid')
                                <span class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-600 text-xs font-bold">PAID</span>
                            @else
                                <span class="px-2 py-1 rounded-lg bg-red-100 text-red-500 text-xs font-bold">UNPAID</span>
                            @endif
                        @else
                            <span class="text-xs text-slate-300">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-14 text-center">
                        <div class="flex flex-col items-center gap-2 text-slate-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                            </svg>
                            <p class="text-sm">Belum ada kunjungan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($visits->hasPages())
    <div class="px-5 py-3 border-t border-slate-100">
        {{ $visits->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection
