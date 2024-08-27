<?php
require_once("./config/database.php");
session_start();

if ($_SESSION['is_login'] == false) {
    header("location: login.php");
}

$kategori_query = "SELECT * FROM kategori";
$list_kategori = $db->query($kategori_query);
?>

<?php
// Pagination tabel
$limit = 5;
// Dapatkan halaman saat ini
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Hitung total data
$total_query = "SELECT COUNT(*) as total FROM kategori";
$total_result = $db->query($total_query);
$total_data = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_data / $limit);

// Query untuk mendapatkan data sesuai halaman
$kategori_query = "SELECT * FROM kategori LIMIT $limit OFFSET $offset";
$kategori = $db->query($kategori_query);
?>

<?php
// Notifikasi untuk insert,delete,update
if (isset($_GET['status']) && isset($_GET['tipe'])) {
    $status = $_GET['status'];
    $tipe = $_GET['tipe'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "includes/head.php"; ?>
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
            <?php include "includes/sidebar.php"; ?>
            <!-- Sidebar -->
        </aside>

        <div class="main">
            <!-- Navbar -->
            <?php include "includes/navbar.php"; ?>
            <!-- Navbar -->

            <!-- Main Content -->
            <main class="content px-3 py-4">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Daftar tabel Kategori -->
                        <div class="col-lg-6 ">
                            <div class="row mb-3">
                                <div class="col-md-6 col-sm-6 ">
                                    <h4>List Kategori</h4>
                                </div>
                                <div class="col-md-6 col-sm-6 justify-content-end">
                                    <div class="input-group">
                                        <input class="form-control" id="menu-search" placeholder="Cari kategori...">
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

                                        </tr>
                                    </thead>
                                    <tbody id="kategori-body">
                                        <?php
                                        if ($total_data == 0) {
                                            echo "<tr><td colspan='3' class='text-center'>Belum ada kategori! Tambah kategori baru dulu!</td></tr>";
                                        } else {
                                            $no = $offset + 1;
                                            foreach ($kategori as $kat) { ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $kat['id_kategori'] ?></td>
                                                    <td><?= $kat['nama_kategori'] ?></td>
                                                </tr>
                                        <?php }
                                        } ?>
                                    </tbody>
                                </table>

                            </div>
                            <!-- Pagination Navigation -->
                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>

                        </div>


                        <!-- Forms for managing categories -->
                        <div class="col-lg-6">
                            <h4>Kelola Kategori</h4>
                            <ul class="nav nav-tabs mb-3" id="kategoriTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="tambah-tab" data-bs-toggle="tab" data-bs-target="#tambah" type="button" role="tab" aria-controls="tambah" aria-selected="true">Tambah</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit" type="button" role="tab" aria-controls="edit" aria-selected="false">Edit</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="delete-tab" data-bs-toggle="tab" data-bs-target="#delete" type="button" role="tab" aria-controls="delete" aria-selected="false">Delete</button>
                                </li>
                            </ul>

                            <div class="tab-content" id="kategoriTabContent">
                                <!-- Form Tambah -->
                                <div class="tab-pane fade show active" id="tambah" role="tabpanel" aria-labelledby="tambah-tab">
                                    <form id="formTambah" method="POST" action="./services/functions/kategori_functions.php">
                                        <input type="hidden" name="action" value="tambah">
                                        <div class="mb-3">
                                            <label for="namaKategori" class="form-label">*Nama Kategori</label>
                                            <input type="text" class="form-control" id="namaKategori" name="nama_kategori" placeholder="Masukan Nama Kategori baru" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Tambah</button>
                                    </form>
                                </div>

                                <!-- Form Edit -->
                                <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                                    <form id="formEdit" method="POST" action="./services/functions/kategori_functions.php">
                                        <input type="hidden" name="action" value="edit">
                                        <div class="mb-3">
                                            <label for="kategoriSelect" class="form-label">Pilih Kategori yang ingin diubah</label>
                                            <select class="form-select" id="kategoriSelect" name="id_kategori">
                                                <?php foreach ($list_kategori as $kat) { ?>
                                                    <option value="<?= $kat['id_kategori'] ?>"><?= $kat['nama_kategori'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="namaKategoriEdit" class="form-label">Nama Kategori Baru</label>
                                            <input type="text" class="form-control" id="namaKategoriEdit" name="nama_kategori_baru" required placeholder="Masukan Nama kategori baru">
                                        </div>
                                        <button type="submit" class="btn btn-warning">Edit</button>
                                    </form>
                                </div>

                                <!-- Form Delete -->
                                <div class="tab-pane fade" id="delete" role="tabpanel" aria-labelledby="delete-tab">
                                    <form id="formDelete" method="POST" action="./services/functions/kategori_functions.php">
                                        <input type="hidden" name="action" value="delete">
                                        <div class="mb-3">
                                            <label for="kategoriSelectDelete" class="form-label">Pilih Kategori yang ingin dihapus</label>
                                            <select class="form-select" id="kategoriSelectDelete" name="id_kategori">
                                                <?php foreach ($list_kategori as $kat) { ?>
                                                    <option value="<?= $kat['id_kategori'] ?>"><?= $kat['nama_kategori'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </main>
            <!-- Main Content -->
        </div>
    </div>

    <?php include "includes/script.php"; ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Generic SweetAlert confirmation function
            function confirmAction(e, actionType, message, formId) {
                e.preventDefault(); // Prevent the form from submitting immediately

                Swal.fire({
                    title: `Konfirmasi ${actionType}`,
                    text: message,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: `Ya, ${actionType}!`,
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.querySelector(formId).submit(); // Submit the form if confirmed
                    }
                });
            }

            // Event listener for the Tambah form
            document.querySelector('#formTambah').addEventListener('submit', function(e) {
                confirmAction(e, 'Tambah', 'Apakah Anda yakin ingin menambahkan kategori ini?', '#formTambah');
            });

            // Event listener for the Edit form
            document.querySelector('#formEdit').addEventListener('submit', function(e) {
                confirmAction(e, 'Edit', 'Apakah Anda yakin ingin mengubah kategori ini?', '#formEdit');
            });

            // Event listener for the Delete form
            document.querySelector('#formDelete').addEventListener('submit', function(e) {
                confirmAction(e, 'Hapus', 'Apakah Anda yakin ingin menghapus kategori ini?', '#formDelete');
            });

            // Notification after form submission
            let status = "<?= isset($_GET['status']) ? $_GET['status'] : '' ?>";
            let tipe = "<?= isset($_GET['tipe']) ? $_GET['tipe'] : '' ?>";
            let message = '';

            if (status === "success") {
                switch (tipe) {
                    case "tambah":
                        message = "Kategori berhasil ditambahkan!";
                        break;
                    case "edit":
                        message = "Kategori berhasil diubah!";
                        break;
                    case "delete":
                        message = "Kategori berhasil dihapus!";
                        break;
                }
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: message
                });
            } else if (status !== '') { // If status is not empty and not success, then it's a failure
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan!'
                });
            }

            // Other event listeners and functions
            const searchInput = document.querySelector('#menu-search');
            const kategoriBody = document.querySelector('#kategori-body');
            const pagination = document.querySelector('.pagination');
            let currentPage = <?= $page ?>;
            let totalPages = <?= $total_pages ?>;

            function fetchCategories(query = '', page = 1) {
                fetch(`./services/functions/kategori_functions.php?action=search&query=${encodeURIComponent(query)}&limit=5&offset=${(page - 1) * 5}`)
                    .then(response => response.json())
                    .then(data => {
                        kategoriBody.innerHTML = '';

                        if (data.length > 0) {
                            data.forEach((kat, index) => {
                                kategoriBody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${kat.id_kategori}</td>
                            <td>${kat.nama_kategori}</td>
                        </tr>
                    `;
                            });
                        } else {
                            kategoriBody.innerHTML = "<tr><td colspan='3' class='text-center'>Kategori tidak ditemukan! Tambah kategori baru dulu!</td></tr>";
                        }
                    });

                fetch(`./services/functions/kategori_functions.php?action=count&query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        totalPages = Math.ceil(data.total / 5);
                        updatePagination();
                    });
            }

            function updatePagination() {
                pagination.innerHTML = '';

                if (currentPage > 1) {
                    pagination.innerHTML += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage - 1}">&laquo;</a></li>`;
                }

                for (let i = 1; i <= totalPages; i++) {
                    pagination.innerHTML += `<li class="page-item ${i === currentPage ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                }

                if (currentPage < totalPages) {
                    pagination.innerHTML += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage + 1}">&raquo;</a></li>`;
                }
            }

            searchInput.addEventListener('input', function() {
                let query = this.value;
                fetchCategories(query, currentPage);
            });

            pagination.addEventListener('click', function(e) {
                if (e.target && e.target.nodeName === 'A') {
                    e.preventDefault();
                    let page = parseInt(e.target.getAttribute('data-page'));
                    if (page > 0 && page <= totalPages) {
                        currentPage = page;
                        fetchCategories(searchInput.value, page);
                    }
                }
            });

            fetchCategories(searchInput.value, currentPage);
        });
    </script>
</body>

</html>