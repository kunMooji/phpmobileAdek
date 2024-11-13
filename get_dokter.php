<?php
$host = 'localhost';
$dbname = 'diet_application';
$username = 'root';     
$password = '';     

// Membuat koneksi ke database
$koneksi = new mysqli($host, $username, $password, $dbname);
if ($koneksi->connect_error) {
    die(json_encode(array('error' => 'Koneksi gagal: ' . $koneksi->connect_error)));
}

// Menangani permintaan GET untuk mengambil data konsultan
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = array();

    // Query untuk mengambil data dokter
    $sql = "SELECT id_konsultan, email, nama_lengkap, jenis, no_hp, foto_dokter FROM konsultan";
    $result = $koneksi->query($sql);

    if ($result) {
        $data['data'] = array();

        while($row = $result->fetch_assoc()) {
            // Jika foto_dokter ada, konversi ke base64
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

    // Mengatur header JSON dan mengirimkan respons
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

$koneksi->close();
?>
