<?php
include('includes/connect.php');
include('includes/flash.php');

// Nếu đã đăng nhập thì redirect về trang chủ
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error_msg   = '';
$success_msg = '';

if (isset($_POST['register'])) {
    $username   = mysqli_real_escape_string($conn, trim($_POST['username']));
    $email      = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password   = $_POST['password'];
    $confirm_pw = $_POST['confirm_password'];

    // Validations
    if (empty($username) || empty($email) || empty($password) || empty($confirm_pw)) {
        $error_msg = 'Vui lòng điền đầy đủ tất cả các trường!';
    } elseif (strlen($username) < 3) {
        $error_msg = 'Tên đăng nhập phải có ít nhất 3 ký tự!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = 'Địa chỉ email không hợp lệ!';
    } elseif (strlen($password) < 6) {
        $error_msg = 'Mật khẩu phải có ít nhất 6 ký tự!';
    } elseif ($password !== $confirm_pw) {
        $error_msg = 'Mật khẩu xác nhận không khớp!';
    } else {
        // Kiểm tra username tồn tại
        $check_uname = "SELECT user_id FROM users WHERE username='$username'";
        if (mysqli_num_rows(mysqli_query($conn, $check_uname)) > 0) {
            $error_msg = 'Tên đăng nhập này đã được sử dụng!';
        } else {
            // Kiểm tra email tồn tại
            $check_email = "SELECT user_id FROM users WHERE email='$email'";
            if (mysqli_num_rows(mysqli_query($conn, $check_email)) > 0) {
                $error_msg = 'Email này đã được đăng ký!';
            } else {
                $hash_password = password_hash($password, PASSWORD_DEFAULT);
                $insert = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hash_password')";
                if (mysqli_query($conn, $insert)) {
                    set_flash('success', 'Đăng ký thành công! Đăng nhập ngay để bắt đầu mua sắm 🎉');
                    header('Location: login.php');
                    exit();
                } else {
                    $error_msg = 'Có lỗi xảy ra. Vui lòng thử lại sau.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký | NhatShop</title>
    <meta name="description" content="Tạo tài khoản NhatShop miễn phí để bắt đầu mua sắm và bán hàng online.">
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
            min-height: 580px;
        }

        .auth-left {
            background: linear-gradient(-180deg, #f53d2d, #f63);
            color: white;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 48px 36px;
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
            font-size: 2.8rem;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 8px;
            text-shadow: 0 2px 12px rgba(0,0,0,0.2);
            position: relative; z-index: 1;
        }
        .auth-left h2 {
            font-size: 1.2rem;
            font-weight: 400;
            opacity: 0.95;
            margin-bottom: 24px;
            position: relative; z-index: 1;
        }
        .auth-steps {
            list-style: none;
            padding: 0; margin: 0;
            text-align: left;
            position: relative; z-index: 1;
        }
        .auth-steps li {
            padding: 8px 0;
            font-size: 0.88rem;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .auth-steps .step-num {
            width: 24px; height: 24px;
            background: rgba(255,255,255,0.25);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .auth-right {
            background: white;
            flex: 1.1;
            padding: 40px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
        }
        .auth-right h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 4px;
        }
        .auth-right .auth-subtitle {
            color: #888;
            font-size: 0.875rem;
            margin-bottom: 22px;
        }

        .form-label-sm {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #666;
            margin-bottom: 5px;
        }
        .form-control-auth {
            border: 1.5px solid #e8e8e8;
            border-radius: 8px;
            font-size: 0.9rem;
            padding: 10px 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control-auth:focus {
            border-color: var(--shopee-primary);
            box-shadow: 0 0 0 3px rgba(238,77,45,0.12);
            outline: none;
        }
        .form-control-auth.is-invalid { border-color: #dc3545; }
        .form-control-auth.is-valid   { border-color: #198754; }

        .input-icon-wrap { position: relative; }
        .input-icon-wrap .form-control-auth { padding-right: 44px; }
        .input-icon-wrap .toggle-pw {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none; color: #aaa;
            cursor: pointer; padding: 0; font-size: 0.95rem;
            transition: color 0.2s;
        }
        .input-icon-wrap .toggle-pw:hover { color: var(--shopee-primary); }

        /* Password Strength */
        .pw-strength-bar {
            height: 4px;
            border-radius: 4px;
            margin-top: 6px;
            background: #eee;
            overflow: hidden;
            transition: all 0.3s;
        }
        .pw-strength-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s, background-color 0.3s;
            width: 0%;
        }
        .pw-strength-text {
            font-size: 0.75rem;
            margin-top: 3px;
        }

        .btn-auth-primary {
            background: linear-gradient(-180deg, #f53d2d, #f63);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            width: 100%;
            transition: opacity 0.2s, transform 0.1s;
        }
        .btn-auth-primary:hover { opacity: 0.92; color: white; transform: translateY(-1px); }
        .btn-auth-primary:active { transform: translateY(0); }
        .btn-auth-primary:disabled { opacity: 0.7; }

        .alert-inline {
            border-radius: 8px;
            font-size: 0.875rem;
            padding: 10px 14px;
            margin-bottom: 16px;
            border: none;
        }

        .auth-footer-link {
            text-align: center;
            margin-top: 16px;
            font-size: 0.875rem;
            color: #888;
        }
        .auth-footer-link a {
            color: var(--shopee-primary);
            font-weight: 600;
            text-decoration: none;
        }
        .auth-footer-link a:hover { text-decoration: underline; }

        @media (max-width: 700px) {
            .auth-left { display: none; }
            .auth-right { padding: 28px 20px; }
        }

        .auth-right { animation: slideInRight 0.4s cubic-bezier(.4,0,.2,1); }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .auth-left { animation: slideInLeft 0.4s cubic-bezier(.4,0,.2,1); }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .auth-header {
            background: linear-gradient(-180deg, #f53d2d, #f63);
            padding: 14px 0;
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
        }
        .auth-header a {
            color: white; text-decoration: none;
            font-size: 1.5rem; font-weight: 700;
        }

        /* Terms checkbox */
        .terms-check .form-check-input {
            border-radius: 4px;
            border: 1.5px solid #ddd;
            width: 1em; height: 1em;
        }
        .terms-check .form-check-input:checked {
            background-color: var(--shopee-primary);
            border-color: var(--shopee-primary);
        }
    </style>
</head>
<body>

<div class="auth-header">
    <div class="container">
        <a href="index.php"><i class="fas fa-shopping-bag me-2"></i>NhatShop</a>
        <span class="text-white ms-3" style="font-size:0.875rem; opacity:0.85;">Đăng Ký</span>
    </div>
</div>

<div class="auth-page-wrap" style="padding-top: 60px;">
    <div class="container py-4">
        <div class="auth-card">

            <!-- Left Branding -->
            <div class="auth-left">
                <div class="auth-brand-logo">
                    <i class="fas fa-shopping-bag"></i>NhatShop
                </div>
                <h2>Tham gia cộng đồng<br>hàng triệu người mua sắm</h2>
                <ul class="auth-steps">
                    <li>
                        <span class="step-num">1</span>
                        Tạo tài khoản miễn phí
                    </li>
                    <li>
                        <span class="step-num">2</span>
                        Khám phá hàng triệu sản phẩm
                    </li>
                    <li>
                        <span class="step-num">3</span>
                        Mua sắm an toàn & nhận hàng tận nhà
                    </li>
                    <li>
                        <span class="step-num">4</span>
                        Bán hàng và kiếm thêm thu nhập
                    </li>
                </ul>
            </div>

            <!-- Right Form -->
            <div class="auth-right">
                <h3>Tạo tài khoản</h3>
                <p class="auth-subtitle">Miễn phí, chỉ mất 30 giây!</p>

                <?php if ($error_msg): ?>
                <div class="alert alert-danger alert-inline d-flex align-items-center gap-2" role="alert">
                    <i class="fas fa-exclamation-circle flex-shrink-0"></i>
                    <div><?php echo $error_msg; ?></div>
                </div>
                <?php endif; ?>

                <form action="register.php" method="POST" id="regForm" novalidate>
                    <input type="hidden" name="register" value="1">

                    <!-- Username -->
                    <div class="mb-3">
                        <label class="form-label-sm">Tên đăng nhập <span class="text-danger">*</span></label>
                        <input type="text" class="form-control-auth w-100" name="username" id="regUsername"
                               placeholder="Tối thiểu 3 ký tự"
                               value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                               required autocomplete="username">
                        <div class="invalid-feedback" id="usernameError"></div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label-sm">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control-auth w-100" name="email" id="regEmail"
                               placeholder="example@email.com"
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                               required autocomplete="email">
                    </div>

                    <!-- Password -->
                    <div class="mb-1">
                        <label class="form-label-sm">Mật khẩu <span class="text-danger">*</span></label>
                        <div class="input-icon-wrap">
                            <input type="password" class="form-control-auth w-100" name="password" id="regPw"
                                   placeholder="Tối thiểu 6 ký tự" required autocomplete="new-password"
                                   oninput="checkStrength(this.value)">
                            <button type="button" class="toggle-pw" onclick="togglePw('regPw', this)" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Strength bar -->
                    <div class="pw-strength-bar mb-1">
                        <div class="pw-strength-fill" id="strengthFill"></div>
                    </div>
                    <div class="pw-strength-text text-muted mb-3" id="strengthText"></div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label class="form-label-sm">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                        <div class="input-icon-wrap">
                            <input type="password" class="form-control-auth w-100" name="confirm_password" id="confPw"
                                   placeholder="Nhập lại mật khẩu" required autocomplete="new-password"
                                   oninput="checkMatch()">
                            <button type="button" class="toggle-pw" onclick="togglePw('confPw', this)" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="small mt-1" id="matchMsg"></div>
                    </div>

                    <!-- Terms -->
                    <div class="form-check terms-check mb-4">
                        <input type="checkbox" class="form-check-input" id="agreeTerms" name="agree" required>
                        <label class="form-check-label small text-muted" for="agreeTerms">
                            Tôi đồng ý với <a href="#" class="text-decoration-none" style="color:var(--shopee-primary);">Điều khoản dịch vụ</a>
                            và <a href="#" class="text-decoration-none" style="color:var(--shopee-primary);">Chính sách bảo mật</a>
                        </label>
                    </div>

                    <button type="submit" name="register" class="btn-auth-primary" id="regBtn">
                        ĐĂNG KÝ NGAY
                    </button>
                </form>

                <div class="auth-footer-link">
                    Đã có tài khoản? <a href="login.php">Đăng nhập</a>
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

function checkStrength(pw) {
    const fill = document.getElementById('strengthFill');
    const text = document.getElementById('strengthText');
    let score = 0;
    if (pw.length >= 6) score++;
    if (pw.length >= 10) score++;
    if (/[A-Z]/.test(pw)) score++;
    if (/[0-9]/.test(pw)) score++;
    if (/[^A-Za-z0-9]/.test(pw)) score++;

    const levels = [
        { w: '20%', color: '#dc3545', label: 'Rất yếu' },
        { w: '40%', color: '#fd7e14', label: 'Yếu' },
        { w: '60%', color: '#ffc107', label: 'Trung bình' },
        { w: '80%', color: '#20c997', label: 'Mạnh' },
        { w: '100%', color: '#198754', label: 'Rất mạnh' },
    ];
    const level = levels[Math.min(score - 1, 4)];
    if (pw.length === 0) {
        fill.style.width = '0%';
        text.textContent = '';
        return;
    }
    fill.style.width = level.w;
    fill.style.backgroundColor = level.color;
    text.textContent = 'Độ mạnh: ' + level.label;
    text.style.color = level.color;
}

function checkMatch() {
    const pw   = document.getElementById('regPw').value;
    const conf = document.getElementById('confPw').value;
    const msg  = document.getElementById('matchMsg');
    if (conf.length === 0) { msg.textContent = ''; return; }
    if (pw === conf) {
        msg.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i><span class="text-success">Mật khẩu khớp!</span>';
    } else {
        msg.innerHTML = '<i class="fas fa-times-circle text-danger me-1"></i><span class="text-danger">Mật khẩu không khớp!</span>';
    }
}

document.getElementById('regForm').addEventListener('submit', function(e) {
    const agree = document.getElementById('agreeTerms');
    if (!agree.checked) {
        e.preventDefault();
        alert('Bạn cần đồng ý với Điều khoản dịch vụ để tiếp tục!');
        return;
    }
    const btn = document.getElementById('regBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang đăng ký...';
    btn.disabled = true;
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
