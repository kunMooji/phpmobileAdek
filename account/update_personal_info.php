<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adek";

$conn = new mysqli($servername, $username, $password, $dbname);

if (isset($_POST['user_id']) && isset($_POST['tanggal_lahir']) && isset($_POST['tinggi_badan']) &&
    isset($_POST['berat_badan']) && isset($_POST['gender']) && isset($_POST['tipe_diet'])) {
    
    $user_id = $_POST['user_id'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $tinggi_badan = $_POST['tinggi_badan'];
    $berat_badan = $_POST['berat_badan'];
    $gender = $_POST['gender'];
    $tipe_diet = $_POST['tipe_diet'];

    if (empty($tanggal_lahir) || empty($tinggi_badan) || empty($berat_badan) || empty($gender) || empty($tipe_diet)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit();
    }


    $sql = "INSERT INTO data_pengguna (user_id, tanggal_lahir, tinggi_badan, berat_badan, gender, tipe_diet)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $user_id, $tanggal_lahir, $tinggi_badan, $berat_badan, $gender, $tipe_diet);

    if ($stmt->execute()) {
        echo json_encode([ 'success' => true, 'message' => 'Personal information inserted successfully' ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
}

$conn->close();
?>
