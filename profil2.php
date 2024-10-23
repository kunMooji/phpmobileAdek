<?php
// Konfigurasi koneksi ke database
$host = 'localhost';
$dbname = 'diet_application';
$username = 'root';  // Sesuaikan dengan username MySQL
$password = '';  // Sesuaikan dengan password MySQL

try {
    // Membuat koneksi
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Cek apakah ada parameter username yang dikirim dari Android
    if (isset($_GET['username'])) {
        $username = $_GET['username'];
        
        // Query untuk mengambil berat_badan dan tinggi_badan berdasarkan username
        $stmt = $pdo->prepare("SELECT berat_badan, tinggi_badan FROM data_pengguna WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        
        // Mengambil hasil query
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Menampilkan data dalam format JSON
            echo json_encode([
                'status' => 'success',
                'data' => $user
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Username tidak disertakan'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Koneksi gagal: ' . $e->getMessage()
    ]);
}
?>
