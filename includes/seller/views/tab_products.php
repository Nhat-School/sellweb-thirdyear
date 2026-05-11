<!-- My Products -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-medium">Sản Phẩm Của Tôi</h5>
        <span class="badge bg-secondary"><?php echo mysqli_num_rows($my_products); ?> sản phẩm</span>
    </div>
    <div class="card-body p-0">
        <?php if(mysqli_num_rows($my_products) > 0): ?>
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Số lượng kho</th>
                    <th>Ngày đăng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php while($mp = mysqli_fetch_assoc($my_products)): ?>
                <tr>
                    <td class="ps-3">
                        <div class="d-flex align-items-center">
                            <?php 
                            $p_img = $mp['product_image1'];
                            if(!empty($p_img) && strpos($p_img, 'http') !== 0 && strpos($p_img, 'assets/') !== 0) {
                                $p_img = 'assets/images/' . $p_img;
                            }
                            ?>
                            <img src="<?php echo htmlspecialchars($p_img); ?>" class="rounded me-3" style="width:50px;height:50px;object-fit:cover;" onerror="this.src='data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Crect width='200' height='200' fill='%23f0f0f0'/%3E%3Ctext x='100' y='110' text-anchor='middle' fill='%23aaa' font-size='14'%3ENo Image%3C/text%3E%3C/svg%3E'">
                            <a href="product_details.php?product_id=<?php echo $mp['product_id']; ?>" class="text-decoration-none text-dark small"><?php echo htmlspecialchars($mp['product_title']); ?></a>
                        </div>
                    </td>
                    <td class="small text-muted"><?php echo $mp['category_title'] ?: '-'; ?></td>
                    <td class="small" style="color:var(--shopee-primary);">₫<?php echo number_format($mp['product_price'], 0, ',', '.'); ?></td>
                    <td class="small"><?php echo $mp['product_stock']; ?></td>
                    <td class="small text-muted"><?php echo date('d/m/Y', strtotime($mp['date_added'])); ?></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="seller_center.php?tab=edit_product&product_id=<?php echo $mp['product_id']; ?>" 
                               class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="seller_center.php?tab=products&delete_product=<?php echo $mp['product_id']; ?>" 
                               class="btn btn-sm btn-outline-danger" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')" title="Xóa">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="text-center py-5 text-muted">
            <i class="fas fa-box-open fa-3x mb-3"></i>
            <p>Bạn chưa có sản phẩm nào. Hãy thêm sản phẩm đầu tiên!</p>
        </div>
        <?php endif; ?>
    </div>
</div>
