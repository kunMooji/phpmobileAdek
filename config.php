<?php
$servername = "localhost";
$username = "root"; // Sesuaikan dengan konfigurasi database
$password = "";
$dbname = "diet"; // Nama database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$user = $_POST['username'];
$pass = $_POST['password'];

// Check if user exists in database
$sql = "SELECT * FROM data_pengguna WHERE username = '$user' AND password = '$pass'";
$result = $conn->query(query: $sql);

if ($result->num_rows > 0) {
    echo "Login successful";
} else {
    echo "Invalid username or password";
}

$conn->close();
?>
