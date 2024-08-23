<?php
require_once("./services/config.php");
session_start();

if ($_SESSION['is_login'] == false) {
    header("location: login.php");
}

// Jumlah data per halaman
$per_page = 5;

// Data kategori
$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search) {
    // Jika ada pencarian
    $kategori_query = $db->prepare("SELECT * FROM kategori WHERE nama_kategori LIKE ?");
    $search_param = "%" . $search . "%";
    $kategori_query->bind_param("s", $search_param);
    $kategori_query->execute();
    $result = $kategori_query->get_result();
} else {
    // Jika tidak ada pencarian
    $kategori_query = $db->query("SELECT * FROM kategori");
    $result = $kategori_query;
}

$total_data = $result->num_rows;
$total_pages = ceil($total_data / $per_page);

$kategori_page = isset($_GET['kategori_page']) ? $_GET['kategori_page'] : 1;
$start = ($kategori_page - 1) * $per_page;

// Jika ada pencarian, lakukan pencarian dengan limit
if ($search) {
    $kategori_query_pagination = $db->prepare("SELECT * FROM kategori WHERE nama_kategori LIKE ? LIMIT ?, ?");
    $kategori_query_pagination->bind_param("sii", $search_param, $start, $per_page);
    $kategori_query_pagination->execute();
    $kategori_pagination = $kategori_query_pagination->get_result();
} else {
    $kategori_query_pagination = $db->query("SELECT * FROM kategori LIMIT $start, $per_page");
    $kategori_pagination = $kategori_query_pagination;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "components/link.php"; ?>
    <style>
        @media (max-width: 1156px) {
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
                <div class="container-fluid">
                    <div class="row">
                        <!-- Daftar tabel Kategori -->
                        <div class="col-lg-6 ">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <h4>List Kategori</h4>
                                </div>
                                <div class="col-md-6 d-flex justify-content-end">
                                    <div class="input-group" style="max-width: 25rem;">
                                        <input class="form-control" id="menu-search" placeholder="Cari kategori..." oninput="searchKategori(this.value)">
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
                                            <th scope="col">ID Kategori</th>
                                            <th scope="col">Nama kategori</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="kategori-body">
                                        <?php if ($total_data > 0) {
                                            $no = $start + 1; // Update no urut
                                            foreach ($kategori_pagination as $kat) { ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $kat['id_kategori'] ?></td>
                                                    <td><?= $kat['nama_kategori'] ?></td>
                                                    <td>
                                                        <a href="editKategori.php?id=<?= $kat['id_kategori'] ?>" class="btn btn-warning btn-sm btn-custom-spacing">Edit</a>
                                                        <a href="deleteKategori.php?id=<?= $kat['id_kategori'] ?>" class="btn btn-danger btn-sm btn-custom-spacing" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">Delete</a>
                                                    </td>
                                                </tr>
                                        <?php }
                                        } ?>
                                        <tr id="kategori-not-found-row" style="display: none;">
                                            <td colspan="4" class="text-center">Kategori tidak ditemukan ! Tambah dulu disamping !</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-center mt-3">
                                        <?php if ($kategori_page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?kategori_page=<?= $kategori_page - 1 ?>&search=<?= urlencode($search); ?>" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                                            <li class="page-item <?= ($i == $kategori_page) ? 'active' : ''; ?>">
                                                <a class="page-link" href="?kategori_page=<?= $i; ?>&search=<?= urlencode($search); ?>"><?= $i; ?></a>
                                            </li>
                                        <?php } ?>

                                        <?php if ($kategori_page < $total_pages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?kategori_page=<?= $kategori_page + 1 ?>&search=<?= urlencode($search); ?>" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>

                        <!-- Form Kelola Kategori -->
                        <div class="col-lg-6">
                            <h4>Tambah Kategori</h4>
                            <form action="addKategori.php" method="POST">
                                <div class="mb-3">
                                    <label for="namaKategori" class="form-label">*Nama Kategori</label>
                                    <input type="text" class="form-control" id="namaKategori" name="nama_kategori" required placeholder="Masukan Nama kategori baru">
                                </div>
                                <button type="submit" class="btn btn-primary">Tambah</button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <!-- Main Content -->
        </div>
    </div>

    <?php include "components/script.php"; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');

            if (status === 'success') {
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Kategori berhasil ditambahkan.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            } else if (status === 'error') {
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menambahkan kategori.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });

        function searchKategori(searchTerm) {
            searchTerm = searchTerm.toLowerCase();
            const rows = document.querySelectorAll('#kategori-body tr:not(#kategori-not-found-row)');
            let found = false;

            rows.forEach(row => {
                const namaKategori = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                if (namaKategori.includes(searchTerm)) {
                    row.style.display = '';
                    found = true;
                } else {
                    row.style.display = 'none';
                }
            });

            const kategoriNotFoundRow = document.getElementById('kategori-not-found-row');
            if (kategoriNotFoundRow) {
                if (!found) {
                    kategoriNotFoundRow.style.display = 'table-row';
                } else {
                    kategoriNotFoundRow.style.display = 'none';
                }
            }
        }
    </script>
</body>

</html>