<?php
$user_id = $_SESSION['user_id'];
$get_user = "SELECT * FROM users WHERE user_id=$user_id";
$result_user = mysqli_query($conn, $get_user);
$row_user = mysqli_fetch_assoc($result_user);
$user_email  = $row_user['email'] ?? '';
$username    = $row_user['username'] ?? '';
$address     = $row_user['address'] ?? '';
$contact     = $row_user['contact'] ?? '';
$user_image  = $row_user['user_image'] ?? '';
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'profile';
$success_msg = '';
$error_msg   = '';
$my_orders = mysqli_query($conn, "
    SELECT o.*, u.username as seller_name 
    FROM orders o 
    JOIN users u ON o.seller_id = u.user_id 
    WHERE o.buyer_id = $user_id 
    ORDER BY o.order_date DESC
");
$product_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM products WHERE seller_id=$user_id"))['c'];
if(isset($_POST['update_profile'])){
    $new_username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_address  = mysqli_real_escape_string($conn, $_POST['address']);
    $new_contact  = mysqli_real_escape_string($conn, $_POST['contact']);
    $img_field = '';
    if(isset($_FILES['user_image']) && $_FILES['user_image']['name'] != ''){
        $new_image = $_FILES['user_image']['name'];
        $temp_image = $_FILES['user_image']['tmp_name'];
        $target_dir = "./assets/images/";
        if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $ext = pathinfo($new_image, PATHINFO_EXTENSION);
        $new_img_name = 'user_' . $user_id . '_' . time() . '.' . $ext;
        if(move_uploaded_file($temp_image, $target_dir . $new_img_name)){
            $img_field = ", user_image='$new_img_name'";
            $user_image = $new_img_name;
        }
    }
    $update = "UPDATE users SET username='$new_username', address='$new_address', contact='$new_contact'$img_field WHERE user_id=$user_id";
    if(mysqli_query($conn, $update)){
        $_SESSION['username'] = $new_username;
        $username = $new_username;
        $address  = $new_address;
        $contact  = $new_contact;
        $success_msg = 'Cập nhật hồ sơ thành công!';
    } else {
        $error_msg = 'Lỗi cập nhật hồ sơ: ' . mysqli_error($conn);
    }
}
if(isset($_POST['change_password'])){
    $old_pw  = $_POST['old_password'];
    $new_pw  = $_POST['new_password'];
    $conf_pw = $_POST['confirm_password'];
    $pw_result = mysqli_query($conn, "SELECT password FROM users WHERE user_id=$user_id");
    $pw_row = mysqli_fetch_assoc($pw_result);
    $db_password = $pw_row['password'];
    $old_match = ($old_pw === $db_password) || password_verify($old_pw, $db_password);
    if(!$old_match){
        $error_msg = 'Mật khẩu hiện tại không đúng!';
    } elseif(strlen($new_pw) < 6){
        $error_msg = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
    } elseif($new_pw !== $conf_pw){
        $error_msg = 'Mật khẩu xác nhận không khớp!';
    } else {
        $hashed = password_hash($new_pw, PASSWORD_DEFAULT);
        if(mysqli_query($conn, "UPDATE users SET password='$hashed' WHERE user_id=$user_id")){
            $success_msg = 'Đổi mật khẩu thành công!';
        } else {
            $error_msg = 'Lỗi đổi mật khẩu!';
        }
    }
}
