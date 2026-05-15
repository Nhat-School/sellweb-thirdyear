<?php
session_start();
include('includes/flash.php');
session_unset();
session_destroy();
header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 
set_flash('success', 'Bạn đã đăng xuất thành công. Hẹn gặp lại! 👋');
header('Location: login.php');
exit();
?>
