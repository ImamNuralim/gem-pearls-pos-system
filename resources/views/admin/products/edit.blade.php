@extends('layouts.app')
@section('title', 'Edit Produk')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-gray-400 hover:text-gray-600 transition">←</a>
        <div>
            <h2 class="text-xl font-bold text-gray-800">Edit Produk</h2>
            <code class="text-xs bg-gray-100 px-2 py-1 rounded-lg text-gray-500">{{ $product->sku }}</code>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Informasi Produk</h3>

            {{-- Nama --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
            </div>

            {{-- Kategori (readonly) --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Kategori</label>
                <input type="text" value="{{ ucfirst($product->category) }}" readonly
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 text-sm text-gray-400">
                <p class="text-xs text-gray-400 mt-1">Kategori tidak bisa diubah setelah produk dibuat</p>
            </div>

            {{-- Harga & Stok --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Harga (IDR)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" required
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Stok</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Batas Notifikasi Stok Menipis</label>
                <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm resize-none">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        {{-- Foto --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Foto Produk</h3>

            @if($product->photos->count() > 0)
            <div class="flex gap-3 mb-4 flex-wrap">
                @foreach($product->photos as $photo)
                <div class="relative">
                    <img src="{{ Storage::url($photo->photo_path) }}"
                        class="w-20 h-20 rounded-xl object-cover border-2 {{ $photo->is_primary ? 'border-amber-400' : 'border-gray-200' }}">
                    @if($photo->is_primary)
                    <span class="absolute -top-1 -right-1 text-xs bg-amber-500 text-white rounded-full w-5 h-5 flex items-center justify-center">★</span>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            <input type="file" name="photos[]" multiple accept="image/*"
                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
            <p class="text-xs text-gray-400 mt-2">Upload foto baru untuk ditambahkan ke produk</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.products.index') }}"
                class="flex-1 text-center py-3 rounded-xl border border-gray-200 text-sm text-gray-500 hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit"
                class="flex-1 py-3 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold transition shadow-md">
                💾 Update Produk
            </button>
        </div>

    </form>
</div>
@endsection
