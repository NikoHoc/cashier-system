<?php
require_once("./config/database.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kategori_id = $_POST['kategori_id'];
    $nama_menu = $_POST['nama_menu'];
    $harga = $_POST['harga'];
    $harga_setengah = !empty($_POST['harga_setengah']) ? $_POST['harga_setengah'] : null;

    // Prepare statement
    $stmt = $db->prepare("INSERT INTO menu (kategori_id_kategori, nama_menu, harga_menu, harga_setengah) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isdd", $kategori_id, $nama_menu, $harga, $harga_setengah);

    // Execute and redirect based on result
    if ($stmt->execute()) {
        header("Location: menu.php?status=success"); // Redirect with success status
    } else {
        header("Location: menu.php?status=error"); // Redirect with error status
    }
    exit();
}
?>
