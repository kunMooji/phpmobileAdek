<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adek";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = array();
    $sql = "SELECT id_menu, nama_menu, kalori, gambar, resep FROM `menu`";

    $result = $conn->query($sql);

    if ($result) {
        $data['data'] = array();

        while($row = $result->fetch_assoc()) {
            // Encode gambar ke base64
            $row['gambar'] = base64_encode($row['gambar']); 
            
            // Pastikan resep tidak kosong
            $row['resep'] = trim($row['resep']); // Hapus spasi di awal/akhir
            
            // Hanya tambahkan jika resep tidak kosong
            if (!empty($row['resep'])) {
                $data['data'][] = $row;
            }
        }
    } else {
        $data['error'] = 'Query gagal: ' . $conn->error;
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}
$conn->close();
?>