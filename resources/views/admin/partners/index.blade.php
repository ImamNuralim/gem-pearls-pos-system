@extends('layouts.app')
@section('title', 'Manajemen Mitra')

@section('content')

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
    {{ session('success') }}
</div>
@endif

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Manajemen Mitra</h2>
        <p class="text-sm text-gray-400 mt-0.5">Travel Agent & Freelance Guide</p>
    </div>
    <a href="{{ route('admin.partners.create') }}"
        class="bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition shadow-sm flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        Tambah Mitra
    </a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    @php
        $totalMitra = \App\Models\Partner::count();
        $travelAgent = \App\Models\Partner::where('type', 'travel_agent')->count();
        $freelance = \App\Models\Partner::where('type', 'freelance_guide')->count();
        $unpaidTotal = \App\Models\Commission::where('status', 'unpaid')->sum('commission_amount');
    @endphp
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400">Total Mitra</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalMitra }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400">Travel Agent</p>
        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $travelAgent }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400">Freelance Guide</p>
        <p class="text-2xl font-bold text-green-600 mt-1">{{ $freelance }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400">Komisi Belum Dibayar</p>
        <p class="text-2xl font-bold text-red-500 mt-1">Rp {{ number_format($unpaidTotal, 0, ',', '.') }}</p>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
    <form method="GET" class="flex gap-3">
        <div class="flex-1 relative">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama atau kode mitra..."
                class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
        </div>
        <select name="type" class="px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm text-gray-600">
            <option value="">Semua Tipe</option>
            <option value="travel_agent" {{ request('type') === 'travel_agent' ? 'selected' : '' }}>Travel Agent</option>
            <option value="freelance_guide" {{ request('type') === 'freelance_guide' ? 'selected' : '' }}>Freelance Guide</option>
        </select>
        <select name="status" class="px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm text-gray-600">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition">Filter</button>
        @if(request()->hasAny(['search', 'type', 'status']))
        <a href="{{ route('admin.partners.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-500 hover:bg-gray-50 transition">Reset</a>
        @endif
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Mitra</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Kode</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Tipe</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Komisi</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Total Transaksi</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Status</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($partners as $partner)
            <tr class="hover:bg-gray-50 transition">
                <td class="py-3 px-4">
                    <p class="font-semibold text-gray-700">{{ $partner->name }}</p>
                    <p class="text-xs text-gray-400">{{ $partner->phone ?? '-' }}</p>
                </td>
                <td class="py-3 px-4">
                    <code class="text-xs bg-gray-100 px-2 py-1 rounded-lg text-gray-600">{{ $partner->code }}</code>
                </td>
                <td class="py-3 px-4">
                    <span class="text-xs px-2 py-1 rounded-full {{ $partner->type === 'travel_agent' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                        {{ $partner->type === 'travel_agent' ? 'Travel Agent' : 'Freelance Guide' }}
                    </span>
                </td>
                <td class="py-3 px-4 font-semibold text-gray-700">
                    {{ $partner->commission_rate }}%
                </td>
                <td class="py-3 px-4 text-gray-600">
                    {{ $partner->transactions_count }} transaksi
                </td>
                <td class="py-3 px-4">
                    <span class="text-xs px-2 py-1 rounded-full {{ $partner->is_active ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' }}">
                        {{ $partner->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="py-3 px-4">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.partners.show', $partner) }}"
                            class="text-xs px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition font-medium">
                            Detail
                        </a>
                        <a href="{{ route('admin.partners.edit', $partner) }}"
                            class="text-xs px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition font-medium">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}"
                            onsubmit="return confirm('Yakin hapus mitra ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs px-3 py-1.5 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition font-medium">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-12 text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mx-auto mb-2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /></svg>
                    <p class="text-sm">Belum ada mitra</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($partners->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">{{ $partners->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
