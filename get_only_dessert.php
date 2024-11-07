<?php
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = array();
    $sql = "SELECT nama_menu, kalori FROM `menu` WHERE kategori = 'dessert'";

    $result = $conn->query($sql); 

    if ($result) {
        $data['data'] = array();    

        while($row = $result->fetch_assoc()) {
            $data['data'][] = $row;     
        }
    } else {
        $data['error'] = 'Query gagal: ' . $conn->error;
    }
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}
$conn->close();
?>
