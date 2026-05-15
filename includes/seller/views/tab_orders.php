
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom p-3">
        <h5 class="mb-0 fw-medium">Quản Lý Đơn Hàng</h5>
    </div>
    <div class="card-body p-0">
        <?php if(mysqli_num_rows($my_orders) > 0): ?>
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Mã Đơn</th>
                    <th>Người Mua</th>
                    <th>Tổng Tiền</th>
                    <th>Ngày Đặt</th>
                    <th>Trạng Thái</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php while($mo = mysqli_fetch_assoc($my_orders)): ?>
                <tr>
                    <td class="ps-3 fw-bold">#<?php echo $mo['order_id']; ?></td>
                    <td class="small"><?php echo htmlspecialchars($mo['buyer_name']); ?></td>
                    <td class="small text-danger fw-bold"><?php echo number_format($mo['total_amount'], 0, ',', '.'); ?> ₫</td>
                    <td class="small text-muted"><?php echo date('d/m/Y H:i', strtotime($mo['order_date'])); ?></td>
                    <td>
                        <?php 
                        $status_class = 'bg-secondary';
                        if($mo['status'] == 'Chờ xác nhận') $status_class = 'bg-warning text-dark';
                        if($mo['status'] == 'Đã xác nhận') $status_class = 'bg-info text-white';
                        if($mo['status'] == 'Đang giao hàng') $status_class = 'bg-primary text-white';
                        if($mo['status'] == 'Đã giao hàng') $status_class = 'bg-success text-white';
                        ?>
                        <span class="badge <?php echo $status_class; ?>"><?php echo $mo['status']; ?></span>
                    </td>
                    <td>
                        <form action="seller_center.php?tab=orders" method="POST" class="d-flex gap-2">
                            <input type="hidden" name="order_id" value="<?php echo $mo['order_id']; ?>">
                            <select name="status" class="form-select form-select-sm" style="width: 140px;">
                                <option value="Chờ xác nhận" <?php echo $mo['status'] == 'Chờ xác nhận' ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                <option value="Đã xác nhận" <?php echo $mo['status'] == 'Đã xác nhận' ? 'selected' : ''; ?>>Xác nhận</option>
                                <option value="Đang giao hàng" <?php echo $mo['status'] == 'Đang giao hàng' ? 'selected' : ''; ?>>Đang giao hàng</option>
                                <option value="Đã giao hàng" <?php echo $mo['status'] == 'Đã giao hàng' ? 'selected' : ''; ?>>Đã giao hàng</option>
                            </select>
                            <button type="submit" name="update_order_status" class="btn btn-sm btn-outline-primary">Lưu</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="text-center py-5 text-muted">
            <i class="fas fa-file-invoice fa-3x mb-3"></i>
            <p>Chưa có đơn hàng nào.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
