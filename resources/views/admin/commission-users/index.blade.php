@extends('layouts.app')
@section('title', 'Akun Komisi')

@section('content')
<style>
    .card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
    .input-field { width:100%; padding:9px 14px; border-radius:10px; border:1.5px solid #e2e8f0; font-size:13px; outline:none; transition:border-color 0.2s; background:#f8fafc; color:#1e293b; font-family:'Poppins',sans-serif; }
    .input-field:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.1); background:#fff; }
    .label { display:block; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; color:#64748b; margin-bottom:6px; }
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
        <h2 class="text-xl font-bold text-slate-800">Akun Komisi</h2>
        <p class="text-sm text-slate-400 mt-0.5">Kelola akses halaman komisi</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="/komisi" target="_blank"
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
            </svg>
            Buka Halaman Komisi
        </a>
        <button onclick="document.getElementById('create-commission-user-modal').classList.remove('hidden')"
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Tambah Akun
        </button>
    </div>
</div>

{{-- URL Info --}}
<div class="card p-4 mb-5 flex items-center gap-3 bg-blue-50 border-blue-100">
    <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/>
        </svg>
    </div>
    <div>
        <p class="text-xs font-bold text-blue-700">URL Halaman Komisi</p>
        <p class="text-sm text-blue-600 font-mono">{{ url('/komisi') }}</p>
    </div>
</div>

{{-- Table --}}
<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-100">
            <tr>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Nama</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Email</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Status</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Dibuat</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($commissionUsers as $cu)
            <tr class="hover:bg-blue-50/20 transition">
                <td class="py-3 px-5">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm flex-shrink-0">
                            {{ substr($cu->name, 0, 1) }}
                        </div>
                        <span class="font-semibold text-slate-700">{{ $cu->name }}</span>
                    </div>
                </td>
                <td class="py-3 px-5 text-xs text-slate-500">{{ $cu->email }}</td>
                <td class="py-3 px-5">
                    <span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $cu->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                        {{ $cu->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="py-3 px-5 text-xs text-slate-400">{{ $cu->created_at->format('d/m/y') }}</td>
                <td class="py-3 px-5">
                    <div class="flex items-center gap-1.5">
                        <button onclick="document.getElementById('edit-cu-{{ $cu->id }}').classList.remove('hidden')"
                            class="p-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                            </svg>
                        </button>
                        <form method="POST" action="{{ route('admin.commission-users.destroy', $cu) }}"
                            onsubmit="return confirm('Yakin hapus akun ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>

            {{-- Edit Modal --}}
            <div id="edit-cu-{{ $cu->id }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl w-full max-w-sm p-6 relative">
                    <button onclick="document.getElementById('edit-cu-{{ $cu->id }}').classList.add('hidden')"
                        class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <h2 class="text-lg font-bold text-slate-800 mb-5">Edit Akun Komisi</h2>
                    <form action="{{ route('admin.commission-users.update', $cu) }}" method="POST" class="space-y-4">
                        @csrf @method('PUT')
                        <div>
                            <label class="label">Nama</label>
                            <input type="text" name="name" value="{{ $cu->name }}" required class="input-field">
                        </div>
                        <div>
                            <label class="label">Email</label>
                            <input type="email" name="email" value="{{ $cu->email }}" required class="input-field">
                        </div>
                        <div>
                            <label class="label">Password Baru (kosongkan jika tidak diubah)</label>
                            <input type="password" name="password" class="input-field" placeholder="••••••••">
                        </div>
                        <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>

            @empty
            <tr>
                <td colspan="5" class="py-14 text-center text-slate-300">
                    <p class="text-sm">Belum ada akun komisi</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Create Modal --}}
<div id="create-commission-user-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-sm p-6 relative">
        <button onclick="document.getElementById('create-commission-user-modal').classList.add('hidden')"
            class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
        <h2 class="text-lg font-bold text-slate-800 mb-5">Tambah Akun Komisi</h2>
        <form action="{{ route('admin.commission-users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="label">Nama</label>
                <input type="text" name="name" required class="input-field" placeholder="Nama akun">
            </div>
            <div>
                <label class="label">Email</label>
                <input type="email" name="email" required class="input-field" placeholder="email@example.com">
            </div>
            <div>
                <label class="label">Password</label>
                <input type="password" name="password" required class="input-field" placeholder="Min. 6 karakter">
            </div>
            <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition">
                Tambah Akun
            </button>
        </form>
    </div>
</div>

@endsection
