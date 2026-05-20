<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Komisi — {{ $commission->partner->name }}</title>
    <style>
        @page { margin: 10px 0; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Liberation Sans, sans-serif; background: #fff; color: #1e293b; font-size: 12px; }

        .header { background: #1e3a5f; padding: 12px 32px; display: table; width: 100%; }
        .header-left { display: table-cell; vertical-align: middle; }
        .header-right { display: table-cell; vertical-align: middle; text-align: right; }
        .logo { width: 48px; height: 48px; object-fit: contain; vertical-align: middle; margin-right: 10px; }
        .brand-name { font-size: 18px; font-weight: bold; color: #fff; letter-spacing: 1px; display: inline; }
        .brand-sub { font-size: 10px; color: #93c5fd; margin-top: 2px; }
        .doc-title { font-size: 14px; font-weight: bold; color: #fbbf24; text-transform: uppercase; letter-spacing: 2px; }
        .doc-date { font-size: 10px; color: #93c5fd; margin-top: 2px; }

        .accent-bar { height: 3px; background: linear-gradient(to right, #1d4ed8, #fbbf24); }

        .body { padding: 16px 32px; }

        .info-grid { display: table; width: 100%; margin-bottom: 10px; }
        .info-row { display: table-row; }
        .info-cell { display: table-cell; width: 50%; padding: 3px 0; vertical-align: top; }
        .info-label { font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.8px; color: #94a3b8; margin-bottom: 1px; }
        .info-value { font-size: 14px; font-weight: bold; color: #1e293b; }

        .divider { border: none; border-top: 1px solid #e2e8f0; margin: 10px 0; }

        .section-title { font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #64748b; background: #f8fafc; padding: 4px 8px; border-left: 3px solid #1d4ed8; margin-bottom: 8px; }

        .guide-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .guide-table th { text-align: left; font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; padding: 4px 6px; border-bottom: 1px solid #e2e8f0; }
        .guide-table td { padding: 4px 6px; font-size: 12px; border-bottom: 1px solid #f8fafc; color: #334155; }
        .guide-code { font-weight: bold; color: #1d4ed8; }

        .rekap-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 16px; margin-bottom: 10px; }
        .rekap-row { display: table; width: 100%; padding: 4px 0; border-bottom: 1px solid #e2e8f0; }
        .rekap-row:last-child { border-bottom: none; }
        .rekap-label { display: table-cell; font-size: 12px; color: #64748b; width: 50%; }
        .rekap-value { display: table-cell; font-size: 12px; font-weight: bold; color: #1e293b; text-align: right; }
        .rekap-value.green { color: #059669; font-size: 14px; }
        .rekap-value.blue { color: #1d4ed8; }

        .total-box { background: #1e3a5f; border-radius: 6px; padding: 10px 16px; display: table; width: 100%; margin-bottom: 16px; }
        .total-label { display: table-cell; font-size: 12px; font-weight: bold; color: #93c5fd; }
        .total-value { display: table-cell; font-size: 20px; font-weight: bold; color: #fbbf24; text-align: right; }

        .footer { padding: 8px 32px; background: #f8fafc; border-top: 2px solid #1e3a5f; margin-top: 16px; page-break-inside: avoid; }
.footer-notes { font-size: 9px; color: #646b76; line-height: 1.6; text-align: left; }
.footer-contact { display: table; width: 100%; margin-top: 6px; }
.footer-contact-col { display: table-cell; font-size: 12px; color: #1e293b; width: 33.33%; text-align: left; vertical-align: top; padding-right: 8px; }
.footer-contact-label { font-size: 9px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; text-align: left; }
.footer-contact-value { font-weight: bold; color: #1e3a5f; font-size: 12px; text-align: left; }
.footer-generated { font-size: 9px; color: #94a3b8; margin-top: 4px; text-align: left; }
.total-box { background: #1e3a5f; border-radius: 6px; padding: 12px 16px; display: table; width: 100%; margin-bottom: 16px; page-break-inside: avoid; page-break-after: avoid; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-left">
            <img src="{{ asset('assets/gem-pearls-logo.png') }}" class="logo">
            <span class="brand-name">Gem Pearls</span>
            <div class="brand-sub">Jewelry & Souvenir · Lombok, NTB</div>
        </div>
        <div class="header-right">
            <div class="doc-title">Slip Komisi Partner</div>
            <div class="doc-date">{{ $commission->commission_date->format('d F Y') }}</div>
        </div>
    </div>

    <div class="accent-bar"></div>

    <div class="body">

        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell">
                    <div class="info-label">Partner</div>
                    <div class="info-value">{{ $commission->partner->name }}</div>
                </div>
                <div class="info-cell">
                    <div class="info-label">Tipe</div>
                    <div class="info-value">{{ $commission->partner->type === 'travel_agent' ? 'Travel Agent' : 'Freelance Guide' }}</div>
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

        @if($commission->visit && $commission->visit->vehicles->isNotEmpty())
        <div class="section-title">Daftar No Plat Kendaraan</div>
        <div style="margin-bottom: 10px;">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    @foreach($commission->visit->vehicles as $vehicle)
                    <td style="padding: 3px 8px 3px 0; font-size:12px; font-weight:bold; color:#1e293b; white-space:nowrap;">
                        {{ $vehicle->plate_number }}
                        <span style="font-weight:normal; color:#64748b; font-size:10px;">· {{ $vehicle->vehicle_type }}</span>
                    </td>
                    @endforeach
                </tr>
            </table>
        </div>
        @endif

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
                <div class="rekap-value green">Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="total-box">
            <div class="total-label">Total Komisi Anda</div>
            <div class="total-value">Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}</div>
        </div>

    </div>

    <div class="footer">
    <div style="display:table; width:100%;">
        <div style="display:table-cell; width:55%; vertical-align:top; padding-right:16px;">
            <div style="font-size:9px; color:#646b76; line-height:1.8;">
                * Mohon menunjukkan slip ini saat pengambilan komisi<br>
                * Batas pengambilan: {{ $commission->pickup_deadline?->format('d M Y') ?? '3 bulan dari tanggal kunjungan' }}<br>
                * Diterbitkan resmi oleh Gem Pearls Jewelry, Lombok NTB
            </div>
        </div>
        <div style="display:table-cell; width:45%; vertical-align:top; text-align:right;">
            <div style="margin-bottom:3px;">
                <div style="font-size:8px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px;">GEM Pearls Lombok</div>
                <div style="font-weight:bold; color:#1e3a5f; font-size:11px;">081916088775</div>
            </div>
            <div style="margin-bottom:3px;">
                <div style="font-size:8px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px;">Transport Anda</div>
                <div style="font-weight:bold; color:#1e3a5f; font-size:11px;">0817141818</div>
            </div>
            <div>
                <div style="font-size:8px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px;">GEM Resto</div>
                <div style="font-weight:bold; color:#1e3a5f; font-size:11px;">08175713400 · 081236450391</div>
            </div>
        </div>
    </div>
    <div style="font-size:9px; color:#94a3b8; text-align:center; margin-top:6px; border-top:1px solid #e2e8f0; padding-top:4px;">
        Generated by Gem Pearls Lombok · {{ now()->format('d/m/Y H:i') }}
    </div>
</div>

</body>
</html>
