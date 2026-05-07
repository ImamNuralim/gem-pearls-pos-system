<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Struk - {{ $transaction->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 300px;
            margin: 0 auto;
            padding: 10px;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }

        .item-name {
            font-weight: bold;
        }

        .struck {
            text-decoration: line-through;
            color: #999;
        }

        @media print {
            body {
                width: 100%;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="center bold" style="font-size:14px">💎 GEM PEARLS</div>
    <div class="center">Lombok, NTB</div>
    <div class="center" style="font-size:10px">Perhiasan & Oleh-oleh Lombok</div>
    <div class="divider"></div>

    <div class="row"><span>Invoice</span><span class="bold">{{ $transaction->invoice_number }}</span></div>
    <div class="row"><span>Tanggal</span><span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span></div>
    <div class="row"><span>Kasir</span><span>{{ $transaction->user->name ?? '-' }}</span></div>
    <div class="row">
        <span>Customer</span><span>{{ ucfirst(str_replace('_', ' ', $transaction->customer_type)) }}</span></div>
    @if ($transaction->partner)
        <div class="row"><span>Mitra</span><span>{{ $transaction->partner->name }}</span></div>
    @endif
    @if ($transaction->member)
        <div class="row"><span>Member</span><span>{{ $transaction->member->name }}</span></div>
    @endif

    <div class="divider"></div>

    @foreach ($transaction->items as $item)
        <div class="item-name">{{ $item->product_name }}</div>
        <div class="row" style="padding-left:8px">
            <span>{{ $item->quantity }}x
                @if ($item->final_price != $item->original_price)
                    <span class="struck">Rp {{ number_format($item->original_price, 0, ',', '.') }}</span>
                    Rp {{ number_format($item->final_price, 0, ',', '.') }}
                @else
                    Rp {{ number_format($item->final_price, 0, ',', '.') }}
                @endif
            </span>
            <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
        </div>
    @endforeach

    <div class="divider"></div>

    <div class="row"><span>Subtotal</span><span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
    </div>
    @if ($transaction->points_discount > 0)
        <div class="row"><span>Diskon Poin</span><span>- Rp
                {{ number_format($transaction->points_discount, 0, ',', '.') }}</span></div>
    @endif
    @if ($transaction->admin_fee > 0)
        <div class="row"><span>Admin Fee</span><span>Rp
                {{ number_format($transaction->admin_fee, 0, ',', '.') }}</span></div>
    @endif
    <div class="divider"></div>
    <div class="row bold" style="font-size:14px">
        <span>TOTAL</span>
        <span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
    </div>
    <div class="row"><span>Bayar ({{ $transaction->payment_method }})</span><span>Rp
            {{ number_format($transaction->amount_paid, 0, ',', '.') }}</span></div>
    @if ($transaction->change_amount > 0)
        <div class="row"><span>Kembalian</span><span>Rp
                {{ number_format($transaction->change_amount, 0, ',', '.') }}</span></div>
    @endif
    @if ($transaction->is_negotiated)
        <div style="text-align:center; margin-top:4px; font-size:10px; color:#888">* Harga Nego</div>
    @endif

    <div class="divider"></div>
    <div class="center" style="margin-top:4px">Terima kasih telah berbelanja!</div>
    <div class="center" style="font-size:10px">Gem Pearls Lombok 🌺</div>

    <div class="no-print" style="margin-top:16px; text-align:center">
        <button onclick="window.print()"
            style="padding:8px 20px; background:#d97706; color:white; border:none; border-radius:8px; cursor:pointer; font-size:13px">
            🖨️ Cetak Struk
        </button>
        <button onclick="window.close()"
            style="padding:8px 20px; background:#e5e7eb; border:none; border-radius:8px; cursor:pointer; font-size:13px; margin-left:8px">
            ✕ Tutup
        </button>
        @if ($transaction->customer_phone)
            <br><br>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $transaction->customer_phone) }}?text={{ urlencode('Halo! Berikut struk pembelian Anda di Gem Pearls Lombok. Invoice: ' . $transaction->invoice_number . ' | Total: Rp ' . number_format($transaction->total, 0, ',', '.') . '. Terima kasih! 💎') }}"
                target="_blank"
                style="display:inline-block; margin-top:8px; padding:8px 20px; background:#25d366; color:white; border-radius:8px; text-decoration:none; font-size:13px">
                📱 Kirim via WhatsApp
            </a>
        @endif
    </div>

</body>

</html>
