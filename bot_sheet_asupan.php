<?php
require_once('config.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $nama_menu = isset($_GET['nama_menu']) ? trim($_GET['nama_menu']) : '';
    
    if (empty($nama_menu)) {
        echo json_encode(['error' => 'Nama menu tidak boleh kosong']);
        exit();
    }

    $data = array();
    $sql = "SELECT id_menu, nama_menu, kalori, protein, karbohidrat, satuan FROM `menu` WHERE nama_menu = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $nama_menu);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $data['data'] = $result->fetch_assoc();
                echo json_encode($data);
            } else {
                echo json_encode(['error' => 'Menu tidak ditemukan']);
            }
        } else {
            echo json_encode(['error' => 'Gagal mengeksekusi query: ' . $stmt->error]);
        }
        
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Gagal mempersiapkan query: ' . $conn->error]);
    }
} else {
    echo json_encode(['error' => 'Method tidak diizinkan']);
}

$conn->close();
?>