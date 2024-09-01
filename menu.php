<?php
require_once("./config/database.php");
session_start();

if ($_SESSION['is_login'] == false) {
    header("location: login.php");
}
?>

<?php
/* Data kategori untuk form */
$kategori_query = "SELECT * FROM kategori";
$list_kategori = $db->query($kategori_query);

/* detail data di form */
if (isset($_POST['action']) && $_POST['action'] === 'get_menu_details') {
    $menu_id = $_POST['menu_id'];
    $menu_query = mysqli_query($db, "SELECT nama_menu, harga_menu, harga_setengah FROM menu WHERE id_menu = $menu_id");

    $menu_data = mysqli_fetch_assoc($menu_query);

    echo json_encode($menu_data);
    exit;
}

// Cek jika ini adalah permintaan AJAX untuk mendapatkan menu berdasarkan kategori
if (isset($_POST['action']) && $_POST['action'] === 'get_menu_by_category') {
    $kategori_id = $_POST['kategori_id'];
    $selected_kategori_query = mysqli_query($db, "SELECT * FROM kategori WHERE id_kategori = $kategori_id");
    $list_menu_query = mysqli_query($db, "SELECT * FROM menu WHERE kategori_id_kategori = $kategori_id");

    if (mysqli_num_rows($list_menu_query) > 0) {
        echo '<option value="" disabled selected>Pilih menu...</option>';
        while ($row_menu = mysqli_fetch_array($list_menu_query)) {
            echo '<option value="' . $row_menu['id_menu'] . '">' . $row_menu['nama_menu'] . '</option>';
        }
    } else {

        $selected_kategori = mysqli_fetch_array($selected_kategori_query);
        echo '<option value="" disabled selected>Pilih menu...</option>';
        echo '<option value="no_item">Tidak ada menu di kategori ' . $selected_kategori['nama_kategori'] . '</option>';
    }
    exit;
}
?>


<?php
/* data untuk tabel */
$tabel_data_query = "SELECT id_menu, nama_kategori, nama_menu, harga_menu, harga_setengah 
                    FROM menu INNER JOIN kategori ON kategori_id_kategori = id_kategori 
                    ORDER BY id_kategori";

$data_menu = $db->query($tabel_data_query);
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
                                    <h4>List Menu</h4>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="myTable" class="table table-hover table-bordered">
                                    <thead>
                                        <tr class="table-dark">
                                            <th class="fw-bold" scope="col">ID Menu</th>
                                            <th class="fw-bold" scope="col">Nama Kategori</th>
                                            <th class="fw-bold" scope="col">Nama Menu</th>
                                            <th class="fw-bold" scope="col">Harga Menu</th>
                                            <th class="fw-bold" scope="col">Harga 1/2</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($data_menu as $menu) { ?>
                                            <tr class="table-info">
                                                <td><?= $menu['id_menu'] ?></td>
                                                <td><?= $menu['nama_kategori'] ?></td>
                                                <td><?= $menu['nama_menu'] ?></td>
                                                <td><?= 'Rp ' . number_format($menu['harga_menu'], 0, ',', '.') ?></td>
                                                <td><?= $menu['harga_setengah'] != 0 ? 'Rp ' . number_format($menu['harga_setengah'], 0, ',', '.') : '-' ?></td>
                                            </tr>
                                        <?php }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
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
                                            <label for="namaKategori" class="form-label fw-semibold text-dark">
                                                <span class="text-danger">*</span>Kategori Menu
                                            </label>
                                            <select class="form-select" id="kategori" name="id_kategori" required>
                                                <option value="" disabled selected>Pilih kategori...</option>
                                                <?php foreach ($list_kategori as $kat) { ?>
                                                    <option value="<?= $kat['id_kategori'] ?>"><?= $kat['nama_kategori'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="namaMenu" class="form-label fw-semibold text-dark">
                                                <span class="text-danger">*</span>Nama Menu
                                            </label>
                                            <input type="text" class="form-control" id="namaMenu" name="nama_menu_baru" required placeholder="Masukan Nama Menu">
                                        </div>
                                        <div class="mb-3">
                                            <label for="harga" class="form-label fw-semibold text-dark">
                                                <span class="text-danger">*</span>Harga Menu
                                            </label>
                                            <input type="number" class="form-control" id="harga" name="harga_menu" required placeholder="Masukan Harga Menu">
                                        </div>
                                        <div class="mb-4">
                                            <label for="hargaSetengah" class="form-label fw-semibold text-dark">
                                                Harga 1/2 <span class="fw-light">(Opsional)</span>
                                            </label>
                                            <input type="number" class="form-control" id="hargaSetengah" name="harga_setengah" placeholder="Masukan Harga 1/2 Menu">
                                        </div>
                                        <div class="mb-3 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">Tambah</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Form Edit -->
                                <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                                    <form id="formEdit" method="POST" action="./services/functions/menu_functions.php">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" id="id_menu" name="id_menu">
                                        <div class="mb-3">
                                            <label for="editKategoriSelect" class="form-label fw-semibold text-dark">
                                                <span class="text-danger">*</span>List kategori menu
                                            </label>
                                            <select class="form-select" id="editKategoriSelect" name="id_kategori" required>
                                                <option value="" disabled selected>Pilih kategori...</option>
                                                <?php foreach ($list_kategori as $kat) { ?>
                                                    <option value="<?= $kat['id_kategori'] ?>"><?= $kat['nama_kategori'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="editMenuSelect" class="form-label fw-semibold text-dark">
                                                <span class="text-danger">*</span>Pilih menu yang ingin diubah
                                            </label>
                                            <select class="form-select" id="editMenuSelect" name="id_menu" required>
                                                <option value="" disabled selected>Pilih menu...</option>
                                                <!-- Options will be populated via AJAX based on selected category -->
                                            </select>
                                        </div>
                                        <div id="editMenuDetails" class="border rounded p-2 mb-3" style="display: none;">
                                            <p><strong>Nama Menu:</strong> <span id="editNamaMenu"></span></p>
                                            <p><strong>Harga Menu:</strong> <span id="editHargaMenuDetails"></span></p>
                                            <p><strong>Harga 1/2:</strong> <span id="editHargaSetengahDetails"></span></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="namaMenu" class="form-label fw-semibold text-dark">
                                                Nama Menu
                                            </label>
                                            <input type="text" class="form-control" id="namaMenu" name="nama_menu" placeholder="Masukan nama menu baru">
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-6">
                                                <label for="editHargaMenu" class="form-label fw-semibold text-dark">
                                                    Harga menu baru
                                                </label>
                                                <input type="number" class="form-control" id="editHargaMenu" name="harga_menu" placeholder="Masukan harga menu baru">
                                            </div>
                                            <div class="col-6">
                                                <label for="editHargaSetengah" class="form-label fw-semibold text-dark">
                                                    Harga 1/2 baru
                                                </label>
                                                <input type="number" class="form-control" id="editHargaSetengah" name="harga_setengah" placeholder="Masukan harga 1/2 baru">
                                            </div>
                                        </div>
                                        <div class="mb-3 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-warning" disabled>Edit</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Form Delete -->
                                <div class="tab-pane fade" id="delete" role="tabpanel" aria-labelledby="delete-tab">
                                    <form id="formDelete" method="POST" action="./services/functions/menu_functions.php">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" id="id_menu_delete" name="id_menu">
                                        <div class="mb-3">
                                            <label for="deleteKategoriSelect" class="form-label fw-semibold text-dark">
                                                <span class="text-danger">*</span>List kategori menu
                                            </label>
                                            <select class="form-select" id="deleteKategoriSelect" name="id_kategori" required>
                                                <option value="" disabled selected>Pilih kategori...</option>
                                                <?php foreach ($list_kategori as $kat) { ?>
                                                    <option value="<?= $kat['id_kategori'] ?>"><?= $kat['nama_kategori'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label for="deleteMenuSelect" class="form-label fw-semibold text-dark">
                                                <span class="text-danger">*</span>Pilih menu yang ingin dihapus
                                            </label>
                                            <select class="form-select" id="deleteMenuSelect" name="id_menu" required>
                                                <option value="" disabled selected>Pilih menu...</option>
                                                <!-- Options will be populated via AJAX based on selected category -->
                                            </select>
                                        </div>
                                        <div id="deleteMenuDetails" class="border rounded p-2 mb-3" style="display: none;">
                                            <p><strong>Nama Menu:</strong> <span id="deleteNamaMenu"></span></p>
                                            <p><strong>Harga Menu:</strong>Rp <span id="deleteHargaMenuDetails"></span></p>
                                            <p><strong>Harga 1/2:</strong>Rp <span id="deleteHargaSetengahDetails"></span></p>
                                        </div>
                                        <div class="mb-3 d-flex justify-content-end">
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
            /* Load Data tables */
            $('#myTable').DataTable({
                language: {
                    searchPlaceholder: "Cari menu", // Add placeholder to search box
                    paginate: {
                        previous: "<", // Use "<" for previous button
                        next: ">", // Use ">" for next button
                    }
                },

                initComplete: function() {
                    this.api().columns([1,]).every( function() {
                            let column = this;

                            // Create select element
                            let select = document.createElement('select');
                            select.add(new Option(''));
                            column.footer().replaceChildren(select);

                            // Apply listener for user change in value
                            select.addEventListener('change', function() {
                                column.search(select.value, {
                                    exact: true
                                }).draw();
                            });

                            // Add list of options
                            column.data().unique().sort().each(function(d, j) {
                                select.add(new Option(d));

                            });
                        });
                }
            });

            /* Form select options */
            // detect edit and delete button input
            $('#editKategoriSelect').change(function() {
                updateMenuOptions.call(this, '#editMenuSelect');
            });

            $('#deleteKategoriSelect').change(function() {
                updateMenuOptions.call(this, '#deleteMenuSelect');
            });

            function updateMenuOptions(selectId) {
                var kategori_id = $(this).val();
                var targetMenuSelect = $(selectId);

                $.ajax({
                    type: 'POST',
                    url: 'menu.php',
                    data: {
                        action: 'get_menu_by_category',
                        kategori_id: kategori_id
                    },
                    success: function(response) {
                        targetMenuSelect.html(response);
                    }
                });
            }

            /* Show selected detail menu and enable delete and edit button */
            // detect edit and delete button input
            $('#editMenuSelect').change(function() {
                updateMenuDetails('edit');
                enableDisableButton('edit');
            });
            $('#deleteMenuSelect').change(function() {
                updateMenuDetails('delete');
                enableDisableButton('delete');
            });

            function enableDisableButton(formType) {
                var selectedOption = formType === 'edit' ? $('#editMenuSelect').val() : $('#deleteMenuSelect').val();
                var button = formType === 'edit' ? $('#formEdit button[type="submit"]') : $('#formDelete button[type="submit"]');

                if (selectedOption === 'no_item' || selectedOption === '') {
                    button.prop('disabled', true)
                } else {
                    button.prop('disabled', false)
                }
            }

            function updateMenuDetails(formType) {
                var menu_id = formType === 'edit' ? $('#editMenuSelect').val() : $('#deleteMenuSelect').val();
                if (menu_id && menu_id !== 'no_item') {
                    $.ajax({
                        type: 'POST',
                        url: 'menu.php', // Make sure this matches your backend file path
                        data: {
                            action: 'get_menu_details',
                            menu_id: menu_id
                        },
                        success: function(response) {
                            var data = JSON.parse(response);
                            if (formType === 'edit') {
                                $('#editMenuDetails').show();
                                $('#editNamaMenu').text(data.nama_menu);
                                $('#editHargaMenuDetails').text(data.harga_menu);
                                $('#editHargaSetengahDetails').text(data.harga_setengah || '-');
                            } else if (formType === 'delete') {
                                $('#deleteMenuDetails').show();
                                $('#deleteNamaMenu').text(data.nama_menu);
                                $('#deleteHargaMenuDetails').text(data.harga_menu);
                                $('#deleteHargaSetengahDetails').text(data.harga_setengah || '-');
                            }
                        }
                    });
                } else {
                    if (formType === 'edit') {
                        $('#editMenuDetails').hide();
                    } else if (formType === 'delete') {
                        $('#deleteMenuDetails').hide();
                    }
                }
            }
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