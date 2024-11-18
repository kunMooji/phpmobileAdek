<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once('config.php');

if (isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['re_password'])) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $re_password = $_POST['re_password'];

    if (empty($email) || empty($username) || empty($password) || empty($re_password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit();
    }

    if ($password !== $re_password) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
        exit();
    }

    // Cek apakah email atau username sudah ada
    $sql = "SELECT * FROM data_pengguna WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email or username already exists']);
        $stmt->close();
        $conn->close();
        exit();
    }

    // hash sblm simpan
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    //insert
    $id_user = uniqid();
    $sql = "INSERT INTO data_pengguna (id_user, username, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $id_user, $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Registration successful']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
}

$conn->close();
?>
