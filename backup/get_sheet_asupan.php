<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adek";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id_menu = isset($_GET['id_menu']) ? intval($_GET['id_menu']) : 0;

$sql = "SELECT id_menu, nama_menu, protein, karbohidrat, lemak, kalori FROM asupan WHERE id_menu = $id_menu";
$result = $conn->query($sql);

$menuArray = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $menuArray[] = [
            'id_menu' => $row['id_menu'],
            'nama_menu' => $row['nama_menu'],
            'protein' => $row['protein'],
            'karbohidrat' => $row['karbohidrat'],
            'lemak' => $row['lemak'],
            'kalori' => $row['kalori']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($menuArray);

$conn->close();
?>
