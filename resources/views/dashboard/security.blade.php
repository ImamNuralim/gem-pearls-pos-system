@extends('layouts.pos')

@section('title', 'Security Visit')
@section('subtitle', 'Security — Partner Visit')

@section('content')

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }

        .input-field {
            width: 100%;
            padding: 10px 16px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            font-size: 13px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #f8fafc;
            color: #1e293b;
        }

        .input-field:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background: #fff;
        }

        .label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            padding: 10px 20px;
            border-radius: 10px;
            background: #2563eb;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn-yellow {
            padding: 10px 20px;
            border-radius: 10px;
            background: #f59e0b;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-yellow:hover {
            background: #d97706;
            transform: translateY(-1px);
        }

        .btn-green {
            padding: 8px 14px;
            border-radius: 8px;
            background: #10b981;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-green:hover {
            background: #059669;
        }

        .btn-blue-sm {
            padding: 8px 14px;
            border-radius: 8px;
            background: #3b82f6;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-blue-sm:hover {
            background: #2563eb;
        }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .badge-blue {
            padding: 3px 10px;
            border-radius: 20px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-yellow {
            padding: 3px 10px;
            border-radius: 20px;
            background: #fef3c7;
            color: #d97706;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-green {
            padding: 3px 10px;
            border-radius: 20px;
            background: #d1fae5;
            color: #065f46;
            font-size: 11px;
            font-weight: 700;
        }

        .page-header {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 60%, #3b82f6 100%);
            border-radius: 16px;
            padding: 24px 28px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    </style>

    <div class="max-w-5xl mx-auto space-y-5" x-data="securityVisitSystem()">

        {{-- Header --}}
        <div class="page-header">
            <div>
                <h2 class="text-xl font-bold">Input Kunjungan Partner</h2>
                <p class="text-blue-200 text-sm mt-0.5">Catat setiap rombongan partner yang datang ke toko</p>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="document.getElementById('walkin-modal').classList.remove('hidden')"
                    class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white text-sm font-semibold transition border border-white/30"
                    style="border-radius:10px">
                    + Walk-in Baru
                </button>
                <button type="button" onclick="document.getElementById('partner-modal').classList.remove('hidden')"
                    class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white text-sm font-semibold transition border border-white/30"
                    style="border-radius:10px">
                    + Partner Baru
                </button>
            </div>
        </div>

        {{-- FORM --}}
        <div class="card p-6">

            <div class="flex items-center gap-2 mb-5">
                <div class="w-1 h-5 rounded-full bg-blue-500"></div>
                <span class="text-sm font-bold text-slate-700">Form Kunjungan</span>
            </div>

            <form action="{{ route('security.visits.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-2 gap-4">

                    {{-- Partner --}}
                    <div>
                        <label class="label">Travel Agent / Freelance</label>
                        <select name="partner_id" class="input-field">
                            <option value="">Pilih Partner</option>
                            @foreach ($partners as $partner)
                                <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sticker --}}
                    <div>
                        <label class="label">No Sticker</label>
                        <input type="text" name="sticker_number" placeholder="Contoh: STK-001" class="input-field">
                    </div>

                    {{-- Visit Date --}}
                    <div>
                        <label class="label">Tanggal Kunjungan</label>
                        <input type="date" name="visit_date" class="input-field">
                    </div>

                    {{-- Pickup Deadline --}}
                    {{-- <div>
                    <label class="label">Batas Pengambilan Komisi</label>
                    <input type="date" name="pickup_deadline" class="input-field">
                </div> --}}

                </div>

                {{-- Description --}}
                <div class="mt-4">
                    <label class="label">Deskripsi / Keterangan Rombongan</label>
                    <textarea name="group_description" rows="2"
                        placeholder="Contoh: Rombongan Jawa Barat / Trip Malaysia / Tour Sekolah" class="input-field" style="resize:none"></textarea>
                </div>

                {{-- Vehicle Notes --}}
                <div class="mt-4">
                    <label class="label">Keterangan Kendaraan</label>
                    <textarea name="vehicle_notes" rows="2" placeholder="Contoh: 2 Bus Pariwisata + 3 Hiace Premio"
                        class="input-field" style="resize:none"></textarea>
                </div>

                {{-- Guides --}}
                <div class="mt-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-1 h-4 rounded-full bg-emerald-500"></div>
                            <span class="section-title">Guide / Driver</span>
                        </div>
                        <button type="button" onclick="document.getElementById('guide-modal').classList.remove('hidden')"
                            class="btn-green">
                            + Guide Baru
                        </button>
                    </div>

                    {{-- Search --}}
                    <div class="relative">
                        <input type="text" x-model="guideSearch" @input.debounce.300ms="searchGuides()"
                            placeholder="Ketik nama atau kode guide..." class="input-field" autocomplete="off">

                        {{-- Dropdown results --}}
                        <div x-show="guideResults.length > 0" x-cloak
                            class="absolute z-30 w-full mt-1 bg-white border border-blue-100 rounded-xl shadow-xl max-h-52 overflow-y-auto">
                            <template x-for="guide in guideResults" :key="guide.id">
                                <div @click="selectGuide(guide)"
                                    class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-50 transition">
                                    <div class="font-semibold text-sm text-slate-800">
                                        <span x-text="guide.guide_code"></span>
                                        <span class="text-gray-400 mx-1">—</span>
                                        <span x-text="guide.name"></span>
                                    </div>
                                    <div class="text-xs text-gray-400 mt-0.5" x-text="guide.phone ?? '-'"></div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Selected Guides --}}
                    <div class="mt-3 space-y-2">
                        <template x-for="(guide, index) in selectedGuides" :key="guide.id">
                            <div
                                class="flex items-center justify-between px-4 py-3 rounded-xl border border-blue-100 bg-blue-50">
                                <div>
                                    <div class="font-semibold text-sm text-slate-800">
                                        <span x-text="guide.guide_code"></span>
                                        <span class="text-gray-400 mx-1">—</span>
                                        <span x-text="guide.name"></span>
                                    </div>
                                    <div class="text-xs text-gray-400 mt-0.5" x-text="guide.phone ?? '-'"></div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="hidden" name="guide_ids[]" :value="guide.id">
                                    <button type="button" @click="removeGuide(index)"
                                        class="w-7 h-7 rounded-full bg-red-100 hover:bg-red-200 text-red-500 text-sm flex items-center justify-center transition">
                                        ✕
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Vehicles --}}
                <div class="mt-5" x-data="{ vehicles: [{ plate: '', type: '' }] }">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-1 h-4 rounded-full bg-blue-500"></div>
                            <span class="section-title">Plat Kendaraan</span>
                        </div>
                        <button type="button" @click="vehicles.push({ plate: '', type: '' })" class="btn-blue-sm">
                            + Tambah Plat
                        </button>
                    </div>

                    <div class="space-y-2">
                        <template x-for="(vehicle, index) in vehicles" :key="index">
                            <div class="grid grid-cols-12 gap-3">
                                <div class="col-span-5">
                                    <input type="text" :name="'plate_numbers[' + index + ']'" x-model="vehicle.plate"
                                        placeholder="Plat Nomor" class="input-field">
                                </div>
                                <div class="col-span-5">
                                    <input type="text" :name="'vehicle_types[' + index + ']'" x-model="vehicle.type"
                                        placeholder="Jenis Kendaraan" class="input-field">
                                </div>
                                <div class="col-span-2">
                                    <button type="button" @click="vehicles.splice(index, 1)"
                                        class="w-full px-3 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 text-red-500 text-sm font-semibold transition">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="btn-yellow" style="padding:12px 28px; font-size:14px;">
                        💾 Simpan Kunjungan
                    </button>
                </div>

            </form>
        </div>

        {{-- TABEL KUNJUNGAN HARI INI --}}
        <div class="card p-6">

            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-5 rounded-full bg-yellow-400"></div>
                    <span class="text-sm font-bold text-slate-700">Kunjungan Hari Ini</span>
                </div>
                <span class="badge-blue">{{ \App\Models\PartnerVisit::whereDate('visit_date', today())->count() }}
                    Kunjungan</span>
            </div>

            @php
                $todayVisits = \App\Models\PartnerVisit::with(['partner', 'guides', 'vehicles'])
                    ->whereDate('visit_date', today())
                    ->latest()
                    ->get();
            @endphp

            @if ($todayVisits->isEmpty())
                <div class="text-center py-12">
                    <div class="text-4xl mb-3">📋</div>
                    <p class="text-sm font-semibold text-gray-400">Belum ada kunjungan hari ini</p>
                    <p class="text-xs text-gray-300 mt-1">Data akan muncul setelah form di atas diisi</p>
                </div>
            @else
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-gray-100">
                                <th class="text-left px-4 py-3 text-xs font-700 text-gray-400 uppercase tracking-wide">Kode
                                    Visit</th>
                                <th class="text-left px-4 py-3 text-xs font-700 text-gray-400 uppercase tracking-wide">
                                    Partner</th>
                                <th class="text-left px-4 py-3 text-xs font-700 text-gray-400 uppercase tracking-wide">
                                    Guide / Driver</th>
                                <th class="text-left px-4 py-3 text-xs font-700 text-gray-400 uppercase tracking-wide">
                                    Kendaraan</th>
                                <th class="text-left px-4 py-3 text-xs font-700 text-gray-400 uppercase tracking-wide">
                                    Sticker</th>
                                <th class="text-left px-4 py-3 text-xs font-700 text-gray-400 uppercase tracking-wide">
                                    Keterangan</th>
                                <th class="text-left px-4 py-3 text-xs font-700 text-gray-400 uppercase tracking-wide">
                                    Status</th>
                                    <th class="text-left px-4 py-3 text-xs font-700 text-gray-400 uppercase tracking-wide">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($todayVisits as $visit)
                                <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition">
                                    <td class="px-4 py-3">
                                        <span class="font-bold text-blue-600 text-xs">{{ $visit->visit_code }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-slate-800 text-sm">
                                            {{ $visit->partner->name ?? '-' }}</div>
                                        <div class="text-xs text-gray-400">
                                            {{ $visit->partner ? ($visit->partner->type === 'travel_agent' ? 'Travel Agent' : 'Freelance') : 'Walk-in' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($visit->guides->isEmpty())
                                            <span class="text-xs text-gray-300">—</span>
                                        @else
                                            <div class="flex flex-wrap gap-1">
                                                @foreach ($visit->guides as $guide)
                                                    <span class="badge-green text-xs">{{ $guide->name }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($visit->visit_type === 'walk_in')
                                            <div class="text-xs text-slate-600 font-medium">
                                                {{ $visit->vehicle_notes ?? '—' }}
                                                @if ($visit->vehicle_description)
                                                    <span class="text-gray-400">· {{ $visit->vehicle_description }}</span>
                                                @endif
                                            </div>
                                        @elseif($visit->vehicles->isEmpty())
                                            <span class="text-xs text-gray-300">—</span>
                                        @else
                                            <div class="space-y-0.5">
                                                @foreach ($visit->vehicles as $v)
                                                    <div class="text-xs text-slate-600 font-medium">{{ $v->plate_number }}
                                                        <span class="text-gray-400">· {{ $v->vehicle_type }}</span></div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge-yellow">{{ $visit->sticker_number ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-xs text-slate-600">{{ $visit->group_description ?? '—' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open"
                                                class="px-2 py-1 rounded-lg text-xs font-bold cursor-pointer transition
            {{ $visit->status === 'pending' ? 'bg-gray-100 text-gray-500' : '' }}
            {{ $visit->status === 'shopping' ? 'bg-blue-100 text-blue-600' : '' }}
            {{ $visit->status === 'completed' ? 'bg-emerald-100 text-emerald-600' : '' }}">
                                                {{ $visit->status === 'pending' ? 'Pending' : ($visit->status === 'shopping' ? 'Shopping' : 'Completed') }}
                                                ▾
                                            </button>
                                            <div x-show="open" @click.outside="open = false" x-cloak
                                                class="absolute right-0 z-20 mt-1 bg-white border border-gray-100 rounded-xl shadow-lg overflow-hidden w-36">
                                                @foreach (['pending' => 'Pending', 'completed' => 'Completed'] as $val => $label)
                                                    @if ($visit->status !== $val)
                                                        <form method="POST"
                                                            action="{{ route('security.visits.status', $visit) }}">
                                                            @csrf
                                                            <input type="hidden" name="status"
                                                                value="{{ $val }}">
                                                            <button type="submit"
                                                                class="w-full text-left px-4 py-2.5 text-xs font-semibold hover:bg-blue-50 transition
                            {{ $val === 'pending' ? 'text-gray-500' : '' }}
                            {{ $val === 'shopping' ? 'text-blue-600' : '' }}
                            {{ $val === 'completed' ? 'text-emerald-600' : '' }}">
                                                                {{ $label }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
    <button onclick="document.getElementById('edit-visit-modal-{{ $visit->id }}').classList.remove('hidden')"
        class="p-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition" title="Edit">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
        </svg>
    </button>
</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>

    </div>

    {{-- Walk-in Modal --}}
    <div id="walkin-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-lg p-6 relative">
            <button type="button" onclick="document.getElementById('walkin-modal').classList.add('hidden')"
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center text-sm transition">
                ✕
            </button>
            <h2 class="text-lg font-bold text-slate-800 mb-5">Tambah Walk-in Baru</h2>
            <form action="{{ route('security.walkin.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="label">Tanggal Kunjungan</label>
                    <input type="date" name="visit_date" required class="input-field">
                </div>
                <div>
                    <label class="label">Kendaraan</label>
                    <input type="text" name="vehicle_notes" placeholder="Contoh: Toyota Avanza" required
                        class="input-field">
                </div>
                <div>
                    <label class="label">Plat Kendaraan</label>
                    <input type="text" name="vehicle_description" placeholder="Contoh: DR 1234 AB" required
                        class="input-field">
                </div>
                <div>
                    <label class="label">Keterangan</label>
                    <textarea name="group_description" rows="2" placeholder="Contoh: Mobil biru, 4 penumpang" class="input-field"
                        style="resize:none"></textarea>
                </div>
                <div class="flex justify-end pt-1">
                    <button type="submit" class="btn-primary">Simpan Walk-in</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
<div id="edit-visit-modal-{{ $visit->id }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 relative">
        <button onclick="document.getElementById('edit-visit-modal-{{ $visit->id }}').classList.add('hidden')"
            class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
        <h2 class="text-lg font-bold text-slate-800 mb-1">Edit Kunjungan</h2>
        <p class="text-xs text-slate-400 mb-5">{{ $visit->visit_code }}</p>

        <form method="POST" action="{{ route('security.visits.update', $visit) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Sticker</label>
                <input type="text" name="sticker_number" value="{{ $visit->sticker_number }}"
                    class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-200 focus:border-blue-400 focus:outline-none text-sm bg-slate-50">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Keterangan</label>
                <input type="text" name="group_description" value="{{ $visit->group_description }}"
                    class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-200 focus:border-blue-400 focus:outline-none text-sm bg-slate-50">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Status</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-200 focus:border-blue-400 focus:outline-none text-sm bg-slate-50">
                    <option value="pending" {{ $visit->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ $visit->status === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>

    {{-- Partner Modal --}}
    <div id="partner-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-5xl p-6 relative" style="max-height:90vh; overflow-y:auto;">
            <button type="button" onclick="document.getElementById('partner-modal').classList.add('hidden')"
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center text-sm transition">
                ✕
            </button>
            <h2 class="text-lg font-bold text-slate-800 mb-5">Tambah Partner Baru</h2>
            <div class="grid grid-cols-2 gap-6">
                {{-- LEFT --}}
                <div>
                    <form action="{{ route('security.partner.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="label">Nama Partner</label>
                            <input type="text" name="name" required class="input-field">
                        </div>
                        <div>
                            <label class="label">Nomor WhatsApp</label>
                            <input type="text" name="phone" class="input-field">
                        </div>
                        <div>
                            <label class="label">Alamat</label>
                            <textarea name="address" rows="3" class="input-field" style="resize:none"></textarea>
                        </div>
                        <div>
                            <label class="label">Tipe Partner</label>
                            <select name="type" class="input-field">
                                <option value="travel_agent">Travel Agent</option>
                                <option value="freelance_guide">Freelance</option>
                            </select>
                        </div>
                        <div class="flex justify-end pt-1">
                            <button type="submit" class="btn-yellow">Simpan Partner</button>
                        </div>
                    </form>
                </div>
                {{-- RIGHT --}}
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-slate-800">Partner Terdaftar</h3>
                            <p class="text-xs text-gray-400">Data travel agent & freelance</p>
                        </div>
                        <span class="badge-blue">{{ \App\Models\Partner::count() }} Partner</span>
                    </div>
                    <div class="h-96 overflow-y-auto border border-gray-100 rounded-xl">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 sticky top-0">
                                <tr>
                                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase">Code</th>
                                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase">Nama</th>
                                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase">Tipe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (\App\Models\Partner::latest()->get() as $partner)
                                    <tr class="border-t border-gray-100 hover:bg-blue-50/40 transition">
                                        <td class="px-4 py-3 font-bold text-blue-600 text-xs">{{ $partner->code }}</td>
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-slate-800">{{ $partner->name }}</div>
                                            <div class="text-xs text-gray-400">{{ $partner->phone ?? '-' }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($partner->type === 'travel_agent')
                                                <span class="badge-blue">Travel Agent</span>
                                            @else
                                                <span class="badge-yellow">Freelance</span>
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

    {{-- Guide Modal --}}
    <div id="guide-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-5xl p-6 relative" style="max-height:90vh; overflow-y:auto;">
            <button type="button" onclick="document.getElementById('guide-modal').classList.add('hidden')"
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center text-sm transition">
                ✕
            </button>
            <h2 class="text-lg font-bold text-slate-800 mb-5">Tambah Guide / Driver</h2>
            <div class="grid grid-cols-2 gap-6">
                {{-- LEFT --}}
                <div>
                    <form action="{{ route('security.guides.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="label">Nama Guide / Driver</label>
                            <input type="text" name="name" required class="input-field">
                        </div>
                        <div>
                            <label class="label">Nomor WhatsApp</label>
                            <input type="text" name="phone" class="input-field">
                        </div>
                        <div>
                            <label class="label">Alamat</label>
                            <textarea name="address" rows="3" class="input-field" style="resize:none"></textarea>
                        </div>
                        <div class="flex justify-end pt-1">
                            <button type="submit" class="btn-green" style="padding:10px 20px; font-size:13px;">Simpan
                                Guide</button>
                        </div>
                    </form>
                </div>
                {{-- RIGHT --}}
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-slate-800">Guide / Driver Terdaftar</h3>
                            <p class="text-xs text-gray-400">Data guide & driver</p>
                        </div>
                        <span class="badge-green">{{ \App\Models\Guide::count() }} Guide</span>
                    </div>
                    <div class="h-96 overflow-y-auto border border-gray-100 rounded-xl">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 sticky top-0">
                                <tr>
                                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase">Code</th>
                                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase">Nama</th>
                                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase">Trip</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (\App\Models\Guide::latest()->get() as $guide)
                                    <tr class="border-t border-gray-100 hover:bg-blue-50/40 transition">
                                        <td class="px-4 py-3 font-bold text-blue-600 text-xs">{{ $guide->guide_code }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-slate-800">{{ $guide->name }}</div>
                                            <div class="text-xs text-gray-400">{{ $guide->phone ?? '-' }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="badge-green">{{ $guide->total_visits ?? 0 }}x Trip</span>
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

    {{-- Toast --}}
    @if (session('success'))
        <div id="success-toast"
            class="fixed top-5 right-5 z-[9999] px-5 py-4 rounded-2xl bg-blue-600 text-white shadow-2xl flex items-center gap-3"
            style="min-width:280px">
            <div class="text-2xl">✅</div>
            <div>
                <div class="font-bold text-sm">Berhasil</div>
                <div class="text-xs text-blue-200">{{ session('success') }}</div>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('success-toast');
                if (toast) {
                    toast.style.transition = '0.4s';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-10px)';
                    setTimeout(() => toast.remove(), 400);
                }
            }, 2500);
        </script>
    @endif

    <script>
        function securityVisitSystem() {
            return {
                guideSearch: '',
                guideResults: [],
                selectedGuides: [],

                async searchGuides() {
                    if (this.guideSearch.length < 2) {
                        this.guideResults = [];
                        return;
                    }
                    try {
                        const res = await fetch(`/security/search-guides?q=${encodeURIComponent(this.guideSearch)}`);
                        this.guideResults = await res.json();
                    } catch (e) {
                        this.guideResults = [];
                    }
                },

                selectGuide(guide) {
                    const exists = this.selectedGuides.find(g => g.id === guide.id);
                    if (!exists) {
                        this.selectedGuides.push(guide);
                    }
                    this.guideSearch = '';
                    this.guideResults = [];
                },

                removeGuide(index) {
                    this.selectedGuides.splice(index, 1);
                }
            }
        }
    </script>

@endsection
