<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once('config.php');

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode([
        "success" => false,
        "message" => "Database connection failed"
    ]));
}

// Retrieve input data
$id_user = filter_input(INPUT_POST, 'id_user', FILTER_SANITIZE_STRING);
$tanggal = filter_input(INPUT_POST, 'tanggal', FILTER_SANITIZE_STRING);
$total_minum = filter_input(INPUT_POST, 'total_minum', FILTER_VALIDATE_FLOAT); // Amount of water consumed

// Validate input
if (empty($id_user) || empty($tanggal) || $total_minum === false) {
    echo json_encode([
        "success" => false,
        "message" => "Missing or invalid required fields"
    ]);
    exit;
}

// Prepare the SQL query to update total_minum in detail_kalori
$query = "UPDATE detail_kalori 
          SET total_minum = ? 
          WHERE id_user = ? AND tanggal = ?";

$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("dss", $total_minum, $id_user, $tanggal);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Data Minuman Berhasil Diperbarui"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Gagal Memperbarui Data Minuman"
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        "success" => false,
        "message" => "Query preparation failed"
    ]);
}

$conn->close();
?>