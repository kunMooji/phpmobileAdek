<?php
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $nama_menu = isset($_GET['nama_menu']) ? $_GET['nama_menu'] : '';

    $data = array();
    $sql = "SELECT nama_menu, kalori, protein, karbohidrat, satuan FROM `menu` WHERE nama_menu = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $nama_menu);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $data['data'] = $result->fetch_assoc();
        } else {
            $data['error'] = 'Menu tidak ditemukan';
        }
        $stmt->close();
    } else {
        $data['error'] = 'Query gagal: ' . $conn->error;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

$conn->close();
?>
