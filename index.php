<?php
require_once("./config/database.php");
session_start();

if ($_SESSION['is_login'] == false) {
  header("location: login.php");
}

$todayDate = date('d F Y');

// Query untuk mendapatkan data keseluruhan
$totalDataQuery = "
    SELECT 
        COALESCE(SUM(total_harga), 0) AS total_penjualan,
        COUNT(*) AS total_transaksi,
        SUM(CASE WHEN tipe_order = 'Bungkus' THEN 1 ELSE 0 END) AS total_bungkus,
        SUM(CASE WHEN tipe_order = 'Dine in' THEN 1 ELSE 0 END) AS total_dine_in
    FROM transaksi";
$totalResult = $db->query($totalDataQuery);

if ($totalResult && $totalResult->num_rows > 0) {
  $totalData = $totalResult->fetch_assoc();
} else {
  $totalData = [
    'total_penjualan' => 0,
    'total_transaksi' => 0,
    'total_bungkus' => 0,
    'total_dine_in' => 0,
  ];
}

// Query untuk mendapatkan data hari ini
$todayDataQuery = "
    SELECT 
        COALESCE(SUM(total_harga), 0) AS total_penjualan,
        COUNT(*) AS total_transaksi,
        SUM(CASE WHEN tipe_order = 'Bungkus' THEN 1 ELSE 0 END) AS total_bungkus,
        SUM(CASE WHEN tipe_order = 'Dine in' THEN 1 ELSE 0 END) AS total_dine_in
    FROM transaksi
    WHERE DATE(tanggal_transaksi) = CURDATE()";
$todayResult = $db->query($todayDataQuery);

if ($todayResult && $todayResult->num_rows > 0) {
  $todayData = $todayResult->fetch_assoc();
} else {
  $todayData = [
    'total_penjualan' => 0,
    'total_transaksi' => 0,
    'total_bungkus' => 0,
    'total_dine_in' => 0,
  ];
}


$grafikDataQuery = "
    SELECT 
        DATE(tanggal_transaksi) AS tanggal,
        COUNT(*) AS total_transaksi
    FROM transaksi
    GROUP BY DATE(tanggal_transaksi)
    ORDER BY tanggal ASC";
$grafikResult = $db->query($grafikDataQuery);

$grafikData = [];
if ($grafikResult && $grafikResult->num_rows > 0) {
  while ($row = $grafikResult->fetch_assoc()) {
    $grafikData[] = $row;
  }
}

$db->close();

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
        <div class="container-fluid">
          <!-- Section 1: Total Keseluruhan Data -->
          <div class="row mb-4">
            <h3>Total Keseluruhan</h3>
            <div class="col-md-3">
              <div class="card" style="background-color: #E74C3C; color: white;">
                <div class="card-body">
                  <h5 class="card-title">Total Penjualan</h5>
                  <p class="card-text">Rp <?= number_format($totalData['total_penjualan'], 0, ',', '.'); ?></p>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card" style="background-color: #3498DB; color: white;">
                <div class="card-body">
                  <h5 class="card-title">Total Transaksi</h5>
                  <p class="card-text"><?= $totalData['total_transaksi']; ?></p>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card" style="background-color: #2ECC71; color: white;">
                <div class="card-body">
                  <h5 class="card-title">Bungkus</h5>
                  <p class="card-text"><?= $totalData['total_bungkus']; ?></p>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card" style="background-color: #9B59B6; color: white;">
                <div class="card-body">
                  <h5 class="card-title">Makan di tempat</h5>
                  <p class="card-text"><?= $totalData['total_dine_in']; ?></p>
                </div>
              </div>
            </div>
          </div>

          <!-- Section 2: Total Pada Hari Ini -->
          <div class="row mb-4">
            <h3>Total Hari Ini - <?= $todayDate; ?></h3>
            <div class="col-md-3">
              <div class="card" style="background-color: #E74C3C; color: white;">
                <div class="card-body">
                  <h5 class="card-title">Total Penjualan</h5>
                  <p class="card-text">Rp <?= number_format($todayData['total_penjualan'], 0, ',', '.'); ?></p>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card" style="background-color: #3498DB; color: white;">
                <div class="card-body">
                  <h5 class="card-title">Total Transaksi</h5>
                  <p class="card-text"><?= $todayData['total_transaksi']; ?></p>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card" style="background-color: #2ECC71; color: white;">
                <div class="card-body">
                  <h5 class="card-title">Bungkus</h5>
                  <p class="card-text"><?= $todayData['total_bungkus']; ?></p>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card" style="background-color: #9B59B6; color: white;">
                <div class="card-body">
                  <h5 class="card-title">Makan di tempat</h5>
                  <p class="card-text"><?= $todayData['total_dine_in']; ?></p>
                </div>
              </div>
            </div>
          </div>


          <!-- Section 3: Shorcut -->
          <div class="row mb-4">
            <h3>Shortcut</h3>
            <div class="col-md-2">
              <button class="btn btn-primary btn-block" onclick="location.href='order.php'" style="background-color: #E74C3C; border-color: #E74C3C;">Buat Nota</button>
            </div>
            <div class="col-md-2">
              <button class="btn btn-primary btn-block" onclick="location.href='nota.php'" style="background-color: #3498DB; border-color: #3498DB;">Cetak Nota</button>
            </div>
            <div class="col-md-2">
              <button class="btn btn-primary btn-block" onclick="location.href='kategori.php'" style="background-color: #2ECC71; border-color: #2ECC71;">Manage Kategori</button>
            </div>
            <div class="col-md-2">
              <button class="btn btn-primary btn-block" onclick="location.href='menu.php'" style="background-color: #9B59B6; border-color: #9B59B6;">Manage Menu</button>
            </div>
            <div class="col-md-2">
              <button class="btn btn-primary btn-block" onclick="location.href='riwayat.php'" style="background-color: #F1C40F; border-color: #F1C40F;">Lihat Laporan</button>
            </div>
          </div>

          <!-- Section 4: Grafik -->
          <div class="row mb-4">
            <h3>Grafik Total Transaksi Per Tanggal</h3>
            <div class="col-md-12">
              <canvas id="transaksiChart" width="600" height="300"></canvas>
            </div>
          </div>
        </div>
      </main>
      <!-- Main Content -->


    </div>
  </div>




  <?php include "includes/script.php"; ?>
  <script>
    // Data dari backend (PHP)
    const grafikData = <?= json_encode($grafikData); ?>;

    const labels = grafikData.map(item => item.tanggal);
    const data = grafikData.map(item => item.total_transaksi);

    // Membuat chart menggunakan Chart.js
    const ctx = document.getElementById('transaksiChart').getContext('2d');
    const transaksiChart = new Chart(ctx, {
      type: 'line', // Tipe grafik: Line chart
      data: {
        labels: labels, // Label untuk x-axis
        datasets: [{
          label: 'Total Transaksi',
          data: data, // Data untuk y-axis
          borderColor: '#3498DB',
          backgroundColor: 'rgba(52, 152, 219, 0.5)',
          tension: 0.3,
        }]
      },
      options: {
        rresponsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            position: 'top',
          },
          title: {
            display: true,
            text: 'Grafik Total Transaksi per Tanggal'
          }
        },
        scales: {
          y: {
            title: {
              display: true,
              text: 'Tanggal Transaksi'
            },
            beginAtZero: true
          },
          x: {
            title: {
              display: true,
              text: 'Total Transaksi'
            },
          }
        }
      }
    });
  </script>
</body>

</html>