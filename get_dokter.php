<?php
$host = 'localhost';
$dbname = 'adek';
$username = 'root';     
$password = '';     

$koneksi = new mysqli($host, $username, $password, $dbname);
if ($koneksi->connect_error) {
    die(json_encode(array('error' => 'Koneksi gagal: ' . $koneksi->connect_error)));
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = array();

    $sql = "SELECT id_konsultan, nama_lengkap, no_hp, foto_dokter FROM konsultan";
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

    // Mengatur header JSON dan mengirimkan respons
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

$koneksi->close();
?>
