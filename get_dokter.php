<?php
$host = 'localhost';
$dbname = 'diet_application';
$username = 'root';     
$password = '';     

$koneksi = new mysqli($host, $username, $password, $dbname);
if ($koneksi->connect_error) {
    die(json_encode(array('error' => 'Koneksi gagal: ' . $koneksi->connect_error)));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = array();

    $sql = "SELECT id_konsultan, email, nama_lengkap, jenis, no_hp, foto_dokter FROM konsultan";
    $result = $koneksi->query($sql);

    if ($result) {
        $data['data'] = array();    

        while($row = $result->fetch_assoc()) {
            if ($row['foto_dokter']) {
                $row['foto_dokter'] = base64_encode($row['foto_dokter']);
            } else {
                $row['foto_dokter'] = null;
            }
            $data['data'][] = $row;     
        }
    } else {
        $data['error'] = 'Query gagal: ' . $koneksi->error;
    }
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

// Add endpoint for uploading doctor image
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_konsultan'])) {
    $id_konsultan = $_POST['id_konsultan'];
    
    if (isset($_FILES['foto_dokter'])) {
        $imageData = file_get_contents($_FILES['foto_dokter']['tmp_name']);
        $stmt = $koneksi->prepare("UPDATE konsultan SET foto_dokter = ? WHERE id_konsultan = ?");
        $stmt->bind_param("si", $imageData, $id_konsultan);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Gagal mengupload gambar']);
        }
        $stmt->close();
    }
}

$koneksi->close();
?>