<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function index(Request $request)
{
    $search = $request->search;

    $receipts = Transaction::with(['user', 'partner', 'member', 'salesStaff'])
        ->where('status', 'completed')
        ->when($search, function($q) use ($search) {
            $q->where('invoice_number', 'like', "%{$search}%")
              ->orWhere('customer_name', 'like', "%{$search}%")
              ->orWhere('customer_phone', 'like', "%{$search}%")
              ->orWhereHas('salesStaff', fn($q) => $q->where('name', 'like', "%{$search}%"));
        })
        ->latest()
        ->paginate(20);

    return view('admin.receipts.index', compact('receipts', 'search'));
}

    public function markPrinted(Transaction $transaction)
    {
        $transaction->update(['is_printed' => true]);
        return back()->with('success', 'Status cetak diperbarui!');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return back()->with('success', 'Struk berhasil dihapus!');
    }
}
