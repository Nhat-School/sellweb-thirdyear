<?php
include('includes/connect.php');
include('includes/flash.php');
include('includes/header.php');

// ==========================================
// FILTER LOGIC
// ==========================================
$where = " WHERE 1=1";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $s = mysqli_real_escape_string($conn, $_GET['search']);
    $where .= " AND (p.product_title LIKE '%$s%' OR p.description LIKE '%$s%')";
}
if (isset($_GET['category']) && is_numeric($_GET['category'])) {
    $cat = intval($_GET['category']);
    $where .= " AND p.category_id = $cat";
}
if (isset($_GET['min_price']) && is_numeric($_GET['min_price'])) {
    $where .= " AND p.product_price >= " . intval($_GET['min_price']);
}
if (isset($_GET['max_price']) && is_numeric($_GET['max_price'])) {
    $where .= " AND p.product_price <= " . intval($_GET['max_price']);
}

// Pagination: 
$per_page = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $per_page;

$count_sql = "SELECT COUNT(*) as total FROM products p $where";
$count_result = mysqli_query($conn, $count_sql);
$total_products = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_products / $per_page);

$products_sql = "SELECT p.*, c.category_title FROM products p LEFT JOIN categories c ON p.category_id = c.category_id $where ORDER BY p.date_added DESC LIMIT $per_page OFFSET $offset";
$result_products = mysqli_query($conn, $products_sql);

// Categories
$categories_sql = "SELECT * FROM categories ORDER BY category_id";
$result_cats = mysqli_query($conn, $categories_sql);
$all_cats = [];
while($cat = mysqli_fetch_assoc($result_cats)) {
    $all_cats[] = $cat;
}
?>

<!-- ==========================================
     CATEGORY BAR — Like NhatShop
     ========================================== -->
<div class="category-bar">
    <div class="container">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <?php foreach($all_cats as $c): ?>
            <a href="index.php?category=<?php echo $c['category_id']; ?>" class="category-item" style="width: 90px;">
                <img src="<?php echo htmlspecialchars($c['category_image']); ?>" alt="<?php echo htmlspecialchars($c['category_title']); ?>" onerror="this.src='data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Crect width='200' height='200' fill='%23f0f0f0'/%3E%3Ctext x='100' y='110' text-anchor='middle' fill='%23aaa' font-size='14'%3ENo Image%3C/text%3E%3C/svg%3E'">
                <div class="cat-name"><?php echo htmlspecialchars($c['category_title']); ?></div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="container mt-3">
    <div class="row">

        <!-- ==========================================
             SIDEBAR FILTER
             ========================================== -->
        <div class="col-md-2 d-none d-md-block filter-sidebar">
            <h6 class="mb-3"><i class="fas fa-filter text-muted me-1"></i>Bộ Lọc Tìm Kiếm</h6>
            <form action="index.php" method="GET">
                <?php if(isset($_GET['search'])): ?>
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
                <?php endif; ?>

                <!-- Theo danh mục -->
                <p class="mb-2 fw-medium small border-bottom pb-2">Theo Danh Mục</p>
                <div class="mb-3" style="max-height: 200px; overflow-y:auto;">
                    <?php foreach($all_cats as $c): ?>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="radio" name="category" id="cat<?php echo $c['category_id'];?>" value="<?php echo $c['category_id'];?>" <?php echo (isset($_GET['category']) && $_GET['category']==$c['category_id']) ? 'checked' : ''; ?> onchange="this.form.submit()">
                        <label class="form-check-label small" for="cat<?php echo $c['category_id'];?>"><?php echo htmlspecialchars($c['category_title']); ?></label>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Khoảng giá -->
                <p class="mb-2 fw-medium small border-bottom pb-2 mt-4">Khoảng Giá</p>
                <div class="d-flex align-items-center gap-1 mb-3">
                    <input type="number" name="min_price" class="form-control form-control-sm" placeholder="₫ TỪ" value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : ''; ?>">
                    <span class="text-muted">-</span>
                    <input type="number" name="max_price" class="form-control form-control-sm" placeholder="₫ ĐẾN" value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>">
                </div>
                <button type="submit" class="btn btn-shopee btn-sm w-100 mb-2">ÁP DỤNG</button>

                <a href="index.php" class="btn btn-light btn-sm w-100 border mt-3">Xóa Tất Cả</a>
            </form>

            <!-- Banner Liên Hệ/Đăng Ký -->
            <div class="mt-4 p-3 rounded text-center text-white shadow-sm" style="background: linear-gradient(135deg, var(--shopee-primary), #ff9900); cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" data-bs-toggle="modal" data-bs-target="#contactModal">
                <i class="fas fa-user-edit fa-2x mb-2"></i>
                <h6 class="fw-bold mb-1">Đăng Ký Thông Tin</h6>
                <p class="small mb-2" style="opacity: 0.9;">Gửi thông tin để được hỗ trợ &amp; nhận ưu đãi</p>
                <div class="btn btn-sm btn-light text-danger w-100 fw-bold" style="color:var(--shopee-primary) !important;">CLICK NGAY ĐỂ ĐĂNG KÝ</div>
            </div>

        </div>

        <!-- ==========================================
             MAIN CONTENT
             ========================================== -->
        <div class="col-md-8">
            
            <!-- Banner Section -->
            <div class="row mb-4 g-2">
                <!-- Left Slider (Bootstrap Carousel) -->
                <div class="col-md-8">
                    <div id="heroCarousel" class="carousel slide carousel-fade shadow-sm rounded overflow-hidden h-100" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
                        </div>
                        <div class="carousel-inner h-100">
                            <div class="carousel-item active" data-bs-interval="3000">
                                <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1000&h=450&fit=crop" class="d-block w-100" alt="Fashion Sale" style="height: 300px; object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.3); border-radius: 8px; bottom: 30px;">
                                    <h4 class="fw-bold">Siêu Deal Thời Trang</h4>
                                    <p class="small mb-0">Giảm đến 50% cho bộ sưu tập mới</p>
                                </div>
                            </div>
                            <div class="carousel-item" data-bs-interval="3000">
                                <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=1000&h=450&fit=crop" class="d-block w-100" alt="Microwave Sale" style="height: 300px; object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.3); border-radius: 8px; bottom: 30px;">
                                    <h4 class="fw-bold">Gia Dụng Thông Minh</h4>
                                    <p class="small mb-0">Lò vi sóng, nồi chiên giá cực sốc</p>
                                </div>
                            </div>
                            <div class="carousel-item" data-bs-interval="3000">
                                <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=1000&h=450&fit=crop" class="d-block w-100" alt="Beauty Sale" style="height: 300px; object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.3); border-radius: 8px; bottom: 30px;">
                                    <h4 class="fw-bold">Thế Giới Mỹ Phẩm</h4>
                                    <p class="small mb-0">Chăm sóc sắc đẹp, ưu đãi ngập tràn</p>
                                </div>
                            </div>
                            <div class="carousel-item" data-bs-interval="3000">
                                <img src="https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=1000&h=450&fit=crop" class="d-block w-100" alt="Home Sale" style="height: 300px; object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.3); border-radius: 8px; bottom: 30px;">
                                    <h4 class="fw-bold">Tân Trang Nhà Cửa</h4>
                                    <p class="small mb-0">Mọi thứ bạn cần cho ngôi nhà ấm cúng</p>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>

                <!-- Right Fixed Banner (4 images) -->
                <div class="col-md-4 d-flex flex-column gap-2">
                    <div class="row g-2 h-100">
                        <div class="col-6 col-md-12 h-50">
                            <div class="banner-slide shadow-sm d-flex align-items-end p-2 h-100" style="background: linear-gradient(transparent, rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1490578474895-699cd4e2cf59?w=400&h=200&fit=crop') center/cover; border-radius:4px;">
                                <span class="badge bg-danger small">Thời Trang Nam</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-12 h-50">
                            <div class="banner-slide shadow-sm d-flex align-items-end p-2 h-100" style="background: linear-gradient(transparent, rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=400&h=200&fit=crop') center/cover; border-radius:4px;">
                                <span class="badge bg-warning text-dark small">Thời Trang Nữ</span>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 h-100">
                        <div class="col-6 col-md-12 h-50">
                            <div class="banner-slide shadow-sm d-flex align-items-end p-2 h-100" style="background: linear-gradient(transparent, rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1498049794561-7780e7231661?w=400&h=200&fit=crop') center/cover; border-radius:4px;">
                                <span class="badge bg-info text-dark small">Điện Tử Giá Rẻ</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-12 h-50">
                            <div class="banner-slide shadow-sm d-flex align-items-end p-2 h-100" style="background: linear-gradient(transparent, rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&h=200&fit=crop') center/cover; border-radius:4px;">
                                <span class="badge bg-success small">Giày Xịn Giảm Sâu</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Best Sellers section (only show if no filters active) -->
            <?php if (!isset($_GET['category']) && !isset($_GET['search']) && !isset($_GET['min_price'])): ?>
            <div class='best-seller-section'>
                <h5 class='mb-3 fw-bold' style='color: var(--shopee-primary);'><i class='fas fa-fire me-2'></i>Bán Chạy Nhất</h5>
                <div class='best-seller-row'>
                    <?php
                    $bs_sql = "SELECT * FROM products ORDER BY sold_count DESC LIMIT 5";
                    $bs_res = mysqli_query($conn, $bs_sql);
                    while($bs = mysqli_fetch_assoc($bs_res)):
                       $pid_bs = $bs['product_id'];
                       $title_bs = htmlspecialchars($bs['product_title']);
                       $sold_bs = intval($bs['sold_count'] ?? 0);
                       $stock_bs = intval($bs['product_stock'] ?? 50);
                       $discount_bs = intval($bs['discount_percent'] ?? 0);
                       
                       $price_bs = $bs['product_price'];
                       if($discount_bs > 0) {
                           $discount_price_bs = $price_bs * (100 - $discount_bs) / 100;
                           $price_html_bs = "<span class='price-original'>" . number_format($price_bs, 0, ',', '.') . " ₫</span><span class='price-discount'>" . number_format($discount_price_bs, 0, ',', '.') . " ₫</span>";
                       } else {
                           $price_html_bs = "<span class='price-tag'>" . number_format($price_bs, 0, ',', '.') . " ₫</span>";
                       }
                       
                       $badge_html_bs = ($discount_bs > 0) ? "<span class='badge-discount'>-{$discount_bs}%</span>" : "";
                       
                       $img_bs = htmlspecialchars($bs['product_image1']);
                       $img_src_bs = $img_bs;
                       if(!empty($img_src_bs) && strpos($img_src_bs, 'http') !== 0 && strpos($img_src_bs, 'assets/') !== 0) {
                           $img_src_bs = 'assets/images/' . $img_src_bs;
                       }
                    ?>
                    <div class='best-seller-card' <?php echo ($stock_bs <= 0) ? "style='opacity: 0.6; pointer-events: none;'" : ""; ?>>
                        <span class='badge-hot'>HOT</span>
                        <?php echo $badge_html_bs; ?>
                        
                        <a href='product_details.php?product_id=<?php echo $pid_bs; ?>'>
                            <img src='<?php echo $img_src_bs; ?>' onerror="this.src='data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Crect width='200' height='200' fill='%23f0f0f0'/%3E%3Ctext x='100' y='110' text-anchor='middle' fill='%23aaa' font-size='14'%3ENo Image%3C/text%3E%3C/svg%3E'">
                        </a>
                        <div class='card-body d-flex flex-column'>
                            <h6 class='card-title'><a href='product_details.php?product_id=<?php echo $pid_bs; ?>' class='text-dark text-decoration-none'><?php echo $title_bs; ?></a></h6>
                            <div class='mt-auto'>
                                <p class='card-text mb-1'><?php echo $price_html_bs; ?></p>
                                <?php if($sold_bs > 0): ?>
                                    <p class='sold-count mb-0 mt-1'><i class='fas fa-fire-flame-curved'></i> Đã bán <?php echo $sold_bs; ?></p>
                                <?php endif; ?>
                                <?php if($stock_bs <= 0): ?>
                                    <span class='badge bg-secondary mt-1 w-100'>Hết hàng</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Active Category title -->
            <?php 
            $active_cat_title = 'Gợi Ý Hôm Nay';
            if(isset($_GET['category'])) {
                foreach($all_cats as $c) {
                    if($c['category_id'] == $_GET['category']) {
                        $active_cat_title = $c['category_title'];
                        break;
                    }
                }
            }
            ?>

            <div class="section-title"><span><?php echo htmlspecialchars($active_cat_title); ?></span></div>

            <!-- Product Grid -->
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-2">
                <?php
                if (mysqli_num_rows($result_products) > 0) {
                    while ($row = mysqli_fetch_assoc($result_products)) {
                        $pid    = $row['product_id'];
                        $title  = htmlspecialchars($row['product_title']);
                        $price  = $row['product_price'];
                        
                        
                        $discount = intval($row['discount_percent'] ?? 0);
                        $stock = intval($row['product_stock'] ?? 50);
                        $sold = intval($row['sold_count'] ?? 0);
                        
                        if($discount > 0) {
                            $discount_amount = $price * (100 - $discount) / 100;
                            $price_html = "<span class='price-original'>" . number_format($price, 0, ',', '.') . " ₫</span><span class='price-discount'>" . number_format($discount_amount, 0, ',', '.') . " ₫</span>";
                        } else {
                            $price_html = "<span class='price-tag'>" . number_format($price, 0, ',', '.') . " ₫</span>";
                        }

                        $badge_discount = ($discount > 0) ? "<span class='position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 rounded small fw-bold z-1'>-{$discount}%</span>" : "";

                        $overlay_html = "";
                        if($stock <= 0){
                            $overlay_html = "<div class='position-absolute w-100 h-100 d-flex justify-content-center align-items-center z-2' style='background:rgba(255,255,255,0.7); top:0; left:0;'><span class='badge bg-secondary fs-6'>Hết Hàng</span></div>";
                        }
                        
                        $img    = htmlspecialchars($row['product_image1']);
                ?>
                <div class="col">
                    <a href="product_details.php?product_id=<?php echo $pid; ?>" class="text-decoration-none text-dark" <?php echo ($stock <= 0) ? "style='pointer-events: none;'" : ""; ?>>
                        <div class="card product-card h-100 border-0 position-relative">
                            <?php echo $badge_discount; ?>
                            <?php echo $overlay_html; ?>
                            <?php 
                            $img_src = $img;
                            if(!empty($img_src) && strpos($img_src, 'http') !== 0 && strpos($img_src, 'assets/') !== 0) {
                                $img_src = 'assets/images/' . $img_src;
                            }
                            ?>
                            <img src="<?php echo $img_src; ?>" class="card-img-top product-image <?php echo ($stock <= 0) ? "opacity-50" : ""; ?>" alt="<?php echo $title; ?>" loading="lazy" onerror="this.src='data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Crect width='200' height='200' fill='%23f0f0f0'/%3E%3Ctext x='100' y='110' text-anchor='middle' fill='%23aaa' font-size='14'%3ENo Image%3C/text%3E%3C/svg%3E'">
                            <div class="card-body p-2 d-flex flex-column">
                                <p class="product-title mb-auto"><?php echo $title; ?></p>
                                <div class="mt-2">
                                    <p class="product-price mb-1" style="line-height:1.2;"><?php echo $price_html; ?></p>
                                    <?php if($sold > 0): ?>
                                      <p class="sold-label mb-0 text-muted" style="font-size: 0.8rem;">Đã bán <?php echo $sold; ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php 
                    }
                } else {
                    echo "<div class='col-12 py-5 text-center'>
                            <i class='fas fa-search fa-3x text-muted mb-3'></i>
                            <h5 class='text-muted'>Không tìm thấy sản phẩm nào</h5>
                            <a href='index.php' class='btn btn-shopee mt-2'>Xem tất cả sản phẩm</a>
                          </div>";
                }
                ?>
            </div>



            <?php if($total_pages > 1): ?>
            <div class="d-flex justify-content-center mt-2 mb-4">
                <nav>
                    <ul class="pagination pagination-sm">
                        <?php for($i = 1; $i <= $total_pages; $i++):
                            $params = $_GET;
                            $params['page'] = $i;
                            $url = 'index.php?' . http_build_query($params);
                        ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo $url; ?>" <?php if($i == $page) echo 'style="background:var(--shopee-primary);border-color:var(--shopee-primary);"'; ?>><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>

        </div>

        <!-- ==========================================
             RIGHT PANEL - Free Ship
             ========================================== -->
        <div class="col-md-2 d-none d-md-block">
            <div class="right-panel-sticky">
                <!-- Free Ship Banner -->
                <div class="rounded-3 overflow-hidden shadow-sm" style="background: linear-gradient(135deg, #00b894 0%, #00cec9 50%, #0984e3 100%);">
                    <div class="p-3 text-center text-white">
                        <div style="position:relative; display:inline-block; margin-bottom:8px;">
                            <i class="fas fa-truck-fast fa-2x" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2)); animation: truckBounce 2s ease-in-out infinite;"></i>
                            <span style="position:absolute; top:-6px; right:-18px; background:#ffc107; color:#333; font-size:0.55rem; font-weight:800; padding:2px 5px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.2); animation: pulse 1.5s ease-in-out infinite;">FREE</span>
                        </div>
                        <h6 class="fw-bold mb-1" style="font-size:0.85rem; text-shadow: 0 1px 2px rgba(0,0,0,0.15); letter-spacing:0.5px;">100% FREE SHIP</h6>
                        <div style="width:30px; height:2px; background:rgba(255,255,255,0.4); margin:6px auto;"></div>
                        <p class="mb-2" style="font-size:0.68rem; opacity:0.92; line-height:1.5;">
                            <i class="fas fa-map-marker-alt me-1"></i>Di chuyển trên<br>
                            <strong>MỌI MIỀN TỔ QUỐC</strong>
                        </p>
                        <div style="display:flex; flex-direction:column; gap:4px; align-items:center;">
                            <span style="background:rgba(255,255,255,0.2); padding:3px 8px; border-radius:10px; font-size:0.62rem;">🚚 Miền Bắc</span>
                            <span style="background:rgba(255,255,255,0.2); padding:3px 8px; border-radius:10px; font-size:0.62rem;">🚚 Miền Trung</span>
                            <span style="background:rgba(255,255,255,0.2); padding:3px 8px; border-radius:10px; font-size:0.62rem;">🚚 Miền Nam</span>
                        </div>
                    </div>
                    <div style="background:rgba(0,0,0,0.1); padding:5px; text-align:center;">
                        <span style="font-size:0.6rem; color:rgba(255,255,255,0.9);"><i class="fas fa-shield-alt me-1"></i>An toàn • Chất lượng</span>
                    </div>
                </div>

                <!-- Extra promo banners -->
                <div class="mt-3 rounded-3 overflow-hidden shadow-sm text-center" style="background: linear-gradient(135deg, #ee4d2d, #ff9900);">
                    <div class="p-3 text-white">
                        <i class="fas fa-percent fa-2x mb-2" style="opacity:0.9;"></i>
                        <h6 class="fw-bold mb-1" style="font-size:0.85rem;">GIẢM ĐẾN 50%</h6>
                        <p class="mb-0" style="font-size:0.65rem; opacity:0.9;">Cho đơn hàng đầu tiên</p>
                    </div>
                </div>

                <div class="mt-3 rounded-3 overflow-hidden shadow-sm text-center" style="background: linear-gradient(135deg, #6c5ce7, #a29bfe);">
                    <div class="p-3 text-white">
                        <i class="fas fa-gift fa-2x mb-2" style="opacity:0.9;"></i>
                        <h6 class="fw-bold mb-1" style="font-size:0.85rem;">VOUCHER MỖI NGÀY</h6>
                        <p class="mb-0" style="font-size:0.65rem; opacity:0.9;">Giảm thêm khi thanh toán</p>
                    </div>
                </div>

                <div class="mt-3 rounded-3 overflow-hidden shadow-sm text-center" style="background: linear-gradient(135deg, #fdcb6e, #e17055);">
                    <div class="p-3 text-white">
                        <i class="fas fa-headset fa-2x mb-2" style="opacity:0.9;"></i>
                        <h6 class="fw-bold mb-1" style="font-size:0.85rem;">HỖ TRỢ 24/7</h6>
                        <p class="mb-0" style="font-size:0.65rem; opacity:0.9;">Liên hệ bất cứ lúc nào</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Contact Form Modal -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background:var(--shopee-primary);">
                <h5 class="modal-title text-white fw-bold"><i class="fas fa-user-check me-2"></i>Đăng Ký Thông Tin Liên Hệ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="ajaxContactForm" action="includes/process_contact.php" method="POST">
                <div class="modal-body p-4">
                    <div id="contactAlert" class="alert alert-success d-none small">
                        <i class="fas fa-check-circle me-1"></i> Gửi yêu cầu thành công! Chúng tôi sẽ liên hệ trong thì giờ sớm nhất.
                    </div>
                    <p class="text-muted small mb-3">Vui lòng để lại thông tin, chúng tôi sẽ liên đới ngay tới bạn thông qua Email hoặc Điện thoại.</p>
                    <div class="mb-3">
                        <label for="contactName" class="form-label small fw-bold">Họ & Tên <span class="text-danger">*</span></label>
                        <input type="text" id="contactName" name="name" class="form-control" required placeholder="Nhập tên của bạn">
                    </div>
                    <div class="mb-3">
                        <label for="contactPhone" class="form-label small fw-bold">Số Điện Thoại <span class="text-danger">*</span></label>
                        <input type="tel" id="contactPhone" name="phone" class="form-control" required placeholder="Nhập số điện thoại">
                    </div>
                    <div class="mb-3">
                        <label for="contactEmail" class="form-label small fw-bold">Email <span class="text-muted">(Tuỳ chọn)</span></label>
                        <input type="email" id="contactEmail" name="email" class="form-control" placeholder="Email nhận phản hồi">
                    </div>
                    <div class="mb-3">
                        <label for="contactMessage" class="form-label small fw-bold">Nội Dung Cần Hỗ Trợ <span class="text-danger">*</span></label>
                        <textarea id="contactMessage" name="message" class="form-control" rows="3" required placeholder="Ghi chi tiết yêu cầu của bạn..."></textarea>
                    </div>
          
                </div>
                <div class="modal-footer border-0 pb-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy Bỏ</button>
                    <button type="submit" id="btnSubmitContact" class="btn btn-shopee px-4">Xác Nhận Gửi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('ajaxContactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSubmitContact');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang xử lý...';
    btn.disabled = true;

    fetch(this.action, {
        method: "POST",
        body: new FormData(this),
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('contactAlert').classList.remove('d-none');
            this.reset();
            setTimeout(() => {
                var myModalEl = document.getElementById('contactModal');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                modal.hide();
                document.getElementById('contactAlert').classList.add('d-none');
            }, 3000);
        } else {
            alert("Lỗi: " + data.message);
        }
        btn.innerHTML = 'Xác Nhận Gửi';
        btn.disabled = false;
    })
    .catch(error => {
        alert("Có lỗi xảy ra khi gửi yêu cầu. Vui lòng thử lại sau!");
        btn.innerHTML = 'Xác Nhận Gửi';
        btn.disabled = false;
    });
});
</script>

<?php include('includes/footer.php'); ?>
