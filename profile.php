<?php
include('includes/connect.php');
include('includes/flash.php');
include('includes/header.php');

if(!isset($_SESSION['user_id'])){
    echo "<script>alert('Vui lòng đăng nhập để xem hồ sơ!');</script>";
    echo "<script>window.open('login.php','_self');</script>";
    exit();
}

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
$error_msg = '';

// Include backend actions
include('includes/profile/actions.php');

// Get buyer's orders
$my_orders = mysqli_query($conn, "
    SELECT o.*, u.username as seller_name 
    FROM orders o 
    JOIN users u ON o.seller_id = u.user_id 
    WHERE o.buyer_id = $user_id 
    ORDER BY o.order_date DESC
");

// Count seller's products
$product_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM products WHERE seller_id=$user_id"))['c'];
?>

<div class="container mt-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3 mb-3">
                <img src="<?php echo $user_image ? 'assets/images/' . htmlspecialchars($user_image) : 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($username); ?>" 
                     class="rounded-circle mx-auto mb-2" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #eee;" 
                     onerror="this.src='https://api.dicebear.com/7.x/initials/svg?seed=User'" id="sidebarAvatar">
                <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($username); ?></h6>
                <p class="text-muted small mb-0"><i class="fas fa-edit"></i> Sửa Hồ Sơ</p>
            </div>

            <div class="list-group shadow-sm border-0">
                <div class="list-group-item bg-light fw-bold small text-muted border-0">
                    <i class="fas fa-user me-2"></i>Tài Khoản Của Tôi
                </div>
                <a href="profile.php?tab=profile" class="list-group-item list-group-item-action border-0 <?php echo $tab=='profile' ? 'active' : ''; ?>" 
                   <?php echo $tab=='profile' ? 'style="background:var(--shopee-primary); border-color:var(--shopee-primary);"' : ''; ?>>Hồ Sơ</a>
                <a href="profile.php?tab=password" class="list-group-item list-group-item-action border-0 <?php echo $tab=='password' ? 'active' : ''; ?>"
                   <?php echo $tab=='password' ? 'style="background:var(--shopee-primary); border-color:var(--shopee-primary);"' : ''; ?>>Đổi Mật Khẩu</a>
                
                <div class="list-group-item bg-light fw-bold small text-muted border-0 mt-2">
                    <i class="fas fa-list-alt me-2"></i>Mua Hàng
                </div>
                <a href="profile.php?tab=orders" class="list-group-item list-group-item-action border-0 <?php echo $tab=='orders' ? 'active' : ''; ?>" 
                   <?php echo $tab=='orders' ? 'style="background:var(--shopee-primary); border-color:var(--shopee-primary);"' : ''; ?>>Đơn Mua</a>
                
                <!-- LINK KÊNH NGƯỜI BÁN -->
                <div class="list-group-item bg-light fw-bold small text-muted border-0 mt-2">
                    <i class="fas fa-store me-2"></i>Bán Hàng
                </div>
                <a href="seller_center.php" class="list-group-item list-group-item-action border-0" style="color:var(--shopee-primary);">
                    <i class="fas fa-plus-circle me-1"></i>Kênh Người Bán
                    <?php if($product_count > 0): ?>
                    <span class="badge bg-danger float-end"><?php echo $product_count; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9">

            <?php if($success_msg): ?>
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo $success_msg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if($error_msg): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error_msg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Render specific tab view -->
            <?php 
            if($tab == 'profile') {
                include('includes/profile/views/tab_profile.php');
            } elseif($tab == 'password') {
                include('includes/profile/views/tab_password.php');
            } elseif($tab == 'orders') {
                include('includes/profile/views/tab_orders.php');
            } else {
                include('includes/profile/views/tab_profile.php');
            }
            ?>

        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if(input.files && input.files[0]) {
        const file = input.files[0];
        const maxSizeMB = 1024;
        if(file.size > maxSizeMB * 1024 * 1024) {
            alert('Ảnh quá lớn! Vui lòng chọn ảnh nhỏ hơn 1 GB.');
            input.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            if(document.getElementById('sidebarAvatar')) document.getElementById('sidebarAvatar').src = e.target.result;
        }
        reader.readAsDataURL(file);
        const nameEl = document.getElementById('imgPreviewName');
        nameEl.textContent = '✓ ' + file.name;
        nameEl.classList.remove('d-none');
    }
}

function togglePw(id, btn) {
    const input = document.getElementById(id);
    if(input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = '<i class="fas fa-eye-slash"></i>';
    } else {
        input.type = 'password';
        btn.innerHTML = '<i class="fas fa-eye"></i>';
    }
}
</script>

<?php include('includes/footer.php'); ?>




