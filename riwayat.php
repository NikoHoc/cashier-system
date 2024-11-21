<?php
require_once("./config/database.php");
session_start();

if ($_SESSION['is_login'] == false) {
  header("location: login.php");
}

$query = "SELECT id_transaksi, tanggal_transaksi, tipe_order, no_meja, subtotal_harga, pajak, status_transaksi FROM transaksi";
$result = $db->query($query);

// Tangani filter tanggal
$filterTanggal = isset($_GET['filterTanggal']) ? $_GET['filterTanggal'] : '';

// Cek jika ada filter tanggal, dan validasi format tanggal
if ($filterTanggal) {
  // Sanitasi input tanggal untuk menghindari SQL Injection
  $filterTanggal = $db->real_escape_string($filterTanggal);

  // Modifikasi query jika ada filter tanggal
  $query = "SELECT id_transaksi, tanggal_transaksi, tipe_order, no_meja, subtotal_harga, pajak, status_transaksi FROM transaksi WHERE tanggal_transaksi = '$filterTanggal'";
} else {
  // Jika tidak ada filter tanggal, ambil semua transaksi
  $query = "SELECT id_transaksi, tanggal_transaksi, tipe_order, no_meja, subtotal_harga, pajak, status_transaksi FROM transaksi";
}

$result = $db->query($query);
?>

<?php
// Tangani permintaan AJAX untuk detail transaksi
if (isset($_POST['id_transaksi'])) {
  $id_transaksi = intval($_POST['id_transaksi']);

  // Query untuk mengambil data transaksi
  $queryTransaksi = "SELECT * FROM transaksi WHERE id_transaksi = $id_transaksi";
  $resultTransaksi = $db->query($queryTransaksi);

  if ($resultTransaksi->num_rows > 0) {
    $transaksi = $resultTransaksi->fetch_assoc();

    // Query untuk mendapatkan detail transaksi
    $queryDetail = "SELECT dt.*, m.nama_menu, m.harga_menu FROM detail_transaksi dt 
                    JOIN menu m ON dt.menu_id_menu = m.id_menu
                    WHERE dt.transaksi_id_transaksi = $id_transaksi";
    $resultDetail = $db->query($queryDetail);

    // Buat HTML respon
    $response = "<p><strong>ID Transaksi:</strong> " . $transaksi['id_transaksi'] . "</p>";
    $response .= "<p><strong>Tanggal Transaksi:</strong> " . $transaksi['tanggal_transaksi'] . "</p>";
    $response .= "<p><strong>Tipe Order:</strong> " . ucfirst($transaksi['tipe_order']) . "</p>";
    $response .= "<p><strong>No Meja:</strong> " . ($transaksi['no_meja'] ?? '-') . "</p>";

    if ($resultDetail->num_rows > 0) {
      $response .= "<h5>Item Pesanan</h5>";
      $response .= "<table class='table table-striped'>";
      $response .= "<thead><tr><th>Nama Menu</th><th>Harga</th><th>Jumlah</th><th>Total Harga</th></tr></thead>";
      $response .= "<tbody>";
      while ($detail = $resultDetail->fetch_assoc()) {
        $response .= "<tr>";
        $response .= "<td>" . $detail['nama_menu'] . "</td>";
        $response .= "<td>Rp " . number_format($detail['harga_menu'], 0, ',', '.') . "</td>";
        $response .= "<td>" . $detail['jumlah'] . "</td>";
        $response .= "<td>Rp " . number_format($detail['total_harga'], 0, ',', '.') . "</td>";
        $response .= "</tr>";
      }
      $response .= "</tbody></table>";
    } else {
      $response .= "<p class='text-muted'>Tidak ada item pada transaksi ini.</p>";
    }

    $response .= "<p><strong>Subtotal Harga:</strong> Rp " . number_format($transaksi['subtotal_harga'], 0, ',', '.') . "</p>";
    $response .= "<p><strong>Pajak:</strong> Rp " . number_format($transaksi['pajak'], 0, ',', '.') . "</p>";
    $response .= "<p><strong>Total Harga:</strong> Rp " . number_format($transaksi['total_harga'], 0, ',', '.') . "</p>";


    echo $response;
  } else {
    echo "<p class='text-danger'>Data transaksi tidak ditemukan.</p>";
  }

  exit;
}
?>

<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require 'vendor/autoload.php';

// Cek apakah tombol export ditekan
if (isset($_POST['export_excel'])) {
  // Query untuk mengambil semua transaksi
  $query = "SELECT id_transaksi, tanggal_transaksi, tipe_order, no_meja, subtotal_harga, pajak, status_transaksi FROM transaksi";
  $result = $db->query($query);

  // Buat Spreadsheet baru
  $spreadsheet = new Spreadsheet();
  $sheet = $spreadsheet->getActiveSheet();

  // Set header kolom
  $sheet->setCellValue('A1', 'ID Transaksi');
  $sheet->setCellValue('B1', 'Tanggal Transaksi');
  $sheet->setCellValue('C1', 'Tipe Order');
  $sheet->setCellValue('D1', 'No Meja');
  $sheet->setCellValue('E1', 'Subtotal Harga');
  $sheet->setCellValue('F1', 'Pajak');
  $sheet->setCellValue('G1', 'Status Transaksi');

  // Menambahkan data ke dalam file Excel
  $row = 2; // Mulai di baris 2
  while ($transaksi = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $transaksi['id_transaksi']);
    $sheet->setCellValue('B' . $row, $transaksi['tanggal_transaksi']);
    $sheet->setCellValue('C' . $row, ucfirst($transaksi['tipe_order']));
    $sheet->setCellValue('D' . $row, $transaksi['no_meja'] ?? '-');
    $sheet->setCellValue('E' . $row, 'Rp ' . number_format($transaksi['subtotal_harga'], 0, ',', '.'));
    $sheet->setCellValue('F' . $row, 'Rp ' . number_format($transaksi['pajak'], 0, ',', '.'));
    $sheet->setCellValue('G' . $row, $transaksi['status_transaksi'] == 1 ? 'Selesai' : 'Pending');
    $row++;
  }

  // Mulai output buffering
  ob_clean(); // Clear any previous output

  // Menulis file Excel ke output
  $writer = new Xlsx($spreadsheet);

  // Tentukan nama file yang akan diunduh
  $filename = 'data_transaksi.xlsx';

  // Set header untuk mendownload file
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="' . $filename . '"');
  header('Cache-Control: max-age=0');

  // Simpan file Excel ke output
  $writer->save('php://output');

  // Akhiri output buffering dan kirim ke browser
  ob_end_flush();

  // Keluar dari script setelah proses ekspor selesai
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
            <div class="d-flex mb-2">
              <h4>Data Transaksi</h4>
              <form class="ms-3" method="POST">
                <button type="submit" name="export_excel" class="btn btn-success">Export to Excel</button>
              </form>
            </div>
            <div class="">
              <form method="GET" action="" class="d-flex mb-2">
                <label for="filterTanggal" class="me-2">Filter Tanggal:</label>
                <input type="date" id="filterTanggal" name="filterTanggal" class="form-control" value="<?= htmlspecialchars($filterTanggal) ?>" />
                <button type="submit" class="btn btn-primary ms-2">Filter</button>
              </form>

            </div>
            <table id="transaksiTable" class="table table-bordered table-hover" style="width:100%">
              <thead>
                <tr>
                  <th>ID Transaksi</th>
                  <th>Tanggal Transaksi</th>
                  <th>Tipe Order</th>
                  <th>No Meja</th>
                  <th>Subtotal Harga</th>
                  <th>Pajak</th>
                  <th>Status Transaksi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($result->num_rows > 0): ?>
                  <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                      <td><?= $row['id_transaksi'] ?></td>
                      <td><?= $row['tanggal_transaksi'] ?></td>
                      <td><?= ucfirst($row['tipe_order']) ?></td>
                      <td><?= $row['no_meja'] ?? '-' ?></td>
                      <td>Rp <?= number_format($row['subtotal_harga'], 0, ',', '.') ?></td>
                      <td>Rp <?= number_format($row['pajak'], 0, ',', '.') ?></td>
                      <td>
                        <?= $row['status_transaksi'] == 1 ? '<span class="badge bg-success">Selesai</span>' : '<span class="badge bg-warning">Pending</span>' ?>
                      </td>
                      <td>
                        <button class="btn btn-primary btn-sm" onclick="lihatDetail(<?= $row['id_transaksi'] ?>)">Lihat Detail</button>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="8" class="text-center">Tidak ada data transaksi.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="detailTransaksiModal" tabindex="-1" aria-labelledby="detailTransaksiModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="detailTransaksiModalLabel">Detail Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div id="modalDetailContent">
                  <!-- Konten detail transaksi akan dimuat di sini -->
                  <p class="text-muted text-center">Memuat data...</p>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>

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
      $('#transaksiTable').DataTable();
      var table = $('#transaksiTable').DataTable();

      
      $('#filterTanggal').on('change', function() {
        var filterDate = this.value;
        table.columns(1).search(filterDate).draw();
        table.destroy(); // Destroy current DataTable instance
        $('#transaksiTable').DataTable(); // Reinitialize
      });
    });

    function lihatDetail(idTransaksi) {
      // Tampilkan modal
      $('#detailTransaksiModal').modal('show');

      $('#modalDetailContent').html('<p class="text-muted text-center">Memuat data...</p>');

      // Lakukan request AJAX ke server untuk mendapatkan detail transaksi
      $.ajax({
        url: 'riwayat.php', // Endpoint PHP untuk mengambil detail transaksi
        method: 'POST',
        data: {
          id_transaksi: idTransaksi
        },
        success: function(response) {
          // Masukkan hasil respon ke dalam modal
          $('#modalDetailContent').html(response);
        },
        error: function() {
          // Jika terjadi kesalahan
          $('#modalDetailContent').html('<p class="text-danger text-center">Gagal memuat data.</p>');
        }
      });
    }
  </script>
</body>

</html>