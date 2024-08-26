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

function searchKategori($db, $query, $limit, $offset) {
    $query = "%$query%";
    $stmt = $db->prepare("SELECT * FROM kategori WHERE nama_kategori LIKE ? LIMIT ? OFFSET ?");
    $stmt->bind_param("sii", $query, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function countKategori($db, $query) {
    $query = "%$query%";
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM kategori WHERE nama_kategori LIKE ?");
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['total'];
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
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'search') {
    $query = $_GET['query'];
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

    $categories = searchKategori($db, $query, $limit, $offset);
    echo json_encode($categories);
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'count') {
    $query = $_GET['query'];
    $total = countKategori($db, $query);
    echo json_encode(['total' => $total]);
    exit();
}