@extends('layouts.app')
@section('title', 'Laporan Penjualan')

@section('content')
<style>
    .card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
    .input-field { padding:9px 14px; border-radius:10px; border:1.5px solid #e2e8f0; font-size:12px; outline:none; transition:border-color 0.2s; background:#f8fafc; color:#1e293b; font-family:'Poppins',sans-serif; }
    .input-field:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.1); background:#fff; }
    .section-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:#94a3b8; }
</style>

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Laporan Penjualan</h2>
        <p class="text-sm text-slate-400 mt-0.5">{{ $startDate->format('d M Y') }} — {{ $endDate->format('d M Y') }}</p>
    </div>
</div>

{{-- Filter --}}
<div class="card p-4 mb-5">
    <form method="GET" action="{{ route('admin.reports.sales') }}" class="flex items-center gap-3">
        <div class="flex items-center gap-2">
            <label class="section-label whitespace-nowrap">Dari</label>
            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="input-field">
        </div>
        <div class="flex items-center gap-2">
            <label class="section-label whitespace-nowrap">Sampai</label>
            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="input-field">
        </div>
        <button type="submit"
            class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition">
            Tampilkan
        </button>
        {{-- Shortcut --}}
        <div class="flex gap-1 ml-2">
            @php
                $shortcuts = [
                    'Hari ini' => [now()->format('Y-m-d'), now()->format('Y-m-d')],
                    '7 Hari'   => [now()->subDays(6)->format('Y-m-d'), now()->format('Y-m-d')],
                    'Bulan Ini'=> [now()->startOfMonth()->format('Y-m-d'), now()->format('Y-m-d')],
                ];
            @endphp
            @foreach($shortcuts as $label => [$s, $e])
                <a href="?start_date={{ $s }}&end_date={{ $e }}"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition
                    {{ $startDate->format('Y-m-d') === $s && $endDate->format('Y-m-d') === $e
                        ? 'bg-blue-600 text-white'
                        : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </form>
</div>

{{-- Stats --}}
<div class="grid grid-cols-4 gap-4 mb-5">
    <div class="card p-4 flex items-center justify-between col-span-2">
        <div>
            <p class="section-label">Total Revenue</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
            </svg>
        </div>
    </div>
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Total Transaksi</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalTransactions }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#64748b" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>
            </svg>
        </div>
    </div>
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Rata-rata / Transaksi</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">Rp {{ $totalTransactions > 0 ? number_format($totalRevenue / $totalTransactions, 0, ',', '.') : '0' }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#d97706" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
            </svg>
        </div>
    </div>
</div>

{{-- Chart --}}
<div class="card p-5 mb-5">
    <div class="flex items-center justify-between mb-4">
        <div>
            <p class="font-bold text-slate-800">Grafik Penjualan</p>
            <p class="section-label mt-0.5">Revenue harian dalam periode</p>
        </div>
    </div>
    <canvas id="salesChart" height="80"></canvas>
</div>

{{-- Table --}}
<div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100">
        <p class="font-bold text-slate-800">Detail Transaksi</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Kode Produk</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Harga</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Qty</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Kasir</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Customer</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Mitra</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Tanggal</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Metode Bayar</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($transactions as $trx)
                    @foreach($trx->items as $item)
                    <tr class="hover:bg-blue-50/20 transition">
                        <td class="py-3 px-5">
                            <code class="text-xs bg-slate-100 px-2 py-1 rounded-lg text-slate-600 font-mono">{{ $item->sku }}</code>
                        </td>
                        <td class="py-3 px-5">
                            @php
                                $finalAfterPoint = $item->final_price;
                                if ($trx->points_discount > 0) {
                                    $finalAfterPoint = $item->final_price - ($trx->points_discount / $trx->items->count());
                                }
                            @endphp
                            @if($item->original_price != $item->final_price || $trx->points_discount > 0)
                                <div class="text-slate-400 line-through text-xs">Rp {{ number_format($item->original_price, 0, ',', '.') }}</div>
                                <div class="font-semibold text-sm flex items-center gap-1">
                                    <span class="text-red-500">Rp {{ number_format($finalAfterPoint, 0, ',', '.') }}</span>
                                    @if($trx->points_discount > 0)
                                        <span class="bg-emerald-100 text-emerald-600 text-xs px-1.5 rounded font-bold">POIN</span>
                                    @else
                                        <span class="bg-red-100 text-red-500 text-xs px-1.5 rounded font-bold">Diskon</span>
                                    @endif
                                </div>
                            @else
                                <div class="font-semibold text-slate-700">Rp {{ number_format($item->final_price, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td class="py-3 px-5 text-slate-600">{{ $item->quantity }}</td>
                        <td class="py-3 px-5 text-xs text-slate-500">{{ $trx->salesStaff->name ?? $trx->user->name ?? '-' }}</td>
                        <td class="py-3 px-5">
                            @if($trx->member)
                                <div class="font-semibold text-amber-500 text-sm">{{ $trx->member->name }}</div>
                                <div class="text-xs text-slate-400">Member</div>
                            @else
                                <span class="text-slate-500 text-xs">{{ $trx->customer_name ?? '-' }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-5 text-slate-500 text-xs">{{ $trx->partner->name ?? '-' }}</td>
                        <td class="py-3 px-5 text-slate-500 text-xs">{{ $trx->created_at->format('d/m/y H:i') }}</td>
                        <td class="py-3 px-5">
                            @php
                                $method = $trx->payment_method;
                                $label = match($method) {
                                    'cash' => ['Cash', 'bg-emerald-100 text-emerald-600'],
                                    'qris_bni' => ['QRIS BNI', 'bg-blue-100 text-blue-600'],
                                    'qris_mandiri' => ['QRIS Mandiri', 'bg-blue-100 text-blue-600'],
                                    'card_bca' => ['Kartu BCA', 'bg-purple-100 text-purple-600'],
                                    'card_mandiri' => ['Kartu Mandiri', 'bg-purple-100 text-purple-600'],
                                    'card_bri' => ['Kartu BRI', 'bg-purple-100 text-purple-600'],
                                    'card_bni' => ['Kartu BNI', 'bg-purple-100 text-purple-600'],
                                    default => [ucfirst($method), 'bg-slate-100 text-slate-500'],
                                };
                            @endphp
                            <span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $label[1] }}">{{ $label[0] }}</span>
                        </td>
                        <td class="py-3 px-5">
                            @if($trx->deleted_at)
                                <span class="px-2 py-1 rounded-lg bg-red-100 text-red-500 text-xs font-semibold">Deleted</span>
                            @else
                                <span class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-600 text-xs font-semibold">Active</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @empty
                <tr>
                    <td colspan="9" class="text-center py-14">
                        <div class="flex flex-col items-center gap-2 text-slate-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z"/>
                            </svg>
                            <p class="text-sm">Tidak ada data transaksi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Build chart data dari transactions
    const rawData = @json($transactions->groupBy(fn($t) => $t->created_at->format('d/m'))->map(fn($g) => $g->sum('total')));

    // Generate semua tanggal dalam range
    const start = new Date('{{ $startDate->format('Y-m-d') }}');
    const end   = new Date('{{ $endDate->format('Y-m-d') }}');
    const labels = [], data = [];

    for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
        const day   = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const label = `${day}/${month}`;
        labels.push(label);
        data.push(rawData[label] ?? 0);
    }

    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Penjualan (Rp)',
                data,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.07)',
                borderWidth: 2.5,
                pointBackgroundColor: '#2563eb',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw)
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        font: { size: 10, family: 'Poppins' },
                        color: '#94a3b8',
                        callback: val => 'Rp ' + new Intl.NumberFormat('id-ID').format(val)
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10, family: 'Poppins' }, color: '#94a3b8' }
                }
            }
        }
    });
</script>

@endsection
