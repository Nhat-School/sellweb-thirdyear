<?php
$host = "db"; 
$user = "root";
$pass = "securepassword";
$db   = "mystore";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Kết nối CSDL thất bại: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
