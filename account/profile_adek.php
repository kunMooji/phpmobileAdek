<?php
header('Content-Type: application/json');

$conn = mysqli_connect("localhost", "root", "", "adek");

if (!$conn) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Koneksi ke Database gagal.'
    ]));
}

if (!isset($_POST['nama_lengkap'])) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Nama lengkap tidak disertakan.'
    ]));
}

$nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);

$query = "SELECT berat_badan, tinggi_badan, tanggal_lahir, gambar FROM data_pengguna WHERE nama_lengkap = '$nama_lengkap'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    // Konversi BLOB menjadi Base64
    $gambarBase64 = $row['gambar'] ? base64_encode($row['gambar']) : null;

    echo json_encode([
        'status' => 'success',
        'data' => [
            'berat_badan' => $row['berat_badan'],
            'tinggi_badan' => $row['tinggi_badan'],
            'tanggal_lahir' => $row['tanggal_lahir'],
            'gambar' => $gambarBase64 // Tambahkan gambar dalam format Base64
        ]
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Data tidak ditemukan.'
    ]);
}

mysqli_close($conn);
?>
