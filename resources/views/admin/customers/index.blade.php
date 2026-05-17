@extends('layouts.app')
@section('title', 'Data No HP Customer')

@section('content')
<style>
    .card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
    .input-field { width:100%; padding:9px 14px; border-radius:10px; border:1.5px solid #e2e8f0; font-size:12px; outline:none; transition:border-color 0.2s; background:#f8fafc; color:#1e293b; font-family:'Poppins',sans-serif; }
    .input-field:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.1); background:#fff; }
    .section-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:#94a3b8; }
</style>

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Data No HP Customer</h2>
        <p class="text-sm text-slate-400 mt-0.5">Nomor WhatsApp customer yang pernah bertransaksi</p>
    </div>
    <div class="flex items-center gap-3">
        <span class="px-3 py-1.5 rounded-xl bg-blue-100 text-blue-700 text-xs font-bold">
            {{ $customers->count() }} Customer
        </span>
        <a href="{{ route('admin.customers.export') }}"
    class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition shadow-sm">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
    </svg>
    Download Excel
</a>

    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-5">
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Total Customer</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $customers->count() }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#2563eb" class="w-5 h-5">
                <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd"/>
            </svg>
        </div>
    </div>
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Total Transaksi</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $customers->sum('total_transaksi') }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#d97706" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z"/>
            </svg>
        </div>
    </div>
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Total Belanja</p>
            <p class="text-lg font-bold text-emerald-600 mt-1">Rp {{ number_format($customers->sum('total_belanja'), 0, ',', '.') }}</p>
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
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">No HP</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Nama</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Tipe</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Total Transaksi</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Total Belanja</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Terakhir Belanja</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($customers as $customer)
                <tr class="hover:bg-blue-50/20 transition">
                    <td class="py-3 px-5">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#059669" class="w-3.5 h-3.5">
                                    <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="font-semibold text-slate-700">{{ $customer->customer_phone }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-5 text-slate-600 text-sm">{{ $customer->customer_name ?? '-' }}</td>
                    <td class="py-3 px-5">
                        @php $type = $customer->customer_type; @endphp
                        <span class="px-2 py-1 rounded-lg text-xs font-semibold
                            {{ $type === 'walk_in' ? 'bg-slate-100 text-slate-500' : '' }}
                            {{ $type === 'travel_agent' ? 'bg-blue-100 text-blue-600' : '' }}
                            {{ $type === 'freelance_guide' ? 'bg-amber-100 text-amber-600' : '' }}
                            {{ $type === 'member' ? 'bg-emerald-100 text-emerald-600' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                        </span>
                    </td>
                    <td class="py-3 px-5 font-bold text-slate-700">{{ $customer->total_transaksi }}x</td>
                    <td class="py-3 px-5 font-bold text-emerald-600">Rp {{ number_format($customer->total_belanja, 0, ',', '.') }}</td>
                    <td class="py-3 px-5 text-xs text-slate-400">
                        {{ \Carbon\Carbon::parse($customer->last_transaction)->format('d/m/y H:i') }}
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-14 text-center">
                        <div class="flex flex-col items-center gap-2 text-slate-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>
                            </svg>
                            <p class="text-sm">Belum ada data no HP customer</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
