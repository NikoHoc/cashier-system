<?php
require_once("./config/database.php");
require_once __DIR__ . '/vendor/autoload.php';

if (isset($_GET['id_transaksi'])) {
    $id_transaksi = intval($_GET['id_transaksi']);

    // Query untuk mendapatkan data transaksi
    $queryTransaksi = "SELECT * FROM transaksi WHERE id_transaksi = $id_transaksi";
    $resultTransaksi = $db->query($queryTransaksi);

    if ($resultTransaksi->num_rows > 0) {
        $transaksi = $resultTransaksi->fetch_assoc();

        // Query untuk mendapatkan detail transaksi
        $queryDetail = "SELECT dt.*, m.nama_menu, m.harga_menu FROM detail_transaksi dt 
                        JOIN menu m ON dt.menu_id_menu = m.id_menu
                        WHERE dt.transaksi_id_transaksi = $id_transaksi";
        $resultDetail = $db->query($queryDetail);

        // Query untuk mendapatkan data admin_depot
        $queryAdmin = "SELECT username, whatsapp_number, store_address FROM admin_depot LIMIT 1";
        $resultAdmin = $db->query($queryAdmin);
        $adminInfo = $resultAdmin->num_rows > 0 ? $resultAdmin->fetch_assoc() : null;

        // Membuat HTML untuk PDF
        $html = "
            <div style='font-family: Arial, sans-serif; font-size: 12px;'>
                <p style='text-align:center;'><strong>{$adminInfo['store_address']}</strong></p>
                <p style='text-align:center;'>Telp: {$adminInfo['whatsapp_number']}</p>
                <hr>
                <p>Nota: {$transaksi['id_transaksi']}</p>
                <p>Tanggal: {$transaksi['tanggal_transaksi']}</p>
                <p>No Meja: {$transaksi['no_meja']}</p>
                <hr>
        ";

        while ($detail = $resultDetail->fetch_assoc()) {
            $html .= "
                <p>{$detail['jumlah']} x {$detail['nama_menu']}</p>
                <p style='text-align:right;'>Rp " . number_format($detail['total_harga'], 0, ',', '.') . "</p>
            ";
        }

        $html .= "
                <hr>
                <p style='text-align:right;'>Subtotal: Rp " . number_format($transaksi['subtotal_harga'], 0, ',', '.') . "</p>
                <p style='text-align:right;'>Pajak: Rp " . number_format($transaksi['pajak'], 0, ',', '.') . "</p>
                <p style='text-align:right;'><strong>Grand Total: Rp " . number_format($transaksi['total_harga'], 0, ',', '.') . "</strong></p>
                <hr>
                <p style='text-align:center;'>Terima Kasih,</p>
                <p style='text-align:center;'>Selamat datang kembali</p>
            </div>
        ";

        // Set ukuran halaman ke lebar 8 cm dan tinggi dinamis
        $mpdf = new \Mpdf\Mpdf([
            'format' => [80, 300] // Lebar: 80mm, Tinggi dinamis
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output();
    } else {
        echo "Transaksi tidak ditemukan.";
    }
    exit;
}
?>
