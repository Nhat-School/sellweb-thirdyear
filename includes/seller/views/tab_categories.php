<!-- Category Management (Admin Only) -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-medium">Quản Lý Danh Mục</h5>
        <button type="button" class="btn btn-shopee btn-sm" data-bs-toggle="modal" data-bs-target="#addCatModal">
            <i class="fas fa-plus-circle me-1"></i>Thêm Danh Mục
        </button>
    </div>
    <div class="card-body p-0">
        <?php 
        mysqli_data_seek($cats_result, 0);
        if(mysqli_num_rows($cats_result) > 0): 
        ?>
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Danh Mục</th>
                    <th>Số Sản Phẩm</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php while($cl = mysqli_fetch_assoc($cats_result)): 
                    $cid = $cl['category_id'];
                    $count_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM products WHERE category_id=$cid");
                    $p_count = mysqli_fetch_assoc($count_res)['total'];
                ?>
                <tr>
                    <td class="ps-3">
                        <div class="d-flex align-items-center">
                            <?php 
                            $c_img = $cl['category_image'];
                            if(!empty($c_img) && strpos($c_img, 'http') !== 0 && strpos($c_img, 'assets/') !== 0) {
                                $c_img = 'assets/images/' . $c_img;
                            }
                            ?>
                            <img src="<?php echo htmlspecialchars($c_img); ?>" width="40" height="40" class="rounded object-fit-cover me-3" onerror="this.src='data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Crect width='200' height='200' fill='%23f0f0f0'/%3E%3Ctext x='100' y='110' text-anchor='middle' fill='%23aaa' font-size='14'%3ENo Image%3C/text%3E%3C/svg%3E'">
                            <span class="fw-medium small"><?php echo htmlspecialchars($cl['category_title']); ?></span>
                        </div>
                    </td>
                    <td class="small text-muted"><?php echo $p_count; ?> sản phẩm</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="seller_center.php?tab=categories&edit_category=<?php echo $cid; ?>" 
                               class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="seller_center.php?tab=categories&delete_category=<?php echo $cid; ?>" 
                               class="btn btn-sm btn-outline-danger" title="Xóa"
                               onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Điều này sẽ không xóa sản phẩm nhưng sản phẩm sẽ không còn danh mục.')">
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
            <i class="fas fa-list fa-3x mb-3"></i>
            <p>Chưa có danh mục nào.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
