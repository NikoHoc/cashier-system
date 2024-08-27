<?php
require_once("../../config/database.php");

function addMenu($db, $nama_kategori) {
    // 
}

function editMenu($db, $id_kategori) {
    
}

function deleteMenu($db, $id_kategori, $id_menu) {
    
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $status = 'error';
    $tipe = '';

    if ($action == 'tambah') {
        $nama_kategori = $_POST['nama_kategori'];
        if (addMenu($db, $nama_kategori)) {
            $status = 'success';
        }
        $tipe = 'tambah';
    } elseif ($action == 'edit') {
        $id_kategori = $_POST['id_kategori'];
        $nama_kategori_baru = $_POST['nama_kategori_baru'];
        if (editMenu($db, $id_kategori, $nama_kategori_baru)) {
            $status = 'success';
        }
        $tipe = 'edit';
    } elseif ($action == 'delete') {
        $id_kategori = $_POST['id_kategori'];
        if (deleteMenu($db, $id_kategori, $id_menu)) {
            $status = 'success';
        }
        $tipe = 'delete';
    }

    header("Location: /kategori.php?status=$status&tipe=$tipe");
    exit();
} 