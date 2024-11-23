<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

// Enable error logging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log incoming request
error_log("Received request with ID: " . $_GET['id_user'] ?? 'no id');

require_once('config.php');

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode([
        "success" => false,
        "message" => "Database connection failed: " . $conn->connect_error
    ]));
}

// Get and validate user ID
$id_user = isset($_GET['id_user']) ? trim($_GET['id_user']) : null;

if (!$id_user) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "ID User tidak ditemukan"
    ]);
    exit();
}

try {
    $query = "
        SELECT 
            DATE(dk.tanggal) as tanggal,
            COUNT(*) as jumlah,
            COALESCE(SUM(dk.total_kalori), 0) as total_kalori,
            m.nama_menu
        FROM 
            detail_kalori dk
        JOIN 
            menu m ON dk.id_menu = m.id_menu
        WHERE 
            dk.id_user = ?
        GROUP BY 
            DATE(dk.tanggal), m.nama_menu
        ORDER BY 
            dk.tanggal DESC
    ";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Query preparation failed: " . $conn->error);
    }

    $stmt->bind_param("s", $id_user);
    
    if (!$stmt->execute()) {
        throw new Exception("Query execution failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "tanggal" => date('Y-m-d', strtotime($row['tanggal'])), // Format tanggal konsisten
            "jumlah" => (int)($row['jumlah'] ?? 0),
            "total_kalori" => (int)($row['total_kalori'] ?? 0),
            "menu_name" => $row['nama_menu'] ?? ''
        ];
    }

    if (empty($data)) {
        echo json_encode([
            "success" => true,
            "message" => "Tidak ada data untuk user ini",
            "data" => []
        ]);
    } else {
        echo json_encode([
            "success" => true,
            "data" => $data
        ]);
    }

} catch (Exception $e) {
    error_log("Error in get_terakhir_dimakan.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Server error: " . $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>