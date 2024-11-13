<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "diet_application";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// Cek apakah request method adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit();
}

// Ambil data dari request
$id_menu = isset($_POST['id_menu']) ? $_POST['id_menu'] : '';
$jumlah = isset($_POST['jumlah']) ? $_POST['jumlah'] : '';
$kalori = isset($_POST['kalori']) ? $_POST['kalori'] : '';
$total_kalori = isset($_POST['total_kalori']) ? $_POST['total_kalori'] : '';

// Validasi input
if (empty($id_menu) || empty($jumlah) || empty($kalori) || empty($total_kalori)) {
    echo json_encode(["success" => false, "message" => "Semua field harus diisi"]);
    exit();
}

// Gunakan prepared statement untuk mencegah SQL injection
$stmt = $conn->prepare("INSERT INTO detail_kalori (id_menu, jumlah, kalori, total_kalori) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $id_menu, $jumlah, $kalori, $total_kalori);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true, 
        "message" => "Data berhasil disimpan",
        "data" => [
            "id_menu" => $id_menu,
            "jumlah" => $jumlah,
            "kalori" => $kalori,
            "total_kalori" => $total_kalori
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