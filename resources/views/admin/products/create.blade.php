@extends('layouts.app')
@section('title', 'Tambah Produk')

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
        <a href="{{ route('admin.products.index') }}"
            class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-slate-800">Tambah Produk Baru</h2>
            <p class="text-sm text-slate-400">SKU akan digenerate otomatis</p>
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

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        {{-- Info Produk --}}
        <div class="card p-5" x-data="{ category: '{{ old('category', '') }}' }">
            <p class="section-title">Informasi Produk</p>

            <div class="space-y-4">

                {{-- Nama --}}
                <div>
                    <label class="label">Nama Produk <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="input-field @error('name') border-red-300 @enderror"
                        placeholder="Masukkan nama produk">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="label">Kategori <span class="text-red-400">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition"
                            :class="category === 'perhiasan' ? 'border-blue-400 bg-blue-50' : 'border-slate-200 hover:border-blue-200'">
                            <input type="radio" name="category" value="perhiasan" x-model="category" class="hidden">
                            <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#d97706" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-700">Perhiasan</p>
                                <p class="text-xs text-slate-400">Anting, gelang, kalung, dll</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition"
                            :class="category === 'oleh-oleh' ? 'border-blue-400 bg-blue-50' : 'border-slate-200 hover:border-blue-200'">
                            <input type="radio" name="category" value="oleh-oleh" x-model="category" class="hidden">
                            <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-700">Oleh-oleh</p>
                                <p class="text-xs text-slate-400">Produk oleh-oleh Lombok</p>
                            </div>
                        </label>
                    </div>

                    {{-- Field khusus perhiasan --}}
                    <div x-show="category === 'perhiasan'" x-transition class="mt-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Jenis Perhiasan <span class="text-red-400">*</span></label>
                            <select name="jewelry_type" class="input-field">
                                <option value="">Pilih Jenis</option>
                                @foreach($jewelryTypes as $code => $label)
                                    <option value="{{ $code }}" {{ old('jewelry_type') === $code ? 'selected' : '' }}>
                                        {{ $code }} — {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="label">Price Tier <span class="text-red-400">*</span></label>
                            <select name="price_tier" class="input-field">
                                <option value="">Pilih Tier</option>
                                @foreach($priceTiers as $code => $range)
                                    <option value="{{ $code }}" {{ old('price_tier') === $code ? 'selected' : '' }}>
                                        Tier {{ $code }} — {{ $range }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Harga & Stok --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Harga (IDR) <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-semibold">Rp</span>
                            <input type="number" name="price" value="{{ old('price') }}" required min="0"
                                class="input-field @error('price') border-red-300 @enderror" style="padding-left:36px;" placeholder="0">
                        </div>
                        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label">Stok Awal <span class="text-red-400">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', 1) }}" required min="0" class="input-field">
                        @error('stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Threshold --}}
                <div>
                    <label class="label">Batas Notifikasi Stok Menipis</label>
                    <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', 3) }}" min="1" class="input-field">
                    <p class="text-xs text-slate-400 mt-1">Sistem akan tandai stok menipis jika stok ≤ nilai ini</p>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="label">Deskripsi</label>
                    <textarea name="description" rows="3" class="input-field" style="resize:none;"
                        placeholder="Deskripsi produk (opsional)">{{ old('description') }}</textarea>
                </div>

            </div>
        </div>

        {{-- Foto --}}
        <div class="card p-5">
            <p class="section-title">Foto Produk</p>
            <input type="file" name="photos[]" multiple accept="image/*"
                class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer">
            <p class="text-xs text-slate-400 mt-2">Foto pertama akan jadi foto utama. Format: JPG, PNG. Maks 2MB per foto.</p>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3">
            <a href="{{ route('admin.products.index') }}"
                class="flex-1 text-center py-3 rounded-xl border border-slate-200 text-sm text-slate-500 hover:bg-slate-50 transition font-medium">
                Batal
            </a>
            <button type="submit"
                class="flex-1 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition shadow-sm flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                Simpan Produk
            </button>
        </div>

    </form>
</div>
@endsection
