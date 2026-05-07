@extends('layouts.app')
@section('title', 'Produk & Inventaris')

@section('content')

{{-- Alert --}}
@if(session('success'))
<div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
    ✅ {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
    ❌ {{ session('error') }}
</div>
@endif

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Produk & Inventaris</h2>
        <p class="text-sm text-gray-400 mt-0.5">Kelola semua produk perhiasan dan oleh-oleh</p>
    </div>
    <a href="{{ route('admin.products.create') }}"
        class="bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition shadow-sm flex items-center gap-2">
        <span>+</span> Tambah Produk
    </a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    @php
        $total = \App\Models\Product::count();
        $aktif = \App\Models\Product::where('is_active', true)->count();
        $habis = \App\Models\Product::where('stock', 0)->count();
        $menipis = \App\Models\Product::whereColumn('stock', '<=', 'low_stock_threshold')->where('stock', '>', 0)->count();
    @endphp

    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400">Total Produk</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $total }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400">Produk Aktif</p>
        <p class="text-2xl font-bold text-green-600 mt-1">{{ $aktif }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400">Stok Menipis</p>
        <p class="text-2xl font-bold text-amber-600 mt-1">{{ $menipis }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-400">Stok Habis</p>
        <p class="text-2xl font-bold text-red-500 mt-1">{{ $habis }}</p>
    </div>
</div>

{{-- Filter & Search --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
    <form method="GET" class="flex gap-3">
        <div class="flex-1 relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">🔍</span>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama produk atau SKU..."
                class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
        </div>
        <select name="category" class="px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm text-gray-600">
            <option value="">Semua Kategori</option>
            <option value="perhiasan" {{ request('category') === 'perhiasan' ? 'selected' : '' }}>Perhiasan</option>
            <option value="oleh-oleh" {{ request('category') === 'oleh-oleh' ? 'selected' : '' }}>Oleh-oleh</option>
        </select>
        <select name="status" class="px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm text-gray-600">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            <option value="menipis" {{ request('status') === 'menipis' ? 'selected' : '' }}>Stok Menipis</option>
            <option value="habis" {{ request('status') === 'habis' ? 'selected' : '' }}>Stok Habis</option>
        </select>
        <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition">
            Filter
        </button>
        @if(request()->hasAny(['search', 'category', 'status']))
        <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-500 hover:bg-gray-50 transition">
            Reset
        </a>
        @endif
    </form>
</div>

{{-- Tabel Produk --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Produk</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">SKU</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Kategori</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Harga</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Stok</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Status</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($products as $product)
            <tr class="hover:bg-gray-50 transition">
                {{-- Produk --}}
                <td class="py-3 px-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-xl flex-shrink-0">
                            @if($product->primaryPhoto)
                                <img src="{{ Storage::url($product->primaryPhoto->photo_path) }}" class="w-10 h-10 rounded-lg object-cover">
                            @else
                                {{ $product->category === 'perhiasan' ? '💍' : '🎁' }}
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold text-gray-700">{{ $product->name }}</p>
                            @if($product->description)
                            <p class="text-xs text-gray-400 truncate max-w-xs">{{ $product->description }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                {{-- SKU --}}
                <td class="py-3 px-4">
                    <code class="text-xs bg-gray-100 px-2 py-1 rounded-lg text-gray-600">{{ $product->sku }}</code>
                </td>
                {{-- Kategori --}}
                <td class="py-3 px-4">
                    <span class="text-xs px-2 py-1 rounded-full
                        {{ $product->category === 'perhiasan' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst($product->category) }}
                    </span>
                </td>
                {{-- Harga --}}
                <td class="py-3 px-4 font-semibold text-gray-700">
                    {{ $product->price_formatted }}
                </td>
                {{-- Stok --}}
                <td class="py-3 px-4">
                    <span class="font-bold
                        {{ $product->stock === 0 ? 'text-red-500' : ($product->stock_status === 'menipis' ? 'text-amber-500' : 'text-green-600') }}">
                        {{ $product->stock }}
                    </span>
                    <span class="text-gray-400 text-xs"> unit</span>
                </td>
                {{-- Status --}}
                <td class="py-3 px-4">
                    @if($product->stock === 0)
                        <span class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-600">Habis</span>
                    @elseif($product->stock_status === 'menipis')
                        <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-600">Menipis</span>
                    @elseif($product->is_active)
                        <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-600">Aktif</span>
                    @else
                        <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-500">Nonaktif</span>
                    @endif
                </td>
                {{-- Aksi --}}
                <td class="py-3 px-4">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}"
                            class="text-xs px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition font-medium">
                            Edit
                        </a>
                        {{-- Restock --}}
                        <button onclick="openRestock({{ $product->id }}, '{{ $product->name }}', {{ $product->stock }})"
                            class="text-xs px-3 py-1.5 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 transition font-medium">
                            Restock
                        </button>
                        {{-- Hapus --}}
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                            onsubmit="return confirm('Yakin hapus produk ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="text-xs px-3 py-1.5 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition font-medium">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-12 text-gray-300">
                    <div class="text-4xl mb-2">📦</div>
                    <p class="text-sm">Belum ada produk</p>
                    <a href="{{ route('admin.products.create') }}" class="text-amber-600 text-xs hover:underline mt-1 inline-block">
                        + Tambah produk pertama
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($products->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $products->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- Modal Restock --}}
<div id="restockModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-bold text-gray-800 mb-1">Restock Produk</h3>
        <p class="text-sm text-gray-400 mb-4" id="restockProductName"></p>

        <form id="restockForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Stok Saat Ini</label>
                <input type="text" id="currentStock" readonly
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 text-sm text-gray-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Jumlah Tambah <span class="text-red-400">*</span></label>
                <input type="number" name="quantity" min="1" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm"
                    placeholder="Masukkan jumlah">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Supplier</label>
                <input type="text" name="supplier"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm"
                    placeholder="Nama supplier (opsional)">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Catatan</label>
                <textarea name="notes" rows="2"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm resize-none"
                    placeholder="Catatan restock (opsional)"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeRestock()"
                    class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-500 hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold transition">
                    💾 Simpan Restock
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
