<?php
require_once("../../config/database.php");

function addKategori($db, $nama_kategori) {
    $stmt = $db->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
    $stmt->bind_param("s", $nama_kategori);
    return $stmt->execute();
}

function deleteKategori($db, $id_kategori) {
    $stmt = $db->prepare("DELETE FROM kategori WHERE id_kategori = ?");
    $stmt->bind_param("i", $id_kategori);
    return $stmt->execute();
}

function editKategori($db, $id_kategori, $nama_kategori_baru) {
    $stmt = $db->prepare("UPDATE kategori SET nama_kategori = ? WHERE id_kategori = ?");
    $stmt->bind_param("si", $nama_kategori_baru, $id_kategori);
    return $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $status = 'error';
    $tipe = '';

    if ($action == 'tambah') {
        $nama_kategori = $_POST['nama_kategori'];
        if (addKategori($db, $nama_kategori)) {
            $status = 'success';
        }
        $tipe = 'tambah';
    } elseif ($action == 'edit') {
        $id_kategori = $_POST['id_kategori'];
        $nama_kategori_baru = $_POST['nama_kategori_baru'];
        if (editKategori($db, $id_kategori, $nama_kategori_baru)) {
            $status = 'success';
        }
        $tipe = 'edit';
    } elseif ($action == 'delete') {
        $id_kategori = $_POST['id_kategori'];
        if (deleteKategori($db, $id_kategori)) {
            $status = 'success';
        }
        $tipe = 'delete';
    }

    header("Location: /kategori.php?status=$status&tipe=$tipe");
    exit();
} 