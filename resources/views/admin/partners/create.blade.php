@extends('layouts.app')
@section('title', 'Tambah Mitra')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.partners.index') }}" class="text-gray-400 hover:text-gray-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-gray-800">Tambah Mitra Baru</h2>
            <p class="text-sm text-gray-400">Kode mitra akan digenerate otomatis</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.partners.store') }}">
        @csrf
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Informasi Mitra</h3>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Tipe Mitra <span class="text-red-400">*</span></label>
                <div class="grid grid-cols-2 gap-3" x-data="{ type: '{{ old('type', '') }}' }">
                    <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition"
                        :class="type === 'travel_agent' ? 'border-amber-400 bg-amber-50' : 'border-gray-200 hover:border-amber-200'">
                        <input type="radio" name="type" value="travel_agent" x-model="type" class="hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-500"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Travel Agent</p>
                            <p class="text-xs text-gray-400">Agen perjalanan</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition"
                        :class="type === 'freelance_guide' ? 'border-amber-400 bg-amber-50' : 'border-gray-200 hover:border-amber-200'">
                        <input type="radio" name="type" value="freelance_guide" x-model="type" class="hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.159.69.159 1.006 0Z" /></svg>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Freelance Guide</p>
                            <p class="text-xs text-gray-400">Guide perorangan</p>
                        </div>
                    </label>
                </div>
                @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Nama <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm"
                    placeholder="Nama travel agent / guide">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">No. HP</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm"
                        placeholder="08xxxxxxxxxx">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm"
                        placeholder="email@example.com">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Persentase Komisi (%) <span class="text-red-400">*</span></label>
                <div class="relative">
                    <input type="number" name="commission_rate" value="{{ old('commission_rate', 0) }}"
                        step="0.1" min="0" max="100" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm pr-8"
                        placeholder="0">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">%</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">Komisi dihitung dari total transaksi yang dibawa mitra ini</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Catatan</label>
                <textarea name="notes" rows="3"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm resize-none"
                    placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.partners.index') }}"
                class="flex-1 text-center py-3 rounded-xl border border-gray-200 text-sm text-gray-500 hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit"
                class="flex-1 py-3 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold transition shadow-md">
                Simpan Mitra
            </button>
        </div>
    </form>
</div>
@endsection
