<?php

$servername = "localhost";
$dbname = "web_loco";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) { 
    die("". $conn->connect_error);
}
    ?>

