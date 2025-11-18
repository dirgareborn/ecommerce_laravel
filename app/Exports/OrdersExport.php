<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection; 
use Maatwebsite\Excel\Concerns\WithHeadings;   

class OrdersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Order::with(['orders_products.product', 'users'])
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($order) {
                return [
                    'ID Order' => $order->id,
                    'Tanggal Order' => $order->created_at->format('Y-m-d H:i'),
                    'Nama' => $order->users->name,
                    'Email' => $order->users->email,
                    'Produk' => $order->orders_products->pluck('product.product_name')->join(', '),
                    'Tanggal Pemakaian' => $order->orders_products->map(fn($p) => $p->start_date . ' s/d ' . $p->end_date)->join(' | '),
                    'Grand Total' => $order->grand_total,
                    'Metode Pembayaran' => $order->payment_method,
                    'Status Pembayaran' => $order->payment_status,
                    'Status Order' => $order->order_status,
                    'Catatan' => $order->payment_verifier_note ?? '',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID Order',
            'Tanggal Order',
            'Nama',
            'Email',
            'Produk',
            'Tanggal Pemakaian',
            'Grand Total',
            'Metode Pembayaran',
            'Status Pembayaran',
            'Status Order',
            'Catatan'
        ];
    }
}
