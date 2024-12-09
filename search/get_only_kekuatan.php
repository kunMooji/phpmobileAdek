<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
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

    // Query to get data for 'kekuatan' category
    $sql = "SELECT id_olahraga, nama_olahraga, deskripsi, gambar, cara_olahraga FROM `olahraga` WHERE jenis_olahraga = 'kekuatan';";

    $result = $conn->query($sql);

    if ($result) {
        $data['data'] = array();

        while ($row = $result->fetch_assoc()) {
            // Ensure the image filepath has no extra spaces
            $row['gambar'] = trim($row['gambar']);

            // Optional: Prefix the filepath with a base URL if required
            // Uncomment the following line and update the base URL accordingly
            // $row['gambar'] = "http://your-server-domain.com/images/" . $row['gambar'];

            // Ensure description has no extra whitespace
            $row['deskripsi'] = trim($row['deskripsi']);

            // Add to the data array
            $data['data'][] = $row;
        }
    } else {
        $data['error'] = 'Query failed: ' . $conn->error;
    }

    // Output JSON
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

// Close the connection
$conn->close();
?>
