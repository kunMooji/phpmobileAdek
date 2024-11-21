<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

include 'db_config.php';

$query = "SELECT username, password FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "Pengguna tidak ditemukan!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard <?php echo $user['username']; ?></title>
</head>
<body>
    <h1>Halo, <?php echo $user['username']; ?>!</h1>
    <p>Password: <?php echo $user['password']; ?></p>
</body>
</html>
