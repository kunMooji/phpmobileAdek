<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adek";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

// Check for GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = array();
    $sql = "SELECT id_olahraga, nama_olahraga, gambar, deskripsi, cara_olahraga FROM `olahraga`";

    $result = $conn->query($sql);

    if ($result) {
        $data['data'] = array();

        while ($row = $result->fetch_assoc()) {
            // Build the full URL for the file path (if needed)
            $row['gambar'] = trim($row['gambar']); // Ensure no extra spaces in the filepath
            
            // Optional: Add a full URL prefix if images are served via HTTP
            // Uncomment and modify the below line if needed
            // $row['gambar'] = "http://your-server-domain.com/images/" . $row['gambar'];
            
            // Ensure the description is not empty
            $row['deskripsi'] = trim($row['deskripsi']); // Remove whitespace
            
            // Add only if description is not empty
            if (!empty($row['deskripsi'])) {
                $data['data'][] = $row;
            }
        }
    } else {
        $data['error'] = 'Query failed: ' . $conn->error;
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

$conn->close();
?>