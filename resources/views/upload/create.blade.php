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
        .input-field {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
            color: #1e293b;
            font-family: 'Poppins', sans-serif;
            -webkit-appearance: none;
        }
        .input-field:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }
        .label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #64748b;
            margin-bottom: 8px;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .cat-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 14px 10px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.15s;
            background: #fff;
        }
        .cat-btn.active { border-color: #3b82f6; background: #eff6ff; }
        .photo-preview {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid #e2e8f0;
        }
    </style>
</head>
<body class="min-h-screen pb-8">

    {{-- Header --}}
    <div class="bg-white border-b border-slate-100 px-4 py-4 flex items-center justify-between sticky top-0 z-10 shadow-sm">
        <div class="flex items-center gap-3">
            <img src="{{ asset('assets/gem-pearls-logo.png') }}" class="w-8 h-8 object-contain">
            <div>
                <h1 class="text-sm font-bold text-slate-800">Upload Produk</h1>
                <p class="text-xs text-slate-400">{{ session('upload_user_name') }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('upload.logout') }}">
            @csrf
            <button type="submit" class="text-xs text-red-400 font-semibold px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                Logout
            </button>
        </form>
    </div>

    <div class="px-4 py-5 max-w-lg mx-auto" x-data="productForm()" x-init="init()">

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

        <form method="POST" action="{{ route('upload.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            {{-- Foto --}}
            <div class="card p-4">
                <label class="label">Foto Produk</label>
                <div class="flex gap-3 flex-wrap mb-3" id="photo-previews"></div>
                <label for="photo-input"
                    class="flex items-center justify-center gap-2 w-full py-4 rounded-xl border-2 border-dashed border-slate-300 text-slate-500 text-sm font-medium cursor-pointer hover:border-blue-400 hover:text-blue-500 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"/>
                    </svg>
                    Ambil / Pilih Foto
                </label>
                <input id="photo-input" type="file" name="photos[]" multiple accept="image/*"
                    class="hidden" onchange="previewPhotos(this)">
                <p class="text-xs text-slate-400 mt-2">Bisa ambil langsung dari kamera atau galeri</p>
            </div>

            {{-- Nama --}}
            <div class="card p-4">
                <label class="label">Nama Produk *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="input-field" placeholder="Masukkan nama produk">
            </div>

            {{-- Kategori --}}
            <div class="card p-4">
                <label class="label">Kategori *</label>
                <div class="grid grid-cols-3 gap-3 mb-4">
                    @foreach($categories as $code => $label)
                    <label class="cat-btn" :class="categoryCode === '{{ $code }}' ? 'active' : ''">
                        <input type="radio" name="category_code" value="{{ $code }}"
                            x-model="categoryCode" @change="onCategoryChange()" class="hidden">
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

            {{-- Tier Harga --}}
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

            {{-- Harga & Stok --}}
            <div class="card p-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Harga (IDR) *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-semibold">Rp</span>
                            <input type="number" name="price" value="{{ old('price') }}" required min="0"
                                class="input-field" style="padding-left:36px;" placeholder="0" inputmode="numeric">
                        </div>
                    </div>
                    <div>
                        <label class="label">Stok *</label>
                        <input type="number" name="stock" value="{{ old('stock', 1) }}" required min="0"
                            class="input-field" inputmode="numeric">
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="card p-4">
                <label class="label">Deskripsi</label>
                <textarea name="description" rows="3" class="input-field" style="resize:none;"
                    placeholder="Deskripsi produk (opsional)">{{ old('description') }}</textarea>
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full py-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm transition shadow-sm flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                Simpan Produk
            </button>

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
