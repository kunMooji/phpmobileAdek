<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "diet_application";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit();
    }

    $sql = "SELECT * FROM data_pengguna WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
    
        if (password_verify($password, $user['password'])) {
            
            echo json_encode([
                'success' => true,
                'message' => 'Login successful!',
                'user' => [
                    'username' => $user['username'],
                    'email' => $user['email'],  
                    'no_hp' => $user['no_hp'],  
                    'berat_badan' => $user['berat_badan'],  
                    'tinggi_badan' => $user['tinggi_badan']     
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Incorrect username or password']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect username or password']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
}

$conn->close();
?>
