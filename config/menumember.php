<?php

return [

    [
        'label' => 'Profil',
        'route' => 'member.profil',
        'pattern' => 'profil',
        'icon' => 'bi bi-person-fill',
        'icon_color' => 'text-primary',
        'badge' => null,
    ],

    [
        'label' => 'Update Password',
        'route' => 'member.akun',
        'pattern' => 'akun',
        'icon' => 'bi bi-key-fill',
        'icon_color' => 'text-warning',
        'badge' => null,
    ],

    [
        'label' => 'Testimonial',
        'route' => 'member.testimoni',
        'pattern' => 'testimoni',
        'icon' => 'bi bi-chat-dots-fill',
        'icon_color' => 'text-success',
        'badge' => null,
    ],

    [
        'label' => 'Daftar Pesanan',
        'route' => 'member.pesanan',
        'pattern' => 'pesanan',
        'icon' => 'bi bi-basket-fill',
        'icon_color' => 'text-info',
        'badge' => 'user_pending_orders',
    ],

];
