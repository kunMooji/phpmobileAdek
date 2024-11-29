<?php
require_once('config.php');

// Set response headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Default response structure
$response = [
    'status' => 'error',
    'message' => 'Tidak ada data',
    'data' => []
];

try {
    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception('Metode request tidak valid');
    }

    // Prepare SQL statement with parameter binding
    $sql = "SELECT nama_olahraga, deskripsi, gambar FROM `olahraga` WHERE jenis_olahraga = ?";
    $stmt = $conn->prepare($sql);
    
    // Bind parameter
    $jenisOlahraga = 'kardio';
    $stmt->bind_param('s', $jenisOlahraga);
    
    // Execute query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if query was successful
    if ($result) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            // Safely encode image to base64 with null check
            $row['gambar'] = $row['gambar'] ? base64_encode($row['gambar']) : null;
            $data[] = $row;
        }

        // Update response if data found
        if (!empty($data)) {
            $response = [
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'data' => $data
            ];
        }
    } else {
        throw new Exception('Gagal mengeksekusi query: ' . $conn->error);
    }
} catch (Exception $e) {
    // Handle any exceptions
    $response = [
        'status' => 'error',
        'message' => $e->getMessage(),
        'data' => []
    ];
    
    // Set appropriate HTTP status code for errors
    http_response_code(500);
}

// Output JSON response
echo json_encode($response);

// Close database connection
$stmt->close();
$conn->close();
?>