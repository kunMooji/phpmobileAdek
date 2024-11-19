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

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode([
        "success" => false,
        "message" => "Database connection failed"
    ]));
}

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
            m.nama_menu, 
            dk.jumlah, 
            dk.total_kalori
        FROM 
            detail_kalori dk
        JOIN 
            menu m ON dk.id_menu = m.id_menu
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

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "nama_menu" => $row['nama_menu'],
            "jumlah" => (int)$row['jumlah'],
            "total_kalori" => (int)$row['total_kalori']
        ];
    }

    echo json_encode([
        "success" => true,
        "data" => $data
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
