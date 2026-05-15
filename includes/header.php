<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NhatShop - Mua Sắm Online | Giá Tốt, Đảm Bảo Chính Hãng</title>
    <meta name="description" content="Mua sắm online hàng triệu sản phẩm ở mọi ngành hàng. Giá tốt, đảm bảo chính hãng, giao hàng nhanh.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>
<div class="d-flex flex-column min-vh-100 p-0 page-wrapper">
<?php
$flash_data = get_flash();
if ($flash_data):
    $fc = flash_class($flash_data['type']);
    $fi = flash_icon($flash_data['type']);
?>
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    <div id="flashToast" class="toast align-items-center text-white bg-<?php echo $fc; ?> border-0 show"
         role="alert" data-bs-delay="4000">
        <div class="d-flex">
            <div class="toast-body fs-sm">
                <i class="fas <?php echo $fi; ?> me-2"></i>
                <?php echo htmlspecialchars($flash_data['message']); ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toastEl = document.getElementById('flashToast');
    if (toastEl) {
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
});
</script>
<?php endif; ?>
<?php
$cart_count = 0;
if (isset($_SESSION['user_id']) && isset($conn)) {
    $uid = $_SESSION['user_id'];
    $r   = mysqli_query($conn, "SELECT SUM(quantity) as c FROM cart WHERE user_id=$uid");
    $row = mysqli_fetch_assoc($r);
    $cart_count = $row['c'] ? $row['c'] : 0;
}
$final_avatar_url = '';
$dicebear_url = '';
if (isset($_SESSION['user_id']) && isset($conn)) {
    $uid_h = $_SESSION['user_id'];
    $dicebear_url = 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($_SESSION['username']);
    $r2 = mysqli_query($conn, "SELECT user_image FROM users WHERE user_id=$uid_h");
    if ($r2 && mysqli_num_rows($r2) > 0) {
        $urow = mysqli_fetch_assoc($r2);
        $avatar_db = $urow['user_image'] ?? '';
        if ($avatar_db !== '' && $avatar_db !== 'default_user.png') {
            $final_avatar_url = 'assets/images/' . $avatar_db;
        } else {
            $final_avatar_url = $dicebear_url;
        }
    }
}
?>
<header class="shopee-header">
    <div class="container">
        <div class="top-nav">
            <div class="nav-links">
                <a href="seller_center.php" class="me-3"><i class="fas fa-store me-1"></i>Kênh Người Bán</a>
                <a href="#" class="me-3">Tải Ứng Dụng</a>
                <a href="#">Kết Nối <i class="fab fa-facebook ms-1"></i> <i class="fab fa-instagram"></i></a>
            </div>
            <div class="user-links d-flex align-items-center gap-2">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="profile.php" class="d-flex align-items-center gap-2 text-decoration-none me-3" style="color: rgba(255,255,255,0.92);">
                        <img src="<?php echo htmlspecialchars($final_avatar_url); ?>"
                             class="header-avatar"
                             onerror="this.src='<?php echo htmlspecialchars($dicebear_url); ?>'"
                             alt="avatar">
                        <span class="header-username fw-bold"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </a>
                    <a href="logout.php" onclick="return confirm('Bạn có chắc muốn đăng xuất?')">Đăng Xuất</a>
                <?php else: ?>
                    <a href="register.php" class="me-3">Đăng Ký</a>
                    <a href="login.php">Đăng Nhập</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="header-main pb-2">
            <a href="index.php" class="header-logo text-white text-decoration-none d-flex align-items-center gap-2">
                <div style="background: white; color: var(--shopee-primary); width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 3px 6px rgba(0,0,0,0.15);">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <span class="logo-text fw-bold fs-4" style="letter-spacing: 0.5px; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">NhatShop</span>
            </a>
            <div class="search-bar flex-grow-1 mx-4">
                <form action="index.php" method="GET">
                    <input type="text" name="search"
                           placeholder="Đăng ký và nhận voucher bạn mới đến 70k!"
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="cart-icon fs-4">
                <a href="cart.php" class="text-white position-relative text-decoration-none">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if ($cart_count > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-white"
                          style="font-size: 0.6rem; color: var(--shopee-primary);">
                        <?php echo $cart_count; ?>
                    </span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </div>
</header>
