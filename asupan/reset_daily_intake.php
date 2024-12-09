<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

include 'config.php';

$response = array('success' => false);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_user = $_POST['id_user'];
    $reset_date = $_POST['reset_date'];

    // Mulai transaksi
    mysqli_begin_transaction($conn);

    try {
        // 1. Ambil semua data asupan
        $query_select = "SELECT * FROM detail_kalori WHERE id_user = '$id_user'";
        $result = mysqli_query($conn, $query_select);

        // 2. Pindahkan ke riwayat makanan
        while ($row = mysqli_fetch_assoc($result)) {
            $insert_history = "INSERT INTO riwayat_makanan
                               (id_user, nama_menu, tanggal, jumlah, total_kalori) 
                               VALUES 
                               ('$id_user', '{$row['nama_menu']}', '$reset_date', 
                                {$row['jumlah']}, {$row['total_kalori']})";
            mysqli_query($conn, $insert_history);
        }

        // 3. Hapus data asupan
        $query_delete = "DELETE FROM detail_kalori WHERE id_user = '$id_user'";
        mysqli_query($conn, $query_delete);

        // Commit transaksi
        mysqli_commit($conn);

        $response['success'] = true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $response['error'] = $e->getMessage();
    }
}

echo json_encode($response);
?>