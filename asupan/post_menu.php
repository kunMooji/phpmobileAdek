<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_fields = ['nama_menu', 'protein', 'karbohidrat', 'lemak', 'kalori', 'satuan', 'kategori_menu'];
    $missing_fields = [];
    
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
            $missing_fields[] = $field;
        }
    }
    
    if (!empty($missing_fields)) {
        echo json_encode([
            'success' => false, 
            'message' => 'Missing required fields: ' . implode(', ', $missing_fields)
        ]);
        exit;
    }
    
    $nama_menu = trim($_POST['nama_menu']);
    $protein = trim($_POST['protein']);
    $karbohidrat = trim($_POST['karbohidrat']);
    $lemak = trim($_POST['lemak']);
    $kalori = trim($_POST['kalori']);
    $satuan = trim($_POST['satuan']);
    $kategori_menu = trim($_POST['kategori_menu']);
 
    $sql = "INSERT INTO menu (nama_menu, protein, karbohidrat, lemak, kalori, satuan, kategori_menu) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssssss", 
            $nama_menu, $protein, $karbohidrat, $lemak, 
            $kalori, $satuan, $kategori_menu
        );
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true, 
                'message' => 'Menu berhasil ditambahkan'
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Gagal menambahkan menu: ' . $stmt->error
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Database error: ' . $conn->error
        ]);
    }
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid request method'
    ]);
}

$conn->close();
?>
