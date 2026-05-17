<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function index()
    {
        $receipts = Transaction::with(['user', 'partner', 'member'])
            ->where('status', 'completed')
            ->latest()
            ->paginate(20);

        return view('admin.receipts.index', compact('receipts'));
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
