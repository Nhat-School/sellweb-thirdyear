<?php
include('connect.php');
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = mysqli_real_escape_string($conn, trim($_POST['name'] ?? ''));
    $phone   = mysqli_real_escape_string($conn, trim($_POST['phone'] ?? ''));
    $email   = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
    $message = mysqli_real_escape_string($conn, trim($_POST['message'] ?? ''));
    if (empty($name) || empty($phone) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ các thông tin bắt buộc!']);
        exit;
    }
    $sql = "INSERT INTO contacts (name, phone, email, message) VALUES ('$name', '$phone', '$email', '$message')";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Thông tin của bạn đã được gửi thành công!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ.']);
}
?>
