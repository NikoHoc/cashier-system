<?php
require_once("./services/config.php");
session_start();

if ($_SESSION['is_login'] == false) {
    header("location: login.php");
}

// Data kategori
$kategori_query = "SELECT * FROM kategori";
$kategori = $db->query($kategori_query);

// Data menu dengan filter kategori
$kategori_filter = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Setup pagination
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query untuk data menu dengan pagination
$menu_query = "SELECT * FROM menu WHERE 1";

if (!empty($kategori_filter)) {
    $menu_query .= " AND kategori_id_kategori = '$kategori_filter'";
}

$menu_query .= " LIMIT $limit OFFSET $offset";
$menu = $db->query($menu_query);

// Hitung total data untuk pagination
$total_query = "SELECT COUNT(*) as total FROM menu WHERE 1";

if (!empty($kategori_filter)) {
    $total_query .= " AND kategori_id_kategori = '$kategori_filter'";
}
$total_result = $db->query($total_query);
$total_data = $total_result->fetch_assoc()['total'];

$total_pages = ceil($total_data / $limit);

// Cek jika tabel menu kosong atau kategori tidak memiliki menu
$isMenuEmpty = ($menu->num_rows == 0);

// Ambil nama kategori yang dipilih jika ada filter kategori
$nama_kategori = "Semua"; // Default
if (!empty($kategori_filter)) {
    $kategori_nama_query = "SELECT nama_kategori FROM kategori WHERE id_kategori = '$kategori_filter'";
    $result = $db->query($kategori_nama_query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nama_kategori = $row['nama_kategori'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "components/link.php"; ?>
    <style>
        .kategori-card {
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .kategori-card:hover {
            transform: scale(1.1);
            background-color: #3B71CA;
            color: #fff;
            /* Optional: adjust the color on hover for better visual feedback */
        }

        @media (max-width: 1340px) {
            .btn-custom-spacing {
                margin-bottom: 0.5rem;
                display: flex;
                justify-content: center;
            }
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
                    <h4>Kategori Menu:</h4>
                    <div class="row">
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                            <div class="card kategori-card border border-primary d-flex <?= (empty($kategori_filter)) ? 'bg-primary text-white' : '' ?>"
                                data-kategori=""
                                style="width: auto; display: inline-block; cursor: pointer; transition: transform 0.2s;">

                                <div class="card-body text-center p-2">
                                    <strong>Semua</strong>
                                </div>
                            </div>
                        </div>
                        <?php foreach ($kategori as $kat) { ?>
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                                <div class="card kategori-card border border-primary d-flex <?= ($kategori_filter == $kat['id_kategori']) ? 'bg-primary text-white' : '' ?>"
                                    data-kategori="<?= $kat['id_kategori'] ?>"
                                    style="width: auto; display: inline-block; cursor:pointer;">
                                    <div class="card-body text-center p-2">
                                        <strong><?= $kat['nama_kategori'] ?></strong>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                            <div class="card kategori-card border border-primary d-flex bg-primary text-white"
                                data-kategori=""
                                style="width: auto; display: inline-block; cursor: pointer; transition: transform 0.2s;">

                                <div class="card-body text-center p-2">
                                    <strong><a href="kategori.php" class="text-white">Tambah Kategori ?</a></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End kategori -->

                <!-- Menu -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <h4><?= $nama_kategori ?></h4>
                                </div>
                                <div class="col-md-6 d-flex justify-content-end">
                                    <div class="input-group" style="max-width: 25rem;">
                                        <input class="form-control" id="menu-search" placeholder="Cari menu...">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

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
                                    <tbody id="menu-body">
                                        <?php
                                        if ($isMenuEmpty) {
                                            echo '<tr><td colspan="5" class="text-center">Belum ada item! Tambah menu dulu disamping!</td></tr>';
                                        } else {
                                            $no = $offset + 1;
                                            foreach ($menu as $menu_item) {
                                        ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $menu_item['nama_menu'] ?></td>
                                                    <td><?= $menu_item['harga_menu'] ?></td>
                                                    <td><?= ($menu_item['harga_setengah'] == NULL) ? '-' : $menu_item['harga_setengah'] ?></td>
                                                    <td>
                                                        <a href="edit_menu.php?id=<?= $menu_item['id_menu'] ?>" class="btn btn-warning btn-sm btn-custom-spacing">Edit</a>
                                                        <a href="delete_menu.php?id=<?= $menu_item['id_menu'] ?>" class="btn btn-danger btn-sm btn-custom-spacing" onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">Delete</a>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                        <!-- Tambahkan baris pesan yang akan ditampilkan ketika pencarian tidak menemukan hasil -->
                                        <tr id="menu-not-found-row" style="display:none;">
                                            <td colspan="5" class="text-center">Item tidak ditemukan! Tambah menu dulu disamping</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Pagination Links -->
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center">
                                        <?php if ($page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?kategori=<?= $kategori_filter ?>&page=<?= $page - 1 ?>" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                                <a class="page-link" href="?kategori=<?= $kategori_filter ?>&page=<?= $i ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php if ($page < $total_pages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?kategori=<?= $kategori_filter ?>&page=<?= $page + 1 ?>" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>

                        <!-- Form Tambah Menu -->
                        <div class="col-lg-6">
                            <h4>Tambah Menu</h4>
                            <form action="addMenu.php" method="POST">
                                <div class="mb-3">
                                    <label for="kategori" class="form-label">*Kategori</label>
                                    <select class="form-select" id="kategori" name="kategori_id" required>
                                        <option value="" disabled selected>Pilih kategori...</option>
                                        <?php
                                        // Ambil data kategori dari database
                                        $kategori_query = "SELECT * FROM kategori";
                                        $kategori_result = $db->query($kategori_query);
                                        foreach ($kategori_result as $kategori) { ?>
                                            <option value="<?= $kategori['id_kategori'] ?>"><?= $kategori['nama_kategori'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="namaMenu" class="form-label">*Nama Menu</label>
                                    <input type="text" class="form-control" id="namaMenu" name="nama_menu" required placeholder="Masukan Nama Menu">
                                </div>
                                <div class="mb-3">
                                    <label for="harga" class="form-label">*Harga</label>
                                    <input type="number" class="form-control" id="harga" name="harga" required placeholder="Masukan Harga Menu">
                                </div>
                                <div class="mb-3">
                                    <label for="hargaSetengah" class="form-label">Harga 1/2 (Opsional)</label>
                                    <input type="number" class="form-control" id="hargaSetengah" name="harga_setengah" placeholder="Masukan Harga 1/2 Menu">
                                </div>
                                <button type="submit" class="btn btn-primary">Tambah</button>
                            </form>
                        </div>
                    </div>

                </div>
                <!-- End Menu -->
            </main>
            <!-- Main Content -->
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');

            if (status === 'success') {
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Menu berhasil ditambahkan.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            } else if (status === 'error') {
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menambahkan menu.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });

        document.querySelectorAll('.kategori-card').forEach(card => {
            card.addEventListener('click', function() {
                let kategoriId = this.getAttribute('data-kategori');
                let currentUrl = window.location.href.split('?')[0];
                let newUrl = `${currentUrl}?kategori=${kategoriId}`;

                window.location.href = newUrl;
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Pencarian untuk tabel Menu
            const searchInputMenu = document.getElementById('menu-search');
            const menuTableBody = document.getElementById('menu-body');
            const menuNotFoundRow = document.getElementById('menu-not-found-row');

            searchInputMenu.addEventListener('input', function() {
                const searchTerm = searchInputMenu.value.toLowerCase();
                const rows = menuTableBody.getElementsByTagName('tr');
                let found = false;

                Array.from(rows).forEach(row => {
                    // Skip baris 'menu-not-found-row' saat mengecek hasil pencarian
                    if (row.id === 'menu-not-found-row') return;

                    const namaMenu = row.getElementsByTagName('td')[1].textContent.toLowerCase();
                    if (namaMenu.includes(searchTerm)) {
                        row.style.display = '';
                        found = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Jika tidak ada item ditemukan, tampilkan baris 'menu-not-found-row'
                menuNotFoundRow.style.display = found ? 'none' : 'table-row';
            });
        });
    </script>

    <?php include "components/script.php"; ?>
</body>

</html>