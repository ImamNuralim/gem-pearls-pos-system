@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<style>
    .stat-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: 20px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .stat-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #94a3b8;
        margin-bottom: 6px;
    }
    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
    }
    .stat-sub {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 6px;
    }
    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .card-title {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
    }
    .card-body { padding: 20px; }
    .section-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #94a3b8;
    }
</style>

<div class="space-y-5">

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-5 gap-4">

        {{-- Penjualan Hari Ini --}}
        <div class="stat-card col-span-2 flex items-center justify-between">
            <div>
                <div class="stat-label">Penjualan Hari Ini</div>
                <div class="stat-value text-blue-600">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
                <div class="stat-sub">{{ $todayTransactions }} transaksi</div>
            </div>
            <div class="stat-icon bg-blue-50">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
                </svg>
            </div>
        </div>

        {{-- Visit Hari Ini --}}
        <div class="stat-card flex items-center justify-between">
            <div>
                <div class="stat-label">Visit Hari Ini</div>
                <div class="stat-value">{{ $todayVisits }}</div>
                <div class="stat-sub">Rombongan</div>
            </div>
            <div class="stat-icon bg-amber-50">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#d97706" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                </svg>
            </div>
        </div>

        {{-- Total Member --}}
        <div class="stat-card flex items-center justify-between">
            <div>
                <div class="stat-label">Total Member</div>
                <div class="stat-value">{{ $totalMembers }}</div>
                <div class="stat-sub">Member aktif</div>
            </div>
            <div class="stat-icon bg-emerald-50">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#059669" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                </svg>
            </div>
        </div>

        {{-- Total Partner --}}
        <div class="stat-card flex items-center justify-between">
            <div>
                <div class="stat-label">Total Partner</div>
                <div class="stat-value">{{ $totalPartners }}</div>
                <div class="stat-sub">Travel & Freelance</div>
            </div>
            <div class="stat-icon bg-purple-50">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#7c3aed" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>
                </svg>
            </div>
        </div>

    </div>

    {{-- CHART + TOP PRODUK --}}
    <div class="grid grid-cols-3 gap-4">

        {{-- Line Chart --}}
        <div class="card col-span-2">
            <div class="card-header">
    <div>
        <div class="card-title">Grafik Penjualan</div>
        <div class="section-label mt-0.5">Total transaksi harian</div>
    </div>
    <div class="flex items-center gap-2">
        <div class="flex gap-1">
            @foreach([7 => '7 Hari', 14 => '14 Hari', 30 => '30 Hari'] as $val => $label)
                <a href="?days={{ $val }}"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition
                    {{ ($days ?? 7) == $val ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
        <a href="{{ route('admin.reports.index') }}" class="text-xs text-blue-500 hover:text-blue-700 font-semibold ml-2">Laporan →</a>
    </div>
</div>
            <div class="card-body">
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>

        {{-- Top Produk --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Produk Terlaris</div>
            </div>
            <div class="card-body space-y-3">
                @forelse($topProducts as $i => $product)
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0
                        {{ $i === 0 ? 'bg-amber-100 text-amber-600' : 'bg-slate-100 text-slate-500' }}">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-semibold text-slate-800 truncate">{{ $product->product_name }}</div>
                        <div class="text-xs text-slate-400">{{ $product->sku }}</div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <div class="text-xs font-bold text-blue-600">{{ $product->total_qty }}x</div>
                        <div class="text-xs text-slate-400">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-slate-300">
                    <p class="text-sm">Belum ada data</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- RECENT TRANSACTIONS --}}
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Transaksi Terbaru</div>
                <div class="section-label mt-0.5">7 transaksi terakhir</div>
            </div>
            <a href="{{ route('admin.reports.sales') }}" class="text-xs text-blue-500 hover:text-blue-700 font-semibold">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-400">Invoice</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-400">Tipe</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-400">Partner</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-400">Pembayaran</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-400">Total</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-400">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recentTransactions as $trx)
                    <tr class="hover:bg-blue-50/20 transition">
                        <td class="px-5 py-3 font-bold text-blue-600 text-xs">{{ $trx->invoice_number }}</td>
                        <td class="px-5 py-3">
                            @if($trx->customer_type === 'walk_in')
                                <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-semibold">Walk-in</span>
                            @elseif($trx->customer_type === 'travel_agent')
                                <span class="px-2 py-1 rounded-lg bg-blue-100 text-blue-600 text-xs font-semibold">Travel Agent</span>
                            @elseif($trx->customer_type === 'freelance_guide')
                                <span class="px-2 py-1 rounded-lg bg-amber-100 text-amber-600 text-xs font-semibold">Freelance</span>
                            @else
                                <span class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-600 text-xs font-semibold">Member</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-xs text-slate-600">{{ $trx->partner->name ?? '-' }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-1 rounded-lg text-xs font-semibold
                                {{ $trx->payment_method === 'cash' ? 'bg-green-100 text-green-600' : '' }}
                                {{ $trx->payment_method === 'qris' ? 'bg-purple-100 text-purple-600' : '' }}
                                {{ $trx->payment_method === 'card' ? 'bg-blue-100 text-blue-600' : '' }}">
                                {{ strtoupper($trx->payment_method) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 font-bold text-slate-800 text-sm">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-xs text-slate-400">{{ $trx->created_at->format('H:i') }} · {{ $trx->created_at->format('d/m/y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-300">
                            <p class="text-sm">Belum ada transaksi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Penjualan (Rp)',
                data: @json($chartData),
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.08)',
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
