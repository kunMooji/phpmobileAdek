<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

$host = 'localhost';
$db_name = 'adek';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT 
                judul AS judulArtikel, 
                kategori AS kategori, 
                gambar AS gambar 
              FROM artikel 
              ORDER BY judul DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    $artikels = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Convert image to base64 for easy transmission
    foreach ($artikels as &$artikel) {
        $artikel['gambar'] = $artikel['gambar'] ? base64_encode($artikel['gambar']) : null;
    }
    
    echo json_encode([
        'status' => 'success', 
        'data' => $artikels
    ]);
} catch(PDOException $e) {
    echo json_encode([
        'status' => 'error', 
        'message' => $e->getMessage()
    ]);
}

$conn = null;
?>