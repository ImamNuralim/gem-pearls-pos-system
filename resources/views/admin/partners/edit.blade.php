@extends('layouts.app')
@section('title', 'Edit Mitra')

@section('content')
<style>
    .card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
    .input-field { width:100%; padding:9px 14px; border-radius:10px; border:1.5px solid #e2e8f0; font-size:13px; outline:none; transition:border-color 0.2s, box-shadow 0.2s; background:#f8fafc; color:#1e293b; font-family:'Poppins',sans-serif; }
    .input-field:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.1); background:#fff; }
    .input-field[readonly] { background:#f1f5f9; color:#94a3b8; cursor:not-allowed; }
    .label { display:block; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; color:#64748b; margin-bottom:6px; }
    .section-title { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:#94a3b8; padding-bottom:12px; border-bottom:1px solid #f1f5f9; margin-bottom:16px; }
</style>

<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-5">
        <a href="{{ route('admin.partners.index') }}"
            class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-slate-800">Edit Mitra</h2>
            <code class="text-xs bg-slate-100 px-2 py-0.5 rounded-lg text-slate-500 font-mono">{{ $partner->code }}</code>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.partners.update', $partner) }}" class="space-y-4">
        @csrf @method('PUT')

        <div class="card p-5">
            <p class="section-title">Informasi Mitra</p>
            <div class="space-y-4">

                <div>
                    <label class="label">Tipe</label>
                    <input type="text" value="{{ $partner->type === 'travel_agent' ? 'Travel Agent' : 'Freelance Guide' }}" readonly class="input-field">
                </div>

                <div>
                    <label class="label">Nama <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $partner->name) }}" required class="input-field">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">No. HP</label>
                        <input type="text" name="phone" value="{{ old('phone', $partner->phone) }}" class="input-field" placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label class="label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $partner->email) }}" class="input-field" placeholder="email@example.com">
                    </div>
                </div>

                <div>
                    <label class="label">Persentase Komisi Default (%)</label>
                    <div class="relative">
                        <input type="number" name="commission_rate" value="{{ old('commission_rate', $partner->commission_rate) }}"
                            step="0.1" min="0" max="100" class="input-field" style="padding-right:32px;">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-semibold">%</span>
                    </div>
                </div>

                <div>
                    <label class="label">Catatan</label>
                    <textarea name="notes" rows="3" class="input-field" style="resize:none;" placeholder="Catatan tambahan (opsional)">{{ old('notes', $partner->notes) }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                        {{ $partner->is_active ? 'checked' : '' }}
                        class="rounded border-slate-300 text-blue-500 focus:ring-blue-400">
                    <label for="is_active" class="text-sm text-slate-600 font-medium">Mitra Aktif</label>
                </div>

            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.partners.index') }}"
                class="flex-1 text-center py-3 rounded-xl border border-slate-200 text-sm text-slate-500 hover:bg-slate-50 transition font-medium">
                Batal
            </a>
            <button type="submit"
                class="flex-1 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition shadow-sm flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                Update Mitra
            </button>
        </div>
    </form>
</div>
@endsection
