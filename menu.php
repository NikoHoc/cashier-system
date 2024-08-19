<?php
require_once("./services/config.php");
session_start();

if ($_SESSION['is_login'] == false) {
    header("location: login.php");
}

//data kategori
$kategori_query = "SELECT * FROM kategori";
$kategori = $db->query($kategori_query);

// Data menu
$jenis_filter = isset($_GET['jenis']) ? $_GET['jenis'] : 'all';
$kategori_filter = isset($_GET['kategori']) ? $_GET['kategori'] : '';

$menu_query = "SELECT * FROM menu WHERE 1"; // 'WHERE 1' digunakan agar selalu true, sehingga kondisi berikutnya dapat ditambahkan dengan aman

if ($jenis_filter == 'makanan') {
    $menu_query .= " AND jenis = 'makanan'";
} elseif ($jenis_filter == 'minuman') {
    $menu_query .= " AND jenis = 'minuman'";
}

if (!empty($kategori_filter)) {
    $menu_query .= " AND kategori_id_kategori = '$kategori_filter'";
}

$menu = $db->query($menu_query);

// Cek apakah data makanan dan minuman kosong
$makanan_empty = true;
$minuman_empty = true;

foreach ($menu as $menu_item) {
    if ($menu_item['jenis'] == 'makanan') {
        $makanan_empty = false;
    } elseif ($menu_item['jenis'] == 'minuman') {
        $minuman_empty = false;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "components/link.php"; ?>
    <style>
        .card.active {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <!-- Sidebar -->
            <?php include "components/sidebar.php"; ?>
            <!-- Sidebar -->
        </aside>

        <div class="main">
            <!-- Navbar -->
            <?php include "components/navbar.php"; ?>
            <!-- Navbar -->

            <!-- Main Content -->
            <main class="content px-3 py-4">
                <!-- Kategori -->
                <div class="container-fluid mb-3">
                    <h4>Daftar Kategori:</h4>
                    <div class="row">
                        <div class="col-lg-1 col-md-4 col-sm-6 mb-2">
                            <div class="card kategori-card <?= (empty($kategori_filter)) ? 'active' : '' ?>"
                                data-kategori=""
                                style="width: auto; display: inline-block; cursor:pointer;">
                                <div class="card-body text-center p-2">
                                    Semua
                                </div>
                            </div>
                        </div>
                        <?php foreach ($kategori as $kat) { ?>
                            <div class="col-lg-1 col-md-4 col-sm-6 mb-2">
                                <div class="card kategori-card <?= ($kategori_filter == $kat['id_kategori']) ? 'active' : '' ?>"
                                    data-kategori="<?= $kat['id_kategori'] ?>"
                                    style="width: auto; display: inline-block; cursor:pointer;">
                                    <div class="card-body text-center p-2">
                                        <?= $kat['nama_kategori'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <!-- End kategori -->

                <!-- Menu -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            <h4>Makanan</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="table-primary">
                                            <th scope="col">No</th>
                                            <th scope="col">Nama Menu</th>
                                            <th scope="col">Harga</th>
                                            <th scope="col">Harga 1/2</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="makanan-body">
                                        <tr id="makanan-empty" style="display: <?= $makanan_empty ? '' : 'none' ?>;">
                                            <td colspan="5" class="text-center">
                                                Menu masih kosong! <a href="addMenu.php">Tambah menu dulu</a>
                                            </td>
                                        </tr>
                                        <?php
                                        $no = 1;
                                        foreach ($menu as $menu_item) {
                                            if ($menu_item['jenis'] == 'makanan') {
                                        ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $menu_item['nama_menu'] ?></td>
                                                    <td><?= $menu_item['harga_menu'] ?></td>
                                                    <td><?= ($menu_item['harga_setengah'] == NULL) ? '-' : $menu_item['harga_setengah'] ?></td>
                                                    <td>
                                                        <a href="edit_menu.php?id=<?= $menu_item['id_menu'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                                        <a href="delete_menu.php?id=<?= $menu_item['id_menu'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">Delete</a>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <h4>Minuman</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="table-primary">
                                            <th scope="col">No</th>
                                            <th scope="col">Nama Menu</th>
                                            <th scope="col">Harga</th>
                                            <th scope="col">Harga 1/2</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="minuman-body">
                                        <tr id="minuman-empty" style="display: <?= $minuman_empty ? '' : 'none' ?>;">
                                            <td colspan="5" class="text-center">
                                                Menu masih kosong! <a href="addMenu.php">Tambah menu dulu</a>
                                            </td>
                                        </tr>
                                        <?php
                                        $no = 1;
                                        foreach ($menu as $menu_item) {
                                            if ($menu_item['jenis'] == 'minuman') {
                                        ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $menu_item['nama_menu'] ?></td>
                                                    <td><?= $menu_item['harga_menu'] ?></td>
                                                    <td><?= ($menu_item['harga_setengah'] == NULL) ? '-' : $menu_item['harga_setengah'] ?></td>
                                                    <td>
                                                        <a href="edit_menu.php?id=<?= $menu_item['id_menu'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                                        <a href="delete_menu.php?id=<?= $menu_item['id_menu'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">Delete</a>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Menu -->
            </main>
            <!-- Main Content -->
        </div>
    </div>

    <script>
        document.querySelectorAll('.kategori-card').forEach(card => {
            card.addEventListener('click', function () {
                let kategoriId = this.getAttribute('data-kategori');
                let currentUrl = window.location.href.split('?')[0];
                let newUrl = `${currentUrl}?kategori=${kategoriId}`;
                
                window.location.href = newUrl;
            });
        });
    </script>

    <?php include "components/script.php"; ?>
</body>

</html>
