<?php
$currentPage = basename($_SERVER['PHP_SELF']);

?>

<div class="d-flex mt-3">
    <button class="toggle-btn" type="button">
        <i class="lni lni-grid-alt"></i>
    </button>
    <div class="sidebar-logo">
        <a href="index.php">Depot Bakso</a>
    </div>
</div>
<ul class="sidebar-nav">
    <li class="sidebar-item">
        <a href="index.php" class="nav-item nav-link sidebar-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>">
            <i class="lni lni-home"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a href="order.php" class="nav-item nav-link sidebar-link <?php echo ($currentPage == 'order.php') ? 'active' : ''; ?>">
            <i class="lni lni-cart"></i>
            <span>Order</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a href="kategori.php" class="nav-item nav-link sidebar-link <?php echo ($currentPage == 'kategori.php') ? 'active' : ''; ?>">
            <i class="lni lni-layers"></i>
            <span>Kategori</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a href="menu.php" class="nav-item nav-link sidebar-link <?php echo ($currentPage == 'menu.php') ? 'active' : ''; ?>">
            <i class="lni lni-library"></i>
            <span>Menu</span>
        </a>
    </li> 
    <li class="sidebar-item">
        <a href="riwayat.php" class="nav-item nav-link sidebar-link <?php echo ($currentPage == 'riwayat.php') ? 'active' : ''; ?>">
            <i class="lni lni-database"></i>
            <span>Riwayat Penjualan</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a href="#" class="sidebar-link">
            <i class="lni lni-cog"></i>
            <span>User</span>
        </a>
    </li>
</ul>
<div class="sidebar-footer">
    <a href='logout.php' class="sidebar-link">
        <i class="lni lni-exit"></i>
        <span>Logout</span>
    </a>
</div>
