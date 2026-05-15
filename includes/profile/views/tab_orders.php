
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom p-3">
        <h5 class="mb-0 fw-medium"><i class="fas fa-shopping-bag me-2" style="color:var(--shopee-primary);"></i>Đơn Mua Của Tôi</h5>
    </div>
    <div class="card-body p-0">
        <?php if(mysqli_num_rows($my_orders) > 0): ?>
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Mã Đơn</th>
                    <th>Người Bán</th>
                    <th>Tổng Tiền</th>
                    <th>Ngày Đặt</th>
                    <th class="text-center">Trạng Thái</th>
                </tr>
            </thead>
            <tbody>
                <?php while($mo = mysqli_fetch_assoc($my_orders)): ?>
                <tr>
                    <td class="ps-3 fw-bold">#<?php echo $mo['order_id']; ?></td>
                    <td class="small"><?php echo htmlspecialchars($mo['seller_name']); ?></td>
                    <td class="small text-danger fw-bold"><?php echo number_format($mo['total_amount'], 0, ',', '.'); ?> ₫</td>
                    <td class="small text-muted"><?php echo date('d/m/Y H:i', strtotime($mo['order_date'])); ?></td>
                    <td class="text-center">
                        <?php 
                        $status_class = 'bg-secondary';
                        if($mo['status'] == 'Chờ xác nhận') $status_class = 'bg-warning text-dark';
                        if($mo['status'] == 'Đã xác nhận') $status_class = 'bg-info text-white';
                        if($mo['status'] == 'Đang giao hàng') $status_class = 'bg-primary text-white';
                        if($mo['status'] == 'Đã giao hàng') $status_class = 'bg-success text-white';
                        ?>
                        <span class="badge <?php echo $status_class; ?> px-3 py-2"><?php echo $mo['status']; ?></span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="text-center py-5 text-muted">
            <i class="fas fa-clipboard-list fa-3x mb-3"></i>
            <p>Bạn chưa có đơn hàng nào.<br><a href="index.php" class="btn btn-shopee btn-sm mt-2">Mua sắm ngay</a></p>
        </div>
        <?php endif; ?>
    </div>
</div>
