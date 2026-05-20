@extends('layouts.app')
@section('title', 'Komisi Partner')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Komisi Partner</h1>
            <p class="text-sm text-slate-400 mt-0.5">Tracking komisi travel agent & freelance</p>
        </div>
        <form method="GET" class="flex gap-2">
    <input type="text" name="search" value="{{ $search ?? '' }}"
        placeholder="Cari partner, sticker..."
        class="px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white w-64">
    <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
        Cari
    </button>
</form>
        <button onclick="document.getElementById('create-commission-modal').classList.remove('hidden')"
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Buat Komisi
        </button>

    </div>

    @if(session('success'))
        <div class="px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs font-bold uppercase text-slate-400 tracking-wide">Total Komisi</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $commissions->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs font-bold uppercase text-slate-400 tracking-wide">Belum Dibayar</p>
            <p class="text-2xl font-bold text-red-500 mt-1">{{ $commissions->where('status', 'unpaid')->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs font-bold uppercase text-slate-400 tracking-wide">Sudah Dibayar</p>
            <p class="text-2xl font-bold text-emerald-500 mt-1">{{ $commissions->where('status', 'paid')->count() }}</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-400">Tanggal</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-400">Partner</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-400">Tipe</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-400">Total Belanja</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-400">Komisi</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-400">Status</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($commissions as $commission)
                    <tr class="hover:bg-blue-50/20 transition">
                        <td class="px-5 py-3.5 text-xs text-slate-500 font-medium">{{ $commission->commission_date?->format('d/m/y  ') }}</td>
                        <td class="px-5 py-3.5">
                            <div class="font-semibold text-slate-800 text-sm">{{ $commission->partner->name }}</div>
                            @if($commission->visit)
                                <div class="text-xs text-blue-500 font-medium">{{ $commission->visit->visit_code }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            @if($commission->partner->type === 'travel_agent')
                                <span class="px-2 py-1 rounded-lg bg-blue-100 text-blue-600 text-xs font-semibold">Travel Agent</span>
                            @else
                                <span class="px-2 py-1 rounded-lg bg-amber-100 text-amber-600 text-xs font-semibold">Freelance</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 font-bold text-slate-800">Rp {{ number_format($commission->total_sales, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5">
                            <form action="{{ route('admin.commissions.update-rate', $commission) }}" method="POST" class="space-y-1">
                                @csrf
                                <div class="flex items-center gap-1.5">
                                    <input type="number" name="commission_rate" value="{{ $commission->commission_rate }}"
                                        step="0.01" min="0" max="100"
                                        class="w-16 px-2 py-1 rounded-lg border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50">
                                    <span class="text-xs text-slate-400">%</span>
                                    <button type="submit" class="px-2 py-1 rounded-lg bg-amber-500 text-white text-xs font-semibold hover:bg-amber-600 transition">Simpan</button>
                                </div>
                            </form>
                            <div class="mt-1 font-bold text-emerald-600 text-sm">Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            @if($commission->status === 'paid')
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-600 text-xs font-bold">PAID</span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg bg-red-100 text-red-500 text-xs font-bold">UNPAID</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1.5">
                                {{-- Detail --}}
                                <button onclick="document.getElementById('detail-modal-{{ $commission->id }}').classList.remove('hidden')"
                                    class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition" title="Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                </button>
                                {{-- Mark Paid --}}
                                @if($commission->status === 'unpaid')
                                    <form action="{{ route('admin.commissions.paid', $commission) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-1.5 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-600 transition" title="Mark Paid">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                                {{-- JPG --}}
@if($commission->status === 'paid')
    <button onclick="downloadAsJpg('{{ route('admin.commissions.view', $commission) }}', 'komisi-{{ $commission->partner->name }}-{{ $commission->commission_date->format('d-m-Y') }}')"
        class="p-1.5 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition" title="Download JPG">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
        </svg>
    </button>
@endif
                                {{-- Delete --}}
                                <form action="{{ route('admin.commissions.destroy', $commission) }}" method="POST"
                                    onsubmit="return confirm('Hapus komisi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 transition" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- Detail Modal --}}
                    <div id="detail-modal-{{ $commission->id }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                        <div class="bg-white rounded-2xl w-full max-w-2xl p-6 relative" style="max-height:90vh; overflow-y:auto;">
                            <button onclick="document.getElementById('detail-modal-{{ $commission->id }}').classList.add('hidden')"
                                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                </svg>
                            </button>

                            <h2 class="text-lg font-bold text-slate-800 mb-1">Detail Komisi</h2>
                            <p class="text-xs text-slate-400 mb-5">{{ $commission->visit?->visit_code ?? '-' }}</p>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="p-3 rounded-xl bg-slate-50">
                                    <p class="text-xs font-bold uppercase text-slate-400">Partner</p>
                                    <p class="font-semibold text-slate-800 mt-1">{{ $commission->partner->name ?? '-' }}</p>
                                </div>
                                <div class="p-3 rounded-xl bg-slate-50">
                                    <p class="text-xs font-bold uppercase text-slate-400">No Sticker</p>
                                    <p class="font-semibold text-slate-800 mt-1">{{ $commission->sticker_number ?? '-' }}</p>
                                </div>
                                <div class="p-3 rounded-xl bg-slate-50">
                                    <p class="text-xs font-bold uppercase text-slate-400">Tanggal Kunjungan</p>
                                    <p class="font-semibold text-slate-800 mt-1">{{ $commission->visit_date?->format('d M Y') ?? '-' }}</p>
                                </div>
                                <div class="p-3 rounded-xl bg-slate-50">
                                    <p class="text-xs font-bold uppercase text-slate-400">Deadline Pengambilan</p>
                                    <p class="font-semibold text-slate-800 mt-1">{{ $commission->pickup_deadline?->format('d M Y') ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="p-3 rounded-xl bg-slate-50 mb-3">
                                <p class="text-xs font-bold uppercase text-slate-400">Deskripsi Rombongan</p>
                                <p class="text-sm text-slate-700 mt-1">{{ $commission->group_description ?? '-' }}</p>
                            </div>

                            <div class="p-3 rounded-xl bg-slate-50 mb-4">
                                <p class="text-xs font-bold uppercase text-slate-400">Keterangan Kendaraan</p>
                                <p class="text-sm text-slate-700 mt-1">{{ $commission->vehicle_notes ?? '-' }}</p>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 mb-4">
    <p class="text-xs font-bold uppercase text-slate-400 mb-2">Daftar Kendaraan</p>
    @forelse($commission->visit?->vehicles ?? [] as $vehicle)
        <div class="flex items-center justify-between px-3 py-2 rounded-lg border border-slate-100 bg-white mb-1.5">
            <span class="font-semibold text-slate-800 text-sm">{{ $vehicle->plate_number }}</span>
            <span class="text-xs text-slate-400">{{ $vehicle->vehicle_type ?? '-' }}</span>
        </div>
    @empty
        <p class="text-sm text-slate-400">Belum ada kendaraan</p>
    @endforelse
</div>

                            {{-- Total Belanja --}}
                            <div class="p-3 rounded-xl bg-blue-50 border border-blue-100 mb-4 flex items-center justify-between">
                                <p class="text-xs font-bold uppercase text-blue-400">Total Belanja</p>
                                <p class="font-bold text-blue-700 text-lg">Rp {{ number_format($commission->total_sales, 0, ',', '.') }}</p>
                            </div>

                            <hr class="my-4 border-slate-100">

                            {{-- Guide / Driver --}}
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="font-bold text-slate-800">Guide / Driver</h3>
                                </div>

                                @forelse($commission->guides as $guide)
                                    <div class="flex items-center gap-3 px-4 py-2.5 rounded-xl border border-slate-100 bg-slate-50 mb-2">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-blue-600">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-800 text-sm">{{ $guide->guide_code }} — {{ $guide->name }}</div>
                                            <div class="text-xs text-slate-400">{{ $guide->phone ?? '-' }}</div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-400 text-center py-4">Belum ada guide</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-2 text-slate-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.036 0-1.875.84-1.875 1.875S10.964 12 12 12s1.875.84 1.875 1.875S13.036 15.75 12 15.75m0-7.5c1.036 0 1.875.84 1.875 1.875M12 15.75v1.5m-7.5-6h15"/>
                                </svg>
                                <p class="text-sm">Belum ada data komisi</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Create Commission Modal --}}
<div id="create-commission-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
    x-data="{ selectedVisit: null, visits: @json($availableVisits) }">
    <div class="bg-white rounded-2xl w-full max-w-lg p-6 relative" style="max-height:90vh; overflow-y:auto;">
        <button onclick="document.getElementById('create-commission-modal').classList.add('hidden')"
            class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
        <h2 class="text-lg font-bold text-slate-800 mb-1">Buat Komisi</h2>
        <p class="text-xs text-slate-400 mb-5">Pilih kunjungan yang sudah selesai</p>

        <form action="{{ route('admin.commissions.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="text-xs font-bold uppercase text-slate-400 block mb-1.5">Kunjungan</label>
                <select name="partner_visit_id" required
                    @change="selectedVisit = visits.find(v => v.id == $event.target.value) || null"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50">
                    <option value="">Pilih kunjungan...</option>
                    @foreach($availableVisits as $visit)
                        <option value="{{ $visit->id }}">
                            {{ $visit->visit_code }} — {{ $visit->partner->name }} ({{ $visit->visit_date->format('d M Y') }})
                        </option>
                    @endforeach
                </select>
                @if($availableVisits->isEmpty())
                    <p class="text-xs text-slate-400 mt-1">Belum ada kunjungan completed yang tersedia</p>
                @endif
            </div>

            {{-- Info Visit --}}
            <div x-show="selectedVisit" x-cloak class="rounded-xl bg-slate-50 border border-slate-100 p-4 space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-xs font-bold uppercase text-slate-400">Partner</p>
                        <p class="font-semibold text-slate-800 text-sm mt-0.5" x-text="selectedVisit?.partner?.name ?? '-'"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-slate-400">No Sticker</p>
                        <p class="font-semibold text-slate-800 text-sm mt-0.5" x-text="selectedVisit?.sticker_number ?? '-'"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-slate-400">Tanggal</p>
                        <p class="font-semibold text-slate-800 text-sm mt-0.5" x-text="selectedVisit?.visit_date ?? '-'"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-slate-400">Deadline</p>
                        <p class="font-semibold text-slate-800 text-sm mt-0.5" x-text="selectedVisit?.pickup_deadline ?? '-'"></p>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase text-slate-400">Deskripsi</p>
                    <p class="text-sm text-slate-600 mt-0.5" x-text="selectedVisit?.group_description ?? '-'"></p>
                </div>
                <div class="flex items-center justify-between pt-1 border-t border-slate-200">
                    <p class="text-xs font-bold uppercase text-slate-400">Total Belanja</p>
                    <p class="font-bold text-blue-600 text-base"
                        x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(selectedVisit?.total_sales ?? 0)"></p>
                </div>
            </div>

            <div>
                <label class="text-xs font-bold uppercase text-slate-400 block mb-1.5">Persentase Komisi</label>
                <div class="flex items-center gap-2">
                    <input type="number" name="commission_rate" min="0" max="100" step="0.01"
                        placeholder="Contoh: 10"
                        class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50">
                    <span class="text-sm font-semibold text-slate-400">%</span>
                </div>
            </div>

            <div class="flex justify-end pt-1">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-sm">
                    Buat Komisi
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function downloadAsJpg(url, filename) {
    const loadingEl = document.createElement('div');
    loadingEl.innerHTML = '<div style="position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;display:flex;align-items:center;justify-content:center;"><div style="background:white;padding:24px;border-radius:12px;font-weight:600;">Membuat gambar...</div></div>';
    document.body.appendChild(loadingEl);

    const iframe = document.createElement('iframe');
    iframe.style.cssText = 'position:fixed;left:-9999px;top:0;width:794px;height:1px;border:none;';
    document.body.appendChild(iframe);

    iframe.onload = function() {
        setTimeout(function() {
            const doc = iframe.contentDocument || iframe.contentWindow.document;
            const body = doc.body;
            const height = Math.max(body.scrollHeight, body.offsetHeight);
            iframe.style.height = height + 'px';

            setTimeout(function() {
                html2canvas(body, {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    width: 794,
                    height: height,
                    windowWidth: 794,
                }).then(function(canvas) {
                    const link = document.createElement('a');
                    link.download = filename + '.jpg';
                    link.href = canvas.toDataURL('image/jpeg', 0.95);
                    link.click();
                    document.body.removeChild(iframe);
                    document.body.removeChild(loadingEl);
                });
            }, 500);
        }, 300);
    };

    iframe.src = url;
}
</script>
@endpush


@endsection
