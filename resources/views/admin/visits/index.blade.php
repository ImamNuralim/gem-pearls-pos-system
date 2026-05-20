@extends('layouts.app')
@section('title', 'Kunjungan')
@section('subtitle', 'Admin — Daftar Kunjungan')

@section('content')
<div class="max-w-7xl mx-auto space-y-5" x-data="{ tab: 'partner' }">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Kunjungan</h2>
            <p class="text-sm text-gray-400 mt-1">Data kunjungan dari security</p>
        </div>
        <span class="px-4 py-2 rounded-xl bg-blue-100 text-blue-700 text-sm font-bold">
            {{ $visits->count() }} Total Visit
        </span>

    </div>

    {{-- Tabs --}}
    <div class="flex gap-2">
        <button @click="tab = 'partner'"
            :class="tab === 'partner' ? 'bg-blue-600 text-white' : 'bg-white text-gray-500 border border-gray-200 hover:border-blue-200'"
            class="px-5 py-2 rounded-xl text-sm font-semibold transition">
            Partner / Travel Agent
            <span class="ml-1 px-2 py-0.5 rounded-full text-xs"
                :class="tab === 'partner' ? 'bg-white/20' : 'bg-gray-100'">
                {{ $partnerVisits->count() }}
            </span>
        </button>
        <button @click="tab = 'walkin'"
            :class="tab === 'walkin' ? 'bg-blue-600 text-white' : 'bg-white text-gray-500 border border-gray-200 hover:border-blue-200'"
            class="px-5 py-2 rounded-xl text-sm font-semibold transition">
            Walk-in
            <span class="ml-1 px-2 py-0.5 rounded-full text-xs"
                :class="tab === 'walkin' ? 'bg-white/20' : 'bg-gray-100'">
                {{ $walkinVisits->count() }}
            </span>
        </button>
        <form method="GET" class="flex gap-2">
    <input type="text" name="search" value="{{ $search ?? '' }}"
        placeholder="Cari kode visit, partner, guide..."
        class="px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white w-64">
    <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
        Cari
    </button>
</form>
    </div>

    {{-- TAB: Partner --}}
    <div x-show="tab === 'partner'" x-transition>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-gray-400">Visit Code</th>
                            <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-gray-400">Partner</th>
                            <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-gray-400">Guide</th>
                            <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-gray-400">Rombongan</th>
                            <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-gray-400">Kendaraan</th>
                            <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-gray-400">Sticker</th>
                            <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-gray-400">Tanggal</th>
                            <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-gray-400">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($partnerVisits as $visit)
                        <tr class="border-t border-gray-100 hover:bg-blue-50/30 transition">
                            <td class="px-5 py-3 font-bold text-blue-600 text-xs">{{ $visit->visit_code }}</td>
                            <td class="px-5 py-3">
                                <div class="font-semibold text-gray-800">{{ $visit->partner->name ?? '-' }}</div>
                                <div class="text-xs text-gray-400">{{ $visit->partner->code ?? '-' }}</div>
                            </td>
                            <td class="px-5 py-3">
                                @if($visit->guides->isEmpty())
                                    <span class="text-gray-300 text-xs">—</span>
                                @else
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($visit->guides as $guide)
                                            <span class="px-2 py-0.5 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-semibold">{{ $guide->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-gray-600 text-xs">{{ $visit->group_description ?? '-' }}</td>
                            <td class="px-5 py-3">
                                @foreach($visit->vehicles as $vehicle)
                                    <div class="text-xs"><span class="font-semibold">{{ $vehicle->plate_number }}</span> <span class="text-gray-400">· {{ $vehicle->vehicle_type }}</span></div>
                                @endforeach
                            </td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-1 rounded-lg bg-amber-100 text-amber-700 text-xs font-bold">{{ $visit->sticker_number ?? '-' }}</span>
                            </td>
                            <td class="px-5 py-3 text-xs text-gray-600">{{ $visit->visit_date?->format('d M Y') }}</td>
                            <td class="px-5 py-3">
                                @if($visit->status === 'pending')
                                    <span class="px-2 py-1 rounded-lg bg-gray-100 text-gray-500 text-xs font-bold">Pending</span>
                                @elseif($visit->status === 'shopping')
                                    <span class="px-2 py-1 rounded-lg bg-blue-100 text-blue-600 text-xs font-bold">Shopping</span>
                                @else
                                    <span class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-600 text-xs font-bold">Completed</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-14 text-center text-gray-300">
                                <div class="text-4xl mb-2">🚍</div>
                                <p class="text-sm">Belum ada kunjungan partner</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TAB: Walk-in --}}
    <div x-show="tab === 'walkin'" x-transition>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-gray-400">Visit Code</th>
                            <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-gray-400">Kendaraan</th>
                            <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-gray-400">Plat</th>
                            <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-gray-400">Keterangan</th>
                            <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-gray-400">Tanggal</th>
                            <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-gray-400">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($walkinVisits as $visit)
                        <tr class="border-t border-gray-100 hover:bg-blue-50/30 transition">
                            <td class="px-5 py-3 font-bold text-blue-600 text-xs">{{ $visit->visit_code }}</td>
                            <td class="px-5 py-3 text-xs font-semibold text-gray-700">{{ $visit->vehicle_notes ?? '—' }}</td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-bold">{{ $visit->vehicle_description ?? '—' }}</span>
                            </td>
                            <td class="px-5 py-3 text-xs text-gray-600">{{ $visit->group_description ?? '—' }}</td>
                            <td class="px-5 py-3 text-xs text-gray-600">{{ $visit->visit_date?->format('d M Y') }}</td>
                            <td class="px-5 py-3">
                                @if($visit->status === 'pending')
                                    <span class="px-2 py-1 rounded-lg bg-gray-100 text-gray-500 text-xs font-bold">Pending</span>
                                @elseif($visit->status === 'shopping')
                                    <span class="px-2 py-1 rounded-lg bg-blue-100 text-blue-600 text-xs font-bold">Shopping</span>
                                @else
                                    <span class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-600 text-xs font-bold">Completed</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-14 text-center text-gray-300">
                                <div class="text-4xl mb-2">🚶</div>
                                <p class="text-sm">Belum ada data walk-in</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
