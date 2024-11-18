<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adek";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit();
}

// Validate data
$id_user = filter_var($_POST['id_user'], FILTER_SANITIZE_STRING);
$id_menu = filter_var($_POST['id_menu'], FILTER_SANITIZE_STRING);
$tanggal = filter_var($_POST['tanggal'], FILTER_SANITIZE_STRING);
$jumlah = filter_var($_POST['jumlah'], FILTER_VALIDATE_INT);
$total_kalori = filter_var($_POST['total_kalori'], FILTER_VALIDATE_FLOAT);
$total_minum = filter_var($_POST['total_minum'], FILTER_VALIDATE_INT);

if ($jumlah === false || $total_kalori === false || $total_minum === false) {
    echo json_encode(["success" => false, "message" => "Invalid data type"]);
    exit();
}

$stmt = $conn->prepare("INSERT INTO detail_kalori (id_user, id_menu, tanggal, jumlah, total_kalori, total_minum) 
                        VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $id_user, $id_menu, $tanggal, $jumlah, $total_kalori, $total_minum);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true, 
        "message" => "Data berhasil disimpan",
        "data" => [
            "id_user" => $id_user,
            "id_menu" => $id_menu,
            "tanggal" => $tanggal,
            "jumlah" => $jumlah,
            "total_kalori" => $total_kalori,
            "total_minum" => $total_minum
        ]
    ]);
} else {
    echo json_encode([
        "success" => false, 
        "message" => "Error: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
