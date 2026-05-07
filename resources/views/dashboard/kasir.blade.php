@extends('layouts.pos')
@section('title', 'Kasir')
@section('subtitle', 'Point of Sale')
@section('container_class', 'h-[calc(100vh-65px)] p-4')

@section('content')
    <div x-data="posSystem()" x-init="init()" class="grid grid-cols-12 gap-4 h-full">

        {{-- KIRI: Produk --}}
        <div class="col-span-7 flex flex-col gap-3 h-full overflow-hidden">

            {{-- Search --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                        <input type="text" x-model="search" @input.debounce.300ms="filterProducts()"
                            placeholder="Cari produk atau scan SKU..."
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                    </div>
                    <select x-model="categoryFilter" @change="filterProducts()"
                        class="px-3 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm text-gray-600">
                        <option value="">Semua</option>
                        <option value="perhiasan">Perhiasan</option>
                        <option value="oleh-oleh">Oleh-oleh</option>
                    </select>
                </div>
            </div>

            {{-- Grid Produk --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3 flex-1 overflow-y-auto">
                <div class="grid grid-cols-3 gap-2">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div @click="addToCart(product)"
                            class="border border-gray-100 rounded-xl p-3 hover:border-amber-300 hover:bg-amber-50 cursor-pointer transition group">
                            <div
                                class="w-full h-20 bg-gray-50 rounded-lg mb-2 flex items-center justify-center overflow-hidden group-hover:bg-amber-100 transition">
                                <template x-if="product.photo">
                                    <img :src="product.photo" class="w-full h-full object-cover rounded-lg">
                                </template>
                                <template x-if="!product.photo">
                                    <span class="text-3xl" x-text="product.category === 'perhiasan' ? '💍' : '🎁'"></span>
                                </template>
                            </div>
                            <p class="text-xs font-semibold text-gray-700 truncate" x-text="product.name"></p>
                            <p class="text-xs text-gray-400 truncate" x-text="product.sku"></p>
                            <p class="text-sm font-bold text-amber-600 mt-1" x-text="product.price_formatted"></p>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-xs text-gray-400">Stok: <span x-text="product.stock"></span></span>
                            </div>
                        </div>
                    </template>

                    <template x-if="filteredProducts.length === 0">
                        <div class="col-span-3 text-center py-12 text-gray-300">
                            <div class="text-4xl mb-2">📦</div>
                            <p class="text-sm">Produk tidak ditemukan</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- KANAN: Keranjang & Checkout --}}
        <div class="col-span-5 flex flex-col gap-3 h-full overflow-hidden">

            {{-- Customer Type --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Tipe Customer</p>
                <div class="grid grid-cols-2 gap-2">
                    <template x-for="type in customerTypes" :key="type.value">
                        <button @click="setCustomerType(type.value)"
                            :class="customerType === type.value ? 'border-amber-400 bg-amber-50 text-amber-700' :
                                'border-gray-200 text-gray-600 hover:border-amber-200'"
                            class="flex items-center gap-2 px-3 py-2 rounded-xl border text-xs font-medium transition">
                            <span x-text="type.icon"></span>
                            <span x-text="type.label"></span>
                        </button>
                    </template>
                </div>

                {{-- Partner Search --}}
                <div x-show="customerType === 'travel_agent' || customerType === 'freelance_guide'" x-transition
                    class="mt-2">
                    <div class="relative">
                        <input type="text" x-model="partnerSearch" @input.debounce.300ms="searchPartner()"
                            placeholder="Cari nama mitra..."
                            class="w-full px-3 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-xs">
                        <div x-show="partnerResults.length > 0"
                            class="absolute z-10 w-full bg-white border border-gray-200 rounded-xl shadow-lg mt-1 max-h-32 overflow-y-auto">
                            <template x-for="partner in partnerResults" :key="partner.id">
                                <div @click="selectPartner(partner)"
                                    class="px-3 py-2 hover:bg-amber-50 cursor-pointer text-xs border-b border-gray-50 last:border-0">
                                    <span class="font-semibold text-gray-700" x-text="partner.name"></span>
                                    <span class="text-gray-400 ml-1" x-text="'(' + partner.code + ')'"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div x-show="selectedPartner"
                        class="mt-1 px-3 py-1.5 bg-amber-50 rounded-lg text-xs text-amber-700 flex items-center justify-between">
                        <span>✅ <span x-text="selectedPartner?.name"></span></span>
                        <button @click="selectedPartner = null; partnerSearch = ''"
                            class="text-red-400 hover:text-red-600">✕</button>
                    </div>
                </div>

                {{-- Member Search --}}
                <div x-show="customerType === 'member'" x-transition class="mt-2">
                    <div class="relative">
                        <input type="text" x-model="memberSearch" @input.debounce.300ms="searchMember()"
                            placeholder="Cari nama atau no. HP member..."
                            class="w-full px-3 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-xs">
                        <div x-show="memberResults.length > 0"
                            class="absolute z-10 w-full bg-white border border-gray-200 rounded-xl shadow-lg mt-1 max-h-32 overflow-y-auto">
                            <template x-for="member in memberResults" :key="member.id">
                                <div @click="selectMember(member)"
                                    class="px-3 py-2 hover:bg-amber-50 cursor-pointer text-xs border-b border-gray-50 last:border-0">
                                    <span class="font-semibold text-gray-700" x-text="member.name"></span>
                                    <span class="text-gray-400 ml-1" x-text="member.phone"></span>
                                    <span class="text-amber-600 ml-1"
                                        x-text="'⭐ ' + member.points_balance + ' poin'"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div x-show="selectedMember" class="mt-1 px-3 py-1.5 bg-amber-50 rounded-lg text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-amber-700">⭐ <span x-text="selectedMember?.name"></span></span>
                            <button @click="selectedMember = null; memberSearch = ''; pointsToRedeem = 0"
                                class="text-red-400 hover:text-red-600">✕</button>
                        </div>
                        <div class="mt-1 flex items-center gap-2">
                            <span class="text-gray-500">Poin: <span x-text="selectedMember?.points_balance"
                                    class="font-bold text-amber-600"></span></span>
                            <input type="number" x-model="pointsToRedeem" :max="selectedMember?.points_balance"
                                @input="calculateTotal()" min="0" placeholder="Redeem poin"
                                class="flex-1 px-2 py-1 rounded-lg border border-gray-200 text-xs focus:outline-none focus:ring-1 focus:ring-amber-400">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Keranjang --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3 flex-1 overflow-y-auto">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Keranjang</p>
                    <div class="flex items-center gap-2">
                        <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full"
                            x-text="cart.length + ' item'"></span>
                        <button x-show="cart.length > 0" @click="clearCart()"
                            class="text-xs text-red-400 hover:text-red-600 transition">🗑 Kosongkan</button>
                    </div>
                </div>

                {{-- Empty --}}
                <div x-show="cart.length === 0" class="flex flex-col items-center justify-center h-24 text-gray-300">
                    <span class="text-3xl mb-1">🛒</span>
                    <p class="text-xs">Keranjang kosong</p>
                </div>

                {{-- Items --}}
                <div class="space-y-2">
                    <template x-for="(item, index) in cart" :key="item.product_id">
                        <div class="border border-gray-100 rounded-xl p-2.5">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-gray-700 truncate" x-text="item.name"></p>
                                    <p class="text-xs text-gray-400" x-text="item.sku"></p>
                                </div>
                                <button @click="removeFromCart(index)"
                                    class="text-red-400 hover:text-red-600 text-xs flex-shrink-0">✕</button>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                {{-- Harga (bisa nego) --}}
                                <div class="flex items-center gap-1">
                                    <template x-if="item.final_price != item.original_price">
                                        <span class="text-xs text-gray-300 line-through"
                                            x-text="formatRupiah(item.original_price)"></span>
                                    </template>
                                    <input type="number" x-model="item.final_price" @input="calculateTotal()"
                                        class="w-28 px-2 py-1 rounded-lg border border-gray-200 text-xs font-bold text-amber-600 focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400">
                                </div>
                                {{-- Qty --}}
                                <div class="flex items-center gap-1">
                                    <button @click="decrementQty(index)"
                                        class="w-6 h-6 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-bold transition flex items-center justify-center">−</button>
                                    <span class="text-xs font-bold text-gray-700 w-6 text-center"
                                        x-text="item.quantity"></span>
                                    <button @click="incrementQty(index)"
                                        class="w-6 h-6 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-bold transition flex items-center justify-center">+</button>
                                </div>
                            </div>
                            <div class="mt-1 text-right">
                                <span class="text-xs font-bold text-gray-700"
                                    x-text="formatRupiah(item.final_price * item.quantity)"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Ringkasan & Bayar --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3">

                {{-- Summary --}}
                <div class="space-y-1 mb-3 text-xs">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal</span>
                        <span x-text="formatRupiah(subtotal)"></span>
                    </div>
                    <div x-show="pointsDiscount > 0" class="flex justify-between text-green-500">
                        <span>Diskon Poin (<span x-text="pointsToRedeem"></span> poin)</span>
                        <span x-text="'- ' + formatRupiah(pointsDiscount)"></span>
                    </div>
                    <div x-show="adminFee > 0" class="flex justify-between text-orange-500">
                        <span>Admin Fee (Mata Uang Asing)</span>
                        <span x-text="formatRupiah(adminFee)"></span>
                    </div>
                    <div class="border-t border-gray-100 pt-1.5 flex justify-between font-bold text-gray-800">
                        <span>Total</span>
                        <span class="text-amber-600 text-base" x-text="formatRupiah(total)"></span>
                    </div>
                </div>

                {{-- Metode Bayar --}}
                <div class="grid grid-cols-3 gap-1.5 mb-2">
                    <template x-for="method in paymentMethods" :key="method.value">
                        <button @click="setPaymentMethod(method.value)"
                            :class="paymentMethod === method.value ? 'border-amber-400 bg-amber-50 text-amber-700' :
                                'border-gray-200 text-gray-500 hover:border-amber-200'"
                            class="flex flex-col items-center gap-0.5 py-2 rounded-xl border text-xs transition">
                            <span x-text="method.icon" class="text-lg"></span>
                            <span x-text="method.label"></span>
                        </button>
                    </template>
                </div>

                {{-- Cash: input mata uang --}}
                <div x-show="paymentMethod === 'cash'" x-transition class="mb-2 space-y-1.5">

                    {{-- Pilih mata uang --}}
                    <div class="flex gap-2 items-center">
                        <span class="text-xs text-gray-500">Mata Uang:</span>
                        <select x-model="currencyCode" @change="setCurrency()"
                            class="flex-1 px-2 py-2 rounded-xl border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-amber-400">
                            <option value="IDR">🇮🇩 IDR</option>
                            <template x-for="(rate, code) in currencyRates" :key="code">
                                <option :value="code" x-text="(currencyFlags[code] || '🌐') + ' ' + code">
                                </option>
                            </template>
                        </select>
                        <button @click="fetchCurrencyRates(); setCurrency()"
                            class="text-xs text-blue-500 hover:text-blue-700 transition flex items-center gap-1 px-2 py-2 rounded-xl border border-gray-200">
                            <span :class="isFetchingRates ? 'animate-spin' : ''">⟳</span>
                        </button>
                    </div>

                    {{-- Tagihan dalam mata uang asing --}}
                    <div class="p-3 rounded-xl border-2 border-amber-300 bg-amber-50">
                        <p class="text-xs text-gray-500 mb-1">Total Tagihan</p>
                        <div x-show="currencyCode === 'IDR'">
                            <p class="text-lg font-bold text-amber-700" x-text="formatRupiah(total)"></p>
                        </div>
                        <div x-show="currencyCode !== 'IDR'">
                            <p class="text-lg font-bold text-amber-700"
                                x-text="formatForeign(totalInForeign) + ' ' + currencyCode"></p>
                            <p class="text-xs text-gray-400 mt-0.5"
                                x-text="'≈ ' + formatRupiah(total) + ' (kurs: 1 ' + currencyCode + ' = Rp ' + formatNumber(currencyRate) + ')'">
                            </p>
                            <p class="text-xs text-orange-500 mt-0.5">+ Admin fee Rp 1.000</p>
                            <p class="text-xs text-gray-400 mt-0.5" x-text="'Update kurs: ' + currencyUpdatedAt"></p>
                        </div>
                    </div>

                    {{-- Input jumlah bayar --}}
                    <div>
                        <p class="text-xs text-gray-500 mb-1">
                            Jumlah Bayar (<span x-text="currencyCode"></span>):
                        </p>
                        <input type="number" x-model="amountPaid" @input="calculateChange()" step="0.01"
                            min="0" :placeholder="'Masukkan jumlah dalam ' + currencyCode"
                            class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 font-bold">
                    </div>

                    {{-- Kembalian --}}
                    <div x-show="amountPaid > 0 && changeAmount >= 0"
                        class="p-2.5 rounded-xl bg-green-50 border border-green-200 space-y-1">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Kembalian (<span x-text="currencyCode"></span>)</span>
                            <span class="font-bold text-green-600"
                                x-text="currencyCode === 'IDR' ? formatRupiah(changeAmount) : formatForeign(changeAmount) + ' ' + currencyCode">
                            </span>
                        </div>
                        <div x-show="currencyCode !== 'IDR'" class="flex justify-between text-xs">
                            <span class="text-gray-500">Kembalian (IDR)</span>
                            <span class="font-bold text-green-600" x-text="formatRupiah(changeAmountIDR)"></span>
                        </div>
                    </div>

                </div>

                {{-- Nomor HP untuk struk --}}
                <div class="mb-2">
                    <input type="text" x-model="customerPhone"
                        placeholder="No. HP customer (opsional, untuk struk WA)"
                        class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>

                {{-- Tombol Checkout --}}
                <button @click="processCheckout()" :disabled="cart.length === 0 || isProcessing"
                    :class="cart.length === 0 ? 'bg-gray-300 cursor-not-allowed' :
                        'bg-amber-600 hover:bg-amber-700 shadow-md hover:shadow-lg'"
                    class="w-full text-white font-bold py-3 rounded-xl transition text-sm">
                    <span x-show="!isProcessing">💳 Proses Pembayaran</span>
                    <span x-show="isProcessing">⏳ Memproses...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Modal Sukses --}}
    <div x-show="showSuccessModal" x-transition class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4 text-center">
            <div class="text-5xl mb-3">✅</div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Transaksi Berhasil!</h3>
            <p class="text-sm text-gray-500 mb-1" x-text="lastTransaction?.invoice_number"></p>
            <p class="text-2xl font-bold text-amber-600 mb-1" x-text="lastTransaction?.total_formatted"></p>
            <p x-show="lastTransaction?.change_amount > 0" class="text-sm text-green-600 mb-3">
                Kembalian: <span x-text="lastTransaction?.change_formatted"></span>
            </p>
            <p x-show="lastTransaction?.points_earned > 0" class="text-xs text-amber-500 mb-3">
                ⭐ +<span x-text="lastTransaction?.points_earned"></span> poin ditambahkan ke member
            </p>
            <div class="flex gap-2 mt-4">
                <a :href="'/kasir/receipt/' + lastTransaction?.id" target="_blank"
                    class="flex-1 py-2.5 rounded-xl bg-blue-50 text-blue-600 text-sm font-semibold hover:bg-blue-100 transition">
                    🧾 Lihat Struk
                </a>
                <button @click="resetPOS()"
                    class="flex-1 py-2.5 rounded-xl bg-amber-600 text-white text-sm font-semibold hover:bg-amber-700 transition">
                    🛒 Transaksi Baru
                </button>
            </div>
        </div>
    </div>

    <script>
        function posSystem() {
            return {
                // Products
                allProducts: @json(
                    $products->map(fn($p) => [
                            'id' => $p->id,
                            'name' => $p->name,
                            'sku' => $p->sku,
                            'price' => (float) $p->price,
                            'price_formatted' => $p->price_formatted,
                            'stock' => $p->stock,
                            'category' => $p->category,
                            'photo' => $p->primaryPhoto ? asset('storage/' . $p->primaryPhoto->photo_path) : null,
                        ])),
                filteredProducts: [],
                search: '',
                categoryFilter: '',

                // Cart
                cart: [],

                // Customer
                customerType: 'walk_in',
                customerTypes: [{
                        value: 'walk_in',
                        label: 'Walk-in',
                        icon: '🚶'
                    },
                    {
                        value: 'travel_agent',
                        label: 'Travel Agent',
                        icon: '🏨'
                    },
                    {
                        value: 'freelance_guide',
                        label: 'Freelance',
                        icon: '🧭'
                    },
                    {
                        value: 'member',
                        label: 'Member',
                        icon: '⭐'
                    },
                ],
                customerName: '',
                customerPhone: '',

                // Partner
                partnerSearch: '',
                partnerResults: [],
                selectedPartner: null,

                // Member
                memberSearch: '',
                memberResults: [],
                selectedMember: null,
                pointsToRedeem: 0,

                // Payment
                paymentMethod: 'cash',
                paymentMethods: [{
                        value: 'cash',
                        label: 'Cash',
                        icon: '💵'
                    },
                    {
                        value: 'qris',
                        label: 'QRIS',
                        icon: '📱'
                    },
                    {
                        value: 'card',
                        label: 'Kartu',
                        icon: '💳'
                    },
                ],
                currencyCode: 'IDR',
                currencyRate: 1,
                amountPaid: 0,
                changeAmount: 0,
                adminFee: 0,

                // Totals
                subtotal: 0,
                pointsDiscount: 0,
                total: 0,

                // State
                isProcessing: false,
                showSuccessModal: false,
                lastTransaction: null,

                // Init
                init() {
                    this.filteredProducts = this.allProducts;
                },

                // Filter produk
                filterProducts() {
                    this.filteredProducts = this.allProducts.filter(p => {
                        const matchSearch = !this.search ||
                            p.name.toLowerCase().includes(this.search.toLowerCase()) ||
                            p.sku.toLowerCase().includes(this.search.toLowerCase());
                        const matchCategory = !this.categoryFilter || p.category === this.categoryFilter;
                        return matchSearch && matchCategory;
                    });
                },

                // Tambah ke keranjang
                addToCart(product) {
                    const existing = this.cart.find(i => i.product_id === product.id);
                    if (existing) {
                        if (existing.quantity < product.stock) {
                            existing.quantity++;
                            this.calculateTotal();
                        } else {
                            alert('Stok tidak cukup!');
                        }
                        return;
                    }
                    this.cart.push({
                        product_id: product.id,
                        name: product.name,
                        sku: product.sku,
                        original_price: product.price,
                        final_price: product.price,
                        quantity: 1,
                        stock: product.stock,
                    });
                    this.calculateTotal();
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                    this.calculateTotal();
                },

                clearCart() {
                    if (confirm('Kosongkan keranjang?')) {
                        this.cart = [];
                        this.calculateTotal();
                    }
                },

                incrementQty(index) {
                    const item = this.cart[index];
                    if (item.quantity < item.stock) {
                        item.quantity++;
                        this.calculateTotal();
                    } else {
                        alert('Stok tidak cukup!');
                    }
                },

                decrementQty(index) {
                    if (this.cart[index].quantity > 1) {
                        this.cart[index].quantity--;
                        this.calculateTotal();
                    } else {
                        this.removeFromCart(index);
                    }
                },

                // Set customer type
                setCustomerType(type) {
                    this.customerType = type;
                    this.selectedPartner = null;
                    this.selectedMember = null;
                    this.partnerSearch = '';
                    this.memberSearch = '';
                    this.pointsToRedeem = 0;
                    this.calculateTotal();
                },

                // Search partner
                async searchPartner() {
                    if (this.partnerSearch.length < 2) {
                        this.partnerResults = [];
                        return;
                    }
                    const type = this.customerType === 'travel_agent' ? 'travel_agent' : 'freelance_guide';
                    const res = await fetch(`/kasir/search-partner?q=${this.partnerSearch}&type=${type}`);
                    this.partnerResults = await res.json();
                },

                selectPartner(partner) {
                    this.selectedPartner = partner;
                    this.partnerSearch = partner.name;
                    this.partnerResults = [];
                },

                // Search member
                async searchMember() {
                    if (this.memberSearch.length < 2) {
                        this.memberResults = [];
                        return;
                    }
                    const res = await fetch(`/kasir/search-member?q=${this.memberSearch}`);
                    this.memberResults = await res.json();
                },

                selectMember(member) {
                    this.selectedMember = member;
                    this.memberSearch = member.name;
                    this.memberResults = [];
                    this.pointsToRedeem = 0;
                },

                // Payment
                setPaymentMethod(method) {
                    this.paymentMethod = method;
                    if (method !== 'cash') {
                        this.currencyCode = 'IDR';
                        this.currencyRate = 1;
                        this.adminFee = 0;
                        this.amountPaid = this.total;
                        this.changeAmount = 0;
                    }
                    this.calculateTotal();
                },

                async setCurrency() {
                    if (this.currencyCode === 'IDR') {
                        this.currencyRate = 1;
                        this.adminFee = 0;
                    } else {
                        this.adminFee = 1000;
                        // Fetch kurs (pakai rate statis dulu)
                        const rates = {
                            USD: 16000,
                            EUR: 17500,
                            SGD: 12000,
                            AUD: 10500,
                            GBP: 20000,
                            JPY: 110,
                            MYR: 3500

                        };
                        this.currencyRate = rates[this.currencyCode] || 15000;
                    }
                    this.calculateTotal();
                },

                calculateTotal() {
                    this.subtotal = this.cart.reduce((sum, item) => sum + (item.final_price * item.quantity), 0);
                    this.pointsDiscount = Math.min(this.pointsToRedeem * 100, this.subtotal);
                    this.total = this.subtotal - this.pointsDiscount + this.adminFee;
                    this.calculateChange();
                },

                calculateChange() {
                    if (this.paymentMethod === 'cash') {
                        const paidInIDR = this.amountPaid * this.currencyRate;
                        this.changeAmount = Math.max(0, paidInIDR - this.total);
                    }
                },

                // Format
                formatRupiah(amount) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(amount));
                },

                formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(num);
                },

                // Checkout
                async processCheckout() {
                    if (this.cart.length === 0) return;

                    if (this.paymentMethod === 'cash' && this.amountPaid <= 0) {
                        alert('Masukkan jumlah uang yang dibayar!');
                        return;
                    }

                    const paidInIDR = this.paymentMethod === 'cash' ?
                        this.amountPaid * this.currencyRate :
                        this.total;

                    if (this.paymentMethod === 'cash' && paidInIDR < this.total) {
                        alert('Uang yang dibayar kurang!');
                        return;
                    }

                    this.isProcessing = true;

                    const payload = {
                        items: this.cart.map(item => ({
                            product_id: item.product_id,
                            quantity: item.quantity,
                            final_price: item.final_price,
                        })),
                        customer_type: this.customerType,
                        customer_name: this.customerName || null,
                        partner_id: this.selectedPartner?.id || null,
                        member_id: this.selectedMember?.id || null,
                        points_redeemed: this.pointsToRedeem,
                        payment_method: this.paymentMethod,
                        currency_code: this.currencyCode,
                        currency_rate: this.currencyRate,
                        amount_paid: paidInIDR,
                        customer_phone: this.customerPhone || null,
                        _token: '{{ csrf_token() }}',
                    };

                    try {
                        const res = await fetch('/kasir/checkout', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(payload),
                        });

                        const data = await res.json();

                        if (data.success) {
                            this.lastTransaction = data.transaction;
                            this.showSuccessModal = true;
                        } else {
                            alert('Error: ' + data.message);
                        }
                    } catch (e) {
                        alert('Terjadi kesalahan. Coba lagi.');
                    } finally {
                        this.isProcessing = false;
                    }
                },

                // Reset POS
                resetPOS() {
                    this.cart = [];
                    this.customerType = 'walk_in';
                    this.selectedPartner = null;
                    this.selectedMember = null;
                    this.partnerSearch = '';
                    this.memberSearch = '';
                    this.pointsToRedeem = 0;
                    this.paymentMethod = 'cash';
                    this.currencyCode = 'IDR';
                    this.currencyRate = 1;
                    this.adminFee = 0;
                    this.amountPaid = 0;
                    this.changeAmount = 0;
                    this.subtotal = 0;
                    this.total = 0;
                    this.pointsDiscount = 0;
                    this.customerPhone = '';
                    this.showSuccessModal = false;
                    this.lastTransaction = null;
                },
            }
        }
    </script>
@endsection
