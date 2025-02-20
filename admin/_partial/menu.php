<?php
// File: _partial/menu.php

$current_page = basename($_SERVER['PHP_SELF']);

// Menu configuration
$menus = [
    'dashboard' => [
        'type' => 'single',
        'title' => 'Dashboards',
        'icon' => 'bx-home-smile',
        'link' => 'index.php',
        'pages' => ['index.php']
    ],
    'users' => [
        'type' => 'section',
        'title' => 'User',
        'items' => [
            [
                'type' => 'dropdown',
                'title' => 'Data User',
                'icon' => 'bx-group',
                'submenu' => [
                    ['title' => 'Admin', 'link' => 'admin/user/admin.php'],
                    ['title' => 'Pengelola Bimbel', 'link' => 'admin/user/pimpinan.php'],
                    ['title' => 'Guru', 'link' => 'admin/user/guru.php'],
                    ['title' => 'Siswa', 'link' => 'admin/user/murid.php']
                ],
                'pages' => ['admin/user/admin.php', 'admin/user/pimpinan.php', 'admin/user/guru.php', 'admin/user/murid.php']
            ]
        ]
    ],
    'kelas' => [
        'type' => 'section',
        'title' => 'Kelas',
        'items' => [
            [
                'type' => 'single',
                'title' => 'Data Kelas',
                'icon' => 'bx-category',
                'link' => 'admin/kelas/data_kelas.php',
                'pages' => ['admin/kelas/data_kelas.php']
            ]
        ]
    ],
    'laporan' => [
        'type' => 'section',
        'title' => 'Laporan',
        'items' => [
            [
                'type' => 'dropdown',
                'title' => 'Laporan',
                'icon' => 'bx-printer',
                'submenu' => [
                    ['title' => 'Data Keseluruhan Evaluasi Kelas', 'link' => 'admin/laporan/laporan-keseluruhan.php'],
                    ['title' => 'Data Evaluasi Kelas', 'link' => 'admin/laporan/laporan-evaluasi.php'],
                    ['title' => 'Data Siswa', 'link' => 'admin/laporan/laporan-siswa.php']
                ],
                'pages' => ['admin/laporan/laporan-keseluruhan.php', 'admin/laporan/laporan-evaluasi.php', 'admin/laporan/laporan-siswa.php']
            ]
        ]
    ]
];

function renderMenuItem($item, $current_page)
{
    if ($item['type'] === 'single') {
        ?>
        <li class="menu-item <?php echo isMenuActive($current_page, $item['pages']) ? 'active' : ''; ?>">
            <a href="<?php echo url($item['link']); ?>" class="menu-link">
                <i class="menu-icon tf-icons bx <?php echo $item['icon']; ?>"></i>
                <div class="text-truncate"><?php echo $item['title']; ?></div>
            </a>
        </li>
        <?php
    } elseif ($item['type'] === 'dropdown') {
        ?>
        <li class="menu-item <?php echo isMenuActive($current_page, $item['pages']) ? 'active open' : ''; ?>">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx <?php echo $item['icon']; ?>"></i>
                <div class="text-truncate"><?php echo $item['title']; ?></div>
            </a>
            <ul class="menu-sub">
                <?php foreach ($item['submenu'] as $submenu): ?>
                    <li class="menu-item <?php echo basename($submenu['link']) == $current_page ? 'active' : ''; ?>">
                        <a href="<?php echo url($submenu['link']); ?>" class="menu-link">
                            <div class="text-truncate"><?php echo $submenu['title']; ?></div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
        <?php
    }
}
?>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="<?php echo url('index.php'); ?>" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="<?php echo asset('img/logo.png'); ?>" width="100" height="100">
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <?php
        foreach ($menus as $key => $menu) {
            if ($menu['type'] === 'single') {
                renderMenuItem($menu, $current_page);
            } elseif ($menu['type'] === 'section') {
                // Render section header
                ?>
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text"><?php echo $menu['title']; ?> Section</span>
                </li>
                <?php
                // Render section items
                foreach ($menu['items'] as $item) {
                    renderMenuItem($item, $current_page);
                }
            }
        }
        ?>
    </ul>
</aside>