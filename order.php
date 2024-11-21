<?php
require_once("./config/database.php");
session_start();

if ($_SESSION['is_login'] == false) {
  header("location: login.php");
}

$transaksi_query = "SELECT id_transaksi, no_meja, tipe_order FROM transaksi WHERE status_transaksi = 0";
$list_transaksi = $db->query($transaksi_query);

$kategori_query = "SELECT * FROM kategori";
$list_kategori = $db->query($kategori_query);
?>
<?php
if (isset($_POST['id_transaksi'])) {
  $id_transaksi = intval($_POST['id_transaksi']);

  // Query untuk mengambil data transaksi berdasarkan id_transaksi
  $queryTransaksi = "SELECT * FROM transaksi WHERE id_transaksi = $id_transaksi";
  $resultTransaksi = $db->query($queryTransaksi);

  if ($resultTransaksi->num_rows > 0) {
    $transaksi = $resultTransaksi->fetch_assoc();

    $queryDetail = "SELECT dt.*, m.nama_menu, m.harga_menu FROM detail_transaksi dt JOIN menu m ON dt.menu_id_menu = m.id_menu
                WHERE dt.transaksi_id_transaksi = $id_transaksi";
    $resultDetail = $db->query($queryDetail);

    // Buat HTML konten modal
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

    echo $response;
  } else {
    echo "<p class='text-danger'>Data transaksi tidak ditemukan.</p>";
  }
  exit; // Hentikan script di sini
}
?>

<?php
if (isset($_POST['update_status_transaksi'])) {
  $id_transaksi = intval($_POST['update_status_transaksi']);

  // Query untuk mengupdate status_transaksi
  $queryUpdate = "UPDATE transaksi SET status_transaksi = 1 WHERE id_transaksi = $id_transaksi";
  if ($db->query($queryUpdate)) {
    echo "Status transaksi berhasil diperbarui.";
  } else {
    echo "Gagal memperbarui status transaksi: " . $db->error;
  }
  exit; // Hentikan script di sini
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
            <div class="col-lg-8">
              <div class="row">
                <h4 class="mb-4 mt-1">Nota</h4>
                <!-- Load Nota -->
                <div class="d-flex flex-wrap gap-2">
                  <?php
                  if ($list_transaksi && $list_transaksi->num_rows > 0) {
                    while ($transaksi = $list_transaksi->fetch_assoc()) {
                      $buttonText = ($transaksi['tipe_order'] === 'Bungkus') ? 'B' : $transaksi['no_meja'];

                      echo "<button class='btn btn-outline-primary' data-bs-toggle='modal' data-bs-target='#detailTransaksiModal' value='" . $transaksi['id_transaksi'] . "'>";
                      echo $buttonText;
                      echo "</button>";
                    }
                  } else {
                    echo "<p class='text-muted text-center w-100'>Belum ada nota</p>";
                  }
                  ?>
                </div>
              </div>
              <div class="row">
                <!-- Load category and menu -->
                <div class="col mt-3 mt-md-3 mt-lg-0">
                  <h4 class="mb-4 mt-4">Data Menu</h4>
                  <ul class="nav nav-tabs" id="kategoriTab" role="tablist">
                    <?php foreach ($list_kategori as $index => $kat) { ?>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $index === 0 ? 'active' : '' ?>"
                          id="tab-<?= $kat['id_kategori'] ?>"
                          data-bs-toggle="tab"
                          data-bs-target="#kategori-<?= $kat['id_kategori'] ?>"
                          type="button"
                          role="tab"
                          aria-controls="kategori-<?= $kat['id_kategori'] ?>"
                          aria-selected="<?= $index === 0 ? 'true' : 'false' ?>">
                          <?= $kat['nama_kategori'] ?>
                        </button>
                      </li>
                    <?php } ?>
                  </ul>

                  <!-- Tab Content for Each Category -->
                  <div class="tab-content bg-light" id="kategoriTabContent">
                    <?php foreach ($list_kategori as $index => $kat) { ?>
                      <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>"
                        id="kategori-<?= $kat['id_kategori'] ?>"
                        role="tabpanel"
                        aria-labelledby="tab-<?= $kat['id_kategori'] ?>">
                        <div class="table-responsive">
                          <table class="table table-hover table-bordered" id="myTable-<?= $kat['id_kategori'] ?>">
                            <thead>
                              <tr class="table-dark">
                                <th>No</th>
                                <th>Nama Menu</th>
                                <th>Harga Menu</th>
                                <th>Harga Setengah</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $menu_query = "SELECT id_menu, nama_menu, harga_menu, harga_setengah FROM menu WHERE kategori_id_kategori = " . $kat['id_kategori'];
                              $menu_items = $db->query($menu_query);
                              $no = 1;
                              if ($menu_items && $menu_items->num_rows > 0) {
                                while ($menu = $menu_items->fetch_assoc()) {
                                  echo "<tr class='table-info'>";
                                  echo "<td>" . $no++ . "</td>";
                                  echo "<td>" . htmlspecialchars($menu['nama_menu']) . "</td>";
                                  echo "<td>Rp " . number_format($menu['harga_menu'], 0, ',', '.') . "</td>";
                                  echo "<td>Rp " . (isset($menu['harga_setengah']) ? number_format($menu['harga_setengah'], 0, ',', '.') : '-') . "</td>";
                                  echo "<td><button class='btn btn-sm btn-success w-100' onclick=\"tambahItem('" . addslashes($menu['nama_menu']) . "', " . $menu['harga_menu'] . ", " . $menu['id_menu'] . ")\">Pilih</button></td>";
                                  echo "</tr>";
                                }
                              } else {
                                echo "<tr><td colspan='5' class='text-muted text-center'>Tidak ada menu dalam kategori ini.</td></tr>";
                              }
                              ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="card">
                <h4 class="mb-4 ms-3 mt-3">Data Nota</h4>
                <form id="notaForm" method="post" action="./services/functions/order_functions.php">
                  <div class="m-3">
                    <label for="tanggalTransaksi" class="form-label">Tanggal Transaksi</label>
                    <input required type="date" class="form-control" id="tanggalTransaksi" name="tanggalTransaksi" value="<?= date('Y-m-d'); ?>" readonly>
                  </div>
                  <div class="m-3">
                    <label for="tipeOrder" class="form-label">Tipe Order</label>
                    <select required class="form-select" id="tipeOrder" name="tipeOrder" onchange="toggleNoMejaInput()">
                      <option value="Bungkus">Bungkus</option>
                      <option value="Dine in">Dine in</option>
                    </select>
                  </div>
                  <div class="m-3" id="noMejaContainer" style="display: none;">
                    <label for="noMeja" class="form-label">Nomor Meja</label>
                    <input type="number" class="form-control" id="noMeja" name="noMeja" min="1">
                  </div>
                  <h5 class="ms-3 me-1">Item:</h5>
                  <div id="detailTransaksi" class="ms-3"></div>
                  <input type="hidden" name="detailItems" id="hiddenDetailItems">
                  <hr class="ms-3 me-3">
                  <div class="ms-3 mb-2">
                    <label>Total Items: <span id="totalItems">0</span></label>
                  </div>
                  <div class="ms-3 mb-1">
                    <label>Subtotal: Rp <span id="subtotal">0</span></label>
                    <input type="hidden" name="subtotal" id="hiddenSubtotal">
                  </div>
                  <div class="form-check ms-3 mb-1">
                    <input class="form-check-input" type="checkbox" id="pajakCheckbox">
                    <label class="form-check-label" for="pajakCheckbox">Pajak (10%)</label>
                    <span id="pajak">- Rp 0</span>
                    <input type="hidden" name="pajak" id="hiddenPajak">
                  </div>
                  <div class="ms-3 mb-3">
                    <label>Total Harga: Rp <span id="totalHarga">0</span></label>
                    <input type="hidden" name="totalHarga" id="hiddenTotalHarga">
                  </div>
                  <button type="submit" style="background-color:#3b7ddd" class="btn btn-primary ms-3 mb-3" onclick="validateNota()">Buat Nota</button>
                </form>
              </div>
            </div>


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
                <button type="button" class="btn btn-success" id="btnSelesai" data-id-transaksi="">Selesai</button>
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
    // buka modal
    document.querySelectorAll('button[data-bs-target="#detailTransaksiModal"]').forEach(button => {
      button.addEventListener('click', function() {
        const idTransaksi = this.value;

        // Set atribut data-id-transaksi pada tombol "Selesai"
        document.getElementById('btnSelesai').setAttribute('data-id-transaksi', idTransaksi);

        // Kirim data ke PHP menggunakan AJAX
        fetch('order.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id_transaksi=${idTransaksi}`
          })
          .then(response => response.text())
          .then(data => {
            document.getElementById('modalDetailContent').innerHTML = data;
          })
          .catch(error => {
            console.error('Error:', error);
          });
      });
    });

    // update transaksi
    document.getElementById('btnSelesai').addEventListener('click', function() {
      const idTransaksi = this.getAttribute('data-id-transaksi');

      if (!idTransaksi) {
        console.error('ID transaksi tidak ditemukan!');
        return;
      }

      // Kirim data ke PHP menggunakan AJAX
      fetch('order.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `update_status_transaksi=${idTransaksi}`
        })
        .then(response => response.text())
        .then(data => {
          // Tampilkan notifikasi sukses atau error
          alert(data);

          // Tutup modal setelah pembaruan berhasil
          const modal = bootstrap.Modal.getInstance(document.getElementById('detailTransaksiModal'));
          modal.hide();

          // Reload halaman untuk melihat perubahan (opsional)
          location.reload();
        })
        .catch(error => {
          console.error('Error:', error);
        });
    });
    $(document).ready(function() {
      <?php foreach ($list_kategori as $kat) { ?>
        $('#myTable-<?= $kat['id_kategori'] ?>').DataTable({
          language: {
            searchPlaceholder: "Cari Menu",
            paginate: {
              previous: "<",
              next: ">"
            }
          },
          pageLength: 15
        });
      <?php } ?>
    });

    let detailItems = [];
    let subtotal = 0;
    let totalHarga = 0;

    /* Function untuk + - item ke nota */
    function tambahItem(nama_menu, harga, menu_id) {
      const itemIndex = detailItems.findIndex(item => item.nama === nama_menu);

      if (itemIndex === -1) {
        detailItems.push({
          nama: nama_menu,
          harga: harga,
          jumlah: 1,
          menu_id: menu_id, // Include the menu_id in the item object
          keteranganChecked: false,
          keteranganText: ""
        });
      } else {
        detailItems[itemIndex].jumlah += 1;
      }

      updateDetailTransaksi();
    }

    function kurangiItem(index) {
      if (detailItems[index].jumlah > 1) {
        detailItems[index].jumlah -= 1;
      } else {
        detailItems.splice(index, 1);
      }

      updateDetailTransaksi();
    }

    function updateDetailTransaksi() {
      const $detailTransaksiDiv = $('#detailTransaksi');
      $detailTransaksiDiv.empty();

      let totalItems = 0;
      subtotal = 0;
      detailItems.forEach((item, index) => {
        const totalHargaItem = item.harga * item.jumlah;
        subtotal += totalHargaItem;
        totalItems += item.jumlah;

        $detailTransaksiDiv.append(`
              <div class="d-flex align-items-center mb-2 mt-2">
                  <button type="button" class="btn btn-sm btn-secondary me-2" onclick="kurangiItem(${index})">-</button>
                  <span>${item.jumlah}</span>
                  <button type="button" class="btn btn-sm btn-secondary ms-2" onclick="tambahItem('${item.nama}', ${item.harga})">+</button>
                  <span class="ms-2">
                    ${item.nama} - Rp ${totalHargaItem.toLocaleString()}
                    ${item.jumlah > 1 ? ` @Rp ${item.harga.toLocaleString()}` : ''}
                  </span>
              </div>
              <div class="form-check mb-2 mt-2">
                  <input type="checkbox" class="form-check-input" id="keteranganCheckbox-${index}" 
                      ${item.keteranganChecked ? 'checked' : ''} 
                      onclick="toggleKeterangan(${index})">
                  <label class="form-check-label" for="keteranganCheckbox-${index}">Tambah Keterangan</label>
              </div>
              <div id="keteranganInput-${index}" class="mb-2 mt-2" style="display: ${item.keteranganChecked ? 'block' : 'none'};">
                  <label for="keterangan" class="form-label">Keterangan</label>
                  <input type="text" class="form-control keterangan-input" data-index="${index}" 
                        id="keterangan-${index}" name="keterangan" 
                        value="${item.keteranganText}">
              </div>
          `);
      });

      $('#hiddenSubtotal').val(subtotal);
      $('#hiddenDetailItems').val(JSON.stringify(detailItems));
      console.log(document.getElementById('hiddenDetailItems').value);
      $('#totalItems').text(totalItems);
      $('#subtotal').text(subtotal.toLocaleString());
      updateTotalHarga();
    }
    /* Function untuk + - item ke nota */


    /* Function to toggle the keterangan input visibility and update the state */
    function toggleKeterangan(index) {
      detailItems[index].keteranganChecked = !detailItems[index].keteranganChecked;
      updateDetailTransaksi();
    }
    $(document).on('input', '.keterangan-input', function() {
      const index = $(this).data('index');
      detailItems[index].keteranganText = $(this).val();
    });


    /* Function untuk update pajak */
    $('#pajakCheckbox').on('change', updateTotalHarga);

    function updateTotalHarga() {
      const pajak = $('#pajakCheckbox').is(':checked') ? subtotal * 0.1 : 0;
      $('#pajak').text(`- Rp ${pajak.toLocaleString()}`);

      totalHarga = subtotal + pajak;
      $('#totalHarga').text(totalHarga.toLocaleString());

      $('#hiddenPajak').val(pajak);
      $('#hiddenTotalHarga').val(totalHarga);
    }
    /* Function untuk update pajak */


    function validateNota() {
      if (totalHarga === 0) {
        alert("Item masih kosong, harap tambah item dulu.");
        event.preventDefault(); // Prevents form submission
        return false;
      }
      return true;
    }

    document.addEventListener('DOMContentLoaded', function() {
      <?php if (isset($_SESSION['status'])): ?>
        Swal.fire({
          icon: '<?= $_SESSION['status'] === 'success' ? 'success' : 'error' ?>',
          title: '<?= $_SESSION['status'] === 'success' ? 'Nota Created Successfully!' : 'Failed to Create Nota' ?>',
          text: '<?= $_SESSION['status'] === 'success' ? 'The nota has been saved.' : 'Please check your data and try again.' ?>'
        });
        <?php unset($_SESSION['status']); // Clear the status after displaying the alert 
        ?>
      <?php endif; ?>
    });

    function toggleNoMejaInput() {
      const tipeOrder = document.getElementById("tipeOrder").value;
      const noMejaContainer = document.getElementById("noMejaContainer");

      if (tipeOrder === "Dine in") {
        noMejaContainer.style.display = "block";
        document.getElementById("noMeja").required = true; // Make No Meja required for Dine in
      } else {
        noMejaContainer.style.display = "none";
        document.getElementById("noMeja").required = false; // Not required for Bungkus
      }
    }

    // Call the function once on page load in case there's a default value in Tipe Order
    document.addEventListener("DOMContentLoaded", toggleNoMejaInput);
  </script>
</body>

</html>