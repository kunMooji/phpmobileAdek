<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adek";

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = array();

    // Query untuk mendapatkan data menu dengan kategori 'makanan_berat'
    $sql = "SELECT nama_menu, kalori, gambar, resep FROM `menu` WHERE kategori_menu = 'makanan_berat'";

    $result = $conn->query($sql);

    if ($result) {
        $data['data'] = array();

        while ($row = $result->fetch_assoc()) {
            // Encode gambar (BLOB) menjadi base64
            $row['gambar'] = base64_encode($row['gambar']);

            // Pastikan "resep" tidak kosong (opsional, jika diperlukan)
            $row['resep'] = trim($row['resep']); // Hapus spasi di awal/akhir

            $data['data'][] = $row;
        }
    } else {
        $data['error'] = 'Query gagal: ' . $conn->error;
    }

    // Output JSON
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

$conn->close();
?>
