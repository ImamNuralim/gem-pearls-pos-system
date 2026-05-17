@extends('layouts.app')
@section('title', 'Manajemen User')

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

@if(session('error'))
<div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-semibold flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
    </svg>
    {{ session('error') }}
</div>
@endif

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Manajemen User</h2>
        <p class="text-sm text-slate-400 mt-0.5">Kelola akun dan hak akses karyawan</p>
    </div>
    <button onclick="document.getElementById('create-user-modal').classList.remove('hidden')"
        class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Tambah User
    </button>
</div>

{{-- Stats --}}
<div class="grid grid-cols-4 gap-4 mb-5">
    @foreach(['owner' => ['label' => 'Owner', 'color' => 'purple'], 'admin' => ['label' => 'Admin', 'color' => 'blue'], 'kasir' => ['label' => 'Kasir', 'color' => 'amber'], 'security' => ['label' => 'Security', 'color' => 'emerald']] as $role => $info)
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">{{ $info['label'] }}</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $users->filter(fn($u) => $u->hasRole($role))->count() }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-{{ $info['color'] }}-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-{{ $info['color'] }}-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
            </svg>
        </div>
    </div>
    @endforeach
</div>

{{-- Table --}}
<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-100">
            <tr>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">User</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Email</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Role</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Dibuat</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($users as $user)
            <tr class="hover:bg-blue-50/20 transition">
                <td class="py-3 px-5">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm flex-shrink-0">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="font-semibold text-slate-700">{{ $user->name }}</div>
                    </div>
                </td>
                <td class="py-3 px-5 text-xs text-slate-500">{{ $user->email }}</td>
                <td class="py-3 px-5">
                    @php $role = $user->roles->first(); @endphp
                    @if($role)
                        <span class="px-2 py-1 rounded-lg text-xs font-bold
                            {{ $role->name === 'owner' ? 'bg-purple-100 text-purple-600' : '' }}
                            {{ $role->name === 'admin' ? 'bg-blue-100 text-blue-600' : '' }}
                            {{ $role->name === 'kasir' ? 'bg-amber-100 text-amber-600' : '' }}
                            {{ $role->name === 'security' ? 'bg-emerald-100 text-emerald-600' : '' }}">
                            {{ ucfirst($role->name) }}
                        </span>
                    @else
                        <span class="text-slate-300 text-xs">—</span>
                    @endif
                </td>
                <td class="py-3 px-5 text-xs text-slate-400">{{ $user->created_at->format('d/m/y') }}</td>
                <td class="py-3 px-5">
                    <div class="flex items-center gap-1.5">
                        {{-- Ganti Role --}}
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition" title="Ganti Role">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                </svg>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-cloak
                                class="absolute right-0 z-20 mt-1 bg-white border border-slate-100 rounded-xl shadow-lg overflow-hidden w-36">
                                @foreach($roles as $r)
                                <form method="POST" action="{{ route('admin.users.role', $user) }}">
                                    @csrf
                                    <input type="hidden" name="role" value="{{ $r->name }}">
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2.5 text-xs font-semibold hover:bg-blue-50 transition
                                        {{ $user->hasRole($r->name) ? 'text-blue-600 bg-blue-50' : 'text-slate-600' }}">
                                        {{ ucfirst($r->name) }}
                                        @if($user->hasRole($r->name)) ✓ @endif
                                    </button>
                                </form>
                                @endforeach
                            </div>
                        </div>
                        {{-- Edit Password --}}
<button onclick="document.getElementById('password-modal-{{ $user->id }}').classList.remove('hidden')"
    class="p-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition" title="Ganti Password">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
    </svg>
</button>
                        {{-- Hapus --}}
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                            onsubmit="return confirm('Yakin hapus user ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 transition" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            {{-- Password Modal --}}
            <div id="password-modal-{{ $user->id }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl w-full max-w-sm p-6 relative">
                    <button onclick="document.getElementById('password-modal-{{ $user->id }}').classList.add('hidden')"
                        class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <h2 class="text-lg font-bold text-slate-800 mb-1">Ganti Password</h2>
                    <p class="text-sm text-slate-400 mb-5">{{ $user->name }}</p>
                    <form action="{{ route('admin.users.password', $user) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="label">Password Baru</label>
                            <input type="password" name="password" required class="input-field" placeholder="Min. 8 karakter">
                        </div>
                        <div>
                            <label class="label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required class="input-field" placeholder="Ulangi password">
                        </div>
                        <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition">
                            Simpan Password
                        </button>
                    </form>
                </div>
            </div>


            @empty
            <tr>
                <td colspan="5" class="py-14 text-center text-slate-300">
                    <p class="text-sm">Belum ada user</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Password Modal --}}
<div id="password-modal-{{ $user->id }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-sm p-6 relative">
        <button onclick="document.getElementById('password-modal-{{ $user->id }}').classList.add('hidden')"
            class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
        <h2 class="text-lg font-bold text-slate-800 mb-1">Ganti Password</h2>
        <p class="text-sm text-slate-400 mb-5">{{ $user->name }}</p>
        <form action="{{ route('admin.users.password', $user) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="label">Password Baru</label>
                <input type="password" name="password" required class="input-field" placeholder="Min. 8 karakter">
            </div>
            <div>
                <label class="label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="input-field" placeholder="Ulangi password">
            </div>
            <button type="submit"
                class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition">
                Simpan Password
            </button>
        </form>
    </div>
</div>

{{-- Create Modal --}}
<div id="create-user-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 relative">
        <button onclick="document.getElementById('create-user-modal').classList.add('hidden')"
            class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
        <h2 class="text-lg font-bold text-slate-800 mb-5">Tambah User Baru</h2>
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="label">Nama</label>
                <input type="text" name="name" required class="input-field" placeholder="Nama lengkap">
            </div>
            <div>
                <label class="label">Email</label>
                <input type="email" name="email" required class="input-field" placeholder="email@example.com">
            </div>
            <div>
                <label class="label">Password</label>
                <input type="password" name="password" required class="input-field" placeholder="Min. 8 karakter">
            </div>
            <div>
                <label class="label">Role</label>
                <select name="role" required class="input-field">
                    <option value="">Pilih Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition">
                Tambah User
            </button>
        </form>
    </div>
</div>

@endsection
