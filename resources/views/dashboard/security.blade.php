@extends('layouts.app')
@section('title', 'Data Tamu')

@section('content')

<style>
    * { font-family: 'Poppins', sans-serif; }
    [x-cloak] { display: none !important; }

    .card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }

    .page-header {
        background: linear-gradient(135deg, #1e3a5f, #2563eb);
        border-radius: 16px;
        padding: 20px 24px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .input-field {
        width: 100%;
        padding: 9px 14px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: #f8fafc;
        color: #1e293b;
        font-family: 'Poppins', sans-serif;
    }

    .input-field:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        background: #fff;
    }

    .label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #64748b;
        margin-bottom: 6px;
    }

    .section-title { font-size: 12px; font-weight: 700; color: #334155; }

    .badge-blue { padding: 3px 10px; border-radius: 20px; background: #dbeafe; color: #1d4ed8; font-size: 11px; font-weight: 700; }
    .badge-green { padding: 3px 10px; border-radius: 20px; background: #dcfce7; color: #15803d; font-size: 11px; font-weight: 700; }
    .badge-yellow { padding: 3px 10px; border-radius: 20px; background: #fef9c3; color: #a16207; font-size: 11px; font-weight: 700; }
    .badge-purple { padding: 3px 10px; border-radius: 20px; background: #f3e8ff; color: #7e22ce; font-size: 11px; font-weight: 700; }

    .btn-primary { padding: 9px 20px; border-radius: 10px; background: #2563eb; color: #fff; font-size: 13px; font-weight: 700; border: none; cursor: pointer; transition: background 0.15s; }
    .btn-primary:hover { background: #1d4ed8; }
    .btn-yellow { padding: 9px 20px; border-radius: 10px; background: #f59e0b; color: #fff; font-size: 13px; font-weight: 700; border: none; cursor: pointer; transition: background 0.15s; }
    .btn-yellow:hover { background: #d97706; }
    .btn-green { padding: 7px 14px; border-radius: 10px; background: #10b981; color: #fff; font-size: 12px; font-weight: 700; border: none; cursor: pointer; transition: background 0.15s; }
    .btn-green:hover { background: #059669; }
    .btn-blue-sm { padding: 6px 12px; border-radius: 8px; background: #eff6ff; color: #2563eb; font-size: 12px; font-weight: 700; border: 1.5px solid #bfdbfe; cursor: pointer; transition: all 0.15s; }
    .btn-blue-sm:hover { background: #dbeafe; }

    .type-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 12px 10px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.15s;
        background: #fff;
        font-family: 'Poppins', sans-serif;
    }
    .type-btn.active { border-color: #3b82f6; background: #eff6ff; }
</style>

<div class="max-w-5xl mx-auto space-y-5" x-data="securityVisitSystem()">

    {{-- Header --}}
    <div class="page-header">
        <div>
            <h2 class="text-xl font-bold">Input Kunjungan</h2>
            <p class="text-blue-200 text-sm mt-0.5">Catat setiap rombongan yang datang ke toko</p>
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

            <div class="grid grid-cols-2 gap-4 mb-4">
                {{-- Tanggal --}}
                <div>
                    <label class="label">Tanggal Kunjungan</label>
                    <input type="date" name="visit_date" value="{{ date('Y-m-d') }}" class="input-field">
                </div>
                {{-- Sticker --}}
                <div>
                    <label class="label">No Sticker</label>
                    <input type="text" name="sticker_number" placeholder="Contoh: STK-001" class="input-field">
                </div>
            </div>


            {{-- 3. Guide --}}
            <div class="mb-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-1 h-4 rounded-full bg-emerald-500"></div>
                        <span class="section-title">Guide</span>
                    </div>
                    <button type="button" onclick="document.getElementById('guide-modal').classList.remove('hidden')"
                        class="btn-green">+ Guide Baru</button>
                </div>
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="label">Nama Guide</label>
                        <div class="relative">
                            <input type="text" x-model="guideSearch" @input.debounce.300ms="searchGuides()"
                                placeholder="Cari nama guide..." class="input-field" autocomplete="off">
                            <div x-show="guideResults.length > 0" x-cloak
                                class="absolute z-30 w-full mt-1 bg-white border border-blue-100 rounded-xl shadow-xl max-h-48 overflow-y-auto">
                                <template x-for="guide in guideResults" :key="guide.id">
                                    <div @click="selectGuide(guide)"
                                        class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-50 transition">
                                        <div class="font-semibold text-sm text-slate-800">
                                            <span x-text="guide.guide_code"></span> —
                                            <span x-text="guide.name"></span>
                                        </div>
                                        <div class="text-xs text-gray-400" x-text="guide.phone ?? '-'"></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="label">No HP Guide</label>
                        <input type="text" x-model="guidePhoneInput" placeholder="08xxxxxxxxxx" class="input-field">
                    </div>
                </div>
                {{-- Selected Guides --}}
                <div class="space-y-2">
                    <template x-for="(guide, index) in selectedGuides" :key="guide.id">
                        <div class="flex items-center justify-between px-4 py-3 rounded-xl border border-emerald-100 bg-emerald-50">
                            <div>
                                <div class="font-semibold text-sm text-slate-800">
                                    <span x-text="guide.guide_code"></span> —
                                    <span x-text="guide.name"></span>
                                </div>
                                <div class="text-xs text-gray-400" x-text="guide.phone ?? '-'"></div>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="guide_ids[]" :value="guide.id">
                                <button type="button" @click="removeGuide(index)"
                                    class="w-7 h-7 rounded-full bg-red-100 hover:bg-red-200 text-red-500 text-sm flex items-center justify-center transition">✕</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 4. Driver --}}
            <div class="mb-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-1 h-4 rounded-full bg-blue-500"></div>
                        <span class="section-title">Sopir / Driver</span>
                    </div>
                    <button type="button" onclick="document.getElementById('driver-modal').classList.remove('hidden')"
                        class="btn-green">+ Driver Baru</button>
                </div>
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="label">Nama Sopir</label>
                        <div class="relative">
                            <input type="text" x-model="driverSearch" @input.debounce.300ms="searchDrivers()"
                                placeholder="Cari nama sopir..." class="input-field" autocomplete="off">
                            <div x-show="driverResults.length > 0" x-cloak
                                class="absolute z-30 w-full mt-1 bg-white border border-blue-100 rounded-xl shadow-xl max-h-48 overflow-y-auto">
                                <template x-for="driver in driverResults" :key="driver.id">
                                    <div @click="selectDriver(driver)"
                                        class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-50 transition">
                                        <div class="font-semibold text-sm text-slate-800">
                                            <span x-text="driver.driver_code"></span> —
                                            <span x-text="driver.name"></span>
                                        </div>
                                        <div class="text-xs text-gray-400" x-text="driver.phone ?? '-'"></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="label">No HP Sopir</label>
                        <input type="text" x-model="driverPhoneInput" placeholder="08xxxxxxxxxx" class="input-field">
                    </div>
                </div>
                {{-- Selected Drivers --}}
                <div class="space-y-2">
                    <template x-for="(driver, index) in selectedDrivers" :key="driver.id">
                        <div class="flex items-center justify-between px-4 py-3 rounded-xl border border-blue-100 bg-blue-50">
                            <div>
                                <div class="font-semibold text-sm text-slate-800">
                                    <span x-text="driver.driver_code"></span> —
                                    <span x-text="driver.name"></span>
                                </div>
                                <div class="text-xs text-gray-400" x-text="driver.phone ?? '-'"></div>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="driver_ids[]" :value="driver.id">
                                <button type="button" @click="removeDriver(index)"
                                    class="w-7 h-7 rounded-full bg-red-100 hover:bg-red-200 text-red-500 text-sm flex items-center justify-center transition">✕</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 5 & 6. Kendaraan & Plat --}}
            <div class="mb-5" x-data="{ vehicles: [{ plate: '', type: '' }] }">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-1 h-4 rounded-full bg-amber-500"></div>
                        <span class="section-title">Kendaraan & Plat</span>
                    </div>
                    <button type="button" @click="vehicles.push({ plate: '', type: '' })" class="btn-green">
                        + Tambah Kendaraan
                    </button>
                </div>
                <div class="space-y-2">
                    <template x-for="(vehicle, index) in vehicles" :key="index">
                        <div class="grid grid-cols-12 gap-3">
                            <div class="col-span-5">
                                <input type="text" :name="'vehicle_types[' + index + ']'" x-model="vehicle.type"
                                    placeholder="Jenis (Bus, Hiace, dll)" class="input-field">
                            </div>
                            <div class="col-span-5">
                                <input type="text" :name="'plate_numbers[' + index + ']'" x-model="vehicle.plate"
                                    placeholder="Plat Nomor" class="input-field">
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

            <div class="mb-5">
                <label class="label">Tipe Kunjungan</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="type-btn" :class="visitType === 'travel_agent' ? 'active' : ''">
                        <input type="radio" name="visit_type_label" value="travel_agent" x-model="visitType" class="hidden">
                        <span class="text-sm font-bold text-slate-700">Travel Agent</span>
                        <span class="text-xs text-slate-400 mt-0.5">Pilih dari daftar</span>
                    </label>
                    <label class="type-btn" :class="visitType === 'freelance' ? 'active' : ''">
                        <input type="radio" name="visit_type_label" value="freelance" x-model="visitType" class="hidden">
                        <span class="text-sm font-bold text-slate-700">Freelance</span>
                        <span class="text-xs text-slate-400 mt-0.5">Guide lepas</span>
                    </label>
                    <label class="type-btn" :class="visitType === 'no_guide' ? 'active' : ''">
                        <input type="radio" name="visit_type_label" value="no_guide" x-model="visitType" class="hidden">
                        <span class="text-sm font-bold text-slate-700">No Guide</span>
                        <span class="text-xs text-slate-400 mt-0.5">Tamu langsung</span>
                    </label>
                </div>
            </div>

            <div x-show="visitType === 'travel_agent'" class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="label" style="margin-bottom:0">Nama Travel Agent</label>
                    <button type="button" onclick="document.getElementById('partner-modal').classList.remove('hidden')"
                        class="btn-green">+ Partner Baru</button>
                </div>
                <select name="partner_id" class="input-field">
                    <option value="">Pilih Travel Agent</option>
                    @foreach($partners->where('type', 'travel_agent') as $partner)
                        <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 7. Keterangan WNA/WNI --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="label">Kewarganegaraan Tamu</label>
                    <select name="visitor_nationality" class="input-field">
                        <option value="">Pilih...</option>
                        <option value="WNI">WNI (Warga Negara Indonesia)</option>
                        <option value="WNA">WNA (Warga Negara Asing)</option>
                        <option value="Campuran">Campuran WNI & WNA</option>
                    </select>
                </div>
                <div>
                    <label class="label">Keterangan Rombongan</label>
                    <input type="text" name="group_description" placeholder="Contoh: Rombongan Jawa Barat" class="input-field">
                </div>
            </div>

            {{-- 8 & 9. Tour Leader --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="label">Nama Tour Leader <span class="text-slate-300 normal-case font-normal">(opsional)</span></label>
                    <input type="text" name="tour_leader_name" placeholder="Nama tour leader" class="input-field">
                </div>
                <div>
                    <label class="label">No HP Tour Leader <span class="text-slate-300 normal-case font-normal">(opsional)</span></label>
                    <input type="text" name="tour_leader_phone" placeholder="08xxxxxxxxxx" class="input-field">
                </div>
            </div>

            {{-- Submit --}}
            <div class="mt-6 flex justify-end">
                <button type="submit" class="btn-green" style="padding:12px 28px; font-size:14px;">
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
            <span class="badge-blue">{{ \App\Models\PartnerVisit::whereDate('visit_date', today())->count() }} Kunjungan</span>
        </div>

        @php
            $todayVisits = \App\Models\PartnerVisit::with(['partner', 'guides', 'drivers', 'vehicles'])
                ->whereDate('visit_date', today())
                ->latest()
                ->get();
        @endphp

        @if($todayVisits->isEmpty())
            <div class="text-center py-12">
                <div class="text-4xl mb-3">📋</div>
                <p class="text-sm font-semibold text-gray-400">Belum ada kunjungan hari ini</p>
                <p class="text-xs text-gray-300 mt-1">Data akan muncul setelah form di atas diisi</p>
            </div>
        @else
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="text-sm" style="min-width: 1000px;">
                    <thead>
                        <tr class="bg-slate-50 border-b border-gray-100">
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase tracking-wide">Kode Visit</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase tracking-wide">Tipe</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase tracking-wide">Partner</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase tracking-wide">Guide</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase tracking-wide">Driver</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase tracking-wide">Kendaraan</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase tracking-wide">Sticker</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase tracking-wide">Keterangan</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase tracking-wide">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($todayVisits as $visit)
                        <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition">
                            <td class="px-4 py-3">
                                <span class="font-bold text-blue-600 text-xs">{{ $visit->visit_code }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @php $typeLabel = $visit->visit_type_label ?? $visit->visit_type; @endphp
                                @if($typeLabel === 'travel_agent')
                                    <span class="badge-blue">Travel Agent</span>
                                @elseif($typeLabel === 'freelance')
                                    <span class="badge-yellow">Freelance</span>
                                @else
                                    <span class="badge-purple">No Guide</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-800 text-sm">{{ $visit->partner->name ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @if($visit->guides->isEmpty())
                                    <span class="text-xs text-gray-300">—</span>
                                @else
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($visit->guides as $guide)
                                            <span class="badge-green text-xs">{{ $guide->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($visit->drivers->isEmpty())
                                    <span class="text-xs text-gray-300">—</span>
                                @else
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($visit->drivers as $driver)
                                            <span class="badge-blue text-xs">{{ $driver->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($visit->vehicles->isEmpty())
                                    <span class="text-xs text-gray-300">—</span>
                                @else
                                    <div class="space-y-0.5">
                                        @foreach($visit->vehicles as $v)
                                            <div class="text-xs text-slate-600 font-medium">{{ $v->plate_number }}
                                                <span class="text-gray-400">· {{ $v->vehicle_type }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge-yellow">{{ $visit->sticker_number ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-xs text-slate-600">{{ $visit->group_description ?? '—' }}</div>
                                @if($visit->visitor_nationality)
                                    <span class="text-xs font-semibold {{ $visit->visitor_nationality === 'WNA' ? 'text-red-500' : 'text-blue-500' }}">
                                        {{ $visit->visitor_nationality }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open"
                                        class="px-2 py-1 rounded-lg text-xs font-bold cursor-pointer transition
                                        {{ $visit->status === 'pending' ? 'bg-gray-100 text-gray-500' : '' }}
                                        {{ $visit->status === 'completed' ? 'bg-emerald-100 text-emerald-600' : '' }}">
                                        {{ $visit->status === 'pending' ? 'Pending' : 'Completed' }} ▾
                                    </button>
                                    <div x-show="open" @click.outside="open = false" x-cloak
                                        class="absolute right-0 z-20 mt-1 bg-white border border-gray-100 rounded-xl shadow-lg overflow-hidden w-36">
                                        @foreach(['pending' => 'Pending', 'completed' => 'Completed'] as $val => $label)
                                            @if($visit->status !== $val)
                                                <form method="POST" action="{{ route('security.visits.status', $visit) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="{{ $val }}">
                                                    <button type="submit"
                                                        class="w-full text-left px-4 py-2.5 text-xs font-semibold hover:bg-blue-50 transition
                                                        {{ $val === 'pending' ? 'text-gray-500' : 'text-emerald-600' }}">
                                                        {{ $label }}
                                                    </button>
                                                </form>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3">
    <div class="flex items-center gap-1.5">
        <button onclick="document.getElementById('edit-visit-modal-{{ $visit->id }}').classList.remove('hidden')"
            class="p-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition" title="Edit">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
            </svg>
        </button>
        <form method="POST" action="{{ route('security.visits.destroy', $visit) }}"
            onsubmit="return confirm('Yakin hapus kunjungan ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 transition" title="Hapus">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                </svg>
            </button>
        </form>
    </div>
</td>
                        </tr>

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
                                        <label class="label">Sticker</label>
                                        <input type="text" name="sticker_number" value="{{ $visit->sticker_number }}"
                                            class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-200 focus:border-blue-400 focus:outline-none text-sm bg-slate-50">
                                    </div>
                                    <div x-data="{ vehicles: {{ json_encode($visit->vehicles->map(fn($v) => ['plate' => $v->plate_number, 'type' => $v->vehicle_type])->values()) }} }">
    <div class="flex items-center justify-between mb-2">
        <label class="label" style="margin-bottom:0">Kendaraan</label>
        <button type="button" @click="vehicles.push({plate:'',type:''})" class="text-xs text-blue-500 font-semibold">+ Tambah</button>
    </div>
    <div class="space-y-2">
        <template x-for="(v, i) in vehicles" :key="i">
            <div class="grid grid-cols-12 gap-2">
                <div class="col-span-5">
                    <input type="text" :name="'vehicle_types['+i+']'" x-model="v.type"
                        placeholder="Jenis" class="w-full px-3 py-2 rounded-xl border-2 border-slate-200 focus:border-blue-400 focus:outline-none text-sm bg-slate-50">
                </div>
                <div class="col-span-5">
                    <input type="text" :name="'plate_numbers['+i+']'" x-model="v.plate"
                        placeholder="Plat" class="w-full px-3 py-2 rounded-xl border-2 border-slate-200 focus:border-blue-400 focus:outline-none text-sm bg-slate-50">
                </div>
                <div class="col-span-2">
                    <button type="button" @click="vehicles.splice(i,1)"
                        class="w-full py-2 rounded-xl bg-red-50 hover:bg-red-100 text-red-500 text-xs font-semibold">Hapus</button>
                </div>
            </div>
        </template>
    </div>
</div>
                                    <div>
                                        <label class="label">Keterangan</label>
                                        <input type="text" name="group_description" value="{{ $visit->group_description }}"
                                            class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-200 focus:border-blue-400 focus:outline-none text-sm bg-slate-50">
                                    </div>
                                    <div>
                                        <label class="label">Kewarganegaraan</label>
                                        <select name="visitor_nationality"
                                            class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-200 focus:border-blue-400 focus:outline-none text-sm bg-slate-50">
                                            <option value="">Pilih...</option>
                                            <option value="WNI" {{ $visit->visitor_nationality === 'WNI' ? 'selected' : '' }}>WNI</option>
                                            <option value="WNA" {{ $visit->visitor_nationality === 'WNA' ? 'selected' : '' }}>WNA</option>
                                            <option value="Campuran" {{ $visit->visitor_nationality === 'Campuran' ? 'selected' : '' }}>Campuran</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="label">Status</label>
                                        <select name="status"
                                            class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-200 focus:border-blue-400 focus:outline-none text-sm bg-slate-50">
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

                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

{{-- Partner Modal --}}
<div id="partner-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-5xl p-6 relative" style="max-height:90vh; overflow-y:auto;">
        <button type="button" onclick="document.getElementById('partner-modal').classList.add('hidden')"
            class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center text-sm transition">✕</button>
        <h2 class="text-lg font-bold text-slate-800 mb-5">Tambah Partner Baru</h2>
        <div class="grid grid-cols-2 gap-6">
            <div>
    <div class="space-y-4" id="partner-form">
    <div>
        <label class="label">Nama Partner</label>
        <input type="text" id="partner-name" required class="input-field">
    </div>
    <div>
        <label class="label">Nomor WhatsApp</label>
        <input type="text" id="partner-phone" class="input-field">
    </div>
    <div>
        <label class="label">Alamat</label>
        <textarea id="partner-address" rows="3" class="input-field" style="resize:none"></textarea>
    </div>
    <div>
        <label class="label">Tipe Partner</label>
        <select id="partner-type" class="input-field">
            <option value="travel_agent">Travel Agent</option>
            <option value="freelance_guide">Freelance</option>
        </select>
    </div>
    <div id="partner-msg" class="hidden text-xs font-semibold text-emerald-600 py-1"></div>
    <div class="flex justify-end">
        <button type="button" onclick="savePartner()" class="btn-yellow">Simpan Partner</button>
    </div>
</div>
</div>
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-slate-800">Partner Terdaftar</h3>
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
                            @foreach(\App\Models\Partner::latest()->get() as $partner)
                            <tr class="border-t border-gray-100 hover:bg-blue-50/40 transition">
                                <td class="px-4 py-3 font-bold text-blue-600 text-xs">{{ $partner->code }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-800">{{ $partner->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $partner->phone ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($partner->type === 'travel_agent')
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

{{-- Guide & Driver Modal --}}
{{-- Guide Modal --}}
<div id="guide-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-4xl p-6 relative" style="max-height:90vh; overflow-y:auto;">
        <button type="button" onclick="document.getElementById('guide-modal').classList.add('hidden')"
            class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center text-sm transition">✕</button>
        <h2 class="text-lg font-bold text-slate-800 mb-5">Tambah Guide</h2>
        <div class="grid grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="label">Nama Guide</label>
                    <input type="text" id="guide-name" required class="input-field">
                </div>
                <div>
                    <label class="label">No HP</label>
                    <input type="text" id="guide-phone" class="input-field">
                </div>
                <div>
                    <label class="label">Alamat</label>
                    <textarea id="guide-address" rows="2" class="input-field" style="resize:none"></textarea>
                </div>
                <div id="guide-msg" class="hidden text-xs font-semibold text-emerald-600 py-1"></div>
                <div class="flex justify-end">
                    <button type="button" onclick="saveGuide()" class="btn-green" style="padding:10px 20px;">Simpan Guide</button>
                </div>
            </div>
            <div class="h-80 overflow-y-auto border border-gray-100 rounded-xl">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 sticky top-0">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase">Kode</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase">Nama</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase">Trip</th>
                        </tr>
                    </thead>
                    <tbody id="guide-list-tbody">
                        @foreach(\App\Models\Guide::latest()->get() as $guide)
                        <tr class="border-t border-gray-100 hover:bg-emerald-50/40 transition">
                            <td class="px-4 py-3 font-bold text-emerald-600 text-xs">{{ $guide->guide_code }}</td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-800">{{ $guide->name }}</div>
                                <div class="text-xs text-gray-400">{{ $guide->phone ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge-green">{{ $guide->total_visits ?? 0 }}x</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Driver Modal --}}
<div id="driver-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-4xl p-6 relative" style="max-height:90vh; overflow-y:auto;">
        <button type="button" onclick="document.getElementById('driver-modal').classList.add('hidden')"
            class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center text-sm transition">✕</button>
        <h2 class="text-lg font-bold text-slate-800 mb-5">Tambah Driver / Sopir</h2>
        <div class="grid grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="label">Nama Driver/Sopir</label>
                    <input type="text" id="driver-name" required class="input-field">
                </div>
                <div>
                    <label class="label">No HP</label>
                    <input type="text" id="driver-phone" class="input-field">
                </div>
                <div>
                    <label class="label">Alamat</label>
                    <textarea id="driver-address" rows="2" class="input-field" style="resize:none"></textarea>
                </div>
                <div id="driver-msg" class="hidden text-xs font-semibold text-emerald-600 py-1"></div>
                <div class="flex justify-end">
                    <button type="button" onclick="saveDriver()" class="btn-primary" style="padding:10px 20px;">Simpan Driver</button>
                </div>
            </div>
            <div class="h-80 overflow-y-auto border border-gray-100 rounded-xl">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 sticky top-0">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase">Kode</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-400 uppercase">Nama</th>
                        </tr>
                    </thead>
                    <tbody id="driver-list-tbody">
                        @foreach(\App\Models\Driver::latest()->get() as $driver)
                        <tr class="border-t border-gray-100 hover:bg-blue-50/40 transition">
                            <td class="px-4 py-3 font-bold text-blue-600 text-xs">{{ $driver->driver_code }}</td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-800">{{ $driver->name }}</div>
                                <div class="text-xs text-gray-400">{{ $driver->phone ?? '-' }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Toast --}}
@if(session('success'))
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

{{-- Script --}}
<script>
function securityVisitSystem() {
    return {
        visitType: 'travel_agent',

        // Guide
        guideSearch: '',
        guideResults: [],
        selectedGuides: [],
        guidePhoneInput: '',

        // Driver
        driverSearch: '',
        driverResults: [],
        selectedDrivers: [],
        driverPhoneInput: '',

        async searchGuides() {
            if (this.guideSearch.length < 2) { this.guideResults = []; return; }
            try {
                const res = await fetch(`/security/search-guides?q=${encodeURIComponent(this.guideSearch)}`);
                this.guideResults = await res.json();
            } catch(e) { this.guideResults = []; }
        },

        selectGuide(guide) {
            if (!this.selectedGuides.find(g => g.id === guide.id)) {
                this.selectedGuides.push(guide);
            }
            this.guideSearch = '';
            this.guideResults = [];
        },

        removeGuide(index) { this.selectedGuides.splice(index, 1); },

        async searchDrivers() {
            if (this.driverSearch.length < 2) { this.driverResults = []; return; }
            try {
                const res = await fetch(`/security/search-drivers?q=${encodeURIComponent(this.driverSearch)}`);
                this.driverResults = await res.json();
            } catch(e) { this.driverResults = []; }
        },

        selectDriver(driver) {
            if (!this.selectedDrivers.find(d => d.id === driver.id)) {
                this.selectedDrivers.push(driver);
            }
            this.driverSearch = '';
            this.driverResults = [];
        },

        removeDriver(index) { this.selectedDrivers.splice(index, 1); }
    }
}


const csrfToken = '{{ csrf_token() }}';

async function saveGuide() {
    const name = document.getElementById('guide-name').value;
    if (!name) { alert('Nama guide wajib diisi!'); return; }
    const res = await fetch('{{ route('security.guides.store') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({
            name,
            phone: document.getElementById('guide-phone').value,
            address: document.getElementById('guide-address').value,
        })
    });
    const data = await res.json();
    if (data.success) {
    document.getElementById('guide-name').value = '';
    document.getElementById('guide-phone').value = '';
    document.getElementById('guide-address').value = '';
    const msg = document.getElementById('guide-msg');
    msg.textContent = `✅ Guide ${data.guide.name} (${data.guide.guide_code}) berhasil ditambahkan!`;
    msg.classList.remove('hidden');
    setTimeout(() => msg.classList.add('hidden'), 3000);
    const tbody = document.getElementById('guide-list-tbody');
    const tr = document.createElement('tr');
    tr.className = 'border-t border-gray-100 hover:bg-emerald-50/40 transition';
    tr.innerHTML = `<td class="px-4 py-3 font-bold text-emerald-600 text-xs">${data.guide.guide_code}</td><td class="px-4 py-3"><div class="font-semibold text-slate-800">${data.guide.name}</div><div class="text-xs text-gray-400">${data.guide.phone || '-'}</div></td><td class="px-4 py-3"><span class="badge-green">0x</span></td>`;
    tbody.prepend(tr);
}
}

async function saveDriver() {
    const name = document.getElementById('driver-name').value;
    if (!name) { alert('Nama driver wajib diisi!'); return; }
    const res = await fetch('{{ route('security.drivers.store') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({
            name,
            phone: document.getElementById('driver-phone').value,
            address: document.getElementById('driver-address').value,
        })
    });
    const data = await res.json();
    if (data.success) {
        document.getElementById('driver-name').value = '';
        document.getElementById('driver-phone').value = '';
        document.getElementById('driver-address').value = '';
        const msg = document.getElementById('driver-msg');
msg.textContent = `✅ Driver ${data.driver.name} (${data.driver.driver_code}) berhasil ditambahkan!`;
msg.classList.remove('hidden');
setTimeout(() => msg.classList.add('hidden'), 3000);

// Tambah row ke tabel
const tbody = document.getElementById('driver-list-tbody');
const tr = document.createElement('tr');
tr.className = 'border-t border-gray-100 hover:bg-blue-50/40 transition';
tr.innerHTML = `
    <td class="px-4 py-3 font-bold text-blue-600 text-xs">${data.driver.driver_code}</td>
    <td class="px-4 py-3">
        <div class="font-semibold text-slate-800">${data.driver.name}</div>
        <div class="text-xs text-gray-400">${data.driver.phone ?? '-'}</div>
    </td>
`;
tbody.prepend(tr);
    }
}

async function savePartner() {
    const name = document.getElementById('partner-name').value;
    if (!name) { alert('Nama partner wajib diisi!'); return; }
    const res = await fetch('{{ route('security.partner.store') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({
            name,
            phone: document.getElementById('partner-phone').value,
            address: document.getElementById('partner-address').value,
            type: document.getElementById('partner-type').value,
        })
    });
    const data = await res.json();
    if (data.success) {
        document.getElementById('partner-name').value = '';
        document.getElementById('partner-phone').value = '';
        document.getElementById('partner-address').value = '';
        const msg = document.getElementById('partner-msg');
        msg.textContent = `✅ Partner ${data.partner.name} berhasil ditambahkan!`;
        msg.classList.remove('hidden');
        setTimeout(() => msg.classList.add('hidden'), 3000);
    }
}
</script>

@endsection
