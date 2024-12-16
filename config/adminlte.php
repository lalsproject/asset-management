<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#61-title
    |
    */

    'title' => 'Aset Management Serba Sepeda',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#62-favicon
    |
    */

    'use_ico_only' => true,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#63-logo
    |
    */

    'logo' => '<b>Aset </b>Management',
    'logo_img' => 'img/logo100.png',
    'logo_img_class' => 'brand-image',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Aset Management',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#64-user-menu
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => true,
    'usermenu_desc' => true,
    'usermenu_profile_url' => true,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#71-layout
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => true,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#721-authentication-views-classes
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#722-admin-panel-classes
    |
    */

    'classes_body' => '',
    'classes_brand' => 'navbar-primary',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-light-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-primary navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#73-sidebar
    |
    */

    'sidebar_mini' => true,
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#74-control-sidebar-right-sidebar
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#65-urls
    |
    */

    'use_route_url' => false,

    'dashboard_url' => 'home',

    'logout_url' => 'logout',

    'login_url' => 'login',

    'register_url' => false,

    'password_reset_url' => '',

    'password_email_url' => '',

    'profile_url' => "profile",

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#92-laravel-mix
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#8-menu-configuration
    |
    */

    'menu' => [
        [
            'text' => 'User Management',
            'icon' => 'fas fa-fw fa-user',
            'can'  => ['user_security','general_setting'],
            'submenu' => [
                [
                    'text' => 'Pengaturan Roles',
                    'url'  => 'admin/roles',
                    'icon' => 'fas fa-angle-right',
                    'icon_color' => 'primary',
                ],
                [
                    'text' => 'Pengaturan User',
                    'url'  => 'admin/users',
                    'icon' => 'fas fa-angle-right',
                    'icon_color' => 'primary',
                ],
               
            ],
        ],

        [
            'text' => 'Master Data',
            'icon' => 'fas fa-fw fa-cubes',
            'submenu' => [
                [
                    'text' => 'Master Api Key',
                    'url'  => 'admin/master/api_key',
                    'can'  => 'master_api_key',
                    'icon' => 'fas fa-angle-right',
                    'icon_color' => 'primary',
                ],
                [
                    'text' => 'Master Status Aset',
                    'url'  => 'admin/master/status',
                    'can'  => 'master_status',
                    'icon' => 'fas fa-angle-right',
                    'icon_color' => 'primary',
                ],
                [
                    'text' => 'Master Jenis Aset',
                    'url'  => 'admin/master/jenis',
                    'can'  => 'master_jenis',
                    'icon' => 'fas fa-angle-right',
                    'icon_color' => 'primary',
                ],
                [
                    'text' => 'Master Kondisi',
                    'url'  => 'admin/master/kondisi',
                    'can'  => 'master_kondisi',
                    'icon' => 'fas fa-angle-right',
                    'icon_color' => 'primary',
                ],
                [
                    'text' => 'Master Divisi',
                    'url'  => 'admin/master/divisi',
                    'can'  => 'master_divisi',
                    'icon' => 'fas fa-angle-right',
                    'icon_color' => 'primary',
                ],
                [
                    'text' => 'Master Satuan',
                    'url'  => 'admin/master/satuan',
                    'can'  => 'master_satuan',
                    'icon' => 'fas fa-angle-right',
                    'icon_color' => 'primary',
                ],
                [
                    'text' => 'Master Jenis Pengadaan',
                    'url'  => 'admin/master/jenis_pengadaan',
                    'can'  => 'master_jenis_pengadaan',
                    'icon' => 'fas fa-angle-right',
                    'icon_color' => 'primary',
                ],
                [
                    'text' => 'Master Jenis Maintenance',
                    'url'  => 'admin/master/jenis_maintenance',
                    'can'  => 'master_jenis_maintenance',
                    'icon' => 'fas fa-angle-right',
                    'icon_color' => 'primary',
                ],
                [
                    'text' => 'Master Lokasi',
                    'url'  => 'admin/master/lokasi',
                    'can'  => 'master_lokasi',
                    'icon' => 'fas fa-angle-right',
                    'icon_color' => 'primary',
                ],
                [
                    'text' => 'Master Ruang',
                    'url'  => 'admin/master/ruang',
                    'can'  => 'master_ruang',
                    'icon' => 'fas fa-angle-right',
                    'icon_color' => 'primary',
                ],
                [
                    'text' => 'Master Barang',
                    'url'  => 'admin/master/barang',
                    'can'  => 'master_barang',
                    'icon' => 'fas fa-angle-right',
                    'icon_color' => 'primary',
                ],
                [
                    'text' => 'Master Sub Barang',
                    'url'  => 'admin/master/barang_sub',
                    'can'  => 'master_barang_sub',
                    'icon' => 'fas fa-angle-right',
                    'icon_color' => 'primary',
                ],

            ],
        ],
        [
            'text' => 'Aset',
            'icon' => 'fas fa-fw fa-store',
            'can'  => ['master_aset','cetak_label','aset_mutasi'],
            'submenu' => [
                [
                    'text' => 'Master Aset',
                    'url'  => 'admin/master/aset',
                    'can'  => 'master_aset',
                    'icon' => 'fas fa-angle-right',
                    'icon_color' => 'primary',
                ],
                [
                    'text' => 'Mutasi',
                    'url'  => 'admin/aset/mutasi',
                    'can'  => ['aset_mutasi'],
                    'icon' => 'fas fa-angle-right',
                    'icon_color' => 'primary',
                ],
                [
                    'text' => 'Cetak Label',
                    'url'  => 'admin/transaksi/cetaklabel',
                    'can'  => ['cetak_label'],
                    'icon' => 'fas fa-angle-right',
                    'icon_color' => 'primary',
                ],
            ],
        ],
        [
            'text' => 'Report',
            'icon' => 'fas fa-fw fa-scroll',
            'can'  => ['report_opname','report_penyusutan'],
            'submenu' => [
                [
                    'text' => 'Opname Aset',
                    'url'  => 'admin/report/opname',
                    'can'  => ['report_opname'],
                    'icon' => 'fas fa-angle-right',
                    'icon_color' => 'primary',
                ],
                [
                    'text' => 'Penyusutan Aset',
                    'url'  => 'admin/report/penyusutan',
                    'can'  => ['report_penyusutan'],
                    'icon' => 'fas fa-angle-right',
                    'icon_color' => 'primary',
                ],
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#83-custom-menu-filters
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#91-plugins
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#93-livewire
    */

    'livewire' => false,
];
