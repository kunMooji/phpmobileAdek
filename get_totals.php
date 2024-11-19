<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adek";

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode([
        "success" => false,
        "message" => "Database connection failed"
    ]));
}

// Ambil id_user dari parameter GET
$id_user = isset($_GET['id_user']) ? $_GET['id_user'] : null;

if (!$id_user) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "ID User tidak ditemukan"
    ]);
    exit();
}

try {
    // Query untuk mendapatkan total data
    $query = "
        SELECT 
            COALESCE(SUM(total_minum), 0) AS total_minum,
            COALESCE(SUM(total_protein), 0) AS total_protein,
            COALESCE(SUM(total_karbohidrat), 0) AS total_karbohidrat,
            COALESCE(SUM(total_lemak), 0) AS total_lemak,
            COALESCE(SUM(total_gula), 0) AS total_gula
        FROM 
            detail_kalori
        WHERE 
            id_user = ?
    ";

    // Siapkan statement
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Query preparation failed: " . $conn->error);
    }

    // Bind parameter
    $stmt->bind_param("s", $id_user);

    // Eksekusi query
    if (!$stmt->execute()) {
        throw new Exception("Query execution failed: " . $stmt->error);
    }

    // Ambil hasil
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        echo json_encode([
            "success" => true,
            "data" => $data
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Tidak ada data ditemukan untuk user ini"
        ]);
    }

} catch (Exception $e) {
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
