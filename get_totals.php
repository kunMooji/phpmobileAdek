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
        COALESCE(SUM(dk.total_minum), 0) AS total_minum,
        COALESCE(SUM(dk.total_protein), 0) AS total_protein,
        COALESCE(SUM(dk.total_karbohidrat), 0) AS total_karbohidrat,
        COALESCE(SUM(dk.total_lemak), 0) AS total_lemak,
        COALESCE(SUM(dk.total_gula), 0) AS total_gula,
        dp.tipe_diet,
        dp.berat_badan,
        dp.tinggi_badan
    FROM 
        detail_kalori dk
    JOIN 
        data_pengguna dp ON dk.id_user = dp.id_user
    WHERE 
        dk.id_user = ?;
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
