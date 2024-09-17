<?php
header('Content-Type: application/json');

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_loco";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil username dari parameter GET
$username = $_GET['username'];

// Query untuk mengambil data profil
$sql = "SELECT username, email, usia, bmi, tipe_diet FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Menyediakan hasil dalam format JSON
$response = array();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response['username'] = $row['username'];
    $response['email'] = $row['email'];
    $response['usia'] = $row['usia'];
    $response['bmi'] = $row['bmi'];
    $response['tipe_diet'] = $row['tipe_diet'];
} else {
    $response['message'] = "No profile found for the given username.";
}

// Menutup koneksi
$stmt->close();
$conn->close();

// Mengembalikan JSON sebagai output
echo json_encode($response);
?>
