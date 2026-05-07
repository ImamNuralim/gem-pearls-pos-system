@extends('layouts.app')
@section('title', 'Detail Mitra')

@section('content')

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
    {{ session('success') }}
</div>
@endif

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.partners.index') }}" class="text-gray-400 hover:text-gray-600 transition">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
    </a>
    <div class="flex-1">
        <h2 class="text-xl font-bold text-gray-800">{{ $partner->name }}</h2>
        <div class="flex items-center gap-2 mt-0.5">
            <code class="text-xs bg-gray-100 px-2 py-0.5 rounded-lg text-gray-500">{{ $partner->code }}</code>
            <span class="text-xs px-2 py-0.5 rounded-full {{ $partner->type === 'travel_agent' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                {{ $partner->type === 'travel_agent' ? 'Travel Agent' : 'Freelance Guide' }}
            </span>
        </div>
    </div>
    <a href="{{ route('admin.partners.edit', $partner) }}"
        class="bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
        Edit
    </a>
</div>

{{-- Stats Komisi --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400">Total Komisi</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($totalCommission, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400">Belum Dibayar</p>
        <p class="text-2xl font-bold text-red-500 mt-1">Rp {{ number_format($unpaidCommission, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400">Sudah Dibayar</p>
        <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($paidCommission, 0, ',', '.') }}</p>
    </div>
</div>

{{-- Riwayat Transaksi & Komisi --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold text-gray-700">Riwayat Transaksi & Komisi</h3>
        {{-- Form update status komisi --}}
        <form method="POST" action="{{ route('admin.partners.commission', $partner) }}" id="commissionForm">
            @csrf
            <input type="hidden" name="status" id="commissionStatus" value="paid">
            <div class="flex gap-2">
                <button type="button" onclick="updateCommission('paid')"
                    class="text-xs px-3 py-1.5 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 transition font-medium">
                    Tandai Dibayar
                </button>
                <button type="button" onclick="updateCommission('unpaid')"
                    class="text-xs px-3 py-1.5 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition font-medium">
                    Tandai Belum Dibayar
                </button>
            </div>
        </form>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="py-3 px-4 text-left">
                    <input type="checkbox" id="checkAll" class="rounded border-gray-300 text-amber-500">
                </th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Invoice</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Tanggal</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Total Transaksi</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Komisi ({{ $partner->commission_rate }}%)</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($transactions as $transaction)
            @php $commission = $partner->commissions->where('transaction_id', $transaction->id)->first() @endphp
            <tr class="hover:bg-gray-50 transition">
                <td class="py-3 px-4">
                    @if($commission)
                    <input type="checkbox" name="commission_ids[]" value="{{ $commission->id }}"
                        form="commissionForm" class="rounded border-gray-300 text-amber-500 commission-check">
                    @endif
                </td>
                <td class="py-3 px-4">
                    <code class="text-xs bg-gray-100 px-2 py-1 rounded-lg text-gray-600">{{ $transaction->invoice_number }}</code>
                </td>
                <td class="py-3 px-4 text-gray-500 text-xs">
                    {{ $transaction->created_at->format('d/m/Y H:i') }}
                </td>
                <td class="py-3 px-4 font-semibold text-gray-700">
                    Rp {{ number_format($transaction->total, 0, ',', '.') }}
                </td>
                <td class="py-3 px-4 font-semibold text-amber-600">
                    @if($commission)
                    Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}
                    @else
                    -
                    @endif
                </td>
                <td class="py-3 px-4">
                    @if($commission)
                    <span class="text-xs px-2 py-1 rounded-full {{ $commission->status === 'paid' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-500' }}">
                        {{ $commission->status === 'paid' ? 'Dibayar' : 'Belum Dibayar' }}
                    </span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-10 text-gray-300 text-sm">Belum ada transaksi</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($transactions->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">{{ $transactions->links() }}</div>
    @endif
</div>

<script>
document.getElementById('checkAll').addEventListener('change', function() {
    document.querySelectorAll('.commission-check').forEach(cb => cb.checked = this.checked);
});

function updateCommission(status) {
    const checked = document.querySelectorAll('.commission-check:checked');
    if (checked.length === 0) {
        alert('Pilih minimal 1 komisi!');
        return;
    }
    document.getElementById('commissionStatus').value = status;
    document.getElementById('commissionForm').submit();
}
</script>

@endsection
