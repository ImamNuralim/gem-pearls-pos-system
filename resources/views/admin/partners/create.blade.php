@extends('layouts.app')
@section('title', 'Tambah Mitra')

@section('content')
<style>
    .card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
    .input-field { width:100%; padding:9px 14px; border-radius:10px; border:1.5px solid #e2e8f0; font-size:13px; outline:none; transition:border-color 0.2s, box-shadow 0.2s; background:#f8fafc; color:#1e293b; font-family:'Poppins',sans-serif; }
    .input-field:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.1); background:#fff; }
    .label { display:block; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; color:#64748b; margin-bottom:6px; }
    .section-title { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:#94a3b8; padding-bottom:12px; border-bottom:1px solid #f1f5f9; margin-bottom:16px; }
</style>

<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-5">
        <a href="{{ route('admin.partners.index') }}"
            class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-slate-800">Tambah Mitra Baru</h2>
            <p class="text-sm text-slate-400">Kode mitra akan digenerate otomatis</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.partners.store') }}" class="space-y-4">
        @csrf

        <div class="card p-5" x-data="{ type: '{{ old('type', '') }}' }">
            <p class="section-title">Informasi Mitra</p>
            <div class="space-y-4">

                {{-- Tipe --}}
                <div>
                    <label class="label">Tipe Mitra <span class="text-red-400">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition"
                            :class="type === 'travel_agent' ? 'border-blue-400 bg-blue-50' : 'border-slate-200 hover:border-blue-200'">
                            <input type="radio" name="type" value="travel_agent" x-model="type" class="hidden">
                            <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-700">Travel Agent</p>
                                <p class="text-xs text-slate-400">Agen perjalanan</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition"
                            :class="type === 'freelance_guide' ? 'border-blue-400 bg-blue-50' : 'border-slate-200 hover:border-blue-200'">
                            <input type="radio" name="type" value="freelance_guide" x-model="type" class="hidden">
                            <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#059669" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c-.317-.159.69-.159 1.006 0l4.994 2.497c.317.159.69.159 1.006 0Z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-700">Freelance Guide</p>
                                <p class="text-xs text-slate-400">Guide perorangan</p>
                            </div>
                        </label>
                    </div>
                    @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label">Nama <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="input-field" placeholder="Nama travel agent / guide">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">No. HP</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="input-field" placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label class="label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="input-field" placeholder="email@example.com">
                    </div>
                </div>

                <div>
                    <label class="label">Persentase Komisi Default (%) <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <input type="number" name="commission_rate" value="{{ old('commission_rate', 0) }}"
                            step="0.1" min="0" max="100" required class="input-field" style="padding-right:32px;" placeholder="0">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-semibold">%</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Bisa diubah per komisi saat buat komisi baru</p>
                </div>

                <div>
                    <label class="label">Catatan</label>
                    <textarea name="notes" rows="3" class="input-field" style="resize:none;"
                        placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                </div>

            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.partners.index') }}"
                class="flex-1 text-center py-3 rounded-xl border border-slate-200 text-sm text-slate-500 hover:bg-slate-50 transition font-medium">
                Batal
            </a>
            <button type="submit"
                class="flex-1 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition shadow-sm flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                Simpan Mitra
            </button>
        </div>
    </form>
</div>
@endsection
