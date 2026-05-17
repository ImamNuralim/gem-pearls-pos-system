<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk - {{ $transaction->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 320px;
            margin: 0 auto;
            padding: 12px;
            background: #fff;
            color: #1a1a1a;
        }

        .header {
            text-align: center;
            padding-bottom: 8px;
        }

        .store-name {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
            font-family: Arial, sans-serif;
        }

        .store-tagline {
            font-size: 10px;
            color: #555;
            margin-top: 2px;
        }

        .divider {
            border: none;
            border-top: 1px dashed #aaa;
            margin: 7px 0;
        }

        .divider-solid {
            border: none;
            border-top: 2px solid #1a1a1a;
            margin: 7px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
            font-size: 11px;
        }

        .row .label { color: #555; }
        .row .value { font-weight: bold; text-align: right; }

        .item-block { margin: 5px 0; }
        .item-name { font-weight: bold; font-size: 11px; }

        .item-detail {
            display: flex;
            justify-content: space-between;
            padding-left: 8px;
            font-size: 11px;
            color: #333;
        }

        .struck {
            text-decoration: line-through;
            color: #999;
            font-size: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 15px;
            font-weight: bold;
            font-family: Arial, sans-serif;
            margin: 4px 0;
        }

        .badge {
            display: inline-block;
            background: #1a1a1a;
            color: #fff;
            font-size: 9px;
            padding: 1px 5px;
            border-radius: 3px;
            font-family: Arial, sans-serif;
            vertical-align: middle;
        }

        .nego-badge {
            background: #dc2626;
        }

        .poin-badge {
            background: #059669;
        }

        .thank-you {
            text-align: center;
            margin: 8px 0 4px;
            font-family: Arial, sans-serif;
        }

        .thank-you .main {
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .thank-you .sub {
            font-size: 10px;
            color: #666;
            margin-top: 2px;
        }

        .footer-info {
            text-align: center;
            font-size: 10px;
            color: #555;
            line-height: 1.8;
            font-family: Arial, sans-serif;
        }

        .footer-info .contact-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .no-print { margin-top: 20px; text-align: center; }

        .btn-print {
            padding: 9px 22px;
            background: #1a1a1a;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-family: Arial, sans-serif;
        }

        .btn-close {
            padding: 9px 22px;
            background: #e5e7eb;
            color: #333;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-family: Arial, sans-serif;
            margin-left: 8px;
        }

        .btn-wa {
            display: inline-block;
            margin-top: 10px;
            padding: 9px 22px;
            background: #25d366;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-family: Arial, sans-serif;
        }

       @media print {
    @page {
        size: 80mm auto;
        margin: 0;
    }
    body {
        width: 80mm;
        margin: 0;
        padding: 5px;
    }
    .no-print { display: none; }
}
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div class="store-name">&#9830; GEM PEARLS</div>
        <div class="store-tagline">Perhiasan & Oleh-oleh Lombok</div>
        <div class="store-tagline">Lombok, Nusa Tenggara Barat</div>
    </div>

    <div class="divider-solid"></div>

    {{-- Info Transaksi --}}
    <div class="row"><span class="label">Invoice</span><span class="value">{{ $transaction->invoice_number }}</span></div>
    <div class="row"><span class="label">Tanggal</span><span class="value">{{ $transaction->created_at->format('d/m/Y H:i') }}</span></div>
    <div class="row"><span class="label">Kasir</span><span class="value">{{ $transaction->user->name ?? '-' }}</span></div>
    <div class="row"><span class="label">Customer</span><span class="value">{{ ucfirst(str_replace('_', ' ', $transaction->customer_type)) }}</span></div>
    @if($transaction->partner)
        <div class="row"><span class="label">Mitra</span><span class="value">{{ $transaction->partner->name }}</span></div>
    @endif
    @if($transaction->member)
        <div class="row"><span class="label">Member</span><span class="value">{{ $transaction->member->name }}</span></div>
    @endif

    <div class="divider"></div>

    {{-- Items --}}
    @foreach($transaction->items as $item)
    <div class="item-block">
        <div class="item-name">
            {{ $item->product_name }}
            @if($item->final_price != $item->original_price)
                <span class="badge nego-badge">NEGO</span>
            @endif
        </div>
        <div class="item-detail">
            <span>
                {{ $item->quantity }}x
                @if($item->final_price != $item->original_price)
                    <span class="struck">Rp {{ number_format($item->original_price, 0, ',', '.') }}</span>
                    Rp {{ number_format($item->final_price, 0, ',', '.') }}
                @else
                    Rp {{ number_format($item->final_price, 0, ',', '.') }}
                @endif
            </span>
            <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
        </div>
    </div>
    @endforeach

    <div class="divider"></div>

    {{-- Summary --}}
    <div class="row"><span class="label">Subtotal</span><span class="value">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span></div>
    @if($transaction->points_discount > 0)
        <div class="row">
            <span class="label">Diskon Poin <span class="badge poin-badge">{{ $transaction->points_redeemed }} poin</span></span>
            <span class="value">- Rp {{ number_format($transaction->points_discount, 0, ',', '.') }}</span>
        </div>
    @endif
    @if($transaction->admin_fee > 0)
        <div class="row"><span class="label">Admin Fee</span><span class="value">Rp {{ number_format($transaction->admin_fee, 0, ',', '.') }}</span></div>
    @endif

    <div class="divider-solid"></div>

    <div class="total-row">
        <span>TOTAL</span>
        <span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
    </div>

    <div class="row"><span class="label">Bayar ({{ strtoupper($transaction->payment_method) }})</span><span class="value">Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}</span></div>
    @if($transaction->change_amount > 0)
        <div class="row"><span class="label">Kembalian</span><span class="value">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span></div>
    @endif

    <div class="divider"></div>

    {{-- Thank You --}}
    <div class="thank-you">
        <div class="main">TERIMA KASIH</div>
        <div class="sub">Selamat berbelanja di Gem Pearls</div>
        <div class="sub">Simpan struk ini sebagai bukti pembelian</div>
    </div>

    <div class="divider"></div>

    {{-- Footer Info --}}
    <div class="footer-info">
        <div class="contact-item">&#128222; 081916088775</div>
        <div class="contact-item">&#128247; Follow @gempearlsjewelry</div>
        <div class="contact-item">&#128722; Shopee GEM Pearls Lombok</div>
    </div>

    <div class="divider"></div>

    {{-- Actions --}}
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">&#128424; Cetak Struk</button>
        <button onclick="window.close()" class="btn-close">&#10005; Tutup</button>
    </div>
    <script>
    // Auto print kalau dibuka dengan parameter ?print=1
    window.addEventListener('load', function() {
        if (new URLSearchParams(window.location.search).get('print') === '1') {
            setTimeout(() => window.print(), 500);
        }
    });
</script>

</body>
</html>
