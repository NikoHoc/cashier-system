<?php
require_once("./config/database.php");
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
    <?php include "includes/head.php"; ?>
    <style>
        /* untuk button edit delete dalam tabel */
        @media (max-width: 1340px) {
            .btn-edit-delete {
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
            <?php include "includes/sidebar.php"; ?>
            <!-- Sidebar -->
        </aside>

        <div class="main">
            <!-- Navbar -->
            <?php include "includes/navbar.php"; ?>
            <!-- Navbar -->

            <!-- Main Content -->
            <main class="content px-3 py-4">
                <!-- Menu -->
                <div class="container-fluid mt-2">
                    <div class="row">
                        <!-- Start Left Side -->
                        <div class="col-lg-6">
                            <!-- Judul Tabel -->
                            <div class="row mb-2 box-select-search">
                                <div class="col-lg-6 mb-2">
                                    <form method="GET" action="">
                                        <div class="input-group">
                                            <select class="form-select" id="kategoriSelect" name="kategori" onchange="this.form.submit()">
                                                <option value="" <?= empty($kategori_filter) ? 'selected' : '' ?>>Semua</option>
                                                <?php foreach ($kategori as $kat) { ?>
                                                    <option value="<?= $kat['id_kategori'] ?>" <?= $kategori_filter == $kat['id_kategori'] ? 'selected' : '' ?>>
                                                        <?= $kat['nama_kategori'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-6 mb-2 justify-content-end">
                                    <div class="input-group">
                                        <input class="form-control" id="menu-search" placeholder="Cari menu...">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- Judul Tabel -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="table-primary">
                                            <th scope="col">No</th>
                                            <th scope="col">Nama Menu</th>
                                            <th scope="col">Harga</th>
                                            <th scope="col">Harga 1/2</th>

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
                        <!-- End Left Side -->

                        <!-- Start Right Side -->
                        <div class="col-lg-6">
                            <h4>Kelola Menu</h4>
                            <!-- Navigation Tabs -->
                            <ul class="nav nav-tabs" id="menuTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="add-tab" data-bs-toggle="tab" data-bs-target="#addMenu" type="button" role="tab" aria-controls="addMenu" aria-selected="true">Tambah</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#editMenu" type="button" role="tab" aria-controls="editMenu" aria-selected="false">Edit</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="delete-tab" data-bs-toggle="tab" data-bs-target="#deleteMenu" type="button" role="tab" aria-controls="deleteMenu" aria-selected="false">Delete</button>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content" id="menuTabsContent">
                                <!-- Tab for Adding Menu -->
                                <div class="tab-pane fade show active" id="addMenu" role="tabpanel" aria-labelledby="add-tab">
                                    <form action="addMenu.php" method="POST">
                                        <!-- Same content as the existing Add Menu form -->
                                        <div class="mb-3">
                                            <label for="kategori" class="form-label">*Kategori</label>
                                            <select class="form-select" id="kategori" name="kategori_id" required>
                                                <option value="" disabled selected>Pilih kategori...</option>
                                                <?php foreach ($kategori as $kat) { ?>
                                                    <option value="<?= $kat['id_kategori'] ?>"><?= $kat['nama_kategori'] ?></option>
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

                                <!-- Tab for Editing Menu -->
                                <div class="tab-pane fade" id="editMenu" role="tabpanel" aria-labelledby="edit-tab">
                                    <form action="editMenu.php" method="POST">
                                        <div class="mb-3">
                                            <label for="kategoriEdit" class="form-label">*Kategori</label>
                                            <select class="form-select" id="kategoriEdit" name="kategori_id_edit" required>
                                                <option value="" disabled selected>Pilih kategori...</option>
                                                <?php foreach ($kategori as $kat) { ?>
                                                    <option value="<?= $kat['id_kategori'] ?>"><?= $kat['nama_kategori'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="menuEditSelect" class="form-label">*Pilih Menu</label>
                                            <select class="form-select" id="menuEditSelect" name="menu_id_edit" required>
                                                <option value="" disabled selected>Pilih menu...</option>
                                                <!-- Options will be populated via AJAX based on selected category -->
                                            </select>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <label for="namaMenu" class="form-label">Nama Menu</label>
                                                <input type="text" class="form-control" id="namaMenu" name="nama_menu" required>
                                            </div>
                                            <div class="col-6">
                                                <label for="editNamaMenu" class="form-label">Nama Menu Baru</label>
                                                <input type="text" class="form-control" id="editNamaMenu" name="edit_nama_menu" required>
                                            </div>

                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <label for="hargaMenu" class="form-label">Harga Menu</label>
                                                <input type="text" class="form-control" id="hargaMenu" name="harga_menu" required>
                                            </div>
                                            <div class="col-6">
                                                <label for="editHargaMenu" class="form-label">Harga Menu Baru</label>
                                                <input type="text" class="form-control" id="editHargaMenu" name="edit_harga_menu" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <label for="hargaSetengah" class="form-label">Harga 1/2</label>
                                                <input type="text" class="form-control" id="hargaSetengah" name="harga_setengah" required>
                                            </div>
                                            <div class="col-6">
                                                <label for="editHargaSetengah" class="form-label">Harga 1/2 Baru</label>
                                                <input type="text" class="form-control" id="editHargaSetengah" name="edit_harga_setengah" required>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Edit</button>
                                    </form>
                                </div>

                                <!-- Tab for Deleting Menu -->
                                <div class="tab-pane fade" id="deleteMenu" role="tabpanel" aria-labelledby="delete-tab">
                                    <form action="deleteMenu.php" method="POST">
                                        <div class="mb-3">
                                            <label for="kategoriDelete" class="form-label">*Kategori</label>
                                            <select class="form-select" id="kategoriDelete" name="kategori_id_delete" required>
                                                <option value="" disabled selected>Pilih kategori...</option>
                                                <?php foreach ($kategori as $kat) { ?>
                                                    <option value="<?= $kat['id_kategori'] ?>"><?= $kat['nama_kategori'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="menuDeleteSelect" class="form-label">*Pilih Menu</label>
                                            <select class="form-select" id="menuDeleteSelect" name="menu_id_delete" required>
                                                <option value="" disabled selected>Pilih menu...</option>
                                                <!-- Options will be populated via AJAX based on selected category -->
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Right Side -->
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

    <?php include "includes/script.php"; ?>
</body>

</html>