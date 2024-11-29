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

$id_user = $_POST['id_user'];
$id_menu = $_POST['id_menu'];
$tanggal = $_POST['tanggal'];
$jumlah = $_POST['jumlah'];
$total_kalori = $_POST['total_kalori'];
$total_protein = $_POST['total_protein'];
$total_karbohidrat = $_POST['total_karbohidrat'];
$total_lemak = $_POST['total_lemak'];
$total_gula = $_POST['total_gula']; 

$query = "INSERT INTO detail_kalori 
          (id_user, id_menu, tanggal, jumlah, total_kalori, total_protein, total_karbohidrat, total_lemak, total_gula) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param(
        "sssiddddd",
        $id_user, $id_menu, $tanggal, $jumlah,
        $total_kalori, $total_protein, $total_karbohidrat, $total_lemak, $total_gula 
    );

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Data berhasil disimpan"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Gagal menyimpan data"
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
