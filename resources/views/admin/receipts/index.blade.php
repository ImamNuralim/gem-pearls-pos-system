@extends('layouts.app')
@section('title', 'Data Struk')

@section('content')
    <style>
        .card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        .section-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #94a3b8;
        }
    </style>

    @if (session('success'))
        <div
            class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-semibold flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Data Struk</h2>
            <p class="text-sm text-slate-400 mt-0.5">Riwayat struk transaksi</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    placeholder="Cari invoice, customer, sales..."
                    class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white w-64">
                <button type="submit"
                    class="px-3 py-1.5 rounded-xl bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700 transition">
                    Cari
                </button>
            </form>
            <span class="px-3 py-1.5 rounded-xl bg-blue-100 text-blue-700 text-xs font-bold">
                {{ $receipts->total() }} Struk
            </span>
            <span class="px-3 py-1.5 rounded-xl bg-emerald-100 text-emerald-700 text-xs font-bold">
                {{ $receipts->where('is_printed', true)->count() }} Sudah Cetak
            </span>
            <span class="px-3 py-1.5 rounded-xl bg-amber-100 text-amber-700 text-xs font-bold">
                {{ $receipts->where('is_printed', false)->count() }} Belum Cetak
            </span>
        </div>
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Kode Struk
                        </th>
                        <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Tanggal
                        </th>
                        <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Customer
                        </th>
                        <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Total</th>
                        <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Kasir</th>
                        <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Status
                            Cetak</th>
                        <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($receipts as $trx)
                        <tr class="hover:bg-blue-50/20 transition">
                            <td class="py-3 px-5">
                                <code
                                    class="text-xs bg-slate-100 px-2 py-1 rounded-lg text-blue-600 font-mono font-bold">{{ $trx->invoice_number }}</code>
                            </td>
                            <td class="py-3 px-5 text-xs text-slate-500">
                                {{ $trx->created_at->format('d/m/y H:i') }}
                            </td>
                            <td class="py-3 px-5">
                                <div class="text-xs font-semibold text-slate-700">
                                    @if ($trx->member)
                                        {{ $trx->member->name }}
                                        <span
                                            class="px-1.5 py-0.5 rounded bg-amber-100 text-amber-600 text-xs ml-1">Member</span>
                                    @elseif($trx->customer_name)
                                        {{ $trx->customer_name }}
                                    @else
                                        {{ ucfirst(str_replace('_', ' ', $trx->customer_type)) }}
                                    @endif
                                </div>
                                @if ($trx->partner)
                                    <div class="text-xs text-slate-400">{{ $trx->partner->name }}</div>
                                @endif
                                @if ($trx->customer_phone)
                                    <div class="text-xs text-slate-400">{{ $trx->customer_phone }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-5 font-bold text-slate-800">
                                Rp {{ number_format($trx->total, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-5 text-xs text-slate-500">
                                {{ $trx->salesStaff->name ?? ($trx->user->name ?? '-') }}
                            </td>
                            <td class="py-3 px-5">
                                @if ($trx->is_printed)
                                    <span
                                        class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-600 text-xs font-bold flex items-center gap-1 w-fit">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="w-3 h-3">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        Done
                                    </span>
                                @else
                                    <span
                                        class="px-2 py-1 rounded-lg bg-amber-100 text-amber-600 text-xs font-bold flex items-center gap-1 w-fit">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="w-3 h-3">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-5">
                                <div class="flex items-center gap-1.5">
                                    {{-- Lihat Struk --}}
                                    <a href="{{ route('kasir.receipt', $trx) }}" target="_blank"
                                        class="p-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition"
                                        title="Lihat Struk">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>
                                    {{-- Print --}}
                                    {{-- Print --}}
                                    <form method="POST" action="{{ route('admin.receipts.print', $trx) }}">
                                        @csrf
                                        <button type="submit"
                                            onclick="window.open('{{ route('kasir.receipt', $trx) }}?print=1', '_blank'); return true;"
                                            class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition"
                                            title="Print">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                                            </svg>
                                        </button>
                                    </form>
                                    {{-- Hapus --}}
                                    <form method="POST" action="{{ route('admin.receipts.destroy', $trx) }}"
                                        onsubmit="return confirm('Yakin hapus struk ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 transition"
                                            title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-14 text-center">
                                <div class="flex flex-col items-center gap-2 text-slate-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    <p class="text-sm">Belum ada struk</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($receipts->hasPages())
            <div class="px-5 py-3 border-t border-slate-100">
                {{ $receipts->links() }}
            </div>
        @endif
    </div>

@endsection
