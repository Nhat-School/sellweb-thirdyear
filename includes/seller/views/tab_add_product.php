
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom p-3">
        <h5 class="mb-0 fw-medium"><i class="fas fa-plus-circle me-2" style="color:var(--shopee-primary);"></i>Thêm Sản Phẩm Mới</h5>
    </div>
    <div class="card-body p-4">
        <form action="seller_center.php?tab=add" method="post" enctype="multipart/form-data" id="addProductForm">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-bold">Tên Sản Phẩm <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="product_title" placeholder="VD: Áo Thun Nam Cao Cấp Premium" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold d-flex justify-content-between w-100">
                        <span>Danh Mục <span class="text-danger">*</span></span>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#addCatModal" class="text-decoration-none">
                            <i class="fas fa-plus-circle"></i> Thêm mới
                        </a>
                    </label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php 
                        mysqli_data_seek($cats_result, 0);
                        while($cat = mysqli_fetch_assoc($cats_result)): ?>
                        <option value="<?php echo $cat['category_id']; ?>"><?php echo htmlspecialchars($cat['category_title']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Giá bán (₫) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="product_price" placeholder="150000" min="1000" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Tồn kho <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="product_stock" placeholder="100" min="0" required value="50">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Giảm giá (%)</label>
                    <input type="number" class="form-control" name="discount_percent" placeholder="0" min="0" max="100" value="0">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold">Mô tả <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Mô tả chi tiết sản phẩm, chất liệu, kích thước..." required></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold">Ảnh Bìa <span class="text-danger">*</span></label>
                    <div class="upload-zone" id="mainImgZone" onclick="document.getElementById('product_image1').click()" 
                         style="border: 2px dashed #ddd; border-radius: 8px; padding: 24px; text-align:center; cursor:pointer; background:#fafafa; transition: border-color 0.2s;">
                        <div id="mainImgPlaceholder">
                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                            <div class="text-muted small">Nhấp để chọn ảnh bìa<br><span style="font-size:0.75rem;">JPG, PNG — Tối đa 5 MB</span></div>
                        </div>
                        <img id="mainImgPreview" src="" alt="" class="d-none rounded" style="max-height:180px; max-width:100%; object-fit:contain;">
                        <div id="mainImgName" class="mt-2 small d-none" style="color:var(--shopee-primary);"></div>
                    </div>
                    <input type="file" id="product_image1" name="product_image1" class="d-none" accept="image/*" required onchange="previewMain(this)">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold">Ảnh Phụ <span class="text-muted fw-normal">(tuỳ chọn, tối đa 5 ảnh)</span></label>
                    <div class="d-flex gap-2 flex-wrap" id="extraPreviewContainer">
                        <div class="upload-extra-btn" id="addExtraBtn" onclick="document.getElementById('extra_images').click()"
                             style="width:80px; height:80px; border: 2px dashed #ddd; border-radius:6px; display:flex; align-items:center; justify-content:center; cursor:pointer; background:#fafafa; flex-shrink:0; transition: border-color 0.2s;">
                            <i class="fas fa-plus text-muted fa-lg"></i>
                        </div>
                    </div>
                    <input type="file" id="extra_images" name="extra_images[]" class="d-none" accept="image/*" multiple onchange="previewExtra(this)">
                    <div class="text-muted small mt-1">Ảnh phụ giúp người mua xem sản phẩm từ nhiều góc độ</div>
                </div>
                <div class="col-12">
                    <hr class="my-1">
                    <div class="d-flex justify-content-end gap-2 pt-2">
                        <button type="reset" class="btn btn-light border px-4" onclick="resetPreviews()">Làm Mới</button>
                        <button type="submit" name="insert_product" class="btn btn-shopee px-5 py-2">
                            <i class="fas fa-upload me-1"></i>Lưu &amp; Đăng Bán
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
