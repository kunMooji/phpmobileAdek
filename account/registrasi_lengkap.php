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

$required_fields = [
    'nama_lengkap', 'email', 'password', 
    'tanggal_lahir', 'tinggi_badan', 'berat_badan', 
    'gender', 'tipe_diet'
];

foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => "Field $field is required"]);
        exit();
    }
}

$nama_lengkap = $_POST['nama_lengkap'];
$email = $_POST['email'];
$password = $_POST['password'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$tinggi_badan = $_POST['tinggi_badan'];
$berat_badan = $_POST['berat_badan'];
$gender = $_POST['gender'];
$tipe_diet = $_POST['tipe_diet'];

$sql = "SELECT * FROM data_pengguna WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email already exists']);
    $stmt->close();
    $conn->close();
    exit();
}

$hashed_password = password_hash($password, PASSWORD_BCRYPT);
$id_user = uniqid();

$conn->begin_transaction();

try {
    $sql_akun = "INSERT INTO data_pengguna (id_user, nama_lengkap, email, password, tanggal_lahir, tinggi_badan, berat_badan, gender, tipe_diet) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_akun = $conn->prepare($sql_akun);
    $stmt_akun->bind_param("sssssssss", 
        $id_user, $nama_lengkap, $email, $hashed_password, 
        $tanggal_lahir, $tinggi_badan, $berat_badan, $gender, $tipe_diet
    );
    
    if (!$stmt_akun->execute()) {
        throw new Exception("Error inserting user data: " . $stmt_akun->error);
    }
    $conn->commit();

    echo json_encode([
        'success' => true, 
        'message' => 'Registration successful', 
        'user_id' => $id_user
    ]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}

$stmt_akun->close();
$conn->close();
?>