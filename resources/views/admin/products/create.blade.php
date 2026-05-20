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
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-4"
        x-data="productForm()" x-init="init()">
        @csrf

        <div class="card p-5">
            <p class="section-title">Informasi Produk</p>
            <div class="space-y-4">

                {{-- Nama --}}
                <div>
                    <label class="label">Nama Produk <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="input-field" placeholder="Masukkan nama produk">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="label">Kategori <span class="text-red-400">*</span></label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach($categories as $code => $label)
                        <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition"
                            :class="categoryCode === '{{ $code }}' ? 'border-blue-400 bg-blue-50' : 'border-slate-200 hover:border-blue-200'">
                            <input type="radio" name="category_code" value="{{ $code }}" x-model="categoryCode" @change="onCategoryChange()" class="hidden">
                            <div>
                                <p class="text-xs font-bold text-slate-700">{{ $label }}</p>
                                <p class="text-xs text-slate-400">{{ $code }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Subkategori --}}
                <div x-show="categoryCode">
                    <label class="label">Subkategori <span class="text-red-400">*</span></label>
                    <select name="subcategory_code" x-model="subcategoryCode" required class="input-field">
                        <option value="">Pilih Subkategori</option>
                        <template x-for="(label, code) in currentSubcategories" :key="code">
                            <option :value="code" x-text="code + ' — ' + label"></option>
                        </template>
                    </select>
                </div>

                {{-- Tier Harga --}}
                <div>
                    <label class="label">Tier Harga <span class="text-red-400">*</span></label>
                    <select name="price_tier" required class="input-field">
                        <option value="">Pilih Tier</option>
                        @foreach($priceTiers as $code => $range)
                            <option value="{{ $code }}" {{ old('price_tier') === $code ? 'selected' : '' }}>
                                Tier {{ $code }} — {{ $range }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Harga & Stok --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Harga (IDR) <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-semibold">Rp</span>
                            <input type="number" name="price" value="{{ old('price') }}" required min="0"
                                class="input-field" style="padding-left:36px;" placeholder="0">
                        </div>
                        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label">Stok Awal <span class="text-red-400">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', 1) }}" required min="0" class="input-field">
                    </div>
                </div>

                <div>
                    <label class="label">Batas Notifikasi Stok Menipis</label>
                    <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', 3) }}" min="1" class="input-field">
                </div>

                <div>
                    <label class="label">Deskripsi</label>
                    <textarea name="description" rows="3" class="input-field" style="resize:none;"
                        placeholder="Deskripsi produk (opsional)">{{ old('description') }}</textarea>
                </div>

            </div>
        </div>

        <div class="card p-5">
            <p class="section-title">Foto Produk</p>
            <input type="file" name="photos[]" multiple accept="image/*"
                class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer">
            <p class="text-xs text-slate-400 mt-2">Foto pertama jadi foto utama. Maks 2MB per foto, otomatis dikompres.</p>
        </div>

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

<script>
function productForm() {
    return {
        categoryCode: '{{ old('category_code', '') }}',
        subcategoryCode: '{{ old('subcategory_code', '') }}',
        allSubcategories: {!! $subcategoriesJson !!},
        currentSubcategories: {},

        init() {
            if (this.categoryCode) {
                this.currentSubcategories = this.allSubcategories[this.categoryCode] || {};
            }
        },

        onCategoryChange() {
            this.subcategoryCode = '';
            this.currentSubcategories = this.allSubcategories[this.categoryCode] || {};
        }
    }
}
</script>

@endsection
