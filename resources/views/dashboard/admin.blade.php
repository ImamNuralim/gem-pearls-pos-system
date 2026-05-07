@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Penjualan Hari Ini</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">Rp 0</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-2xl">💰</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">0</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-2xl">🧾</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Produk Aktif</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">0</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-2xl">📦</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Tamu Hari Ini</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">0</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-2xl">👥</div>
        </div>
    </div>

</div>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <p class="text-gray-400 text-sm text-center py-8">Data akan muncul setelah transaksi pertama 🚀</p>
</div>
@endsection
