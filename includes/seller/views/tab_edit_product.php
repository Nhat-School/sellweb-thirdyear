<!-- Edit Product Form -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-medium"><i class="fas fa-edit me-2" style="color:var(--shopee-primary);"></i>Chỉnh Sửa Sản Phẩm: <?php echo htmlspecialchars($edit_p['product_title']); ?></h5>
        <a href="seller_center.php?tab=products" class="btn btn-sm btn-light border text-muted small">Quay lại danh sách</a>
    </div>
    <div class="card-body p-4">
        <form action="seller_center.php?tab=products" method="post" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo $edit_p['product_id']; ?>">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-bold">Tên Sản Phẩm <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="product_title" value="<?php echo htmlspecialchars($edit_p['product_title']); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Danh Mục <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php 
                        mysqli_data_seek($cats_result, 0);
                        while($cat = mysqli_fetch_assoc($cats_result)): ?>
                        <option value="<?php echo $cat['category_id']; ?>" <?php echo ($edit_p['category_id'] == $cat['category_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['category_title']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Giá bán (₫) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="product_price" value="<?php echo floatval($edit_p['product_price']); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Tồn kho <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="product_stock" value="<?php echo intval($edit_p['product_stock'] ?? 50); ?>" min="0" required>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold">Giảm giá (%)</label>
                    <input type="number" class="form-control" name="discount_percent" value="<?php echo intval($edit_p['discount_percent'] ?? 0); ?>" min="0" max="100" style="max-width: 250px;">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold">Mô tả <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="5" required><?php echo htmlspecialchars($edit_p['description']); ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold">Ảnh Bìa (Tải lên để thay đổi)</label>
                    <div class="mb-2">
                        <?php 
                        $e_img = $edit_p['product_image1'];
                        if(!empty($e_img) && strpos($e_img, 'http') !== 0 && strpos($e_img, 'assets/') !== 0) {
                            $e_img = 'assets/images/' . $e_img;
                        }
                        ?>
                        <img src="<?php echo htmlspecialchars($e_img); ?>" class="rounded" style="width:100px; height:100px; object-fit:cover; border:1px solid #ddd;" onerror="this.src='data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Crect width='200' height='200' fill='%23f0f0f0'/%3E%3Ctext x='100' y='110' text-anchor='middle' fill='%23aaa' font-size='14'%3ENo Image%3C/text%3E%3C/svg%3E'">
                    </div>
                    <input type="file" name="product_image1" class="form-control" accept="image/*">
                </div>
                <div class="col-12 pt-3 border-top">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="seller_center.php?tab=products" class="btn btn-light border px-4">Hủy Bỏ</a>
                        <button type="submit" name="update_product" class="btn btn-shopee px-5">Cập Nhật Ngay</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
