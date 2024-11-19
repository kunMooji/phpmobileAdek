<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adek";

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode([
        "success" => false,
        "message" => "Database connection failed"
    ]));
}

// Get and validate user ID
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
    $query = "
        SELECT 
            COUNT(*) as jumlah_menu,
            COALESCE(SUM(dk.total_kalori), 0) as total_kalori
        FROM 
            detail_kalori dk
        WHERE 
            dk.id_user = ?
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
    $row = $result->fetch_assoc();

    echo json_encode([
        "success" => true,
        "data" => [
            "jumlah_menu" => (int)($row['jumlah_menu'] ?? 0),
            "total_kalori" => (int)($row['total_kalori'] ?? 0)
        ]
    ]);

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