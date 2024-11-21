<?php
require_once("./config/database.php");
session_start();

if ($_SESSION['is_login'] == false) {
    header("location: login.php");
}
$transaksi_query = "SELECT id_transaksi, no_meja, tipe_order FROM transaksi WHERE status_transaksi = 1";
$list_transaksi = $db->query($transaksi_query);
?>
<?php
if (isset($_POST['id_transaksi'])) {
    $id_transaksi = intval($_POST['id_transaksi']);

    // Query untuk mengambil data transaksi
    $queryTransaksi = "SELECT * FROM transaksi WHERE id_transaksi = $id_transaksi";
    $resultTransaksi = $db->query($queryTransaksi);

    if ($resultTransaksi->num_rows > 0) {
        $transaksi = $resultTransaksi->fetch_assoc();

        $queryDetail = "SELECT dt.*, m.nama_menu, m.harga_menu FROM detail_transaksi dt 
                        JOIN menu m ON dt.menu_id_menu = m.id_menu
                        WHERE dt.transaksi_id_transaksi = $id_transaksi";
        $resultDetail = $db->query($queryDetail);

        // Buat HTML konten
        $response = "<p><strong>ID Transaksi:</strong> " . $transaksi['id_transaksi'] . "</p>";
        $response .= "<p><strong>Tanggal Transaksi:</strong> " . $transaksi['tanggal_transaksi'] . "</p>";
        $response .= "<p><strong>Tipe Order:</strong> " . $transaksi['tipe_order'] . "</p>";
        $response .= "<p><strong>No Meja:</strong> " . ($transaksi['no_meja'] ?? '-') . "</p>";

        if ($resultDetail->num_rows > 0) {
            $response .= "<h5>Item Pesanan</h5>";
            $response .= "<table class='table table-striped'>";
            $response .= "<thead>";
            $response .= "<tr><th>Nama Menu</th><th>Harga</th><th>Jumlah</th><th>Total Harga</th></tr>";
            $response .= "</thead>";
            $response .= "<tbody>";
            while ($detail = $resultDetail->fetch_assoc()) {
                $response .= "<tr>";
                $response .= "<td>" . $detail['nama_menu'] . "</td>";
                $response .= "<td>" . number_format($detail['harga_menu'], 0, ',', '.') . "</td>";
                $response .= "<td>" . $detail['jumlah'] . "</td>";
                $response .= "<td>" . number_format($detail['total_harga'], 0, ',', '.') . "</td>";
                $response .= "</tr>";
            }
            $response .= "</tbody>";
            $response .= "</table>";
        } else {
            $response .= "<p class='text-muted'>Tidak ada item pada transaksi ini.</p>";
        }
        $response .= "<p><strong>Subtotal Harga:</strong> " . number_format($transaksi['subtotal_harga'], 0, ',', '.') . "</p>";
        $response .= "<p><strong>Pajak:</strong> " . number_format($transaksi['pajak'], 0, ',', '.') . "</p>";
        $response .= "<p><strong>Total Harga:</strong> " . number_format($transaksi['total_harga'], 0, ',', '.') . "</p>";

        // Tambahkan tombol cetak
        $response .= "<div class='mt-4'>";
        $response .= "<button class='btn btn-primary' onclick=\"loadPDFPreview($id_transaksi)\">Cetak Nota</button>";
        $response .= "</div>";

        echo $response;
    } else {
        echo "<p class='text-danger'>Data transaksi tidak ditemukan.</p>";
    }
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "includes/head.php"; ?>
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
                        <div class="col-lg-4">
                            <h4>List Nota</h4>
                            <select class="form-select" id="notaSelector" name="nota">
                                <option value="" selected>Pilih Nota</option>
                                <?php
                                if ($list_transaksi->num_rows > 0) {
                                    while ($transaksi = $list_transaksi->fetch_assoc()) {
                                        $label = "Nota #" . $transaksi['id_transaksi'];
                                        if (!empty($transaksi['no_meja'])) {
                                            $label .= " - Meja " . $transaksi['no_meja'];
                                        }
                                        $label .= " (" . ucfirst($transaksi['tipe_order']) . ")";
                                        echo "<option value='{$transaksi['id_transaksi']}'>{$label}</option>";
                                    }
                                } else {
                                    echo "<option value='' disabled>Tidak ada nota tersedia</option>";
                                }
                                ?>
                            </select>

                            <div id="detailTransaksiContainer" class="mt-4 ms-1">
                                <p class="text-muted">Silakan pilih nota untuk melihat detail transaksi.</p>
                            </div>
                        </div>

                        <div class="col-lg-8">

                        </div>
                    </div>
                    
                </div>
            </main>
            <!-- Main Content -->
        </div>
    </div>




    <?php include "includes/script.php"; ?>
    <script>
        document.getElementById('notaSelector').addEventListener('change', function() {
            const idTransaksi = this.value;

            // Pastikan ada nilai yang dipilih
            if (!idTransaksi) {
                document.getElementById('detailTransaksiContainer').innerHTML = '<p class="text-muted">Silakan pilih nota untuk melihat detail transaksi.</p>';
                return;
            }

            // Kirim AJAX request ke server
            fetch('nota.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_transaksi=${idTransaksi}`
            })
            .then(response => response.text())
            .then(data => {
                // Tampilkan data transaksi di container
                document.getElementById('detailTransaksiContainer').innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('detailTransaksiContainer').innerHTML = '<p class="text-danger">Terjadi kesalahan saat memuat data.</p>';
            });
        });

        function loadPDFPreview(idTransaksi) {
            const pdfContainer = document.querySelector('.col-lg-8');
            if (idTransaksi) {
                // Atur konten dalam col-lg-8 sebagai preview PDF
                pdfContainer.innerHTML = `
                    <iframe src="cetak_nota.php?id_transaksi=${idTransaksi}" 
                            style="width:100%;height:400px;border:none;" 
                            title="Preview Nota"></iframe>`;
            } else {
                pdfContainer.innerHTML = '<p class="text-muted">Pilih nota untuk melihat preview PDF.</p>';
            }
        }
    </script>
</body>

</html>