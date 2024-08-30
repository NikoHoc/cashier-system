<?php
require_once("../../config/database.php");

function addMenu($db, $id_kategori, $nama_menu, $harga_menu, $harga_setengah) {
    $query = "INSERT INTO menu (kategori_id_kategori, nama_menu, harga_menu, harga_setengah) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("isdd", $id_kategori, $nama_menu, $harga_menu, $harga_setengah);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function editMenu($db, $id_menu, $id_kategori, $nama_menu, $harga_menu, $harga_setengah) {
    if ($nama_menu != null && $harga_menu == null && $harga_setengah == null) {
        // edit nama menu
        $query = "UPDATE menu SET nama_menu = ? WHERE id_menu = ? AND kategori_id_kategori = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sii", $nama_menu, $id_menu, $id_kategori);

    } else if ($nama_menu == null && $harga_menu != null && $harga_setengah == null) {
        // edit harga menu
        $query = "UPDATE menu SET harga_menu = ? WHERE id_menu = ? AND kategori_id_kategori = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("dii", $harga_menu, $id_menu, $id_kategori);
    } else if ($nama_menu == null && $harga_menu == null && $harga_setengah != null) {
        // edit harga 1/2
        $query = "UPDATE menu SET harga_setengah = ? WHERE id_menu = ? AND kategori_id_kategori = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("dii", $harga_setengah, $id_menu, $id_kategori);
    } else if ($nama_menu != null && $harga_menu != null && $harga_setengah == null) {
        // edit nama menu dan harga
        $query = "UPDATE menu SET nama_menu = ?, harga_menu = ? WHERE id_menu = ? AND kategori_id_kategori = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sdii", $nama_menu, $harga_menu, $id_menu, $id_kategori);

    } else if ($nama_menu != null && $harga_menu == null && $harga_setengah != null) {
        // edit nama menu dan harga setengah
        $query = "UPDATE menu SET nama_menu = ?, harga_setengah = ? WHERE id_menu = ? AND kategori_id_kategori = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sdii", $harga_menu, $harga_setengah, $id_menu, $id_kategori);

    } else if ($nama_menu == null && $harga_menu != null && $harga_setengah != null) {
        // edit harga dan harga 1/2
        $query = "UPDATE menu SET harga_menu = ?, harga_setengah = ? WHERE id_menu = ? AND kategori_id_kategori = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ddii",$harga_menu, $harga_setengah, $id_menu, $id_kategori);

    } else {
        // edit nama, harga, harga 1/2
        $query = "UPDATE menu SET nama_menu = ?, harga_menu = ?, harga_setengah = ? WHERE id_menu = ? AND kategori_id_kategori = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sddii", $nama_menu, $harga_menu, $harga_setengah, $id_menu, $id_kategori);
    }
    

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function deleteMenu($db,  $id_menu) {
    $query = "DELETE FROM menu WHERE id_menu = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id_menu);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $status = 'error';
    $tipe = '';

    if ($action == 'tambah') {
        $id_kategori = $_POST['id_kategori'];
        $nama_menu = $_POST['nama_menu_baru'];
        $harga_menu = $_POST['harga_menu'];
        $harga_setengah = !empty($_POST['harga_setengah']) ? $_POST['harga_setengah'] : 0; 
    
        if (addMenu($db, $id_kategori, $nama_menu, $harga_menu, $harga_setengah)) {
            $status = 'success';
        }
        $tipe = 'tambah';
    } elseif ($action == 'edit') {
        $id_menu = $_POST['id_menu']; 
        $id_kategori = $_POST['id_kategori']; 
        $nama_menu_baru = isset($_POST['nama_menu']) ? $_POST['nama_menu'] : null;
        $harga_menu = isset($_POST['harga_menu']) ? $_POST['harga_menu'] : null;
        $harga_setengah = isset($_POST['harga_setengah']) ? $_POST['harga_setengah'] : null; 

        if ($nama_menu_baru == null && $harga_menu == null && $harga_setengah == null ) {
            $tipe='edit';
            header("Location: /menu.php?status=$status&tipe=$tipe");
            exit();
        }
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

    header("Location: /menu.php?status=$status&tipe=$tipe");
    exit();
} 