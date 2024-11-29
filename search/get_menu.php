<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adek";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = array();
    $sql = "SELECT nama_menu, kalori, gambar FROM `menu`";

    $result = $conn->query($sql);

    if ($result) {
        $data['data'] = array();

        while($row = $result->fetch_assoc()) {
            // Konversi BLOB menjadi string base64
            $row['gambar'] = base64_encode($row['gambar']); 
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
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adek";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = array();
    $sql = "SELECT nama_menu, kalori, gambar FROM `menu`";

    $result = $conn->query($sql);

    if ($result) {
        $data['data'] = array();

        while($row = $result->fetch_assoc()) {
            // Konversi BLOB menjadi string base64
            $row['gambar'] = base64_encode($row['gambar']); 
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
