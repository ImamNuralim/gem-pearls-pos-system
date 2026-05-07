@extends('layouts.app')
@section('title', 'Manajemen Member')

@section('content')

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">✅ {{ session('success') }}</div>
@endif

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Manajemen Member</h2>
        <p class="text-sm text-gray-400 mt-0.5">Data member & sistem poin</p>
    </div>
    <a href="{{ route('admin.members.create') }}"
        class="bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition shadow-sm flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        Tambah Member
    </a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    @php
        $totalMember = \App\Models\Member::count();
        $totalPoin = \App\Models\Member::sum('points_balance');
        $aktivMember = \App\Models\Member::where('is_active', true)->count();
    @endphp
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400">Total Member</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalMember }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400">Member Aktif</p>
        <p class="text-2xl font-bold text-green-600 mt-1">{{ $aktivMember }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400">Total Poin Beredar</p>
        <p class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($totalPoin, 0, ',', '.') }}</p>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
    <form method="GET" class="flex gap-3">
        <div class="flex-1 relative">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama, HP, atau email..."
                class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
        </div>
        <select name="status" class="px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm text-gray-600">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition">Filter</button>
        @if(request()->hasAny(['search', 'status']))
        <a href="{{ route('admin.members.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-500 hover:bg-gray-50 transition">Reset</a>
        @endif
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Member</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">No. HP</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Poin</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Terdaftar</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Status</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($members as $member)
            <tr class="hover:bg-gray-50 transition">
                <td class="py-3 px-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-700 font-bold text-sm">
                            {{ substr($member->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-700">{{ $member->name }}</p>
                            <p class="text-xs text-gray-400">{{ $member->email ?? '-' }}</p>
                        </div>
                    </div>
                </td>
                <td class="py-3 px-4 text-gray-600">{{ $member->phone }}</td>
                <td class="py-3 px-4">
                    <span class="font-bold text-amber-600">{{ number_format($member->points_balance, 0, ',', '.') }}</span>
                    <span class="text-xs text-gray-400"> poin</span>
                    <p class="text-xs text-gray-400">≈ Rp {{ number_format($member->points_balance * 100, 0, ',', '.') }}</p>
                </td>
                <td class="py-3 px-4 text-gray-500 text-xs">{{ $member->registered_at->format('d/m/Y') }}</td>
                <td class="py-3 px-4">
                    <span class="text-xs px-2 py-1 rounded-full {{ $member->is_active ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' }}">
                        {{ $member->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="py-3 px-4">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.members.show', $member) }}"
                            class="text-xs px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition font-medium">
                            Detail
                        </a>
                        <a href="{{ route('admin.members.edit', $member) }}"
                            class="text-xs px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition font-medium">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.members.destroy', $member) }}"
                            onsubmit="return confirm('Yakin hapus member ini?')">
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
                <td colspan="6" class="text-center py-12 text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mx-auto mb-2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                    <p class="text-sm">Belum ada member</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($members->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">{{ $members->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
