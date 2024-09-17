<?php
header("Content-Type: application/json; charset=UTF-8");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_loco";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}
// Baca data JSON dari body request
$data = json_decode(file_get_contents('php://input'), true);

// Validasi apakah data yang diperlukan ada
if (!isset($data['username']) || !isset($data['email']) || !isset($data['usia']) || !isset($data['bmi']) || !isset($data['tipe_diet'])) {
    echo json_encode(['message' => 'Invalid input']);
    exit();
}

$username = $data['username'];
$email = $data['email'];
$usia = $data['usia'];
$bmi = $data['bmi'];
$tipe_diet = $data['tipe_diet'];

// Query untuk memperbarui data profil berdasarkan username
$query = "UPDATE users SET email='$email', usia='$usia', bmi='$bmi', tipe_diet='$tipe_diet' WHERE username='$username'";

if (mysqli_query($conn, $query)) {
    echo json_encode(['message' => 'Profile updated successfully']);
} else {
    echo json_encode(['message' => 'Error updating profile']);
}

// Tutup koneksi
mysqli_close($conn);
?>
