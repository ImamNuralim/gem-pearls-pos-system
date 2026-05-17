<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Komisi — {{ $commission->partner->name }}</title>
    <style>
        @page { margin: 0; size: A4; }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Liberation Sans, sans-serif;
            background: #fff;
            color: #1e293b;
            font-size: 12px;
        }

        /* HEADER */
        .header {
    background: #1e3a5f;
    padding: 16px 36px;
    display: table;
    width: 100%;
}

.header-left {
    display: table-cell;
    vertical-align: middle;
}

.header-right {
    display: table-cell;
    vertical-align: middle;
    text-align: right;
}

        .logo {
            width: 64px;
            height: 64px;
            object-fit: contain;
        }

        .brand-name {
            font-size: 22px;
            font-weight: bold;
            color: #fff;
            letter-spacing: 1px;
        }

        .brand-sub {
            font-size: 11px;
            color: #93c5fd;
            margin-top: 2px;
        }


        .doc-title {
            font-size: 14px;
            font-weight: bold;
            color: #fbbf24;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .doc-date {
            font-size: 11px;
            color: #93c5fd;
            margin-top: 4px;
        }

        /* ACCENT BAR */
        .accent-bar {
            height: 4px;
            background: linear-gradient(to right, #1d4ed8, #fbbf24);
        }

        /* BODY */
        .body {
            padding: 30px 36px;
        }

        /* INFO GRID */
        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .info-row {
            display: table-row;
        }

        .info-cell {
            display: table-cell;
            width: 50%;
            padding: 6px 0;
            vertical-align: top;
        }

        .info-label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #94a3b8;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 13px;
            font-weight: bold;
            color: #1e293b;
        }

        /* DIVIDER */
        .divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 20px 0;
        }

        .divider-bold {
            border: none;
            border-top: 2px solid #1e3a5f;
            margin: 20px 0;
        }

        /* SECTION TITLE */
        .section-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            background: #f8fafc;
            padding: 6px 10px;
            border-left: 3px solid #1d4ed8;
            margin-bottom: 12px;
        }

        /* PLAT TABLE */
        .plat-list {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .plat-row {
            display: table-row;
        }

        .plat-cell {
            display: table-cell;
            padding: 5px 8px;
            font-size: 11px;
            border-bottom: 1px solid #f1f5f9;
        }

        .plat-cell.bold { font-weight: bold; color: #1e293b; }
        .plat-cell.muted { color: #64748b; }

        /* GUIDE TABLE */
        .guide-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .guide-table th {
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
        }

        .guide-table td {
            padding: 7px 8px;
            font-size: 11px;
            border-bottom: 1px solid #f8fafc;
            color: #334155;
        }

        .guide-code {
            font-weight: bold;
            color: #1d4ed8;
        }

        /* REKAP KOMISI */
        .rekap-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .rekap-row {
            display: table;
            width: 100%;
            padding: 6px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .rekap-row:last-child { border-bottom: none; }

        .rekap-label {
            display: table-cell;
            font-size: 11px;
            color: #64748b;
            width: 50%;
        }

        .rekap-value {
            display: table-cell;
            font-size: 11px;
            font-weight: bold;
            color: #1e293b;
            text-align: right;
        }

        .rekap-value.green { color: #059669; }
        .rekap-value.blue { color: #1d4ed8; }

        /* TOTAL BOX */
        .total-box {
            background: #1e3a5f;
            border-radius: 8px;
            padding: 16px 20px;
            display: table;
            width: 100%;
            margin-bottom: 24px;
        }

        .total-label {
            display: table-cell;
            font-size: 13px;
            font-weight: bold;
            color: #93c5fd;
        }

        .total-value {
            display: table-cell;
            font-size: 22px;
            font-weight: bold;
            color: #fbbf24;
            text-align: right;
        }

        /* SIGNATURE */
        .signature-area {
            display: table;
            width: 100%;
            margin-top: 30px;
        }

        .signature-cell {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 20px;
        }

       .signature-title {
    font-size: 10px;
    color: #64748b;
    margin-bottom: 60px;
}

.signature-role {
    font-size: 9px;
    color: #94a3b8;
    margin-top: 6px;
}

        .signature-line {
            border-top: 1px solid #1e293b;
            padding-top: 6px;
            font-size: 11px;
            font-weight: bold;
            color: #1e293b;
        }


        /* FOOTER */
        .footer {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 10px 36px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

        .footer-notes {
            font-size: 9px;
            color: #646b76;
            line-height: 1.8;
        }

        .footer-generated {
            font-size: 9px;
            color: #818d9c;
            text-align: right;
            margin-top: 6px;
        }

        /* BADGE */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-blue { background: #dbeafe; color: #1d4ed8; }
        .badge-amber { background: #fef3c7; color: #d97706; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <div class="header-left">
            <img src="{{ public_path('assets/gem-pearls-logo.png') }}" class="logo">
            <div>
                <div class="brand-name">Gem Pearls</div>
                <div class="brand-sub">Jewelry & Souvenir · Lombok, NTB</div>
            </div>
        </div>
        <div class="header-right">
            <div class="doc-title">Slip Komisi Partner</div>
            <div class="doc-date">{{ $commission->commission_date->format('d F Y') }}</div>
        </div>
    </div>

    <div class="accent-bar"></div>

    {{-- BODY --}}
    <div class="body">

        {{-- INFO UTAMA --}}
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell">
                    <div class="info-label">Partner</div>
                    <div class="info-value">{{ $commission->partner->name }}</div>
                </div>
                <div class="info-cell">
                    <div class="info-label">Tipe</div>
                    <div class="info-value">
                        {{ $commission->partner->type === 'travel_agent' ? 'Travel Agent' : 'Freelance Guide' }}
                    </div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-cell">
                    <div class="info-label">No Sticker</div>
                    <div class="info-value">{{ $commission->sticker_number ?? '-' }}</div>
                </div>
                <div class="info-cell">
                    <div class="info-label">Tanggal Kunjungan</div>
                    <div class="info-value">{{ $commission->visit_date?->format('d M Y') ?? '-' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-cell">
                    <div class="info-label">Deskripsi Rombongan</div>
                    <div class="info-value">{{ $commission->group_description ?? '-' }}</div>
                </div>
                <div class="info-cell">
                    <div class="info-label">Batas Pengambilan</div>
                    <div class="info-value">{{ $commission->pickup_deadline?->format('d M Y') ?? '-' }}</div>
                </div>
            </div>
        </div>

        <hr class="divider">

        {{-- DAFTAR PLAT --}}
        @if($commission->visit && $commission->visit->vehicles->isNotEmpty())
        <div class="section-title">Daftar No Plat Kendaraan</div>
        <div style="margin-bottom: 16px;">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    @foreach($commission->visit->vehicles as $vehicle)
                    <td style="padding: 4px 10px 4px 0; font-size:12px; font-weight:bold; color:#1e293b; white-space:nowrap;">
                        {{ $vehicle->plate_number }}
                        <span style="font-weight:normal; color:#64748b; font-size:10px;">· {{ $vehicle->vehicle_type }}</span>
                    </td>
                    @endforeach
                </tr>
            </table>
        </div>
        @endif

        {{-- DAFTAR GUIDE --}}
        @if($commission->guides->isNotEmpty())
        <div class="section-title">Daftar Guide / Driver</div>
        <table class="guide-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>No. HP</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commission->guides as $guide)
                <tr>
                    <td class="guide-code">{{ $guide->guide_code }}</td>
                    <td>{{ $guide->name }}</td>
                    <td>{{ $guide->phone ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <hr class="divider">

        {{-- REKAP KOMISI --}}
        <div class="section-title">Rekap Komisi</div>
        <div class="rekap-box">
            <div class="rekap-row">
                <div class="rekap-label">Total Belanja Tamu</div>
                <div class="rekap-value blue">Rp {{ number_format($commission->total_sales, 0, ',', '.') }}</div>
            </div>
            <div class="rekap-row">
                <div class="rekap-label">Persentase Komisi</div>
                <div class="rekap-value">{{ number_format($commission->commission_rate, 2) }}%</div>
            </div>
            <div class="rekap-row">
                <div class="rekap-label" style="font-weight:bold; color:#1e293b;">Total Komisi</div>
                <div class="rekap-value green" style="font-size:14px;">Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- SIGNATURE --}}
        <div class="signature-area">
            <div class="signature-cell">
                <div class="signature-title">Penerima Komisi</div>
                <div class="signature-line">{{ $commission->partner->name }}</div>
                <div class="signature-role">Partner</div>
            </div>
            <div class="signature-cell">
                <div class="signature-title">Disetujui Oleh</div>
                <div class="signature-line">Gem Pearls</div>
                <div class="signature-role">Management</div>
            </div>
        </div>

    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <div class="footer-notes">
            * Mohon menunjukkan slip ini saat pengambilan komisi<br>
            * Batas pengambilan: {{ $commission->pickup_deadline?->format('d M Y') ?? '3 bulan dari tanggal kunjungan' }}<br>
            * Slip ini diterbitkan secara resmi oleh Gem Pearls Jewelry, Lombok NTB
        </div>
        <div class="footer-generated">
            Generated by Gem Pearls Lombok · {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

</body>
</html>
