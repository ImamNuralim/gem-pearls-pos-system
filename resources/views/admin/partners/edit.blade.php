@extends('layouts.app')
@section('title', 'Edit Mitra')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.partners.index') }}" class="text-gray-400 hover:text-gray-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-gray-800">Edit Mitra</h2>
            <code class="text-xs bg-gray-100 px-2 py-1 rounded-lg text-gray-500">{{ $partner->code }}</code>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.partners.update', $partner) }}">
        @csrf @method('PUT')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Tipe</label>
                <input type="text" value="{{ $partner->type === 'travel_agent' ? 'Travel Agent' : 'Freelance Guide' }}" readonly
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 text-sm text-gray-400">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Nama</label>
                <input type="text" name="name" value="{{ old('name', $partner->name) }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">No. HP</label>
                    <input type="text" name="phone" value="{{ old('phone', $partner->phone) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $partner->email) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Persentase Komisi (%)</label>
                <div class="relative">
                    <input type="number" name="commission_rate" value="{{ old('commission_rate', $partner->commission_rate) }}"
                        step="0.1" min="0" max="100"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm pr-8">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">%</span>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1.5">Catatan</label>
                <textarea name="notes" rows="3"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm resize-none">{{ old('notes', $partner->notes) }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    {{ $partner->is_active ? 'checked' : '' }}
                    class="rounded border-gray-300 text-amber-500 focus:ring-amber-400">
                <label for="is_active" class="text-sm text-gray-600">Mitra Aktif</label>
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.partners.index') }}"
                class="flex-1 text-center py-3 rounded-xl border border-gray-200 text-sm text-gray-500 hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit"
                class="flex-1 py-3 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold transition shadow-md">
                Update Mitra
            </button>
        </div>
    </form>
</div>
@endsection
