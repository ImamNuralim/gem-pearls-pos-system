<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerPhoneExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Transaction::whereNotNull('customer_phone')
            ->where('customer_phone', '!=', '')
            ->select('customer_phone', 'customer_name', 'customer_type')
            ->selectRaw('COUNT(*) as total_transaksi')
            ->selectRaw('SUM(total) as total_belanja')
            ->selectRaw('MAX(created_at) as last_transaction')
            ->groupBy('customer_phone', 'customer_name', 'customer_type')
            ->orderByDesc('last_transaction')
            ->get();
    }

    public function headings(): array
{
    return ['No HP', 'Nama'];
}

public function map($row): array
{
    $phone = preg_replace('/[^0-9]/', '', $row->customer_phone);
    if (str_starts_with($phone, '0')) {
        $phone = '62' . substr($phone, 1);
    }

    return [
        "\t" . $phone, // prefix tab biar Excel ga convert ke scientific notation
        $row->customer_name ?? 'Customer GEM Pearls',
    ];
}
}
