<?php
require_once("../../config/database.php");
session_start();

function updateUserData($username, $email, $password, $whatsapp_number, $store_address, $db) {
    $update_query = "UPDATE admin_depot SET email_admin = ?, password = ?, whatsapp_number = ?, store_address = ? WHERE username = ?";
    $stmt = $db->prepare($update_query);
    $stmt->bind_param("sssss", $email, $password, $whatsapp_number, $store_address, $username);

    $success = $stmt->execute();
    $stmt->close();

    return $success;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $email = $_POST['email'];
    $password = $_POST['password'];
    $whatsapp_number = $_POST['wa'];
    $store_address = $_POST['alamat'];

    // Ambil user ID yang login dari session
    $loggedInUserId = $_SESSION['username'];

    // Panggil fungsi untuk memperbarui data
    $updateSuccess = updateUserData($loggedInUserId, $email, $password, $whatsapp_number, $store_address, $db);

    if ($updateSuccess) {
        // Jika berhasil
        echo json_encode(["success" => true]);
    } else {
        // Jika gagal
        echo json_encode(["success" => false]);
    }

    $db->close();
}
?>
