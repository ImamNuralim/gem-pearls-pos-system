@extends('layouts.app')

@section('title', 'Komisi Partner')

@section('content')

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Komisi Partner
                </h1>
                <p class="text-sm text-gray-500">
                    Tracking komisi travel agent & freelance
                </p>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr class="text-left text-gray-500">

                            <th class="p-4 font-semibold">Tanggal</th>
                            <th class="p-4 font-semibold">Partner</th>
                            <th class="p-4 font-semibold">Type</th>
                            <th class="p-4 font-semibold">Total Belanja</th>
                            <th class="p-4 font-semibold">Komisi</th>
                            <th class="p-4 font-semibold">Status</th>
                            <th class="p-4 font-semibold">Action</th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse($commissions as $commission)
                            <tr class="hover:bg-gray-50 transition">

                                {{-- Tanggal --}}
                                <td class="p-4">
                                    {{ $commission->commission_date?->format('d/m/Y') }}
                                </td>

                                {{-- Partner --}}
                                <td class="p-4 font-semibold text-gray-800">
                                    {{ $commission->partner->name }}
                                </td>

                                {{-- Type --}}
                                <td class="p-4">

                                    @if ($commission->partner->type === 'travel_agent')
                                        <span class="px-2 py-1 rounded-lg bg-blue-100 text-blue-600 text-xs font-semibold">
                                            Travel Agent
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 rounded-lg bg-amber-100 text-amber-600 text-xs font-semibold">
                                            Freelance
                                        </span>
                                    @endif

                                </td>

                                {{-- Total Belanja --}}
                                <td class="p-4 font-bold text-gray-800">
                                    Rp {{ number_format($commission->total_sales, 0, ',', '.') }}
                                </td>

                                {{-- Komisi --}}
                                <td class="p-4">

                                    <form action="{{ route('admin.commissions.update-rate', $commission) }}" method="POST"
                                        class="space-y-2">

                                        @csrf

                                        <div class="flex items-center gap-2">

                                            <input type="number" name="commission_rate"
                                                value="{{ $commission->commission_rate }}" step="0.01" min="0"
                                                max="100"
                                                class="w-20 px-2 py-1 rounded-lg border border-gray-200 text-xs font-semibold">

                                            <span class="text-xs text-gray-500">%</span>

                                            <button type="submit"
                                                class="px-2 py-1 rounded-lg bg-amber-500 text-white text-[11px] font-semibold hover:bg-amber-600 transition">

                                                Save

                                            </button>

                                        </div>

                                    </form>

                                    <div class="mt-2 font-bold text-green-600">
                                        Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}
                                    </div>

                                </td>

                                {{-- Status --}}
                                <td class="p-4">

                                    @if ($commission->status === 'paid')
                                        <span class="px-3 py-1 rounded-xl bg-green-100 text-green-600 text-xs font-bold">
                                            PAID
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-xl bg-red-100 text-red-600 text-xs font-bold">
                                            UNPAID
                                        </span>
                                    @endif

                                </td>

                                {{-- Action --}}
                                <td class="p-4">

                                    <div class="flex items-center gap-2">
                                        <button
                                            onclick="document.getElementById('detail-modal-{{ $commission->id }}').classList.remove('hidden')"
                                            class="px-3 py-2 rounded-xl bg-gray-700 text-white text-xs font-semibold hover:bg-gray-800 transition">

                                            Detail

                                        </button>

                                        @if ($commission->status === 'unpaid')
                                            <form action="{{ route('admin.commissions.paid', $commission) }}"
                                                method="POST">

                                                @csrf

                                                <button type="submit"
                                                    class="px-3 py-2 rounded-xl bg-amber-500 text-white text-xs font-semibold hover:bg-amber-600 transition">

                                                    Mark Paid

                                                </button>

                                            </form>
                                        @endif

                                        @if ($commission->status === 'paid')
                                            <a href="{{ route('admin.commissions.pdf', $commission) }}" target="_blank"
                                                class=" text-white text-xs font-semibold hover:bg-blue-600 transition"
                                                style="background:#3b82f6; color:white; padding:10px; border-radius:12px;">
                                                PDF
                                            </a>
                                        @endif

                                    </div>

                                </td>

                            </tr>

                            <div id="detail-modal-{{ $commission->id }}"
                                class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">

                                <div class="bg-white rounded-2xl w-full max-w-2xl p-6 relative">

                                    {{-- Close --}}
                                    <button
                                        onclick="document.getElementById('detail-modal-{{ $commission->id }}').classList.add('hidden')"
                                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">

                                        ✕

                                    </button>

                                    <h2 class="text-xl font-bold text-gray-800 mb-6">
                                        Detail Komisi
                                    </h2>

                                    {{-- Visit Information --}}
                                    <div class="space-y-5">

                                        <div class="border-b border-gray-100 pb-4">

                                            <h3 class="text-lg font-bold text-gray-800">
                                                Informasi Kunjungan
                                            </h3>

                                            <p class="text-sm text-gray-400">
                                                Data yang diinput oleh security
                                            </p>

                                        </div>

                                        {{-- Grid --}}
                                        <div class="grid grid-cols-2 gap-5">

                                            {{-- Partner --}}
                                            <div>

                                                <label class="text-xs font-bold uppercase tracking-wide text-gray-400">
                                                    Partner
                                                </label>

                                                <div class="mt-1 text-sm font-semibold text-gray-800">

                                                    {{ $commission->partner->name ?? '-' }}

                                                </div>

                                            </div>

                                            {{-- Sticker --}}
                                            <div>

                                                <label class="text-xs font-bold uppercase tracking-wide text-gray-400">
                                                    No Sticker
                                                </label>

                                                <div class="mt-1 text-sm font-semibold text-gray-800">

                                                    {{ $commission->sticker_number ?? '-' }}

                                                </div>

                                            </div>

                                            {{-- Visit Date --}}
                                            <div>

                                                <label class="text-xs font-bold uppercase tracking-wide text-gray-400">
                                                    Tanggal Kunjungan
                                                </label>

                                                <div class="mt-1 text-sm font-semibold text-gray-800">

                                                    {{ $commission->visit_date?->format('d M Y') ?? '-' }}

                                                </div>

                                            </div>

                                            {{-- Deadline --}}
                                            <div>

                                                <label class="text-xs font-bold uppercase tracking-wide text-gray-400">
                                                    Deadline Pengambilan
                                                </label>

                                                <div class="mt-1 text-sm font-semibold text-gray-800">

                                                    {{ $commission->pickup_deadline?->format('d M Y') ?? '-' }}

                                                </div>

                                            </div>

                                        </div>

                                        {{-- Description --}}
                                        <div>

                                            <label class="text-xs font-bold uppercase tracking-wide text-gray-400">
                                                Deskripsi Rombongan
                                            </label>

                                            <div class="mt-1 text-sm text-gray-700 leading-relaxed">

                                                {{ $commission->group_description ?? '-' }}

                                            </div>

                                        </div>

                                        {{-- Vehicle Notes --}}
                                        <div>

                                            <label class="text-xs font-bold uppercase tracking-wide text-gray-400">
                                                Keterangan Kendaraan
                                            </label>

                                            <div class="mt-1 text-sm text-gray-700 leading-relaxed">

                                                {{ $commission->vehicle_notes ?? '-' }}

                                            </div>

                                        </div>
                                        {{-- Vehicles --}}
                                        <div>

                                            <label class="text-xs font-bold uppercase tracking-wide text-gray-400">
                                                Daftar Kendaraan
                                            </label>

                                            <div class="mt-3 space-y-2">

                                                @forelse($commission->vehicles as $vehicle)
                                                    <div
                                                        class="flex items-center justify-between px-4 py-3 rounded-xl border border-gray-100 bg-gray-50">

                                                        <div>

                                                            <div class="font-semibold text-gray-800">

                                                                {{ $vehicle->plate_number }}

                                                            </div>

                                                            <div class="text-xs text-gray-500">

                                                                {{ $vehicle->vehicle_type ?? '-' }}

                                                            </div>

                                                        </div>

                                                        <div
                                                            class="px-2 py-1 rounded-lg bg-blue-100 text-blue-600 text-xs font-bold">

                                                            Kendaraan

                                                        </div>

                                                    </div>

                                                @empty

                                                    <div class="text-sm text-gray-400">
                                                        Belum ada kendaraan
                                                    </div>
                                                @endforelse

                                            </div>

                                        </div>

                                    </div>

                                    <hr class="my-6">

                                    {{-- Guide / Driver --}}
                                    <div>

                                        <h3 class="text-lg font-bold text-gray-800 mb-4">
                                            Guide / Driver
                                        </h3>

                                        {{-- Attach Guide --}}
                                        <form action="{{ route('admin.commissions.attach-guide', $commission) }}"
                                            method="POST" class="flex gap-2 mb-4">

                                            @csrf

                                            <select name="guide_id"
                                                class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200">

                                                <option value="">
                                                    Pilih Guide / Driver
                                                </option>

                                                @foreach (\App\Models\Guide::orderBy('name')->get() as $guide)
                                                    <option value="{{ $guide->id }}">

                                                        {{ $guide->guide_code }}
                                                        —
                                                        {{ $guide->name }}
                                                        ({{ $guide->total_visits }}x trip)
                                                    </option>
                                                @endforeach

                                            </select>

                                            <button type="submit"
                                                class="px-4 py-2.5 rounded-xl bg-blue-500 text-white text-sm font-semibold hover:bg-blue-600 transition">

                                                Tambah

                                            </button>

                                        </form>

                                        {{-- Guide List --}}
                                        <div class="space-y-2">

                                            @forelse($commission->guides as $guide)
                                                <div
                                                    class="flex items-center justify-between px-4 py-3 rounded-xl border border-gray-100 bg-gray-50">

                                                    <div>

                                                        <div class="font-semibold text-gray-800">

                                                            {{ $guide->guide_code }}
                                                            —
                                                            {{ $guide->name }}

                                                        </div>

                                                        <div class="text-xs text-gray-500">

                                                            {{ $guide->phone }}

                                                        </div>

                                                    </div>

                                                    <div
                                                        class="text-xs px-2 py-1 rounded-lg bg-amber-100 text-amber-600 font-semibold">

                                                        {{ $guide->total_visits }}x Trip

                                                    </div>

                                                </div>

                                            @empty

                                                <div class="text-sm text-gray-400">
                                                    Belum ada guide
                                                </div>
                                            @endforelse

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <tr>
                                <td colspan="7" class="p-10 text-center text-gray-400">
                                    Belum ada data komisi
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection
