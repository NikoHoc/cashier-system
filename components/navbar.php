<?php
// Get the current file name to determine which page is active
$currentPage = basename($_SERVER['PHP_SELF']);

if ($currentPage == 'index.php') {
    $pageTittle = "Admin Dashboard";
} elseif ($currentPage == 'menu.php') {
    $pageTittle = "Menu Depot";
} elseif ($currentPage == 'order.php') {
    $pageTittle = "Order";
} elseif ($currentPage == 'riwayat.php') {
    $pageTittle = "Riwayat Penjualan";
} else {
    $pageTittle = "Unknown Page";
}
?>

<nav class="navbar navbar-expand px-4 py-3">
    <h3 class="fw-bold fs-4 mt-2"><?= $pageTittle ?></h3>
    <div class="navbar-collapse collapse">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                    <img src="./assets/images/logo_sapi.png" class="avatar img-fluid" alt="">
                </a>
                <div class="dropdown-menu dropdown-menu-end rounded">

                </div>
            </li>
        </ul>
    </div>
</nav>