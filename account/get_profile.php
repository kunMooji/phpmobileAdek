<?php
header('Content-Type: application/json');

$conn = mysqli_connect("localhost", "root", "", "adek");

if (!$conn) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Database connection failed'
    ]));
}

// Ubah dari $_GET menjadi $_POST atau $_REQUEST
$id_user = isset($_POST['id_user']) ? $_POST['id_user'] : null;

if ($id_user) {
    $sql = "SELECT nama_lengkap, tipe_diet, gender, berat_badan, tinggi_badan, email, no_hp, tanggal_lahir 
            FROM data_pengguna 
            WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Ambil data sebagai satu objek, bukan array
        $row = $result->fetch_assoc();
        
        // Tampilkan data dalam format JSON
        echo json_encode(array(
            "status" => "success", 
            "data" => $row
        ));
    } else {
        echo json_encode(array("status" => "error", "message" => "Data tidak ditemukan"));
    }

    $stmt->close();
} else {
    echo json_encode(array("status" => "error", "message" => "Parameter id_user tidak ditemukan"));
}

// Tutup koneksi
$conn->close();
?>