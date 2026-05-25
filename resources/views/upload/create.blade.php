<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Upload Produk — Gem Pearls</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background: #f8fafc; }
        [x-cloak] { display: none !important; }
        .input-field { width:100%; padding:12px 16px; border-radius:12px; border:1.5px solid #e2e8f0; font-size:14px; outline:none; transition:border-color 0.2s; background:#fff; color:#1e293b; font-family:'Poppins',sans-serif; -webkit-appearance:none; }
        .input-field:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.1); }
        .label { display:block; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; color:#64748b; margin-bottom:8px; }
        .card { background:#fff; border-radius:16px; border:1px solid #e2e8f0; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
        .cat-btn { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:14px 10px; border-radius:12px; border:2px solid #e2e8f0; cursor:pointer; transition:all 0.15s; background:#fff; }
        .cat-btn.active { border-color:#3b82f6; background:#eff6ff; }
        .photo-preview { width:80px; height:80px; border-radius:12px; object-fit:cover; border:2px solid #e2e8f0; }
        .tab-btn { padding:8px 16px; border-radius:10px; font-size:13px; font-weight:600; transition:all 0.15s; cursor:pointer; border:none; }
        .tab-btn.active { background:#2563eb; color:#fff; }
        .tab-btn:not(.active) { background:#f1f5f9; color:#64748b; }
    </style>
</head>
<body class="min-h-screen pb-8">

    {{-- Header --}}
    <div class="bg-white border-b border-slate-100 px-4 py-4 flex items-center justify-between sticky top-0 z-10 shadow-sm">
        <div class="flex items-center gap-3">
            <img src="{{ asset('assets/gem-pearls-logo.png') }}" class="w-8 h-8 object-contain">
            <div>
                <h1 class="text-sm font-bold text-slate-800">Manajemen Produk</h1>
                <p class="text-xs text-slate-400">{{ session('upload_user_name') }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('upload.logout') }}">
            @csrf
            <button type="submit" class="text-xs text-red-400 font-semibold px-3 py-1.5 rounded-lg hover:bg-red-50 transition">Logout</button>
        </form>
    </div>

    <div class="px-4 py-5 max-w-lg mx-auto" x-data="uploadApp()" x-init="init()">

        @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-semibold flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm">
            <ul class="space-y-1">
                @foreach($errors->all() as $error)<li>• {{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        {{-- Tabs --}}
        <div class="flex gap-2 mb-5">
            <button class="tab-btn flex-1" :class="activeTab === 'upload' ? 'active' : ''" @click="activeTab = 'upload'">
                + Upload Produk
            </button>
            <button class="tab-btn flex-1" :class="activeTab === 'list' ? 'active' : ''" @click="activeTab = 'list'; loadProducts()">
                Daftar Produk
            </button>
        </div>

        {{-- TAB UPLOAD --}}
        <div x-show="activeTab === 'upload'">
            <form method="POST" action="{{ route('upload.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="card p-4">
                    <label class="label">Foto Produk</label>
                    <div class="flex gap-3 flex-wrap mb-3" id="photo-previews"></div>
                    <label for="photo-input" class="flex items-center justify-center gap-2 w-full py-4 rounded-xl border-2 border-dashed border-slate-300 text-slate-500 text-sm font-medium cursor-pointer hover:border-blue-400 hover:text-blue-500 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"/>
                        </svg>
                        Ambil / Pilih Foto
                    </label>
                    <input id="photo-input" type="file" name="photos[]" multiple accept="image/*" class="hidden" onchange="previewPhotos(this)">
                    <p class="text-xs text-slate-400 mt-2">Bisa ambil langsung dari kamera atau galeri</p>
                </div>

                <div class="card p-4">
                    <label class="label">Nama Produk *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input-field" placeholder="Masukkan nama produk">
                </div>

                <div class="card p-4">
                    <label class="label">Kategori *</label>
                    <div class="grid grid-cols-3 gap-3 mb-4">
                        @foreach($categories as $code => $label)
                        <label class="cat-btn" :class="categoryCode === '{{ $code }}' ? 'active' : ''">
                            <input type="radio" name="category_code" value="{{ $code }}" x-model="categoryCode" @change="onCategoryChange()" class="hidden">
                            <span class="text-xs font-bold text-slate-700">{{ $label }}</span>
                            <span class="text-xs text-slate-400 mt-0.5">{{ $code }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div x-show="categoryCode">
                        <label class="label">Subkategori *</label>
                        <select name="subcategory_code" x-model="subcategoryCode" required class="input-field">
                            <option value="">Pilih Subkategori</option>
                            <template x-for="(label, code) in currentSubcategories" :key="code">
                                <option :value="code" x-text="code + ' — ' + label"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="card p-4">
                    <label class="label">Tier Harga *</label>
                    <select name="price_tier" required class="input-field">
                        <option value="">Pilih Tier</option>
                        @foreach($priceTiers as $code => $range)
                            <option value="{{ $code }}" {{ old('price_tier') === $code ? 'selected' : '' }}>
                                Tier {{ $code }} — {{ $range }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="card p-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Harga (IDR) *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-semibold">Rp</span>
                                <input type="text" id="price-display" class="input-field" style="padding-left:36px;" placeholder="0"
                                    oninput="let r=this.value.replace(/\D/g,''); this.value=r?new Intl.NumberFormat('id-ID').format(r):''; document.getElementById('price-value').value=r;">
                                <input type="hidden" name="price" id="price-value" value="{{ old('price') }}">
                            </div>
                        </div>
                        <div>
                            <label class="label">Stok *</label>
                            <input type="number" name="stock" value="{{ old('stock', 1) }}" required min="0" class="input-field" inputmode="numeric">
                        </div>
                    </div>
                </div>

                <div class="card p-4">
                    <label class="label">Deskripsi</label>
                    <textarea name="description" rows="3" class="input-field" style="resize:none;" placeholder="Deskripsi produk (opsional)">{{ old('description') }}</textarea>
                </div>

                <button type="submit" class="w-full py-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm transition shadow-sm flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    Simpan Produk
                </button>
            </form>
        </div>

        {{-- TAB DAFTAR PRODUK --}}
        <div x-show="activeTab === 'list'">
            <div x-show="isLoading" class="text-center py-10 text-slate-400 text-sm">Memuat produk...</div>

            <div x-show="!isLoading" class="space-y-3">
                <template x-for="product in products" :key="product.id">
                    <div class="card p-3 flex items-center gap-3">
                        {{-- Foto --}}
                        <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 cursor-pointer"
                            @click="product.photo ? previewPhoto = product.photo : null">
                            <template x-if="product.photo">
                                <img :src="product.photo" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!product.photo">
                                <div class="w-full h-full flex items-center justify-center text-2xl">📦</div>
                            </template>
                        </div>
                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-slate-800 text-sm truncate" x-text="product.name"></p>
                            <p class="text-xs text-slate-400 truncate" x-text="product.sku"></p>
                            <p class="text-xs font-bold text-blue-600 mt-0.5" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(product.price)"></p>
                            <p class="text-xs text-slate-400" x-text="'Stok: ' + product.stock"></p>
                        </div>
                        {{-- Actions --}}
                        <div class="flex flex-col gap-1.5 flex-shrink-0">
                            <button @click="openEdit(product)"
                                class="p-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                </svg>
                            </button>
                            <button @click="deleteProduct(product)"
                                class="p-2 rounded-xl bg-red-50 text-red-400 hover:bg-red-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>

                <div x-show="products.length === 0 && !isLoading" class="text-center py-10 text-slate-300">
                    <p class="text-sm">Belum ada produk</p>
                </div>
            </div>
        </div>

        {{-- Preview Photo Modal --}}
        <div x-show="previewPhoto" x-cloak @click="previewPhoto = null"
            class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
            <img :src="previewPhoto" class="max-w-full max-h-full rounded-2xl object-contain shadow-2xl">
        </div>

        {{-- Edit Modal --}}
        <div x-show="editProduct" x-cloak class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl w-full max-w-sm p-6 relative" @click.stop>
                <button @click="editProduct = null" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
                <h2 class="text-base font-bold text-slate-800 mb-4">Edit Produk</h2>
                <div class="space-y-3">
                    <div>
                        <label class="label">Nama</label>
                        <input type="text" x-model="editProduct.name" class="input-field">
                    </div>
                    <div>
                        <label class="label">Harga (IDR)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-semibold">Rp</span>
                            <input type="text" id="edit-price-display"
                                :value="new Intl.NumberFormat('id-ID').format(editProduct.price)"
                                class="input-field" style="padding-left:36px;"
                                oninput="let r=this.value.replace(/\D/g,''); this.value=r?new Intl.NumberFormat('id-ID').format(r):''; document.querySelector('[x-data]')._x_dataStack[0].editProduct.price=parseInt(r)||0;">
                        </div>
                    </div>
                    <div>
                        <label class="label">Stok</label>
                        <input type="number" x-model="editProduct.stock" min="0" class="input-field" inputmode="numeric">
                    </div>
                    <div x-show="saveMsg" class="text-xs font-semibold text-emerald-600 py-1" x-text="saveMsg"></div>
                    <button @click="saveEdit()" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition">
                        Simpan
                    </button>
                </div>
            </div>
        </div>

    </div>

<script>
const CSRF = '{{ csrf_token() }}';

function uploadApp() {
    return {
        activeTab: 'upload',
        categoryCode: '{{ old('category_code', '') }}',
        subcategoryCode: '{{ old('subcategory_code', '') }}',
        allSubcategories: {!! $subcategoriesJson !!},
        currentSubcategories: {},
        products: [],
        isLoading: false,
        previewPhoto: null,
        editProduct: null,
        saveMsg: '',

        init() {
            if (this.categoryCode) {
                this.currentSubcategories = this.allSubcategories[this.categoryCode] || {};
            }
        },

        onCategoryChange() {
            this.subcategoryCode = '';
            this.currentSubcategories = this.allSubcategories[this.categoryCode] || {};
        },

        async loadProducts() {
            this.isLoading = true;
            try {
                const res = await fetch('{{ route('upload.products') }}');
                this.products = await res.json();
            } catch(e) { console.error(e); }
            finally { this.isLoading = false; }
        },

        openEdit(product) {
            this.editProduct = { ...product };
            this.saveMsg = '';
        },

        async saveEdit() {
            try {
                const res = await fetch(`/upload/products/${this.editProduct.id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify({ name: this.editProduct.name, price: this.editProduct.price, stock: this.editProduct.stock })
                });
                const data = await res.json();
                if (data.success) {
                    const idx = this.products.findIndex(p => p.id === this.editProduct.id);
                    if (idx !== -1) this.products[idx] = { ...this.products[idx], ...this.editProduct };
                    this.saveMsg = '✅ Berhasil disimpan!';
                    setTimeout(() => { this.editProduct = null; this.saveMsg = ''; }, 1000);
                }
            } catch(e) { console.error(e); }
        },

        async deleteProduct(product) {
            if (!confirm(`Hapus produk "${product.name}"?`)) return;
            try {
                const res = await fetch(`/upload/products/${product.id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    this.products = this.products.filter(p => p.id !== product.id);
                }
            } catch(e) { console.error(e); }
        }
    }
}

function previewPhotos(input) {
    const container = document.getElementById('photo-previews');
    container.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'photo-preview';
            container.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}
</script>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
