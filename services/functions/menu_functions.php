<?php
require_once("../../config/database.php");

function addMenu($db, $id_kategori, $nama_menu, $harga_menu, $harga_setengah = null) {
    
}

function editMenu($db, $id_kategori) {
    
}

function deleteMenu($db, $id_menu) {
    
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $status = 'error';
    $tipe = '';

    if ($action == 'tambah') {
        $id_kategori = $_POST['id_kategori'];
        $nama_menu = $_POST['nama_menu'];
        $harga_menu = $_POST['harga_menu'];
        $harga_setengah = isset($_POST['harga_setengah']) ? $_POST['harga_setengah'] : null; 
        
        if (addMenu($db, $id_kategori, $nama_menu, $harga_menu, $harga_setengah = null)) {
            $status = 'success';
        }
        $tipe = 'tambah';
    } elseif ($action == 'edit') {
        $id_menu = $_POST['id_menu']; 
        $id_kategori = $_POST['id_kategori']; 
        $nama_menu_baru = isset($_POST['nama_menu']) ? $_POST['nama_menu'] : null;
        $harga_menu = isset($_POST['harga_menu']) ? $_POST['harga_menu'] : null;
        $harga_setengah = isset($_POST['harga_setengah']) ? $_POST['harga_setengah'] : null; 
        if (editMenu($db, $id_menu, $id_kategori, $nama_menu_baru, $harga_menu, $harga_setengah)) {
            $status = 'success';
        }
        $tipe = 'edit';
    } elseif ($action == 'delete') {
        $id_menu = $_POST['id_menu'];
        if (deleteMenu($db, $id_menu)) {
            $status = 'success';
        }
        $tipe = 'delete';
    }

    header("Location: /kategori.php?status=$status&tipe=$tipe");
    exit();
} 