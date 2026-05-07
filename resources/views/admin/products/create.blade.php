@extends('layouts.app')
@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-gray-400 hover:text-gray-600 transition">←</a>
        <div>
            <h2 class="text-xl font-bold text-gray-800">Tambah Produk Baru</h2>
            <p class="text-sm text-gray-400">SKU akan digenerate otomatis</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Informasi Produk</h3>

            {{-- Nama --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Nama Produk <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm @error('name') border-red-300 @enderror"
                    placeholder="Masukkan nama produk">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Kategori --}}
            <div class="mb-4" x-data="{ category: '{{ old('category', '') }}' }">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Kategori <span class="text-red-400">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition"
                        :class="category === 'perhiasan' ? 'border-amber-400 bg-amber-50' : 'border-gray-200 hover:border-amber-200'">
                        <input type="radio" name="category" value="perhiasan" x-model="category" class="hidden">
                        <span class="text-2xl">💍</span>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Perhiasan</p>
                            <p class="text-xs text-gray-400">Anting, gelang, kalung, dll</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition"
                        :class="category === 'oleh-oleh' ? 'border-amber-400 bg-amber-50' : 'border-gray-200 hover:border-amber-200'">
                        <input type="radio" name="category" value="oleh-oleh" x-model="category" class="hidden">
                        <span class="text-2xl">🎁</span>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Oleh-oleh</p>
                            <p class="text-xs text-gray-400">Produk oleh-oleh Lombok</p>
                        </div>
                    </label>
                </div>

                {{-- Field khusus perhiasan --}}
                <div x-show="category === 'perhiasan'" x-transition class="mt-4 grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1.5">Jenis Perhiasan <span class="text-red-400">*</span></label>
                        <select name="jewelry_type"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($jewelryTypes as $code => $label)
                            <option value="{{ $code }}" {{ old('jewelry_type') === $code ? 'selected' : '' }}>
                                {{ $code }} — {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1.5">Price Tier <span class="text-red-400">*</span></label>
                        <select name="price_tier"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                            <option value="">-- Pilih Tier --</option>
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
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Harga (IDR) <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                        <input type="number" name="price" value="{{ old('price') }}" required min="0"
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm"
                            placeholder="0">
                    </div>
                    @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Stok Awal <span class="text-red-400">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', 1) }}" required min="0"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                    @error('stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Threshold --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Batas Notifikasi Stok Menipis</label>
                <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', 3) }}" min="1"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                <p class="text-xs text-gray-400 mt-1">Sistem akan tandai stok menipis jika stok ≤ nilai ini</p>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm resize-none"
                    placeholder="Deskripsi produk (opsional)">{{ old('description') }}</textarea>
            </div>
        </div>

        {{-- Foto --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Foto Produk</h3>
            <input type="file" name="photos[]" multiple accept="image/*"
                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition">
            <p class="text-xs text-gray-400 mt-2">Foto pertama akan jadi foto utama. Format: JPG, PNG. Maks 2MB per foto.</p>
        </div>

        {{-- Submit --}}
        <div class="flex gap-3">
            <a href="{{ route('admin.products.index') }}"
                class="flex-1 text-center py-3 rounded-xl border border-gray-200 text-sm text-gray-500 hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit"
                class="flex-1 py-3 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold transition shadow-md">
                💾 Simpan Produk
            </button>
        </div>

    </form>
</div>
@endsection
