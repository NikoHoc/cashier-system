<?php
$currentPage = basename($_SERVER['PHP_SELF']);

if ($currentPage == 'index.php') {
    $pageTittle = "Admin Dashboard";
} else if ($currentPage == 'menu.php') {
    $pageTittle = "Daftar Menu";
} else if ($currentPage == 'order.php') {
    $pageTittle = "Pesanan";
} else if ($currentPage == 'riwayat.php') {
    $pageTittle = "Riwayat Penjualan";
} else if ($currentPage = "kategori.php"){
    $pageTittle = "Daftar Kategori";
} else if ($currentPage = "user.php"){
    $pageTittle = "User";
} else {
    $pageTittle = "Unknown Page";
}

$username = $_SESSION['username'] ?? 'Guest';
?>

<nav class="navbar navbar-expand px-4 py-3">
    <h3 class="fw-bold fs-4 mt-2"><?= $pageTittle ?></h3>
    <div class="navbar-collapse collapse">
        <ul class="navbar-nav ms-auto">
            <span class="me-2"><?= $username ?></span>
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