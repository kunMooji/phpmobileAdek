<?php
$host = 'localhost';
$dbname = 'diet_application';
$username = 'root';     
$password = '';     

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
    if ($kategori) {
        $stmt = $pdo->prepare("SELECT nama_menu FROM menu WHERE kategori = :kategori");
        $stmt->bindParam(':kategori', $kategori);
    } else {
        $stmt = $pdo->prepare("SELECT nama_menu FROM menu");
    }

    $stmt->execute();
    $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($menus) {
        echo json_encode([
            'status' => 'success',
            'data' => $menus
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Menu tidak ditemukan'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Koneksi gagal: ' . $e->getMessage()
    ]);
}
?>
