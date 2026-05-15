
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom p-4">
        <h5 class="mb-1 fw-medium">Hồ Sơ Của Tôi</h5>
        <p class="text-muted mb-0 small">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
    </div>
    <div class="card-body p-4">
        <form action="profile.php?tab=profile" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8 border-end pe-4">
                    <table class="w-100">
                        <tr class="align-top">
                            <td class="text-end text-muted small pe-3 py-2" style="width:130px;">Tên đăng nhập</td>
                            <td class="py-2 fw-medium text-muted"><?php echo htmlspecialchars($username); ?></td>
                        </tr>
                        <tr class="align-top">
                            <td class="text-end text-muted small pe-3 py-2">Tên hiển thị</td>
                            <td class="py-2"><input type="text" class="form-control form-control-sm" name="username" value="<?php echo htmlspecialchars($username); ?>" required></td>
                        </tr>
                        <tr class="align-top">
                            <td class="text-end text-muted small pe-3 py-2">Email</td>
                            <td class="py-2 text-muted small"><?php echo htmlspecialchars($user_email); ?></td>
                        </tr>
                        <tr class="align-top">
                            <td class="text-end text-muted small pe-3 py-2">Số điện thoại</td>
                            <td class="py-2"><input type="text" class="form-control form-control-sm" name="contact" value="<?php echo htmlspecialchars($contact); ?>" placeholder="Chưa cập nhật"></td>
                        </tr>
                        <tr class="align-top" id="addressField">
                            <td class="text-end text-muted small pe-3 py-2">Địa chỉ</td>
                            <td class="py-2"><input type="text" class="form-control form-control-sm" name="address" value="<?php echo htmlspecialchars($address); ?>" placeholder="Chưa cập nhật"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="pt-3"><button type="submit" name="update_profile" class="btn btn-shopee px-4">Lưu</button></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4 text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <img src="<?php echo $user_image ? 'assets/images/' . htmlspecialchars($user_image) : 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($username); ?>" 
                             class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #eee;" 
                             onerror="this.src='https://api.dicebear.com/7.x/initials/svg?seed=User'" id="previewImg">
                    </div>
                    <div>
                        <label for="user_image" class="btn btn-outline-secondary btn-sm px-3">
                            <i class="fas fa-camera me-1"></i>Chọn Ảnh
                        </label>
                        <input type="file" id="user_image" name="user_image" class="d-none" accept="image/jpeg,image/png" onchange="previewImage(this)">
                    </div>
                    <div id="imgPreviewName" class="mt-2 text-muted small d-none" style="color:var(--shopee-primary)!important;"></div>
                    <div class="mt-2 text-muted small">
                        Tối đa 1 GB<br>Định dạng: JPEG, PNG
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
