<?php

return [

    [
        'title' => 'Dashboard',
        'icon'  => 'fas fa-th',
        'route' => 'admin.dashboard',
    ],

    [
        'title' => 'Manajemen Admin',
        'icon'  => 'fas fa-user-cog',
        'submenu' => [
            [
                'title' => 'Daftar Admin',
                'route' => 'admin.subadmins',
                'permission' => 'admin', // hanya tipe admin
            ],
            [
                'title' => 'Roles',
                'route' => 'admin.roles.index',
                'permission' => 'admin',
            ],
        ],
    ],

    [
        'title' => 'Master Data',
        'icon'  => 'fas fa-boxes',
        'submenu' => [
            ['title' => 'Kategori', 'route' => 'admin.categories.index'],
            ['title' => 'Produk', 'route' => 'admin.products.index'],
            ['title' => 'Banner', 'route' => 'admin.banners.index'],
            ['title' => 'Kupon', 'route' => 'admin.coupons.index'],
            ['title' => 'Bank', 'route' => 'admin.account-banks.index'],
        ],
    ],

    [
        'title' => 'Manajemen Order',
        'icon'  => 'fas fa-shopping-cart',
        'submenu' => [
            ['title' => 'Pelanggan', 'route' => 'admin.customers.index'],
            ['title' => 'Orderan', 'route' => 'admin.orders.index'],
            ['title' => 'Kalender Penggunaan', 'route' => 'admin.calendar.index'],
        ],
    ],

    [
        'title' => 'Kerjasama',
        'icon'  => 'fas fa-handshake',
        'submenu' => [
            ['title' => 'Kerjasama', 'route' => 'admin.orders.index'],
            ['title' => 'Mitra',  'icon'  => 'fas fa-people-group', 'route' => 
'admin.mitra.index'],
        ],
    ],

    [
        'title' => 'Dokumen dan Informasi',
        'header' => true,
    ],
    [
        'title' => 'Informasi',
        'icon'  => 'fas fa-info-circle',
        'route' => 'admin.information.index',
    ],
    [   'title' => 'Dokumen', 
        'icon'  => 'fas fa-file',
        'route' => 'admin.document.index'
    ],

    [
        'title' => 'File Manager',
        'header' => true,
    ],
    [
        'title' => 'File',
        'icon'  => 'fas fa-file',
        'route' => 'admin.files.index',
    ],

    [
        'title' => 'System',
        'header' => true,
    ],
    [
        'title' => 'Informasi & Log',
        'icon'  => 'fas fa-info-circle',
        'route' => 'admin.system',
    ],
    [
        'title' => 'Database',
        'icon'  => 'fas fa-database',
        'route' => 'admin.database',
    ],

];
