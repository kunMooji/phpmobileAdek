<?php
header('Content-Type: application/json');

// Koneksi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'adek';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Koneksi database gagal: ' . $conn->connect_error]));
}

// Pastikan parameter ID menu diterima
if (!isset($_GET['id_menu'])) {
    die(json_encode(['error' => 'ID menu tidak diberikan']));
}

$menuId = $_GET['id_menu'];

// Query untuk mengambil detail menu menggunakan prepared statement
$query = "SELECT id_menu, nama_menu, kalori, resep, gambar FROM menu WHERE id_menu = '1'";

if ($stmt = $conn->prepare($query)) {
    // Bind parameter
    $stmt->bind_param("i", $menuId); // "i" menandakan integer
    
    // Eksekusi query
    $stmt->execute();
    
    // Ambil hasil
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $menu = $result->fetch_assoc();

        // Konversi gambar ke base64 jika perlu
        $gambarBase64 = $menu['gambar'] ? base64_encode($menu['gambar']) : null;

        $response = [
            'id_menu' => $menu['id_menu'],
            'nama_menu' => $menu['nama_menu'],
            'kalori' => $menu['kalori'],
            'resep' => $menu['resep'],
            'gambar' => $gambarBase64
        ];

        echo json_encode(['data' => $response]);
    } else {
        echo json_encode(['error' => 'Menu tidak ditemukan']);
    }

    // Menutup statement
    $stmt->close();
} else {
    die(json_encode(['error' => 'Prepared statement gagal: ' . $conn->error]));
}

$conn->close();
?>
