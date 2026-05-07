@extends('layouts.app')

@section('content')
    <div class="p-6">

        <h1 class="text-2xl font-bold mb-4">Laporan Penjualan</h1>

        <!-- Filter Tanggal -->
        <form method="GET" action="{{ route('admin.reports.sales') }}" class="mb-6 flex gap-4">
            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="border rounded px-3 py-2">

            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="border rounded px-3 py-2">

            <button type="submit" class="bg-amber-600 text-white px-4 py-2 rounded hover:bg-amber-700">
                Filter
            </button>
        </form>

        <!-- Summary -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl shadow">
                <p class="text-gray-500">Total Revenue</p>
                <p class="text-xl font-bold">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </p>
            </div>

            <div class="bg-white p-4 rounded-xl shadow">
                <p class="text-gray-500">Total Transaksi</p>
                <p class="text-xl font-bold">
                    {{ $totalTransactions }}
                </p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow overflow-x-auto">
            <div class="bg-white rounded-xl shadow overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-left">Kode Produk</th>
                            <th class="p-3 text-left">Harga</th>
                            <th class="p-3 text-left">Qty</th>
                            <th class="p-3 text-left">Kasir</th>
                            <th class="p-3 text-left">Customer</th>
                            <th class="p-3 text-left">Mitra</th>
                            <th class="p-3 text-left">Tanggal</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($transactions as $trx)
                            @foreach ($trx->items as $item)
                                <tr class="border-t">

                                    <!-- KODE PRODUK -->
                                    <td class="p-3">
                                        {{ $item->sku }}
                                    </td>

                                    <!-- HARGA -->
                                    <td class="p-3">
    @php
        $finalAfterPoint = $item->final_price;

        if ($trx->points_discount > 0) {
            $finalAfterPoint = $item->final_price - ($trx->points_discount / $trx->items->count());
        }
    @endphp

    @if ($item->original_price != $item->final_price || $trx->points_discount > 0)

        <!-- harga asli -->
        <div class="text-gray-400 line-through text-xs">
            Rp {{ number_format($item->original_price, 0, ',', '.') }}
        </div>

        <!-- harga akhir -->
        <div class="font-semibold flex items-center gap-1">
            <span class="text-red-600">
                Rp {{ number_format($finalAfterPoint, 0, ',', '.') }}
            </span>

            @if ($trx->points_discount > 0)
                <span class="bg-green-100 text-green-600 text-[10px] px-1 rounded">
                    - POIN
                </span>
            @elseif ($item->original_price != $item->final_price)
                <span class="bg-red-100 text-red-600 text-[10px] px-1 rounded">
                    NEGO
                </span>
            @endif
        </div>

    @else
        <div>
            Rp {{ number_format($item->final_price, 0, ',', '.') }}
        </div>
    @endif
</td>

                                    <!-- QTY -->
                                    <td class="p-3">
                                        {{ $item->quantity }}
                                    </td>

                                    <!-- KASIR -->
                                    <td class="p-3">
                                        {{ $trx->user->name ?? '-' }}
                                    </td>

                                    <!-- CUSTOMER -->
                                    <td class="p-3">
                                        @if ($trx->member)
                                            <div class="font-semibold text-amber-600">
                                                {{ $trx->member->name }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                Member
                                            </div>
                                        @else
                                            {{ $trx->customer_name ?? '-' }}
                                        @endif
                                    </td>

                                    <!-- MITRA -->
                                    <td class="p-3">
                                        {{ $trx->partner->name ?? '-' }}
                                    </td>

                                    <!-- TANGGAL -->
                                    <td class="p-3">
                                        {{ $trx->created_at->format('d/m/Y H:i') }}
                                    </td>

                                    <!-- ACTION -->
                                    <td class="p-3">
                                        @if ($trx->deleted_at)
                                            <span class="text-red-500 text-xs">Deleted</span>
                                        @else
                                            <span class="text-green-500 text-xs">Active</span>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="9" class="p-3 text-center text-gray-500">
                                    Tidak ada data
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
