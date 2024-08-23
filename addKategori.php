<?php
require_once("./services/config.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kategori = $_POST['nama_kategori'];

    // Prepare statement
    $stmt = $db->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
    $stmt->bind_param("s", $nama_kategori);

    if ($stmt->execute()) {
        header("Location: kategori.php?status=success"); // Redirect with success status
    } else {
        header("Location: kategori.php?status=error"); // Redirect with error status
    }
    exit();
}
?>
