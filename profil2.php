<?php

require_once('config.php');

try {
 
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    

    if (isset($_GET['username'])) {
        $username = $_GET['username'];
       
        $stmt = $pdo->prepare("SELECT berat_badan, tinggi_badan FROM data_pengguna WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
   
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
  
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
