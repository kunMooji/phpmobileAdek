<?php
header('Content-Type: application/json');

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_loco";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$username = $_GET['username'];

$sql = "SELECT username, email, usia, bmi, tipe_diet FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

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

$stmt->close();
$conn->close();

echo json_encode($response);
?>
