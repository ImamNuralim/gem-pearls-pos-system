@extends('layouts.pos')

@section('title', 'Security Visit')
@section('subtitle', 'Security — Partner Visit')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Input Kunjungan Partner
            </h2>

            <p class="text-sm text-gray-400 mt-1">
                Catat setiap rombongan partner yang datang ke toko
            </p>
        </div>

        <button
    type="button"
    onclick="document.getElementById('partner-modal').classList.remove('hidden')"
    class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold transition">

    + Partner Baru

</button>

    </div>

    {{-- FORM --}}
    <form action="{{ route('security.visits.store') }}"
      method="POST"
      class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

    @csrf

    <div class="grid grid-cols-2 gap-5">

        {{-- Partner --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                Travel Agent / Freelance
            </label>

            <select
                name="partner_id"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">

                <option value="">
                    Pilih Partner
                </option>

                @foreach($partners as $partner)

                    <option value="{{ $partner->id }}">
                        {{ $partner->name }}
                    </option>

                @endforeach

            </select>
        </div>

        {{-- Sticker --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                No Sticker
            </label>

            <input type="text"
                name="sticker_number"
                placeholder="Contoh: STK-001"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
        </div>

        {{-- Visit Date --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                Tanggal Kunjungan
            </label>

            <input type="date"
                name="visit_date"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
        </div>

        {{-- Pickup Deadline --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                Batas Pengambilan Komisi
            </label>

            <input type="date"
                name="pickup_deadline"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
        </div>

    </div>

    {{-- Description --}}
    <div class="mt-5">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            Deskripsi / Keterangan Rombongan
        </label>

        <textarea
            name="group_description"
            rows="3"
            placeholder="Contoh: Rombongan Jawa Barat / Trip Malaysia / Tour Sekolah"
            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm"></textarea>
    </div>

    {{-- Vehicle Notes --}}
    <div class="mt-5">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            Keterangan Kendaraan
        </label>

        <textarea
            name="vehicle_notes"
            rows="3"
            placeholder="Contoh: 2 Bus Pariwisata + 3 Hiace Premio"
            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm"></textarea>
    </div>

    {{-- Vehicles --}}
<div class="mt-6"
     x-data="{
        vehicles: [
            {
                plate: '',
                type: ''
            }
        ]
     }">

    <div class="flex items-center justify-between mb-3">

        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide">
            Plat Kendaraan
        </h3>

        <button type="button"
            @click="vehicles.push({ plate: '', type: '' })"
            class="px-3 py-2 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold transition">

            + Tambah Plat

        </button>

    </div>

    <div class="space-y-3">

        <template x-for="(vehicle, index) in vehicles" :key="index">

            <div class="grid grid-cols-12 gap-3">

                {{-- Plat --}}
                <div class="col-span-5">

                    <input type="text"
                        :name="'plate_numbers[' + index + ']'"
                        x-model="vehicle.plate"
                        placeholder="Plat Nomor"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">

                </div>

                {{-- Jenis --}}
                <div class="col-span-5">

                    <input type="text"
                        :name="'vehicle_types[' + index + ']'"
                        x-model="vehicle.type"
                        placeholder="Jenis Kendaraan"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">

                </div>

                {{-- Delete --}}
                <div class="col-span-2">

                    <button type="button"
                        @click="vehicles.splice(index, 1)"
                        class="w-full px-3 py-2.5 rounded-xl bg-red-100 hover:bg-red-200 text-red-600 text-sm font-semibold transition">

                        Hapus

                    </button>

                </div>

            </div>

        </template>

    </div>

</div>

    {{-- Submit --}}
    <div class="mt-6 flex justify-end">

        <button type="submit"
            class="px-6 py-3 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-semibold text-sm transition shadow-sm">

            Simpan Kunjungan

        </button>

    </div>

</form>
    {{-- TABLE --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-lg font-bold text-gray-800">
                    Kunjungan Hari Ini
                </h3>

                <p class="text-sm text-gray-400">
                    Data rombongan partner yang datang hari ini
                </p>
            </div>

            <span
                class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">

                0 Visit

            </span>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>

                    <tr class="border-b border-gray-100 text-left">

                        <th class="py-3 px-2 text-gray-400 font-semibold uppercase text-xs">
                            Partner
                        </th>

                        <th class="py-3 px-2 text-gray-400 font-semibold uppercase text-xs">
                            Rombongan
                        </th>

                        <th class="py-3 px-2 text-gray-400 font-semibold uppercase text-xs">
                            Kendaraan
                        </th>

                        <th class="py-3 px-2 text-gray-400 font-semibold uppercase text-xs">
                            Sticker
                        </th>

                        <th class="py-3 px-2 text-gray-400 font-semibold uppercase text-xs">
                            Total Sales
                        </th>

                        <th class="py-3 px-2 text-gray-400 font-semibold uppercase text-xs">
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody>

    @forelse($visits as $visit)

        <tr class="border-b border-gray-100">

            {{-- Partner --}}
            <td class="py-4 px-2 font-semibold text-gray-800">
                {{ $visit->partner->name ?? '-' }}
            </td>

            {{-- Rombongan --}}
            <td class="py-4 px-2 text-gray-600">
                {{ $visit->group_description ?? '-' }}
            </td>

            {{-- Kendaraan --}}
            <td class="py-4 px-2">

                <div class="space-y-1">

                    @foreach($visit->vehicles as $vehicle)

                        <div class="text-xs">

                            <span class="font-semibold">
                                {{ $vehicle->plate_number }}
                            </span>

                            <span class="text-gray-400">
                                — {{ $vehicle->vehicle_type }}
                            </span>

                        </div>

                    @endforeach

                </div>

            </td>

            {{-- Sticker --}}
            <td class="py-4 px-2">
                {{ $visit->sticker_number ?? '-' }}
            </td>

            {{-- Total Sales --}}
            <td class="py-4 px-2 font-semibold text-green-600">
                Rp {{ number_format($visit->total_sales, 0, ',', '.') }}
            </td>

            {{-- Status --}}
            <td class="py-4 px-2">

                @if($visit->status === 'paid')

                    <span class="px-2 py-1 rounded-lg bg-green-100 text-green-600 text-xs font-bold">
                        Paid
                    </span>

                @else

                    <span class="px-2 py-1 rounded-lg bg-amber-100 text-amber-600 text-xs font-bold">
                        Unpaid
                    </span>

                @endif

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="6"
                class="py-12 text-center text-gray-300">

                <div class="text-4xl mb-2">
                    🚍
                </div>

                <p>
                    Belum ada kunjungan hari ini
                </p>

            </td>

        </tr>

    @endforelse

</tbody>

            </table>

        </div>

    </div>

</div>

{{-- Partner Modal --}}
<div id="partner-modal"
     class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl w-full max-w-6xl p-6 relative">

        {{-- Close --}}
        <button
            type="button"
            onclick="document.getElementById('partner-modal').classList.add('hidden')"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">

            ✕

        </button>

        <h2 class="text-xl font-bold text-gray-800 mb-6">
            Tambah Partner Baru
        </h2>

        <div class="grid grid-cols-2 gap-6">

    {{-- LEFT --}}
    <div>

        <form action="{{ route('security.partner.store') }}"
              method="POST"
              class="space-y-4">

            @csrf

            {{-- Name --}}
            <div>

                <label class="text-sm font-semibold text-gray-700">
                    Nama Partner
                </label>

                <input type="text"
                    name="name"
                    required
                    class="w-full mt-1 px-4 py-2.5 rounded-xl border border-gray-200">

            </div>

            {{-- Phone --}}
            <div>

                <label class="text-sm font-semibold text-gray-700">
                    Nomor WhatsApp
                </label>

                <input type="text"
                    name="phone"
                    class="w-full mt-1 px-4 py-2.5 rounded-xl border border-gray-200">

            </div>

            {{-- Address --}}
            <div>

                <label class="text-sm font-semibold text-gray-700">
                    Alamat
                </label>

                <textarea
                    name="address"
                    rows="4"
                    class="w-full mt-1 px-4 py-2.5 rounded-xl border border-gray-200"></textarea>

            </div>

            {{-- Type --}}
            <div>

                <label class="text-sm font-semibold text-gray-700">
                    Tipe Partner
                </label>

                <select
                    name="type"
                    class="w-full mt-1 px-4 py-2.5 rounded-xl border border-gray-200">

                    <option value="travel_agent">
                        Travel Agent
                    </option>

                    <option value="freelance_guide">
                        Freelance
                    </option>

                </select>

            </div>

            {{-- Submit --}}
            <div class="flex justify-end pt-2">

                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-amber-600 text-white text-sm font-semibold hover:bg-amber-700 transition">

                    Simpan Partner

                </button>

            </div>

        </form>

    </div>

    {{-- RIGHT --}}
    <div>

        <div class="flex items-center justify-between mb-4">

            <div>

                <h3 class="text-lg font-bold text-gray-800">
                    Partner Terdaftar
                </h3>

                <p class="text-sm text-gray-400">
                    Data travel agent & freelance
                </p>

            </div>

            <div class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">

                {{ \App\Models\Partner::count() }} Partner

            </div>

        </div>

        <div class="h-[420px] overflow-y-auto border border-gray-100 rounded-2xl">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 sticky top-0">

                    <tr>

                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase">
                            Code
                        </th>

                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase">
                            Nama
                        </th>

                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase">
                            Tipe
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach(\App\Models\Partner::latest()->get() as $partner)

                        <tr class="border-t border-gray-100 hover:bg-gray-50 transition">

                            <td class="px-4 py-3 font-semibold text-gray-700">
                                {{ $partner->code }}
                            </td>

                            <td class="px-4 py-3">

                                <div class="font-semibold text-gray-800">
                                    {{ $partner->name }}
                                </div>

                                <div class="text-xs text-gray-400">
                                    {{ $partner->phone ?? '-' }}
                                </div>

                            </td>

                            <td class="px-4 py-3">

                                @if($partner->type === 'travel_agent')

                                    <span class="px-2 py-1 rounded-lg bg-blue-100 text-blue-600 text-xs font-bold">
                                        Travel Agent
                                    </span>

                                @else

                                    <span class="px-2 py-1 rounded-lg bg-amber-100 text-amber-600 text-xs font-bold">
                                        Freelance
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

    </div>

</div>

@endsection
