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

if (isset($_POST['nama_lengkap']) && isset($_POST['email']) && isset($_POST['password'])) {
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($nama_lengkap) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit();
    }

    // Cek apakah email sudah ada
    $sql = "SELECT * FROM data_pengguna WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
        $stmt->close();
        $conn->close();
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $id_user = hash('sha256', uniqid(mt_rand(), true));
    $sql = "INSERT INTO data_pengguna (id_user, nama_lengkap, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $id_user, $nama_lengkap, $email, $hashed_password);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Registration successful',
            'user_id' => $id_user
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $stmt->error
        ]);
    }
    
    $stmt->close();
    
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
}

$conn->close();
?>
