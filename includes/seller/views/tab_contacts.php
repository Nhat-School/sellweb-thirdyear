
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom p-3">
        <h5 class="mb-0 fw-medium">Quản Lý Thông Tin Liên Hệ</h5>
    </div>
    <div class="card-body p-0">
        <?php if(mysqli_num_rows($my_contacts) > 0): ?>
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Khách Hàng</th>
                    <th>Liên Hệ</th>
                    <th>Nội Dung</th>
                    <th>Trạng Thái</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php while($c = mysqli_fetch_assoc($my_contacts)): ?>
                <tr>
                    <td class="ps-3">
                        <div class="fw-bold"><?php echo htmlspecialchars($c['name']); ?></div>
                        <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($c['created_at'])); ?></small>
                    </td>
                    <td>
                        <div class="small"><i class="fas fa-phone-alt me-1 text-muted"></i> <?php echo htmlspecialchars($c['phone']); ?></div>
                        <div class="small text-lowercase"><i class="fas fa-envelope me-1 text-muted"></i> <?php echo htmlspecialchars($c['email'] ?? 'N/A'); ?></div>
                    </td>
                    <td>
                        <div class="small text-truncate" style="max-width: 200px;" title="<?php echo htmlspecialchars($c['message']); ?>">
                            <?php echo htmlspecialchars($c['message']); ?>
                        </div>
                    </td>
                    <td>
                        <?php 
                        $s_class = 'bg-secondary';
                        if($c['status'] == 'Mới') $s_class = 'bg-danger';
                        if($c['status'] == 'Đã liên hệ') $s_class = 'bg-success';
                        if($c['status'] == 'Đã bỏ qua') $s_class = 'bg-light text-dark';
                        ?>
                        <span class="badge <?php echo $s_class; ?>"><?php echo $c['status']; ?></span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <form action="seller_center.php?tab=contacts" method="POST" class="d-flex gap-1">
                                <input type="hidden" name="contact_id" value="<?php echo $c['id']; ?>">
                                <select name="status" class="form-select form-select-sm" style="width: 120px;" onchange="this.form.submit()">
                                    <option value="Mới" <?php echo $c['status'] == 'Mới' ? 'selected' : ''; ?>>Mới</option>
                                    <option value="Đã liên hệ" <?php echo $c['status'] == 'Đã liên hệ' ? 'selected' : ''; ?>>Đã liên hệ</option>
                                    <option value="Đã bỏ qua" <?php echo $c['status'] == 'Đã bỏ qua' ? 'selected' : ''; ?>>Bỏ qua</option>
                                </select>
                                <input type="hidden" name="update_contact_status" value="1">
                            </form>
                            <a href="seller_center.php?tab=contacts&delete_contact=<?php echo $c['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa liên hệ này?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="text-center py-5 text-muted">
            <i class="fas fa-id-card-alt fa-3x mb-3"></i>
            <p>Chưa có thông tin liên hệ nào.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
