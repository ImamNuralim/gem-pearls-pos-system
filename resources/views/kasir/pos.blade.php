@extends('layouts.pos')
@section('title', 'Kasir')
@section('subtitle', 'Point of Sale')
@section('container_class', 'p-4')

@section('content')

<style>
    * { font-family: 'Poppins', sans-serif; }

    .card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }

    .input-pos {
        width: 100%;
        padding: 8px 14px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        font-size: 12px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: #f8fafc;
        color: #1e293b;
        font-family: 'Poppins', sans-serif;
    }

    .input-pos:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        background: #fff;
    }

    .section-label {
        font-size: 10px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 8px;
    }

    .product-card {
        border: 1.5px solid #f1f5f9;
        border-radius: 12px;
        padding: 10px;
        cursor: pointer;
        transition: all 0.15s;
    }

    .product-card:hover {
        border-color: #3b82f6;
        background: #eff6ff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59,130,246,0.1);
    }

    .type-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 10px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.15s;
        background: #fff;
        font-family: 'Poppins', sans-serif;
    }

    .type-btn.active {
        border-color: #3b82f6;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .type-btn:hover:not(.active) {
        border-color: #bfdbfe;
        background: #f8fafc;
    }

    .pay-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
        padding: 8px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.15s;
        background: #fff;
        font-family: 'Poppins', sans-serif;
    }

    .pay-btn.active {
        border-color: #3b82f6;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .pay-btn:hover:not(.active) {
        border-color: #06087d80;
    }

    .cart-item {
        border: 1.5px solid #f1f5f9;
        border-radius: 12px;
        padding: 10px;
        transition: border-color 0.15s;
    }

    .cart-item:hover {
        border-color: #bfdbfe;
    }

    .btn-checkout {
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-checkout.active {
        background: linear-gradient(135deg, #1d3dca, #0521a1);
        color: #fff;
        box-shadow: 0 4px 12px rgba(25, 0, 117, 0.3);
    }

    .btn-checkout.active:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(16, 0, 97, 0.425);
    }

    .btn-checkout.disabled {
        background: #e2e8f0;
        color: #94a3b8;
        cursor: not-allowed;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        padding: 2px 0;
    }

    .badge-blue {
        padding: 2px 8px;
        border-radius: 20px;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 10px;
        font-weight: 700;
    }

    .partner-result-item {
        padding: 8px 12px;
        font-size: 11px;
        cursor: pointer;
        border-bottom: 1px solid #f8fafc;
        transition: background 0.1s;
    }

    .partner-result-item:hover { background: #eff6ff; }
</style>

<div x-data="posSystem()" x-init="init()" class="grid grid-cols-12 gap-4" style="min-height: calc(100vh - 80px)">

    {{-- KIRI: Produk --}}
    <div class="col-span-7 flex flex-col gap-3">

        {{-- Search --}}
        <div class="card p-3">
            <div class="flex gap-2">
                <div class="flex-1 relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                    </span>
                    <input type="text" x-model="search" @input.debounce.300ms="filterProducts()"
                        placeholder="Cari produk atau scan SKU..."
                        class="input-pos" style="padding-left: 36px;">
                </div>
                <select x-model="categoryFilter" @change="filterProducts()" class="input-pos" style="width:auto; padding: 8px 12px;">
                    <option value="">Semua</option>
                    <option value="perhiasan">Perhiasan</option>
                    <option value="oleh-oleh">Oleh-oleh</option>
                </select>
            </div>
        </div>

        {{-- Grid Produk --}}
        <div class="card p-3 overflow-y-auto" style="max-height: calc(100vh - 200px)">
            <div class="grid grid-cols-3 gap-2">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div @click="addToCart(product)" class="product-card">
                        <div class="w-full bg-slate-50 rounded-lg mb-2 flex items-center justify-center overflow-hidden">
                            <template x-if="product.photo">
                                <img :src="product.photo" class="aspect-square w-full h-full object-cover rounded-lg">
                            </template>
                            <template x-if="!product.photo">
                                <span class="text-3xl" x-text="product.category === 'perhiasan' ? '💍' : '🎁'"></span>
                            </template>
                        </div>
                        <p class="text-xs font-semibold text-slate-700 truncate" x-text="product.name"></p>
                        <p class="text-xs text-slate-400 truncate" x-text="product.sku"></p>
                        <p class="text-sm font-bold text-blue-600 mt-1" x-text="product.price_formatted"></p>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-xs text-slate-400">Stok: <span class="font-semibold text-slate-600" x-text="product.stock"></span></span>
                        </div>
                    </div>
                </template>

                <template x-if="filteredProducts.length === 0">
                    <div class="col-span-3 text-center py-12 text-slate-300">
                        <div class="text-4xl mb-2">📦</div>
                        <p class="text-sm">Produk tidak ditemukan</p>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- KANAN: Keranjang & Checkout --}}
    <div class="col-span-5 flex flex-col gap-3">

        {{-- Customer Type --}}
        <div class="card p-3">
            <p class="section-label">Tipe Customer</p>
            <div class="grid grid-cols-2 gap-2">
                <template x-for="type in customerTypes" :key="type.value">
                    <button @click="setCustomerType(type.value)"
                        :class="customerType === type.value ? 'active' : ''"
                        class="type-btn">
                        <template x-if="type.icon === 'user'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                            </svg>
                        </template>
                        <template x-if="type.icon === 'building'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                            </svg>
                        </template>
                        <template x-if="type.icon === 'map'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.159.69.159 1.006 0Z"/>
                            </svg>
                        </template>
                        <template x-if="type.icon === 'star'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.601a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/>
                            </svg>
                        </template>
                        <span x-text="type.label"></span>
                    </button>
                </template>
            </div>

            {{-- Partner Search --}}
           {{-- Today Visits --}}
<div x-show="customerType === 'travel_agent' || customerType === 'freelance_guide' || customerType === 'walk_in'" x-transition class="mt-3">

    <p class="section-label">Kunjungan Hari Ini</p>

    <div x-show="partnerVisits.length === 0"
        class="text-xs text-slate-400 text-center py-3 bg-slate-50 rounded-xl">
        Belum ada kunjungan hari ini
    </div>

    <div x-show="partnerVisits.length > 0" class="space-y-2">
        <template x-for="visit in partnerVisits" :key="visit.id">
            <div @click="selectVisit(visit)"
                :class="selectedVisit === visit.id ? 'border-blue-400 bg-blue-50' : 'border-gray-100 hover:border-blue-200 hover:bg-blue-50/50'"
                class="px-3 py-2.5 rounded-xl border cursor-pointer transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-blue-600" x-text="visit.visit_code"></span>
                    <span x-show="selectedVisit === visit.id" class="text-xs text-blue-500">✓ Dipilih</span>
                </div>
                <div class="text-xs font-semibold text-slate-700 mt-0.5" x-text="visit.partner?.name ?? '—'"></div>
                <div class="text-xs text-slate-400" x-text="visit.group_description ?? 'Tanpa deskripsi'"></div>
<div x-show="visit.visit_type === 'walk_in'" class="text-xs text-slate-500 mt-0.5">
    <span x-text="visit.vehicle_notes ?? ''"></span>
    <span x-show="visit.vehicle_description" x-text="' · ' + visit.vehicle_description"></span>
</div>
            </div>
        </template>
    </div>

</div>

            {{-- Member Search --}}
            <div x-show="customerType === 'member'" x-transition class="mt-3">
                <div class="relative">
                    <button @click="showAddMember = true" class="text-xs text-blue-500 hover:text-blue-700 mb-1.5 font-semibold">
                        + Tambah Member Baru
                    </button>
                    <input type="text" x-model="memberSearch" @input.debounce.300ms="searchMember()"
                        placeholder="Cari nama atau no. HP member..."
                        class="input-pos">
                    <div x-show="memberResults.length > 0"
                        class="absolute z-10 w-full bg-white border border-blue-100 rounded-xl shadow-lg mt-1 max-h-36 overflow-y-auto">
                        <template x-for="member in memberResults" :key="member.id">
                            <div @click="selectMember(member)" class="partner-result-item">
                                <span class="font-semibold text-slate-700" x-text="member.name"></span>
                                <span class="text-slate-400 ml-1" x-text="member.phone"></span>
                                <span class="text-blue-600 ml-1" x-text="'⭐ ' + member.points_balance + ' poin'"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <div x-show="selectedMember" class="mt-2 px-3 py-2 bg-blue-50 rounded-xl border border-blue-100 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-blue-700 font-semibold">⭐ <span x-text="selectedMember?.name"></span></span>
                        <button @click="selectedMember = null; memberSearch = ''; pointsToRedeem = 0"
                            class="text-red-400 hover:text-red-600">✕</button>
                    </div>
                    <div class="mt-1.5 flex items-center gap-2">
                        <span class="text-slate-500">Poin: <span x-text="selectedMember?.points_balance" class="font-bold text-blue-600"></span></span>
                        <input type="number" x-model="pointsToRedeem" :max="selectedMember?.points_balance"
                            @input="calculateTotal()" min="0" placeholder="Redeem poin"
                            @focus="$event.target.select()"
                            class="input-pos flex-1" style="padding: 6px 10px;">
                    </div>
                </div>
            </div>
        </div>

        {{-- Keranjang --}}
        <div class="card p-3 flex-1 overflow-y-auto" style="max-height: 320px">
            <div class="flex items-center justify-between mb-2">
                <p class="section-label" style="margin-bottom:0">Keranjang</p>
                <div class="flex items-center gap-2">
                    <span class="badge-blue" x-text="cart.length + ' item'"></span>
                    <button x-show="cart.length > 0" @click="clearCart()"
                        class="text-xs text-red-400 hover:text-red-600 transition font-semibold">🗑 Kosongkan</button>
                </div>
            </div>

            <div x-show="cart.length === 0" class="flex flex-col items-center justify-center h-20 text-slate-300">
                <span class="text-2xl mb-1">🛒</span>
                <p class="text-xs">Keranjang kosong</p>
            </div>

            <div class="space-y-2 mt-2">
                <template x-for="(item, index) in cart" :key="item.product_id">
                    <div class="cart-item">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-slate-700 truncate" x-text="item.name"></p>
                                <p class="text-xs text-slate-400" x-text="item.sku"></p>
                            </div>
                            <button @click="removeFromCart(index)" class="text-red-400 hover:text-red-600 text-xs flex-shrink-0">✕</button>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="flex items-center gap-1">
                                <template x-if="item.final_price != item.original_price">
                                    <span class="text-xs text-slate-300 line-through" x-text="formatRupiah(item.original_price)"></span>
                                </template>
                                <input type="number" x-model="item.final_price" @input="calculateTotal()"
                                    class="input-pos font-bold text-blue-600" style="width:110px; padding: 5px 8px; font-size:12px;">
                            </div>
                            <div class="flex items-center gap-1">
                                <button @click="decrementQty(index)"
                                    class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 text-xs font-bold transition flex items-center justify-center">−</button>
                                <span class="text-xs font-bold text-slate-700 w-6 text-center" x-text="item.quantity"></span>
                                <button @click="incrementQty(index)"
                                    class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 text-xs font-bold transition flex items-center justify-center">+</button>
                            </div>
                        </div>
                        <div class="mt-1 text-right">
                            <span class="text-xs font-bold text-slate-700" x-text="formatRupiah(item.final_price * item.quantity)"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Ringkasan & Bayar --}}
<div class="card p-3 flex-shrink-0">

    {{-- Summary --}}
    <div class="space-y-1 mb-3">
        <div class="summary-row text-slate-500">
            <span>Subtotal</span>
            <span x-text="formatRupiah(subtotal)"></span>
        </div>
        <div x-show="pointsDiscount > 0" class="summary-row text-emerald-500">
            <span>Diskon Poin (<span x-text="pointsToRedeem"></span> poin)</span>
            <span x-text="'- ' + formatRupiah(pointsDiscount)"></span>
        </div>
        <div x-show="adminFee > 0" class="summary-row text-orange-500">
            <span>Admin Fee</span>
            <span x-text="formatRupiah(adminFee)"></span>
        </div>
        <div class="summary-row font-bold text-slate-800 pt-1.5" style="border-top: 1.5px solid #f1f5f9; font-size:13px;">
            <span>Total</span>
            <span class="text-blue-600 text-base" x-text="formatRupiah(total)"></span>
        </div>
    </div>

    {{-- Metode Bayar --}}
    <div class="grid grid-cols-3 gap-1.5 mb-3">
        <template x-for="method in paymentMethods" :key="method.value">
            <button @click="setPaymentMethod(method.value)"
                :class="paymentMethod === method.value ? 'active' : ''"
                class="pay-btn">
                <template x-if="method.icon === 'banknotes'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
                    </svg>
                </template>
                <template x-if="method.icon === 'qrcode'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z"/>
                    </svg>
                </template>
                <template x-if="method.icon === 'creditcard'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 21Z"/>
                    </svg>
                </template>
                <span x-text="method.label"></span>
            </button>
        </template>
    </div>

    {{-- Cash: mata uang --}}
    <div x-show="paymentMethod === 'cash'" x-transition class="mb-3 space-y-2">

        <div class="flex gap-2 items-center">
            <p class="section-label" style="margin-bottom:0; white-space:nowrap;">Mata Uang</p>
            <select x-model="currencyCode" @change="setCurrency()" class="input-pos flex-1">
                <option value="IDR">🇮🇩 IDR</option>
                <option x-show="currencyRates.USD" value="USD">🇺🇸 USD</option>
                <option x-show="currencyRates.EUR" value="EUR">🇪🇺 EUR</option>
                <option x-show="currencyRates.SGD" value="SGD">🇸🇬 SGD</option>
                <option x-show="currencyRates.AUD" value="AUD">🇦🇺 AUD</option>
                <option x-show="currencyRates.GBP" value="GBP">🇬🇧 GBP</option>
                <option x-show="currencyRates.JPY" value="JPY">🇯🇵 JPY</option>
                <option x-show="currencyRates.MYR" value="MYR">🇲🇾 MYR</option>
                <option x-show="currencyRates.CNY" value="CNY">🇨🇳 CNY</option>
                <option x-show="currencyRates.SAR" value="SAR">🇸🇦 SAR</option>
            </select>
            <button @click="fetchCurrencyRates(); setCurrency()"
                class="px-3 py-2 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 transition flex-shrink-0">
                <span :class="isFetchingRates ? 'animate-spin inline-block' : ''">⟳</span>
            </button>
        </div>

        <div class="p-3 rounded-xl bg-blue-50 border border-blue-100 space-y-1">
            <p class="section-label">Total Tagihan</p>
            <p class="text-sm font-bold text-blue-700" x-text="formatRupiah(total)"></p>
            <div x-show="currencyCode !== 'IDR'">
                <p class="text-sm font-bold text-blue-600 mt-1">
                    <span x-text="formatForeign(totalInForeign)"></span> <span x-text="currencyCode"></span>
                </p>
                <p class="text-xs text-slate-400 mt-0.5" x-text="'Kurs: 1 ' + currencyCode + ' = Rp ' + formatNumber(currencyRate)"></p>
                <p class="text-xs text-orange-500">+ Admin fee Rp 1.000</p>
                <p class="text-xs text-slate-400" x-text="'Update kurs: ' + currencyUpdatedAt"></p>
            </div>
        </div>

    </div>

    {{-- Jumlah Bayar --}}
    <div class="mb-3">
        <p class="section-label mb-1.5">Jumlah Bayar (<span x-text="currencyCode"></span>)</p>
        <input type="number" x-model="amountPaid" @input="calculateChange()"
            @focus="$event.target.select()" :placeholder="'Masukkan jumlah dalam ' + currencyCode"
            class="input-pos font-bold" style="font-size:13px;" step="0.01" min="0">

        <div x-show="amountPaid > 0"
            class="mt-2 p-2.5 rounded-xl bg-emerald-50 border border-emerald-100 space-y-1">
            <div class="summary-row">
                <span class="text-slate-500">Kembalian (<span x-text="currencyCode"></span>)</span>
                <span class="font-bold text-emerald-600"
                    x-text="currencyCode === 'IDR' ? formatRupiah(changeAmount) : formatForeign(changeAmount) + ' ' + currencyCode">
                </span>
            </div>
            <div x-show="currencyCode !== 'IDR'" class="summary-row">
                <span class="text-slate-500">Kembalian (IDR)</span>
                <span class="font-bold text-emerald-600" x-text="formatRupiah(changeAmountIDR)"></span>
            </div>
        </div>
    </div>

    {{-- No HP --}}
    <div class="mb-3">
        <p class="section-label mb-1.5">No. WhatsApp Customer</p>
        <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-emerald-500">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd"/>
                </svg>
            </span>
            <input type="text" x-model="customerPhone"
                placeholder="08xxxxxxxxxx (opsional)"
                class="input-pos" style="padding-left: 36px;">
        </div>
        <p class="text-xs text-slate-400 mt-1">Struk akan dikirim otomatis via WhatsApp</p>
    </div>

    {{-- Tombol Checkout --}}
    <button @click="processCheckout()" :disabled="cart.length === 0 || isProcessing"
        :class="cart.length === 0 ? 'disabled' : 'active'"
        class="btn-checkout">
        <span x-show="!isProcessing" class="flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 21Z"/>
            </svg>
            Proses Pembayaran
        </span>
        <span x-show="isProcessing" class="flex items-center justify-center gap-2">
            <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 22 6.373 22 12h-4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
        </span>
    </button>

</div>

        {{-- Modal Sukses --}}
        <div x-show="showSuccessModal" x-cloak x-transition
            class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4 text-center">
                <div class="flex justify-center mb-3">
                    <div class="w-14 h-14 flex items-center justify-center rounded-full bg-emerald-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-emerald-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Transaksi Berhasil!</h3>
                <p class="text-sm text-slate-500 mb-1" x-text="lastTransaction?.invoice_number"></p>
                <p class="text-2xl font-bold text-blue-600 mb-1" x-text="lastTransaction?.total_formatted"></p>
                <p x-show="lastTransaction?.change_amount > 0" class="text-sm text-emerald-600 mb-3">
                    Kembalian: <span x-text="lastTransaction?.change_formatted"></span>
                </p>
                <p x-show="lastTransaction?.points_earned > 0" class="text-xs text-blue-500 mb-3">
                    ⭐ +<span x-text="lastTransaction?.points_earned"></span> poin ditambahkan ke member
                </p>
                <div class="flex gap-2 mt-4">
                    <a :href="'/kasir/receipt/' + lastTransaction?.id" target="_blank"
                        class="flex items-center justify-center gap-2 flex-1 py-2.5 rounded-xl bg-blue-50 text-blue-600 text-sm font-semibold hover:bg-blue-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375H8.25m0 0L12 3.75m-3.75 4.5L12 12m-3.75-3.75H4.875A2.625 2.625 0 0 0 2.25 10.875v6.375A2.625 2.625 0 0 0 4.875 19.875h14.25A2.625 2.625 0 0 0 21.75 17.25v-3"/>
                        </svg>
                        <span>Lihat Struk</span>
                    </a>
                    <button @click="resetPOS()"
                        class="flex items-center justify-center gap-2 flex-1 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                        </svg>
                        <span>Transaksi Baru</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Modal Tambah Member --}}
        <div x-show="showAddMember" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-[9999]">
            <div class="bg-white p-5 rounded-2xl w-80 shadow-xl">
                <h3 class="font-bold text-slate-800 mb-4">Tambah Member</h3>
                <input type="text" x-model="newMemberName" placeholder="Nama" class="input-pos mb-2">
                <input type="text" x-model="newMemberPhone" placeholder="No. WhatsApp" class="input-pos mb-3">
                <div class="flex gap-2">
                    <button @click="createMember()" class="flex-1 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">Simpan</button>
                    <button @click="showAddMember = false" class="flex-1 py-2.5 rounded-xl bg-slate-100 text-slate-600 text-sm font-semibold hover:bg-slate-200 transition">Batal</button>
                </div>
            </div>
        </div>

        {{-- Modal Member Success --}}
        <div x-show="showMemberSuccess" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-[9999]">
            <div class="bg-white p-5 rounded-2xl w-72 text-center shadow-xl">
                <div class="text-3xl mb-2">✅</div>
                <h3 class="font-bold text-emerald-600 mb-2">Member berhasil ditambahkan</h3>
                <button @click="showMemberSuccess = false" class="mt-3 px-6 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">OK</button>
            </div>
        </div>

    </div>

    <script>
        function posSystem() {
            return {
                showMemberSuccess: false,
                showAddMember: false,
                newMemberName: '',
                newMemberPhone: '',
                allProducts: {!! $productsJson !!},
                filteredProducts: [],
                search: '',
                categoryFilter: '',
                cart: [],
                customerType: 'walk_in',
                customerTypes: [
                    { value: 'walk_in', label: 'Walk-in', icon: 'user' },
                    { value: 'travel_agent', label: 'Travel Agent', icon: 'building' },
                    { value: 'freelance_guide', label: 'Freelance', icon: 'map' },
                    { value: 'member', label: 'Member', icon: 'star' },
                ],
                customerName: '',
                customerPhone: '',
                partnerSearch: '',
                partnerResults: [],
                selectedPartner: null,
                partnerVisits: [],
                selectedVisit: null,
                memberSearch: '',
                memberResults: [],
                selectedMember: null,
                pointsToRedeem: 0,
                paymentMethod: 'cash',
                paymentMethods: [
                    { value: 'cash', label: 'Cash', icon: 'banknotes' },
                    { value: 'qris', label: 'QRIS', icon: 'qrcode' },
                    { value: 'card', label: 'Kartu', icon: 'creditcard' },
                ],
                currencyCode: 'IDR',
                currencyRate: 1,
                currencyRates: {},
                currencyUpdatedAt: '',
                isFetchingRates: false,
                amountPaid: 0,
                changeAmount: 0,
                adminFee: 0,
                subtotal: 0,
                pointsDiscount: 0,
                total: 0,
                totalInForeign: 0,
                changeAmountIDR: 0,
                isProcessing: false,
                showSuccessModal: false,
                lastTransaction: null,

                init() {
                    this.filteredProducts = this.allProducts;
                    this.showSuccessModal = false;
                    this.lastTransaction = null;
                    this.fetchCurrencyRates();
                },

                async fetchCurrencyRates() {
                    this.isFetchingRates = true;
                    try {
                        const res = await fetch('/api/currency-rates');
                        const data = await res.json();
                        this.currencyRates = data.rates;
                        this.currencyUpdatedAt = data.updated_at;
                        if (this.currencyCode !== 'IDR' && this.currencyRates[this.currencyCode]) {
                            this.currencyRate = this.currencyRates[this.currencyCode];
                            this.calculateTotal();
                        }
                    } catch (e) {
                        console.error('Gagal fetch kurs:', e);
                    } finally {
                        this.isFetchingRates = false;
                    }
                },

                filterProducts() {
                    this.filteredProducts = this.allProducts.filter(p => {
                        const matchSearch = !this.search ||
                            p.name.toLowerCase().includes(this.search.toLowerCase()) ||
                            p.sku.toLowerCase().includes(this.search.toLowerCase());
                        const matchCategory = !this.categoryFilter || p.category === this.categoryFilter;
                        return matchSearch && matchCategory;
                    });
                },

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

                removeFromCart(index) { this.cart.splice(index, 1); this.calculateTotal(); },
                clearCart() { if (confirm('Kosongkan keranjang?')) { this.cart = []; this.calculateTotal(); } },
                incrementQty(index) {
                    const item = this.cart[index];
                    if (item.quantity < item.stock) { item.quantity++; this.calculateTotal(); }
                    else { alert('Stok tidak cukup!'); }
                },
                decrementQty(index) {
                    if (this.cart[index].quantity > 1) { this.cart[index].quantity--; this.calculateTotal(); }
                    else { this.removeFromCart(index); }
                },

                setCustomerType(type) {
    this.customerType = type;
    this.selectedPartner = null;
    this.selectedMember = null;
    this.partnerSearch = '';
    this.memberSearch = '';
    this.pointsToRedeem = 0;
    this.partnerVisits = [];
    this.selectedVisit = null;
    this.calculateTotal();
    if (type === 'travel_agent' || type === 'freelance_guide') {
        this.fetchTodayVisits(type);
    } else if (type === 'walk_in') {
        this.fetchTodayVisits('walk_in');
    }
},

                async searchPartner() {
                    if (this.partnerSearch.length < 2) { this.partnerResults = []; return; }
                    const type = this.customerType === 'travel_agent' ? 'travel_agent' : 'freelance_guide';
                    const res = await fetch(`/kasir/search-partner?q=${this.partnerSearch}&type=${type}`);
                    this.partnerResults = await res.json();
                },

                async fetchTodayVisits(type) {
    try {
        const res = await fetch(`/api/today-visits?type=${type}`);
        this.partnerVisits = await res.json();
    } catch (e) { console.error(e); }
},

                selectPartner(partner) {
                    this.selectedPartner = partner;
                    this.partnerSearch = partner.name;
                    this.partnerResults = [];
                    this.fetchPartnerVisits(partner.id);
                },
                selectVisit(visit) {
    this.selectedVisit = visit.id;
    this.selectedPartner = visit.partner ?? { id: null, name: visit.group_description };
},

                async searchMember() {
                    if (this.memberSearch.length < 2) { this.memberResults = []; return; }
                    const res = await fetch(`/kasir/search-member?q=${this.memberSearch}`);
                    this.memberResults = await res.json();
                },

                selectMember(member) {
                    this.selectedMember = member;
                    this.memberSearch = member.name;
                    this.memberResults = [];
                    this.pointsToRedeem = 0;
                },

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
                    if (this.currencyCode === 'IDR') { this.currencyRate = 1; this.adminFee = 0; }
                    else { this.adminFee = 1000; this.currencyRate = this.currencyRates[this.currencyCode] || 15000; }
                    this.amountPaid = 0; this.changeAmount = 0; this.changeAmountIDR = 0;
                    this.calculateTotal();
                },

                calculateTotal() {
                    this.subtotal = this.cart.reduce((sum, item) => sum + (item.final_price * item.quantity), 0);
                    this.pointsDiscount = Math.min(this.pointsToRedeem * 100, this.subtotal);
                    this.total = this.subtotal - this.pointsDiscount + this.adminFee;
                    if (this.currencyCode !== 'IDR' && this.currencyRate > 0) {
                        this.totalInForeign = parseFloat((this.total / this.currencyRate).toFixed(2));
                    } else {
                        this.totalInForeign = this.total;
                    }
                    this.calculateChange();
                },

                calculateChange() {
                    if (this.amountPaid > 0) {
                        if (this.currencyCode === 'IDR') {
                            this.changeAmount = Math.max(0, this.amountPaid - this.total);
                            this.changeAmountIDR = this.changeAmount;
                        } else {
                            this.changeAmount = Math.max(0, parseFloat((this.amountPaid - this.totalInForeign).toFixed(2)));
                            this.changeAmountIDR = Math.max(0, Math.round(this.changeAmount * this.currencyRate));
                        }
                    } else {
                        this.changeAmount = 0; this.changeAmountIDR = 0;
                    }
                },

                formatRupiah(amount) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(amount)); },
                formatNumber(num) { return new Intl.NumberFormat('id-ID').format(num); },
                formatForeign(amount) {
                    return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(amount);
                },

                async processCheckout() {
                    if (this.cart.length === 0) return;
                    if (this.paymentMethod === 'cash' && this.amountPaid <= 0) { alert('Masukkan jumlah uang yang dibayar!'); return; }
                    const paidInIDR = this.paymentMethod === 'cash' ? this.amountPaid * this.currencyRate : this.total;
                    if (this.paymentMethod === 'cash' && paidInIDR < this.total) { alert('Uang yang dibayar kurang!'); return; }

                    this.isProcessing = true;
                    const payload = {
                        items: this.cart.map(item => ({ product_id: item.product_id, quantity: item.quantity, final_price: item.final_price })),
                        customer_type: this.customerType,
                        customer_name: this.customerName || null,
                        partner_id: this.selectedPartner?.id || null,
                        partner_visit_id: this.selectedVisit || null,
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
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(payload),
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.lastTransaction = data.transaction;
                            this.$nextTick(() => { this.showSuccessModal = true; });
                        } else {
                            alert('Error: ' + data.message);
                        }
                    } catch (e) {
                        alert('Terjadi kesalahan. Coba lagi.');
                    } finally {
                        this.isProcessing = false;
                    }
                },

                async createMember() {
                    if (!this.newMemberName || !this.newMemberPhone) { alert('Nama & No WA wajib diisi'); return; }
                    try {
                        const res = await fetch('/kasir/create-member', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ name: this.newMemberName, phone: this.newMemberPhone, _token: '{{ csrf_token() }}' })
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.selectedMember = data.member;
                            this.newMemberName = ''; this.newMemberPhone = '';
                            this.showAddMember = false; this.showMemberSuccess = true;
                        } else { alert('Gagal tambah member'); }
                    } catch (e) { alert('Error'); }
                },

                resetPOS() {
                    this.cart = []; this.customerType = 'walk_in';
                    this.selectedPartner = null; this.selectedMember = null;
                    this.partnerSearch = ''; this.memberSearch = '';
                    this.pointsToRedeem = 0; this.paymentMethod = 'cash';
                    this.currencyCode = 'IDR'; this.currencyRate = 1;
                    this.adminFee = 0; this.amountPaid = 0; this.changeAmount = 0;
                    this.subtotal = 0; this.total = 0; this.pointsDiscount = 0;
                    this.customerPhone = ''; this.showSuccessModal = false;
                    this.lastTransaction = null; this.partnerVisits = []; this.selectedVisit = null;

                    fetch('/kasir/search-product?q=')
        .then(r => r.json())
        .then(products => {
            this.allProducts = products;
            this.filteredProducts = products;
        });
                },
            }
        }
    </script>
</div>

@endsection
