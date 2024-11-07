<?php
require_once("../../config/database.php");
session_start();

if (!isset($_SESSION['id_admin'])) {
    die("Error: admin_id is not set in the session.");
}

echo "Admin ID: " . $_SESSION['id_admin'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggalTransaksi = $_POST['tanggalTransaksi'];
    $tipeOrder = $_POST['tipeOrder'];
    $noMeja = ($tipeOrder === 'Bungkus') ? NULL : $_POST['noMeja'];
    $subtotal = $_POST['subtotal'];
    $pajak = isset($_POST['pajak']) ? $subtotal * 0.1 : 0;
    $totalHarga = $subtotal + $pajak;
    $adminId = $_SESSION['id_admin'];

    $db->begin_transaction();

    try {
        $transaksi_query = "INSERT INTO transaksi (tanggal_transaksi, tipe_order, no_meja, subtotal_harga, pajak, total_harga, status_transaksi, admin_id_admin) 
                            VALUES (?, ?, ?, ?, ?, ?, 0, ?)";
        $stmt = $db->prepare($transaksi_query);
        $stmt->bind_param("sssdddi", $tanggalTransaksi, $tipeOrder, $noMeja, $subtotal, $pajak, $totalHarga, $adminId);
        $stmt->execute();
        $transaksiId = $stmt->insert_id;
    
        // Decode and log detail items
        $detailItems = json_decode($_POST['detailItems'], true);
        //$_SESSION['debug_detailItems'] = $detailItems;
    
        foreach ($detailItems as $item) {
            $jumlah = $item['jumlah'];
            $harga = $item['harga'];
            $keterangan = $item['keterangan'] ?? "";
            $menuId = $item['menu_id'];
            $totalHargaItem = $jumlah * $harga;
    
            $detail_query = "INSERT INTO detail_transaksi (jumlah, total_harga, keterangan, transaksi_id_transaksi, menu_id_menu) 
                             VALUES (?, ?, ?, ?, ?)";
            $detail_stmt = $db->prepare($detail_query);
            $detail_stmt->bind_param("idsii", $jumlah, $totalHargaItem, $keterangan, $transaksiId, $menuId);
            $detail_stmt->execute();
        }
    
        $db->commit();
        $_SESSION['status'] = 'success';
    } catch (Exception $e) {
        $db->rollback();
        $_SESSION['status'] = 'error';
        //$_SESSION['debug_error'] = $e->getMessage();
    }
    
    header("Location: ../../order.php");
    exit();
    
}
?>