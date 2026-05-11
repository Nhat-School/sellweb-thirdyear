<?php
session_start();
include('includes/flash.php');

// Clear session
session_unset();
session_destroy();


// Prevent caching to ensure the user is truly redirected and can't go "back" to a logged-in state
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

set_flash('success', 'Bạn đã đăng xuất thành công. Hẹn gặp lại! 👋');
header('Location: login.php');
exit();
?>
