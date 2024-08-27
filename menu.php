<?php
require_once("./config/database.php");
session_start();

if ($_SESSION['is_login'] == false) {
    header("location: login.php");
}

// Data kategori
$kategori_query = "SELECT * FROM kategori";
$list_kategori = $db->query($kategori_query);

$menu_query = "SELECT * FROM menu";
$list_menu = $db->query($menu_query);

?>

<?php
$tabel_data_query = "SELECT 
                        id_menu, 
                        nama_kategori, 
                        nama_menu, 
                        harga_menu, 
                        harga_setengah 
                    FROM 
                        menu 
                    INNER JOIN 
                        kategori 
                    ON 
                        kategori_id_kategori = id_kategori";

$data_menu = $db->query($tabel_data_query);

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
                                        <tr class="table-primary">
                                            <th scope="col">ID Menu</th>
                                            <th scope="col">Nama Kategori</th>
                                            <th scope="col">Nama Menu</th>
                                            <th scope="col">Harga Menu</th>
                                            <th scope="col">Harga 1/2</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($data_menu->num_rows > 0) {
                                            while ($row = $data_menu->fetch_assoc()) { ?>
                                                <tr>
                                                    <td><?= $row['id_menu'] ?></td>
                                                    <td><?= $row['nama_kategori'] ?></td>
                                                    <td><?= $row['nama_menu'] ?></td>
                                                    <td><?= $row['harga_menu'] ?></td>
                                                    <td><?= $row['harga_setengah'] ?></td>
                                                </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td colspan="5">Tidak ada data menu yang ditemukan</td>
                                            </tr>
                                        <?php }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <!-- Forms for managing categories -->
                        <div class="col-lg-6 mt-3 mt-md-3 mt-lg-0">
                            <h4 class="mb-4">Kelola Menu</h4>
                            <ul class="nav nav-tabs mb-3" id="menuTab" role="tablist">
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

                            <div class="tab-content card bg-light p-4" id="menuTabContent">
                                <!-- Form Tambah -->
                                <div class="tab-pane fade show active" id="tambah" role="tabpanel" aria-labelledby="tambah-tab">
                                    <form id="formTambah" method="POST" action="./services/functions/menu_functions.php">
                                        <input type="hidden" name="action" value="tambah">
                                        <div class="mb-3">
                                            <label for="kategori" class="form-label">*Kategori</label>
                                            <select class="form-select" id="kategori" name="kategori_id" required>
                                                <option value="" disabled selected>Pilih kategori...</option>
                                                <?php foreach ($list_kategori as $kat) { ?>
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

                                <!-- Form Edit -->
                                <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                                    <form id="formEdit" method="POST" action="./services/functions/kategori_functions.php">
                                        <input type="hidden" name="action" value="edit">
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
                                        <button type="submit" class="btn btn-warning">Edit</button>
                                    </form>
                                </div>

                                <!-- Form Delete -->
                                <div class="tab-pane fade" id="delete" role="tabpanel" aria-labelledby="delete-tab">
                                    <form id="formDelete" method="POST" action="./services/functions/kategori_functions.php">
                                        <input type="hidden" name="action" value="delete">
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
        $(document).ready(function() {
            $('#myTable').DataTable({
                language: {
                    searchPlaceholder: "Cari menu", // Add placeholder to search box
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
                confirmAction(e, 'Tambah', 'Apakah Anda yakin ingin menambahkan menu ini?', '#formTambah');
            });

            // Event listener for the Edit form
            $('#formEdit').on('submit', function(e) {
                confirmAction(e, 'Edit', 'Apakah Anda yakin ingin mengubah menu ini?', '#formEdit');
            });

            // Event listener for the Delete form
            $('#formDelete').on('submit', function(e) {
                confirmAction(e, 'Hapus', 'Apakah Anda yakin ingin menghapus menu ini?', '#formDelete');
            });

            // Notification after form submission
            let status = "<?= isset($_GET['status']) ? $_GET['status'] : '' ?>";
            let tipe = "<?= isset($_GET['tipe']) ? $_GET['tipe'] : '' ?>";
            let message = '';

            if (status === "success") {
                switch (tipe) {
                    case "tambah":
                        message = "Menu berhasil ditambahkan!";
                        break;
                    case "edit":
                        message = "Menu berhasil diubah!";
                        break;
                    case "delete":
                        message = "Menu berhasil dihapus!";
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