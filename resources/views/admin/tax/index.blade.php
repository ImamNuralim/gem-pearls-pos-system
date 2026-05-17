@extends('layouts.app')
@section('title', 'Laporan Pajak')

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
        <h2 class="text-xl font-bold text-slate-800">Laporan Pajak UMKM</h2>
        <p class="text-sm text-slate-400 mt-0.5">PPh Final UMKM 0.5% dari penjualan bersih</p>
    </div>
    <div class="flex items-center gap-3">
        {{-- Filter Tahun --}}
        <form method="GET">
            <select name="year" onchange="this.form.submit()" class="input-field" style="width:auto; padding:8px 12px;">
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>
        <button onclick="document.getElementById('create-tax-modal').classList.remove('hidden')"
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Buat Pajak
        </button>
    </div>
</div>

{{-- Summary Cards --}}
@php
    $totalSalesYear    = $reports->sum('total_sales');
    $totalCommission   = $reports->sum('commission_amount');
    $totalSalesFinal   = $reports->sum('sales_final');
    $totalTax          = $reports->sum('tax_amount');
@endphp
<div class="grid grid-cols-4 gap-4 mb-5">
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Total Penjualan</p>
            <p class="text-lg font-bold text-slate-800 mt-1">Rp {{ number_format($totalSalesYear, 0, ',', '.') }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
            </svg>
        </div>
    </div>
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Total Pot. Komisi</p>
            <p class="text-lg font-bold text-red-500 mt-1">Rp {{ number_format($totalCommission, 0, ',', '.') }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ef4444" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </div>
    </div>
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Total Sales Final</p>
            <p class="text-lg font-bold text-emerald-600 mt-1">Rp {{ number_format($totalSalesFinal, 0, ',', '.') }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#059669" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </div>
    </div>
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Total Pajak 0.5%</p>
            <p class="text-lg font-bold text-amber-500 mt-1">Rp {{ number_format($totalTax, 0, ',', '.') }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#d97706" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.036 0-1.875.84-1.875 1.875S10.964 12 12 12s1.875.84 1.875 1.875S13.036 15.75 12 15.75m0-7.5c1.036 0 1.875.84 1.875 1.875M12 15.75v1.5m-7.5-6h15"/>
            </svg>
        </div>
    </div>
</div>

{{-- Tabel --}}
<div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100">
        <p class="font-bold text-slate-800">Laporan Penjualan Mutiara | UMKM {{ $year }}</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">No</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Bulan</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Periode</th>
                    <th class="text-right py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Sales Mutiara</th>
                    <th class="text-right py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Pot. Komisi</th>
                    <th class="text-right py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Sales Final</th>
                    <th class="text-right py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Pajak 0.5%</th>
                    <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @php
                    $months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                    $no = 1;
                @endphp
                @foreach($months as $i => $monthName)
                @php $report = $reports->firstWhere('month', $i + 1); @endphp
                <tr class="hover:bg-blue-50/20 transition {{ $report ? '' : 'opacity-50' }}">
                    <td class="py-3 px-5 text-xs text-slate-400">{{ $i + 1 }}</td>
                    <td class="py-3 px-5 font-semibold text-slate-700">{{ $monthName }}</td>
                    <td class="py-3 px-5 text-xs text-slate-400">
                        @if($report)
                            {{ $report->period_start->format('d/m/y') }} — {{ $report->period_end->format('d/m/y') }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="py-3 px-5 text-right font-semibold text-slate-700">
                        @if($report)
                            {{ number_format($report->total_sales, 0, ',', '.') }}
                        @else
                            <span class="text-slate-300">-</span>
                        @endif
                    </td>
                    <td class="py-3 px-5 text-right text-red-500 font-semibold">
                        @if($report)
                            {{ number_format($report->commission_amount, 0, ',', '.') }}
                            <span class="text-xs text-slate-400">({{ $report->commission_rate }}%)</span>
                        @else
                            <span class="text-slate-300">-</span>
                        @endif
                    </td>
                    <td class="py-3 px-5 text-right font-bold text-emerald-600">
                        @if($report)
                            {{ number_format($report->sales_final, 0, ',', '.') }}
                        @else
                            <span class="text-slate-300">-</span>
                        @endif
                    </td>
                    <td class="py-3 px-5 text-right font-bold text-amber-500">
                        @if($report)
                            {{ number_format($report->tax_amount, 0, ',', '.') }}
                        @else
                            <span class="text-slate-300">-</span>
                        @endif
                    </td>
                    <td class="py-3 px-5">
                        @if($report)
                            <form method="POST" action="{{ route('admin.tax.destroy', $report) }}"
                                onsubmit="return confirm('Hapus data pajak ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 transition" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            {{-- Total --}}
            <tfoot class="bg-slate-50 border-t-2 border-slate-200">
                <tr>
                    <td colspan="3" class="py-3 px-5 font-bold text-slate-800">Total</td>
                    <td class="py-3 px-5 text-right font-bold text-slate-800">{{ number_format($totalSalesYear, 0, ',', '.') }}</td>
                    <td class="py-3 px-5 text-right font-bold text-red-500">{{ number_format($totalCommission, 0, ',', '.') }}</td>
                    <td class="py-3 px-5 text-right font-bold text-emerald-600">{{ number_format($totalSalesFinal, 0, ',', '.') }}</td>
                    <td class="py-3 px-5 text-right font-bold text-amber-500">{{ number_format($totalTax, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- Create Modal --}}
<div id="create-tax-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
    x-data="taxForm()">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 relative">
        <button onclick="document.getElementById('create-tax-modal').classList.add('hidden')"
            class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
        <h2 class="text-lg font-bold text-slate-800 mb-5">Buat Laporan Pajak</h2>

        <form action="{{ route('admin.tax.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Periode --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="label">Dari Tanggal</label>
                    <input type="date" name="period_start" x-model="startDate"
                        @change="fetchSales()" required class="input-field">
                </div>
                <div>
                    <label class="label">Sampai Tanggal</label>
                    <input type="date" name="period_end" x-model="endDate"
                        @change="fetchSales()" required class="input-field">
                </div>
            </div>

            {{-- Total Sales (auto fetch) --}}
            <div>
                <label class="label">Total Penjualan</label>
                <div class="relative">
                    <input type="number" name="total_sales" x-model="totalSales"
                        class="input-field font-bold" readonly
                        placeholder="Pilih tanggal terlebih dahulu">
                    <span x-show="isFetching" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400">
                        Mengambil data...
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-1">
                    <span x-show="totalSales > 0" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(totalSales)"></span>
                </p>
            </div>

            {{-- Potongan Komisi --}}
            <div>
                <label class="label">Potongan Komisi (%)</label>
                <div class="relative">
                    <input type="number" name="commission_rate" x-model="commissionRate"
                        @input="calculate()" min="0" max="100" step="0.1"
                        required class="input-field" placeholder="Contoh: 30" style="padding-right:32px;">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-semibold">%</span>
                </div>
            </div>

            {{-- Preview Kalkulasi --}}
            <div x-show="totalSales > 0 && commissionRate > 0" x-cloak
                class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                <p class="section-label mb-2">Preview Kalkulasi</p>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Sales Mutiara</span>
                    <span class="font-semibold text-slate-700" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(totalSales)"></span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Pot. Komisi (<span x-text="commissionRate"></span>%)</span>
                    <span class="font-semibold text-red-500" x-text="'- Rp ' + new Intl.NumberFormat('id-ID').format(commissionAmount)"></span>
                </div>
                <div class="flex justify-between text-xs border-t border-slate-200 pt-2">
                    <span class="font-bold text-slate-700">Sales Final</span>
                    <span class="font-bold text-emerald-600" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(salesFinal)"></span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="font-bold text-slate-700">Pajak 0.5%</span>
                    <span class="font-bold text-amber-500" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(taxAmount)"></span>
                </div>
            </div>

            {{-- Catatan --}}
            <div>
                <label class="label">Catatan (opsional)</label>
                <input type="text" name="notes" class="input-field" placeholder="Catatan tambahan">
            </div>

            <button type="submit"
                class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition">
                Simpan Laporan Pajak
            </button>
        </form>
    </div>
</div>

<script>
function taxForm() {
    return {
        startDate: '',
        endDate: '',
        totalSales: 0,
        commissionRate: 0,
        commissionAmount: 0,
        salesFinal: 0,
        taxAmount: 0,
        isFetching: false,

        async fetchSales() {
            if (!this.startDate || !this.endDate) return;
            this.isFetching = true;
            try {
                const res = await fetch(`/admin/tax/fetch-sales?start_date=${this.startDate}&end_date=${this.endDate}`);
                const data = await res.json();
                this.totalSales = data.total_sales;
                this.calculate();
            } catch(e) {
                console.error(e);
            } finally {
                this.isFetching = false;
            }
        },

        calculate() {
            this.commissionAmount = this.totalSales * (this.commissionRate / 100);
            this.salesFinal = this.totalSales - this.commissionAmount;
            this.taxAmount = this.salesFinal * 0.005;
        }
    }
}
</script>

@endsection
