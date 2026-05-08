@extends('layouts.app')

@section('title', 'Kunjungan Partner')
@section('subtitle', 'Admin — Daftar Kunjungan')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>

            <h2 class="text-2xl font-bold text-gray-800">
                Daftar Kunjungan Partner
            </h2>

            <p class="text-sm text-gray-400 mt-1">
                Data kunjungan dari security
            </p>

        </div>

        <div
            class="px-4 py-2 rounded-xl bg-amber-100 text-amber-700 text-sm font-bold">

            {{ $visits->count() }} Visit

        </div>

    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wide text-gray-400">
                            Visit Code
                        </th>

                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wide text-gray-400">
                            Partner
                        </th>

                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wide text-gray-400">
                            Rombongan
                        </th>

                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wide text-gray-400">
                            Kendaraan
                        </th>

                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wide text-gray-400">
                            Sticker
                        </th>

                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wide text-gray-400">
                            Tanggal
                        </th>

                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wide text-gray-400">
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($visits as $visit)

                        <tr class="border-t border-gray-100 hover:bg-gray-50 transition">

                            {{-- Code --}}
                            <td class="px-6 py-4 font-bold text-gray-700">

                                {{ $visit->visit_code }}

                            </td>

                            {{-- Partner --}}
                            <td class="px-6 py-4">

                                <div class="font-semibold text-gray-800">

                                    {{ $visit->partner->name ?? '-' }}

                                </div>

                                <div class="text-xs text-gray-400">

                                    {{ $visit->partner->code ?? '-' }}

                                </div>

                            </td>

                            {{-- Group --}}
                            <td class="px-6 py-4 text-gray-600">

                                {{ $visit->group_description ?? '-' }}

                            </td>

                            {{-- Vehicles --}}
                            <td class="px-6 py-4">

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
                            <td class="px-6 py-4">

                                {{ $visit->sticker_number ?? '-' }}

                            </td>

                            {{-- Date --}}
                            <td class="px-6 py-4">

                                {{ $visit->visit_date?->format('d M Y') }}

                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">

                                @if($visit->status === 'pending')

                                    <span
                                        class="px-3 py-1 rounded-lg bg-gray-100 text-gray-600 text-xs font-bold">

                                        Pending

                                    </span>

                                @elseif($visit->status === 'shopping')

                                    <span
                                        class="px-3 py-1 rounded-lg bg-blue-100 text-blue-600 text-xs font-bold">

                                        Shopping

                                    </span>

                                @else

                                    <span
                                        class="px-3 py-1 rounded-lg bg-green-100 text-green-600 text-xs font-bold">

                                        Completed

                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="py-16 text-center text-gray-300">

                                <div class="text-5xl mb-3">
                                    🚍
                                </div>

                                <p class="text-sm">
                                    Belum ada data kunjungan
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
