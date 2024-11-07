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
    $sql = "SELECT * FROM konsultan";
    $result = $koneksi->query($sql);

    if ($result) {
        $data['data'] = array();    

        while($row = $result->fetch_assoc()) {
            $data['data'][] = $row;     
        }
    } else {
        $data['error'] = 'Query gagal: ' . $koneksi->error;
    }
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}
$koneksi->close();
?>
