@extends('layouts.app')
@section('title', 'Produk & Inventaris')

@section('content')
<style>
    .card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
    .input-field { width:100%; padding:9px 14px; border-radius:10px; border:1.5px solid #e2e8f0; font-size:12px; outline:none; transition:border-color 0.2s, box-shadow 0.2s; background:#f8fafc; color:#1e293b; font-family:'Poppins',sans-serif; }
    .input-field:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.1); background:#fff; }
    .btn-primary { padding:9px 18px; border-radius:10px; background:#2563eb; color:#fff; font-size:12px; font-weight:600; border:none; cursor:pointer; transition:background 0.2s; font-family:'Poppins',sans-serif; }
    .btn-primary:hover { background:#1d4ed8; }
    .section-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:#94a3b8; }
</style>

{{-- Alerts --}}
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
        <h2 class="text-xl font-bold text-slate-800">Produk & Inventaris</h2>
        <p class="text-sm text-slate-400 mt-0.5">Kelola semua produk perhiasan dan oleh-oleh</p>
    </div>
    <a href="{{ route('admin.products.create') }}"
        class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Tambah Produk
    </a>
</div>

{{-- Stats --}}
@php
    $total    = \App\Models\Product::count();
    $aktif    = \App\Models\Product::where('is_active', true)->count();
    $habis    = \App\Models\Product::where('stock', 0)->count();
    $menipis  = \App\Models\Product::whereColumn('stock', '<=', 'low_stock_threshold')->where('stock', '>', 0)->count();
@endphp
<div class="grid grid-cols-4 gap-4 mb-5">
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Total Produk</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $total }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
            </svg>
        </div>
    </div>
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Produk Aktif</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $aktif }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#059669" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </div>
    </div>
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Stok Menipis</p>
            <p class="text-2xl font-bold text-amber-500 mt-1">{{ $menipis }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#d97706" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
            </svg>
        </div>
    </div>
    <div class="card p-4 flex items-center justify-between">
        <div>
            <p class="section-label">Stok Habis</p>
            <p class="text-2xl font-bold text-red-500 mt-1">{{ $habis }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ef4444" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card p-4 mb-4">
    <form method="GET" class="flex gap-2">
        <div class="flex-1 relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama produk atau SKU..."
                class="input-field" style="padding-left:36px;">
        </div>
        <select name="category" class="input-field" style="width:auto; padding:9px 12px;">
            <option value="">Semua Kategori</option>
            <option value="perhiasan" {{ request('category') === 'perhiasan' ? 'selected' : '' }}>Perhiasan</option>
            <option value="oleh-oleh" {{ request('category') === 'oleh-oleh' ? 'selected' : '' }}>Oleh-oleh</option>
        </select>
        <select name="status" class="input-field" style="width:auto; padding:9px 12px;">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            <option value="menipis" {{ request('status') === 'menipis' ? 'selected' : '' }}>Stok Menipis</option>
            <option value="habis" {{ request('status') === 'habis' ? 'selected' : '' }}>Stok Habis</option>
        </select>
        <button type="submit" class="btn-primary">Filter</button>
        @if(request()->hasAny(['search', 'category', 'status']))
            <a href="{{ route('admin.products.index') }}"
                class="px-4 py-2 rounded-xl border border-slate-200 text-sm text-slate-500 hover:bg-slate-50 transition font-medium">
                Reset
            </a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-100">
            <tr>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Produk</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">SKU</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Kategori</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Harga</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Stok</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Status</th>
                <th class="text-left py-3 px-5 text-xs font-bold text-slate-400 uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($products as $product)
            <tr class="hover:bg-blue-50/20 transition">
                <td class="py-3 px-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center flex-shrink-0 overflow-hidden border border-slate-100">
                            @if($product->primaryPhoto)
                                <img src="{{ Storage::url($product->primaryPhoto->photo_path) }}" class="w-10 h-10 object-cover">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#94a3b8" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold text-slate-700 text-sm">{{ $product->name }}</p>
                            @if($product->description)
                                <p class="text-xs text-slate-400 truncate max-w-xs">{{ $product->description }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="py-3 px-5">
                    <code class="text-xs bg-slate-100 px-2 py-1 rounded-lg text-slate-600 font-mono">{{ $product->sku }}</code>
                </td>
                <td class="py-3 px-5">
                    <span class="text-xs px-2 py-1 rounded-lg font-semibold
                        {{ $product->category === 'perhiasan' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst($product->category) }}
                    </span>
                </td>
                <td class="py-3 px-5 font-semibold text-slate-700">{{ $product->price_formatted }}</td>
                <td class="py-3 px-5">
                    <span class="font-bold
                        {{ $product->stock === 0 ? 'text-red-500' : ($product->stock_status === 'menipis' ? 'text-amber-500' : 'text-emerald-600') }}">
                        {{ $product->stock }}
                    </span>
                    <span class="text-slate-400 text-xs"> item</span>
                </td>
                <td class="py-3 px-5">
                    @if($product->stock === 0)
                        <span class="text-xs px-2 py-1 rounded-lg bg-red-100 text-red-500 font-semibold">Habis</span>
                    @elseif($product->stock_status === 'menipis')
                        <span class="text-xs px-2 py-1 rounded-lg bg-amber-100 text-amber-600 font-semibold">Menipis</span>
                    @elseif($product->is_active)
                        <span class="text-xs px-2 py-1 rounded-lg bg-emerald-100 text-emerald-600 font-semibold">Aktif</span>
                    @else
                        <span class="text-xs px-2 py-1 rounded-lg bg-slate-100 text-slate-500 font-semibold">Nonaktif</span>
                    @endif
                </td>
                <td class="py-3 px-5">
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.products.edit', $product) }}"
                            class="p-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                            </svg>
                        </a>
                        <button onclick="openRestock({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->stock }})"
                            class="p-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 transition" title="Restock">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                        </button>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                            onsubmit="return confirm('Yakin hapus produk ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 transition" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-14">
                    <div class="flex flex-col items-center gap-2 text-slate-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                        </svg>
                        <p class="text-sm">Belum ada produk</p>
                        <a href="{{ route('admin.products.create') }}" class="text-blue-500 text-xs hover:underline">+ Tambah produk pertama</a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($products->hasPages())
    <div class="px-5 py-3 border-t border-slate-100">
        {{ $products->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- Modal Restock --}}
<div id="restockModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-bold text-slate-800 mb-1">Restock Produk</h3>
        <p class="text-sm text-slate-400 mb-5" id="restockProductName"></p>
        <form id="restockForm" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-xs font-bold uppercase text-slate-400 block mb-1.5">Stok Saat Ini</label>
                <input type="text" id="currentStock" readonly class="input-field bg-slate-100 text-slate-500">
            </div>
            <div>
                <label class="text-xs font-bold uppercase text-slate-400 block mb-1.5">Jumlah Tambah <span class="text-red-400">*</span></label>
                <input type="number" name="quantity" min="1" required class="input-field" placeholder="Masukkan jumlah">
            </div>
            <div>
                <label class="text-xs font-bold uppercase text-slate-400 block mb-1.5">Supplier</label>
                <input type="text" name="supplier" class="input-field" placeholder="Nama supplier (opsional)">
            </div>
            <div>
                <label class="text-xs font-bold uppercase text-slate-400 block mb-1.5">Catatan</label>
                <textarea name="notes" rows="2" class="input-field" style="resize:none;" placeholder="Catatan restock (opsional)"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeRestock()"
                    class="flex-1 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-500 hover:bg-slate-50 transition font-medium">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition">
                    Simpan Restock
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRestock(id, name, stock) {
    document.getElementById('restockProductName').textContent = name;
    document.getElementById('currentStock').value = stock + ' unit';
    document.getElementById('restockForm').action = `/admin/products/${id}/restock`;
    document.getElementById('restockModal').classList.remove('hidden');
}
function closeRestock() {
    document.getElementById('restockModal').classList.add('hidden');
}
</script>

@endsection
