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
        .tab-content.card {
            margin-top: -1rem;
            border-top: none;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;

            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            /* Optional: Adds a subtle shadow */
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
                <div class="container-fluid card p-4">
                    <div class="row">
                        <!-- Daftar tabel Kategori -->
                        <div class="col-lg-6">
                            <div class="row mb-3">
                                <div class="col-md-6 col-sm-6 ">
                                    <h4>List Kategori</h4>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="myTable" class="table table-hover table-bordered">
                                    <thead>
                                        <tr class="table-dark">
                                            <th class="fw-bold" scope="col">No</th>
                                            <th class="fw-bold" scope="col">ID Kategori</th>
                                            <th class="fw-bold" scope="col">Nama kategori</th>
                                        </tr>
                                    </thead>
                                    <tbody id="kategori-body">
                                        <?php
                                        $no = 1;
                                        foreach ($list_kategori as $kat) { ?>
                                            <tr class="table-info">
                                                <td><?= $no++ ?></td>
                                                <td><?= $kat['id_kategori'] ?></td>
                                                <td><?= $kat['nama_kategori'] ?></td>
                                            </tr>
                                        <?php }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <!-- Forms for managing categories -->
                        <div class="col-lg-6 mt-3 mt-md-3 mt-lg-0">
                            <h4 class="mb-4">Kelola Kategori</h4>
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

                            <div class="tab-content card bg-light p-4" id="kategoriTabContent">
                                <!-- Form Tambah -->
                                <div class="tab-pane fade show active" id="tambah" role="tabpanel" aria-labelledby="tambah-tab">
                                    <form id="formTambah" method="POST" action="./services/functions/kategori_functions.php">
                                        <input type="hidden" name="action" value="tambah">
                                        <div class="mb-4">
                                            <label for="namaKategori" class="form-label fw-semibold text-dark">
                                                <span class="text-danger">*</span>Nama kategori
                                            </label>
                                            <input type="text" class="form-control" id="namaKategori" name="nama_kategori" placeholder="Masukan Nama Kategori baru" required>
                                        </div>
                                        <div class="mb-3 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">Tambah</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Form Edit -->
                                <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                                    <form id="formEdit" method="POST" action="./services/functions/kategori_functions.php">
                                        <input type="hidden" name="action" value="edit">
                                        <div class="mb-3">
                                            <label for="kategoriSelect" class="form-label fw-semibold text-dark">
                                                <span class="text-danger">*</span>Pilih kategori yang ingin diubah
                                            </label>
                                            <select class="form-select" id="kategoriSelect" name="id_kategori">
                                                <?php foreach ($list_kategori as $kat) { ?>
                                                    <option value="<?= $kat['id_kategori'] ?>"><?= $kat['nama_kategori'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="namaKategoriEdit" class="form-label fw-semibold text-dark">
                                                <span class="text-danger">*</span>Nama kategori baru
                                            </label>
                                            <input type="text" class="form-control" id="namaKategoriEdit" name="nama_kategori_baru" required placeholder="Masukan Nama kategori baru">
                                        </div>
                                        <div class="mb-4 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-warning">Edit</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Form Delete -->
                                <div class="tab-pane fade" id="delete" role="tabpanel" aria-labelledby="delete-tab">
                                    <form id="formDelete" method="POST" action="./services/functions/kategori_functions.php">
                                        <input type="hidden" name="action" value="delete">
                                        <div class="mb-3">
                                            <label for="kategoriSelectDelete" class="form-label fw-semibold text-dark">
                                                <span class="text-danger">*</span>Pilih kategori yang ingin dihapus
                                            </label>
                                            <select class="form-select" id="kategoriSelectDelete" name="id_kategori">
                                                <?php foreach ($list_kategori as $kat) { ?>
                                                    <option value="<?= $kat['id_kategori'] ?>"><?= $kat['nama_kategori'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="mb-4 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </div>
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
        $(document).ready(function() {
            $('#myTable').DataTable({
                language: {
                    searchPlaceholder: "Cari kategori", // Add placeholder to search box
                    paginate: {
                        previous: "<", // Use "<" for previous button
                        next: ">" // Use ">" for next button
                    }
                }
            });
        });
        
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
            $('#formTambah').on('submit', function(e) {
                confirmAction(e, 'Tambah', 'Apakah Anda yakin ingin menambahkan kategori ini?', '#formTambah');
            });

            // Event listener for the Edit form
            $('#formEdit').on('submit', function(e) {
                confirmAction(e, 'Edit', 'Apakah Anda yakin ingin mengubah kategori ini?', '#formEdit');
            });

            // Event listener for the Delete form
            $('#formDelete').on('submit', function(e) {
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


        });
    </script>
</body>

</html>