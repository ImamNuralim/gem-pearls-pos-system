<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
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

        return view('kasir.pos', compact('products', 'productsJson'));
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
            'payment_method' => 'required|in:cash,qris,card',
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

            // Hitung komisi mitra
            if ($request->partner_id) {

                $partner = Partner::findOrFail($request->partner_id);

                // Cari komisi partner hari ini
                $commission = Commission::where('partner_id', $partner->id)
                    ->whereDate('commission_date', now()->toDateString())
                    ->first();

                // Kalau belum ada → buat baru
                if (!$commission) {

                    $commissionRate = $partner->commission_rate ?? 0;
                    $commissionAmount = $total * ($commissionRate / 100);

                    Commission::create([
                        'partner_id' => $partner->id,
                        'commission_date' => now()->toDateString(),
                        'total_sales' => $total,
                        'commission_rate' => $commissionRate,
                        'commission_amount' => $commissionAmount,
                        'status' => 'unpaid',
                    ]);

                } else {

                    // Kalau sudah ada → tambah total belanja
                    $commission->total_sales += $total;

                    // Recalculate komisi
                    $commission->commission_amount =
                        $commission->total_sales * ($commission->commission_rate / 100);

                    $commission->save();
                }
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
}
