
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom p-4">
        <h5 class="mb-1 fw-medium">Đổi Mật Khẩu</h5>
        <p class="text-muted mb-0 small">Để bảo mật tài khoản, vui lòng không chia sẻ mật khẩu cho người khác</p>
    </div>
    <div class="card-body p-4">
        <form action="profile.php?tab=password" method="post" style="max-width: 420px;">
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted">Mật Khẩu Hiện Tại <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" class="form-control" name="old_password" id="oldPw" required placeholder="Nhập mật khẩu hiện tại">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePw('oldPw',this)"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted">Mật Khẩu Mới <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" class="form-control" name="new_password" id="newPw" required placeholder="Tối thiểu 6 ký tự">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePw('newPw',this)"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted">Xác Nhận Mật Khẩu Mới <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" class="form-control" name="confirm_password" id="confPw" required placeholder="Nhập lại mật khẩu mới">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePw('confPw',this)"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <button type="submit" name="change_password" class="btn btn-shopee px-4">Xác Nhận</button>
        </form>
    </div>
</div>
