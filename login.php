<?php
include('includes/connect.php');
include('includes/flash.php');
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
$error_msg   = '';
$success_msg = '';
$flash = get_flash();
if ($flash) {
    if ($flash['type'] === 'success') $success_msg = $flash['message'];
    else $error_msg = $flash['message'];
}
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = $_POST['password'];
    if (empty($username) || empty($password)) {
        $error_msg = 'Vui lòng điền đầy đủ thông tin!';
    } else {
        $select_query = "SELECT * FROM users WHERE username='$username' OR email='$username'";
        $result   = mysqli_query($conn, $select_query);
        $row_count = mysqli_num_rows($result);
        $row_data  = mysqli_fetch_assoc($result);
        if ($row_count > 0) {
            $old_match = ($password === $row_data['password']);
            $hash_match = password_verify($password, $row_data['password']);
            if ($old_match || $hash_match) {
                $_SESSION['username'] = $row_data['username'];
                $_SESSION['user_id']  = $row_data['user_id'];
                if (isset($_POST['remember'])) {
                    setcookie('remember_user', $row_data['username'], time() + (86400 * 30), '/');
                }
                set_flash('success', 'Chào mừng trở lại, ' . htmlspecialchars($row_data['username']) . '! 🎉');
                header('Location: index.php');
                exit();
            } else {
                $error_msg = 'Mật khẩu không đúng. Vui lòng thử lại.';
            }
        } else {
            $error_msg = 'Tài khoản không tồn tại. <a href="register.php" class="text-danger fw-bold">Đăng ký ngay?</a>';
        }
    }
}
$remembered = isset($_COOKIE['remember_user']) ? htmlspecialchars($_COOKIE['remember_user']) : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập | NhatShop</title>
    <meta name="description" content="Đăng nhập vào tài khoản NhatShop để mua sắm và bán hàng online.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background: #f5f5f5; }
        .auth-page-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #f53d2d 0%, #ff6633 50%, #ff9944 100%);
            padding: 0;
        }
        .auth-card {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            display: flex;
            min-height: 520px;
        }
        /* Left branding panel */
        .auth-left {
            background: linear-gradient(-180deg, #f53d2d, #f63);
            color: white;
            flex: 1.1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 48px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .auth-left::before {
            content: '';
            position: absolute;
            top: -60px; left: -60px;
            width: 200px; height: 200px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }
        .auth-left::after {
            content: '';
            position: absolute;
            bottom: -80px; right: -80px;
            width: 280px; height: 280px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
        }
        .auth-brand-logo {
            font-size: 3rem;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 8px;
            text-shadow: 0 2px 12px rgba(0,0,0,0.2);
            position: relative; z-index: 1;
        }
        .auth-brand-logo i { margin-right: 8px; }
        .auth-left h2 {
            font-size: 1.3rem;
            font-weight: 400;
            opacity: 0.95;
            margin-bottom: 24px;
            position: relative; z-index: 1;
        }
        .auth-features {
            list-style: none;
            padding: 0; margin: 0;
            text-align: left;
            position: relative; z-index: 1;
        }
        .auth-features li {
            padding: 6px 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .auth-features li i {
            margin-right: 8px;
            opacity: 0.8;
        }
        /* Right form panel */
        .auth-right {
            background: white;
            flex: 1;
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .auth-right h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 6px;
        }
        .auth-right .auth-subtitle {
            color: #888;
            font-size: 0.875rem;
            margin-bottom: 28px;
        }
        .form-floating label { color: #999; font-size: 0.875rem; }
        .form-floating .form-control {
            border: 1.5px solid #e8e8e8;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-floating .form-control:focus {
            border-color: var(--shopee-primary);
            box-shadow: 0 0 0 3px rgba(238,77,45,0.12);
        }
        .btn-auth-primary {
            background: linear-gradient(-180deg, #f53d2d, #f63);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            width: 100%;
            transition: opacity 0.2s, transform 0.1s;
        }
        .btn-auth-primary:hover {
            opacity: 0.92;
            color: white;
            transform: translateY(-1px);
        }
        .btn-auth-primary:active { transform: translateY(0); }
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            color: #ccc;
            font-size: 0.8rem;
        }
        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #eee;
        }
        .input-icon-wrap {
            position: relative;
        }
        .input-icon-wrap .form-control { padding-right: 44px; }
        .input-icon-wrap .toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #aaa;
            cursor: pointer;
            padding: 0;
            font-size: 0.95rem;
            transition: color 0.2s;
        }
        .input-icon-wrap .toggle-pw:hover { color: var(--shopee-primary); }
        .alert-inline {
            border-radius: 8px;
            font-size: 0.875rem;
            padding: 10px 14px;
            margin-bottom: 18px;
            border: none;
        }
        .auth-footer-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.875rem;
            color: #888;
        }
        .auth-footer-link a {
            color: var(--shopee-primary);
            font-weight: 600;
            text-decoration: none;
        }
        .auth-footer-link a:hover { text-decoration: underline; }
        /* Social login buttons */
        .btn-social {
            border: 1.5px solid #e8e8e8;
            border-radius: 8px;
            padding: 9px 16px;
            font-size: 0.875rem;
            background: white;
            color: #555;
            flex: 1;
            transition: border-color 0.2s, background 0.2s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .btn-social:hover {
            border-color: #ccc;
            background: #fafafa;
            color: #333;
        }
        /* Responsive */
        @media (max-width: 700px) {
            .auth-left { display: none; }
            .auth-right { padding: 32px 24px; }
            .auth-card { border-radius: 12px; }
        }
        /* Animate in */
        .auth-right {
            animation: slideInRight 0.4s cubic-bezier(.4,0,.2,1);
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .auth-left {
            animation: slideInLeft 0.4s cubic-bezier(.4,0,.2,1);
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        /* Header minimal */
        .auth-header {
            background: linear-gradient(-180deg, #f53d2d, #f63);
            padding: 14px 0;
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
        }
        .auth-header a {
            color: white;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 700;
        }
    </style>
</head>
<body>
<div class="auth-header">
    <div class="container">
        <a href="index.php"><i class="fas fa-shopping-bag me-2"></i>NhatShop</a>
        <span class="text-white ms-3" style="font-size:0.875rem; opacity:0.85;">Đăng Nhập</span>
    </div>
</div>
<div class="auth-page-wrap" style="padding-top: 60px;">
    <div class="container py-4">
        <div class="auth-card">
            <div class="auth-left">
                <div class="auth-brand-logo">
                    <i class="fas fa-shopping-bag"></i>NhatShop
                </div>
                <h2>Nền tảng mua sắm <br>hàng đầu Đông Nam Á</h2>
                <ul class="auth-features">
                    <li><i class="fas fa-shield-alt"></i> Mua sắm an toàn & bảo mật</li>
                    <li><i class="fas fa-truck"></i> Giao hàng nhanh toàn quốc</li>
                    <li><i class="fas fa-tag"></i> Hàng triệu deal giảm giá mỗi ngày</li>
                    <li><i class="fas fa-headset"></i> Hỗ trợ 24/7</li>
                </ul>
            </div>
            <div class="auth-right">
                <h3>Đăng nhập</h3>
                <p class="auth-subtitle">Chào mừng trở lại! Vui lòng đăng nhập để tiếp tục.</p>
                <?php if ($error_msg): ?>
                <div class="alert alert-danger alert-inline d-flex align-items-center gap-2" role="alert">
                    <i class="fas fa-exclamation-circle flex-shrink-0"></i>
                    <div><?php echo $error_msg; ?></div>
                </div>
                <?php endif; ?>
                <?php if ($success_msg): ?>
                <div class="alert alert-success alert-inline d-flex align-items-center gap-2" role="alert">
                    <i class="fas fa-check-circle flex-shrink-0"></i>
                    <div><?php echo htmlspecialchars($success_msg); ?></div>
                </div>
                <?php endif; ?>
                <form action="login.php" method="POST" id="loginForm" novalidate>
                    <input type="hidden" name="login" value="1">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Tên đăng nhập hoặc Email</label>
                        <input type="text" class="form-control" name="username" id="username"
                               placeholder="Nhập tên đăng nhập hoặc email"
                               value="<?php echo $remembered ?: (isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''); ?>"
                               required autocomplete="username">
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label small fw-semibold text-muted mb-0">Mật khẩu</label>
                            <a href="#" class="text-decoration-none small" style="color: var(--shopee-primary);">Quên mật khẩu?</a>
                        </div>
                        <div class="input-icon-wrap">
                            <input type="password" class="form-control" name="password" id="loginPw"
                                   placeholder="Nhập mật khẩu" required autocomplete="current-password">
                            <button type="button" class="toggle-pw" onclick="togglePw('loginPw', this)" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="rememberMe"
                               <?php echo $remembered ? 'checked' : ''; ?>>
                        <label class="form-check-label small text-muted" for="rememberMe">Nhớ tài khoản</label>
                    </div>
                    <button type="submit" name="login" class="btn-auth-primary" id="loginBtn">
                        ĐĂNG NHẬP
                    </button>
                </form>
                <div class="auth-footer-link">
                    Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
// Loading state on submit
document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang đăng nhập...';
    btn.disabled = true;
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
