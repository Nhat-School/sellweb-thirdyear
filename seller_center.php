<?php
include('includes/connect.php');
include('includes/flash.php');
include('includes/header.php');
if(!isset($_SESSION['user_id'])){
    echo "<script>alert('Vui lòng đăng nhập để truy cập Kênh Người Bán!');</script>";
    echo "<script>window.open('login.php','_self');</script>";
    exit();
}
include('includes/seller/actions.php');
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body text-center py-4" style="background: linear-gradient(135deg, var(--shopee-primary), #ff9900); border-radius: 4px;">
                    <i class="fas fa-store text-white fa-2x mb-2"></i>
                    <h6 class="text-white mb-0">Kênh Người Bán</h6>
                </div>
            </div>
            <div class="list-group border-0 shadow-sm">
                <a href="seller_center.php?tab=products" class="list-group-item list-group-item-action <?php echo $tab=='products' ? 'active' : ''; ?>" <?php if($tab=='products') echo 'style="background:var(--shopee-primary); border-color:var(--shopee-primary);"'; ?>>
                    <i class="fas fa-box me-2"></i>Sản Phẩm Của Tôi
                </a>
                <a href="seller_center.php?tab=add" class="list-group-item list-group-item-action <?php echo $tab=='add' ? 'active' : ''; ?>" <?php if($tab=='add') echo 'style="background:var(--shopee-primary); border-color:var(--shopee-primary);"'; ?>>
                    <i class="fas fa-plus-circle me-2"></i>Thêm Sản Phẩm
                </a>
                <a href="seller_center.php?tab=orders" class="list-group-item list-group-item-action <?php echo $tab=='orders' ? 'active' : ''; ?>" <?php if($tab=='orders') echo 'style="background:var(--shopee-primary); border-color:var(--shopee-primary);"'; ?>>
                    <i class="fas fa-file-invoice-dollar me-2"></i>Quản Lý Đơn Hàng
                </a>
                <?php if ($is_admin): ?>
                <a href="seller_center.php?tab=categories" class="list-group-item list-group-item-action <?php echo $tab=='categories' ? 'active' : ''; ?>" <?php if($tab=='categories') echo 'style="background:var(--shopee-primary); border-color:var(--shopee-primary);"'; ?>>
                    <i class="fas fa-th-list me-2"></i>Quản Lý Danh Mục
                </a>
                <a href="seller_center.php?tab=contacts" class="list-group-item list-group-item-action <?php echo $tab=='contacts' ? 'active' : ''; ?>" <?php if($tab=='contacts') echo 'style="background:var(--shopee-primary); border-color:var(--shopee-primary);"'; ?>>
                    <i class="fas fa-id-card me-2"></i>Thông Liên Hệ
                </a>
                <?php endif; ?>
                <a href="profile.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-user me-2 text-muted"></i>Hồ Sơ Shop
                </a>
                <a href="index.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-home me-2 text-muted"></i>Về Trang Chủ
                </a>
            </div>
        </div>
        <div class="col-md-9">
            <?php if(!empty($success_msg)): ?>
            <div class="alert alert-success alert-dismissible fade show mb-3">
                <i class="fas fa-check-circle me-2"></i><?php echo $success_msg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            <?php if(!empty($error_msg)): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error_msg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            <?php 
            if($tab == 'add') {
                include('includes/seller/views/tab_add_product.php');
            } elseif($tab == 'products') {
                include('includes/seller/views/tab_products.php');
            } elseif($tab == 'orders') {
                include('includes/seller/views/tab_orders.php');
            } elseif($tab == 'edit_product' && isset($edit_p)) {
                include('includes/seller/views/tab_edit_product.php');
            } elseif($tab == 'categories' && $is_admin) {
                include('includes/seller/views/tab_categories.php');
            } elseif($tab == 'contacts' && $is_admin) {
                include('includes/seller/views/tab_contacts.php');
            } else {
                include('includes/seller/views/tab_products.php');
            }
            ?>
        </div>
    </div>
</div>
<?php if ($is_admin): ?>
<div class="modal fade" id="addCatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background:var(--shopee-primary);">
                <h5 class="modal-title text-white fw-bold"><i class="fas fa-folder-plus me-2"></i>Thêm Danh Mục Mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="seller_center.php?tab=add" method="post" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tên Danh Mục <span class="text-danger">*</span></label>
                        <input type="text" name="cat_title" class="form-control" placeholder="VD: Áo Khoác Nữ" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Ảnh Bìa Danh Mục (Tải lên)</label>
                        <input type="file" name="cat_img_file" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" name="insert_category" class="btn btn-shopee px-4">Lưu Danh Mục</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editCatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background:var(--shopee-primary);">
                <h5 class="modal-title text-white fw-bold"><i class="fas fa-edit me-2"></i>Chỉnh Sửa Danh Mục</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="seller_center.php?tab=categories" method="post" enctype="multipart/form-data">
                <input type="hidden" name="category_id" value="<?php echo $edit_c['category_id'] ?? ''; ?>">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tên Danh Mục <span class="text-danger">*</span></label>
                        <input type="text" name="cat_title" class="form-control" value="<?php echo htmlspecialchars($edit_c['category_title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Ảnh Hiện Tại</label>
                        <div class="mb-2">
                            <?php if(!empty($edit_c['category_image'])): ?>
                            <img src="<?php echo htmlspecialchars($edit_c['category_image']); ?>" width="80" height="80" class="rounded object-fit-cover shadow-sm">
                            <?php endif; ?>
                        </div>
                        <label class="form-label small fw-bold">Tải Lên Ảnh Mới</label>
                        <input type="file" name="cat_img_file" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" name="update_category" class="btn btn-shopee px-4">Cập Nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<style>
.upload-zone:hover { border-color: var(--shopee-primary) !important; }
.upload-extra-btn:hover { border-color: var(--shopee-primary) !important; }
.extra-thumb {
    width: 80px; height: 80px; object-fit: cover;
    border-radius: 6px; border: 2px solid #ddd;
    flex-shrink: 0;
}
</style>
<script>
<?php if(isset($_GET['edit_category']) && $is_admin): ?>
document.addEventListener('DOMContentLoaded', function() {
    var editModal = new bootstrap.Modal(document.getElementById('editCatModal'));
    editModal.show();
});
<?php endif; ?>
function previewMain(input) {
    if(input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            const zone = document.getElementById('mainImgZone');
            const placeholder = document.getElementById('mainImgPlaceholder');
            const preview = document.getElementById('mainImgPreview');
            const nameEl = document.getElementById('mainImgName');
            placeholder.classList.add('d-none');
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            nameEl.textContent = '✓ ' + file.name;
            nameEl.classList.remove('d-none');
            zone.style.borderColor = 'var(--shopee-primary)';
        }
        reader.readAsDataURL(file);
    }
}
function previewExtra(input) {
    const container = document.getElementById('extraPreviewContainer');
    const existing = container.querySelectorAll('.extra-thumb');
    existing.forEach(el => el.remove());
    if(input.files) {
        const maxFiles = 5;
        const filesToShow = Math.min(input.files.length, maxFiles);
        for(let i = 0; i < filesToShow; i++) {
            const reader = new FileReader();
            const file = input.files[i];
            reader.onload = (function(index) {
                return function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'extra-thumb';
                    img.title = file.name;
                    const addBtn = document.getElementById('addExtraBtn');
                    container.insertBefore(img, addBtn);
                };
            })(i);
            reader.readAsDataURL(file);
        }
    }
}
function resetPreviews() {
    document.getElementById('mainImgPreview').classList.add('d-none');
    document.getElementById('mainImgPreview').src = '';
    document.getElementById('mainImgPlaceholder').classList.remove('d-none');
    document.getElementById('mainImgName').classList.add('d-none');
    document.getElementById('mainImgZone').style.borderColor = '#ddd';
    const existing = document.querySelectorAll('.extra-thumb');
    existing.forEach(el => el.remove());
}
</script>
<?php include('includes/footer.php'); ?>
