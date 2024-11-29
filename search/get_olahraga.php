<?php
// Set headers securely
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adek";

// Database connection with error handling
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Koneksi database gagal: ' . $conn->connect_error,
        'data' => []
    ]);
    exit();
}

// Ensure only GET requests are processed
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Prepare response structure
    $response = [
        'status' => 'error',
        'message' => 'Tidak ada data',
        'data' => []
    ];

    try {
        // Prepare SQL query
        $sql = "SELECT nama_olahraga, deskripsi, gambar FROM `olahraga` ORDER BY nama_olahraga ASC";
        $result = $conn->query($sql);

        // Check if query was successful
        if ($result) {
            $data = [];
            
            // Fetch and process results
            while ($row = $result->fetch_assoc()) {
                // Safely encode image to base64, handling null values
                $row['gambar'] = $row['gambar'] ? base64_encode($row['gambar']) : null;
                $data[] = $row;
            }

            // Update response if data found
            if (!empty($data)) {
                $response = [
                    'status' => 'success',
                    'message' => 'Data olahraga berhasil diambil',
                    'data' => $data
                ];
            }
        } else {
            // Handle query execution error
            throw new Exception('Query gagal: ' . $conn->error);
        }
    } catch (Exception $e) {
        // Set error response
        http_response_code(500);
        $response = [
            'status' => 'error',
            'message' => $e->getMessage(),
            'data' => []
        ];
    }

    // Output JSON response
    echo json_encode($response);
    exit();
} else {
    // Handle invalid request method
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak diizinkan',
        'data' => []
    ]);
}

// Close database connection
$conn->close();
?>