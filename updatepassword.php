<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once('config.php');

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Memastikan data POST diterima
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $newPassword = $_POST['password'];

    // Validasi input
    if (empty($username) || empty($newPassword)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit();
    }

    // Hash password baru
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update password di database
    $sql = "UPDATE data_pengguna SET password = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashedPassword, $username);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Username not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
}

$conn->close();
?>
