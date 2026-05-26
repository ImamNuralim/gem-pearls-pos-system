<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Services\FonnteService;
use App\Models\Product;
use App\Models\Partner;
use App\Models\Member;
use App\Models\Commission;
use App\Models\MemberPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{

    public function destroy(\App\Models\Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus (soft delete)');
    }
    // Halaman utama kasir
    public function index()
    {

        $salesStaffs = \App\Models\SalesStaff::where('is_active', true)->orderBy('name')->get();
        $products = Product::with('primaryPhoto')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->get();

        $productsJson = $products->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'price' => (float) $p->price,
                'price_formatted' => $p->price_formatted,
                'stock' => $p->stock,
                'category' => $p->category,
                'photo' => $p->primaryPhoto ? asset('storage/' . $p->primaryPhoto->photo_path) : null,
            ];
        })->values()->toJson();

        return view('kasir.pos', compact('products', 'productsJson', 'salesStaffs'));

    }

    public function viewPdf(Commission $commission)
    {
        return view('admin.commissions.pdf', compact('commission'));
    }

    // Search produk (AJAX)
    public function searchProduct(Request $request)
    {
        $products = Product::with('primaryPhoto')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%")
                    ->orWhere('sku', 'like', "%{$request->q}%");
            })
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'price' => $p->price,
                'price_formatted' => $p->price_formatted,
                'stock' => $p->stock,
                'category' => $p->category,
                'photo' => $p->primaryPhoto ? asset('storage/' . $p->primaryPhoto->photo_path) : null,
            ]);

        return response()->json($products);
    }

    // Search partner (AJAX)
    public function searchPartner(Request $request)
    {
        $partners = Partner::where('is_active', true)
            ->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%")
                    ->orWhere('code', 'like', "%{$request->q}%");
            })
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->get(['id', 'name', 'code', 'type', 'commission_rate']);

        return response()->json($partners);
    }

    // Search member (AJAX)
    public function searchMember(Request $request)
    {
        $members = Member::where('is_active', true)
            ->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%")
                    ->orWhere('phone', 'like', "%{$request->q}%");
            })
            ->get(['id', 'name', 'phone', 'points_balance']);

        return response()->json($members);
    }

    // Proses checkout
    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.final_price' => 'required|numeric|min:0',
            'customer_type' => 'required|in:walk_in,travel_agent,freelance_guide,member',
            'payment_method' => 'required|in:cash,qris,qris_bni,qris_mandiri,card,card_bca,card_mandiri,card_bri,card_bni',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $isNegotiated = false;
            $itemsData = [];

            // Validasi & hitung item
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak cukup!");
                }

                $originalPrice = $product->price;
                $finalPrice = $item['final_price'];

                if ($finalPrice != $originalPrice) {
                    $isNegotiated = true;
                }

                $itemSubtotal = $finalPrice * $item['quantity'];
                $subtotal += $itemSubtotal;

                $itemsData[] = [
                    'product' => $product,
                    'original_price' => $originalPrice,
                    'final_price' => $finalPrice,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemSubtotal,
                ];
            }

            // Hitung poin redeem
            $pointsRedeemed = 0;
            $pointsDiscount = 0;
            $member = null;

            if ($request->customer_type === 'member' && $request->member_id) {
                $member = Member::findOrFail($request->member_id);

                $pointsRedeemed = $request->points_redeemed ?? 0;

                // ❗ VALIDASI
                if ($pointsRedeemed < 0) {
                    throw new \Exception('Poin tidak valid');
                }

                if ($pointsRedeemed > $member->points_balance) {
                    throw new \Exception('Poin tidak cukup');
                }

                // Hitung diskon
                $pointsDiscount = $pointsRedeemed * 100; // 1 poin = Rp 100

                // Maksimal diskon = subtotal
                if ($pointsDiscount > $subtotal) {
                    $pointsDiscount = $subtotal;
                    $pointsRedeemed = floor($subtotal / 100);
                }
            }
            // Admin fee mata uang asing
            $adminFee = 0;
            $currencyCode = $request->currency_code ?? 'IDR';
            $currencyRate = $request->currency_rate ?? 1;

            if ($currencyCode !== 'IDR') {
                $adminFee = 1000;
            }

            $total = $subtotal - $pointsDiscount + $adminFee;
            $amountPaid = $request->amount_paid;
            $changeAmount = max(0, $amountPaid - $total);

            // Buat transaksi
            $transaction = Transaction::create([
                'invoice_number' => Transaction::generateInvoiceNumber(),
                'receipt_token' => \Str::uuid(),
                'user_id' => auth()->id() ?? 1,
                'customer_type' => $request->customer_type,
                'customer_name' => $request->customer_name,
                'partner_id' => $request->partner_id,
                'member_id' => $request->member_id,
                'subtotal' => $subtotal,
                'points_redeemed' => $pointsRedeemed,
                'points_discount' => $pointsDiscount,
                'total' => $total,
                'is_negotiated' => $isNegotiated,
                'payment_method' => $request->payment_method,
                'currency_code' => $currencyCode,
                'currency_rate' => $currencyRate,
                'admin_fee' => $adminFee,
                'amount_paid' => $amountPaid,
                'change_amount' => $changeAmount,
                'customer_phone' => $request->customer_phone,
                'status' => 'completed',
                'sales_staff_id' => $request->sales_staff_id,
                'partner_visit_id' => $request->partner_visit_id ?? null, // ← tambah ini
            ]);

            // Simpan items & kurangi stok
            foreach ($itemsData as $itemData) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $itemData['product']->id,
                    'product_name' => $itemData['product']->name,
                    'sku' => $itemData['product']->sku,
                    'original_price' => $itemData['original_price'],
                    'final_price' => $itemData['final_price'],
                    'quantity' => $itemData['quantity'],
                    'subtotal' => $itemData['subtotal'],
                ]);

                // Kurangi stok
                $itemData['product']->decrement('stock', $itemData['quantity']);

                // Auto nonaktif kalau stok 0
                if ($itemData['product']->fresh()->stock === 0) {
                    $itemData['product']->update(['is_active' => false]);
                }
            }

            // Update poin member
            if ($member) {
                // Kurangi poin yang diredeeem
                if ($pointsRedeemed > 0) {
                    $member->decrement('points_balance', $pointsRedeemed);
                    MemberPoint::create([
                        'member_id' => $member->id,
                        'transaction_id' => $transaction->id,
                        'type' => 'redeem',
                        'points' => $pointsRedeemed,
                        'note' => 'Redeem poin - Invoice ' . $transaction->invoice_number,
                    ]);
                }

                // Tambah poin baru (setiap Rp 10.000 = 1 poin)
                $earnedPoints = floor($total / 10000);
                if ($earnedPoints > 0) {
                    $member->increment('points_balance', $earnedPoints);
                    MemberPoint::create([
                        'member_id' => $member->id,
                        'transaction_id' => $transaction->id,
                        'type' => 'earn',
                        'points' => $earnedPoints,
                        'note' => 'Poin dari pembelian - Invoice ' . $transaction->invoice_number,
                    ]);
                }
            }


            if ($transaction->partner_visit_id) {
                \App\Models\PartnerVisit::where('id', $transaction->partner_visit_id)->increment('total_sales', $total);
            }

            if ($request->customer_phone) {
                $receiptUrl = route('receipt.public', $transaction->receipt_token);

                $message = "Halo! Terima kasih sudah berbelanja di *Gem Pearls Lombok* 💎\n\n";
                $message .= "📋 *Struk Pembelian*\n";
                $message .= "Invoice: *{$transaction->invoice_number}*\n";
                $message .= "Tanggal: {$transaction->created_at->format('d/m/Y H:i')}\n";
                $message .= "Total: *Rp " . number_format($transaction->total, 0, ',', '.') . "*\n";
                $message .= "Metode: " . strtoupper($transaction->payment_method) . "\n";

                if ($changeAmount > 0) {
                    $message .= "Kembalian: Rp " . number_format($changeAmount, 0, ',', '.') . "\n";
                }

                $message .= "\n🔗 Lihat struk: {$receiptUrl}\n\n";
                $message .= "_Simpan pesan ini sebagai bukti pembelian_\n";
                $message .= "📱 IG: @gempearlsjewelry\n";
                $message .= "🛍️ Shopee: GEM Pearls Lombok";

                app(FonnteService::class)->send($request->customer_phone, $message);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil!',
                'transaction' => [
                    'id' => $transaction->id,
                    'invoice_number' => $transaction->invoice_number,
                    'total' => $total,
                    'total_formatted' => 'Rp ' . number_format($total, 0, ',', '.'),
                    'change_amount' => $changeAmount,
                    'change_formatted' => 'Rp ' . number_format($changeAmount, 0, ',', '.'),
                    'points_earned' => $member ? floor($total / 10000) : 0,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    // Detail transaksi untuk struk
    public function receipt(Transaction $transaction)
    {
        $transaction->load(['items', 'partner', 'member', 'user']);
        return view('kasir.receipt', compact('transaction'));
    }
    public function storeMember(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:members,phone',
        ]);

        $member = Member::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'points_balance' => 0,
            'is_active' => true,
            'registered_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'member' => [
                'id' => $member->id,
                'name' => $member->name,
                'phone' => $member->phone,
                'points_balance' => 0,
            ]
        ]);
    }
    public function publicReceipt(string $token)
    {
        $transaction = Transaction::where('receipt_token', $token)
            ->with(['items', 'partner', 'member', 'user'])
            ->firstOrFail();

        return view('kasir.receipt', compact('transaction'));
    }

    public function receiptData(Transaction $transaction)
    {
        $transaction->load(['items', 'salesStaff', 'user', 'member']);
        return response()->json([
            'invoice_number' => $transaction->invoice_number,
            'date' => $transaction->created_at->format('d/m/Y H:i'),
            'sales' => $transaction->salesStaff->name ?? $transaction->user->name ?? '-',
            'items' => $transaction->items->map(fn($i) => [
                'name' => $i->product_name,
                'qty' => $i->quantity,
                'price' => number_format($i->final_price, 0, ',', '.'),
                'subtotal' => number_format($i->subtotal, 0, ',', '.'),
            ]),
            'total' => number_format($transaction->total, 0, ',', '.'),
            'amount_paid' => number_format($transaction->amount_paid, 0, ',', '.'),
            'change' => number_format($transaction->change_amount, 0, ',', '.'),
        ]);
    }


    private function padLine($left, $right, $width = 48) {
    $space = $width - strlen($left) - strlen($right);
    return $left . str_repeat(' ', max(1, $space)) . $right;
}

    public function printRaw(Request $request)
{
    $transaction = \App\Models\Transaction::with(['items', 'salesStaff', 'user', 'member', 'partner'])
        ->findOrFail($request->transaction_id);

    $ESC = "\x1B";
    $GS  = "\x1D";
    $LF  = "\n";
    $SEP  = str_repeat('-', 48) . $LF;
    $SEP2 = str_repeat('=', 48) . $LF;

    $data  = $ESC . "@";
    $data .= $ESC . "a\x01";
    $data .= $ESC . "E\x01";
    $data .= "* GEM PEARLS *" . $LF;
    $data .= $ESC . "E\x00";
    $data .= "Perhiasan & Oleh-oleh Lombok" . $LF;
    $data .= "Jl. Raya Meninting, No. 69" . $LF;
    $data .= "Batu Layar, Lombok Barat, NTB" . $LF;
    $data .= $SEP2;
    $data .= $ESC . "a\x00";

    $data .= $this->padLine("Invoice :", $transaction->invoice_number) . $LF;
    $data .= $this->padLine("Tanggal :", $transaction->created_at->format('d/m/Y H:i')) . $LF;
    if ($transaction->salesStaff) {
        $data .= $this->padLine("Sales   :", $transaction->salesStaff->name) . $LF;
    }
    $data .= $this->padLine("Customer:", ucfirst(str_replace('_', ' ', $transaction->customer_type))) . $LF;
    if ($transaction->partner) {
        $data .= $this->padLine("Mitra   :", $transaction->partner->name) . $LF;
    }
    if ($transaction->member) {
        $data .= $this->padLine("Member  :", $transaction->member->name) . $LF;
    }

    $data .= $SEP;

    foreach ($transaction->items as $item) {
        $data .= $ESC . "E\x01";
        $data .= $item->product_name . $LF;
        $data .= $ESC . "E\x00";

        if ($item->final_price != $item->original_price) {
            $data .= "  Harga asal: Rp " . number_format($item->original_price, 0, ',', '.') . " [DISKON]" . $LF;
            $qty   = "  " . $item->quantity . "x @ Rp " . number_format($item->final_price, 0, ',', '.');
            $total = "Rp " . number_format($item->subtotal, 0, ',', '.');
            $data .= $ESC . "E\x01";
            $data .= $this->padLine($qty, $total) . $LF;
            $data .= $ESC . "E\x00";
        } else {
            $qty   = "  " . $item->quantity . "x @ Rp " . number_format($item->final_price, 0, ',', '.');
            $total = "Rp " . number_format($item->subtotal, 0, ',', '.');
            $data .= $ESC . "E\x01";
            $data .= $this->padLine($qty, $total) . $LF;
            $data .= $ESC . "E\x00";
        }
    }

    $data .= $SEP;
    $data .= $this->padLine("Subtotal", "Rp " . number_format($transaction->subtotal, 0, ',', '.')) . $LF;

    if ($transaction->points_discount > 0) {
        $data .= $this->padLine("Diskon Poin", "-Rp " . number_format($transaction->points_discount, 0, ',', '.')) . $LF;
    }
    if ($transaction->admin_fee > 0) {
        $data .= $this->padLine("Admin Fee", "Rp " . number_format($transaction->admin_fee, 0, ',', '.')) . $LF;
    }

    $data .= $SEP2;
    $data .= $ESC . "E\x01";
    $data .= $this->padLine("TOTAL", "Rp " . number_format($transaction->total, 0, ',', '.')) . $LF;
    $data .= $ESC . "E\x00";
    $data .= $this->padLine("Bayar (" . strtoupper($transaction->payment_method) . ")", "Rp " . number_format($transaction->amount_paid, 0, ',', '.')) . $LF;

    if ($transaction->change_amount > 0) {
        $data .= $ESC . "E\x01";
        $data .= $this->padLine("Kembalian", "Rp " . number_format($transaction->change_amount, 0, ',', '.')) . $LF;
        $data .= $ESC . "E\x00";
    }

    $data .= $SEP;
    $data .= $ESC . "a\x01";
    $data .= "TERIMA KASIH" . $LF;
    $data .= "Selamat berbelanja di Gem Pearls" . $LF;
    $data .= "Simpan struk sebagai bukti pembelian" . $LF;
    $data .= $SEP;
    $data .= "081916088775" . $LF;
    $data .= "Follow @gempearlsjewelry" . $LF;
    $data .= "Shopee GEM Pearls Lombok" . $LF;
    $data .= $LF . $LF . $LF;
    $data .= $GS . "V\x41\x00";

    // PrintNode API
    $printerId = $request->printer_id ?? 75491642; // ID printer dari PrintNode
    $apiKey    = 'lqj-qSGpSjmccSPCRYKlsyHY9oHvBvuhrBpgX_Qulyo';

    $payload = [
        'printerId' => (int) $printerId,
        'title'     => 'Struk ' . $transaction->invoice_number,
        'contentType' => 'raw_base64',
        'content'   => base64_encode($data),
        'source'    => 'Gem Pearls POS',
    ];

    $ch = curl_init('https://api.printnode.com/printjobs');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode($apiKey . ':'),
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 201) {
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'PrintNode error: ' . $response]);
}
}
