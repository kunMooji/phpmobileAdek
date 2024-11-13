<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "diet_application";

try {
    // Membuat koneksi PDO ke database
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Mengecek apakah username diberikan melalui GET parameter
    if (isset($_GET['username'])) {
        $username = $_GET['username'];

        // Query untuk mengambil berat dan tinggi badan berdasarkan username
        $stmt = $pdo->prepare("SELECT berat_badan, tinggi_badan FROM data_pengguna WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        // Menyimpan hasil query ke dalam array asosiatif
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Jika data ditemukan, kirim status success dengan data
            echo json_encode([
                'status' => 'success',
                'data' => $user
            ]);
        } else {
            // Jika pengguna tidak ditemukan, kirim status error
            echo json_encode([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan'
            ]);
        }
    } else {
        // Jika username tidak disertakan dalam request
        echo json_encode([
            'status' => 'error',
            'message' => 'Username tidak disertakan'
        ]);
    }
} catch (PDOException $e) {
    // Menangani error jika koneksi atau query gagal
    echo json_encode([
        'status' => 'error',
        'message' => 'Koneksi gagal: ' . $e->getMessage()
    ]);
}
?>
