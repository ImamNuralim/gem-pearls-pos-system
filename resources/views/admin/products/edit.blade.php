@extends('layouts.app')
@section('title', 'Edit Produk')

@section('content')
<style>
    .card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
    .input-field { width:100%; padding:9px 14px; border-radius:10px; border:1.5px solid #e2e8f0; font-size:13px; outline:none; transition:border-color 0.2s, box-shadow 0.2s; background:#f8fafc; color:#1e293b; font-family:'Poppins',sans-serif; }
    .input-field:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.1); background:#fff; }
    .input-field[readonly] { background:#f1f5f9; color:#94a3b8; cursor:not-allowed; }
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
            <h2 class="text-xl font-bold text-slate-800">Edit Produk</h2>
            <code class="text-xs bg-slate-100 px-2 py-0.5 rounded-lg text-slate-500 font-mono">{{ $product->sku }}</code>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    @php
        $currentCategoryCode = $categoryMapReverse[$product->category] ?? 'PER';
        $currentSubcategoryCode = $product->jewelry_type ?? '';
        $currentTier = $product->price_tier ?? '';
    @endphp

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf @method('PUT')

        <div class="card p-5">
            <p class="section-title">Informasi Produk</p>
            <div class="space-y-4">

                {{-- Nama --}}
                <div>
                    <label class="label">Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="input-field">
                </div>

                {{-- Kategori (readonly) --}}
                <div>
                    <label class="label">Kategori</label>
                    <input type="text" value="{{ $categories[$currentCategoryCode] ?? ucfirst($product->category) }} ({{ $currentCategoryCode }})" readonly class="input-field">
                    <p class="text-xs text-slate-400 mt-1">Kategori tidak bisa diubah setelah produk dibuat</p>
                </div>

                {{-- Subkategori (readonly) --}}
                @if($currentSubcategoryCode)
                <div>
                    <label class="label">Subkategori</label>
                    @php
                        $subLabel = $subcategories[$currentCategoryCode][$currentSubcategoryCode] ?? $currentSubcategoryCode;
                    @endphp
                    <input type="text" value="{{ $currentSubcategoryCode }} — {{ $subLabel }}" readonly class="input-field">
                </div>
                @endif

                {{-- Tier (readonly) --}}
                @if($currentTier)
                <div>
                    <label class="label">Tier Harga</label>
                    <input type="text" value="Tier {{ $currentTier }} — {{ $priceTiers[$currentTier] ?? '-' }}" readonly class="input-field">
                </div>
                @endif

                <div class="grid grid-cols-2 gap-4">
    <div>
        <label class="label">Harga (IDR)</label>
        <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-semibold">Rp</span>
            <input type="text" id="price-display"
                value="{{ number_format(old('price', $product->price), 0, ',', '.') }}"
                class="input-field" style="padding-left:36px;"
                oninput="let r=this.value.replace(/\D/g,''); this.value=r?new Intl.NumberFormat('id-ID').format(r):''; document.getElementById('price-value').value=r;">
            <input type="hidden" name="price" id="price-value" value="{{ old('price', $product->price) }}">
        </div>
    </div>
    <div>
        <label class="label">Stok</label>
        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required class="input-field">
    </div>
</div>

                <div>
                    <label class="label">Batas Notifikasi Stok Menipis</label>
                    <input type="number" name="low_stock_threshold"
                        value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" class="input-field">
                </div>

                <div>
                    <label class="label">Deskripsi</label>
                    <textarea name="description" rows="3" class="input-field" style="resize:none;">{{ old('description', $product->description) }}</textarea>
                </div>

            </div>
        </div>

        {{-- Foto --}}
        <div class="card p-5">
            <p class="section-title">Foto Produk</p>

            @if($product->photos->count() > 0)
            <div class="flex gap-3 mb-4 flex-wrap">
                @foreach($product->photos as $photo)
                <div class="relative">
                    <img src="{{ Storage::url($photo->photo_path) }}"
                        class="w-20 h-20 rounded-xl object-cover border-2 {{ $photo->is_primary ? 'border-blue-400' : 'border-slate-200' }}">
                    @if($photo->is_primary)
                        <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-blue-500 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="white" class="w-3 h-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                        </span>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            <label class="label">Upload Foto Baru</label>
            <input type="file" name="photos[]" multiple accept="image/*"
                class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer">
            <p class="text-xs text-slate-400 mt-2">Foto baru akan ditambahkan. Maks 2MB, otomatis dikompres.</p>
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
                Update Produk
            </button>
        </div>

    </form>

</div>

<script>
function formatPriceInput(el) {
    let raw = el.value.replace(/\D/g, '');
    el.value = raw ? new Intl.NumberFormat('id-ID').format(raw) : '';
    document.getElementById('price-value').value = raw;
}
</script>
@endsection
