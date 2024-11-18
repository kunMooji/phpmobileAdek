<?php
$host = 'localhost';
$dbname = 'adek';
$username = 'root';     
$password = '';     

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $kategori_menu = isset($_GET['kategori_menu']) ? $_GET['kategori_menu'] : '';
    if ($kategori_menu) {
        $stmt = $pdo->prepare("SELECT nama_menu FROM menu WHERE kategori_menu = :kategori_menu");
        $stmt->bindParam(':kategori_menu', $kategori_menu);
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
