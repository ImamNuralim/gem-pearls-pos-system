@extends('layouts.pos')
@section('title', 'Data Tamu')
@section('subtitle', 'Security — Input Tamu')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Input Data Tamu</h2>
            <p class="text-sm text-gray-400 mt-0.5">Catat setiap tamu yang masuk ke toko</p>
        </div>
        <button class="bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition shadow-sm">
            + Tamu Baru
        </button>
    </div>

    {{-- Form Input --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-sm font-semibold text-gray-600 mb-4 uppercase tracking-wider">Form Data Tamu</h3>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Nama Tamu</label>
                <input type="text" placeholder="Masukkan nama tamu"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Asal</label>
                <input type="text" placeholder="Kota / Negara asal"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Mitra / Travel Agent</label>
                <select class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm text-gray-600">
                    <option value="">-- Tanpa Mitra (Walk-in) --</option>
                    <option>Lombok Tour Agency</option>
                    <option>Freelance: Budi Guide</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Tujuan Kunjungan</label>
                <input type="text" placeholder="Belanja, lihat-lihat, dll"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Jam Masuk</label>
                <input type="time"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Jam Keluar</label>
                <input type="time"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-600 mb-1.5">Catatan</label>
            <textarea rows="2" placeholder="Catatan tambahan (opsional)"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm resize-none"></textarea>
        </div>

        <div class="mt-4 flex justify-end">
            <button class="bg-amber-600 hover:bg-amber-700 text-white font-semibold px-6 py-2.5 rounded-xl transition shadow-sm text-sm">
                💾 Simpan Data Tamu
            </button>
        </div>
    </div>

    {{-- Tabel Data Tamu Hari Ini --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Tamu Hari Ini</h3>
            <span class="text-xs bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full">0 tamu</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left py-3 px-2 text-xs font-semibold text-gray-400 uppercase">Nama</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-gray-400 uppercase">Asal</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-gray-400 uppercase">Mitra</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-gray-400 uppercase">Masuk</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-gray-400 uppercase">Keluar</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-gray-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-300">
                            <div class="text-3xl mb-2">📋</div>
                            <p>Belum ada tamu hari ini</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
