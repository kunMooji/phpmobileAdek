<?php
header('Content-Type: application/json');

$conn = mysqli_connect("localhost", "root", "", "adek");

if (!$conn) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Database connection failed'
    ]));
}

if (!isset($_POST['nama_lengkap'])) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Nama lengkap tidak disertakan'
    ]));
}

$nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);

$query = "SELECT berat_badan, tinggi_badan FROM data_pengguna WHERE nama_lengkap = '$nama_lengkap'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo json_encode([
        'status' => 'success',
        'data' => [
            'berat_badan' => $row['berat_badan'],
            'tinggi_badan' => $row['tinggi_badan']
        ]
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Data tidak ditemukan'
    ]);
}

mysqli_close($conn);
?>
