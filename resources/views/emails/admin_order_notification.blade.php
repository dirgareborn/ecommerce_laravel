@component('mail::message')
    # Pesanan Baru Telah Diterima

    Halo Admin,

    Telah dibuat pesanan baru oleh **{{ $order->users->name }}**.

    @component('mail::panel')
        **ID Order:** {{ $order->id }}
        **Total:** Rp{{ number_format($order->grand_total, 0, ',', '.') }}
        **Status:** {{ ucfirst($order->order_status) }}
    @endcomponent

    Silakan cek detailnya di dashboard admin untuk menindaklanjuti.

    Terima kasih,
    **Sistem Mallbisnisunm.com**
@endcomponent
