<?php
header('Content-Type: application/json');

$conn = mysqli_connect("localhost", "root", "", "diet_application");

if (!$conn) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Database connection failed'
    ]));
}

// Cek apakah username ada
if (!isset($_POST['username'])) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Username tidak disertakan'
    ]));
}

$username = mysqli_real_escape_string($conn, $_POST['username']);

$query = "SELECT berat_badan, tinggi_badan FROM data_pengguna WHERE username = '$username'";
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