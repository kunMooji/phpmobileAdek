<?php
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = array();
    $sql = "SELECT nama_olahraga, kalori  FROM `olahraga` ORDER BY nama_olahraga ASC ";

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
