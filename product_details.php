<?php
include('includes/connect.php');
include('includes/flash.php');
include('includes/header.php');

// Add to cart
if(isset($_POST['add_to_cart'])){
    if(!isset($_SESSION['user_id'])){
        echo "<script>alert('Vui lòng đăng nhập để thêm vào giỏ hàng!');</script>";
        echo "<script>window.open('login.php','_self');</script>";
    } else {
        $uid = $_SESSION['user_id'];
        $pid = intval($_POST['product_id']);
        $qty = intval($_POST['quantity']);

        // Lấy thông tin tồn kho hiện tại
        $stock_q = mysqli_query($conn, "SELECT product_title, product_stock FROM products WHERE product_id=$pid");
        $stock_data = mysqli_fetch_assoc($stock_q);
        $max_stock = $stock_data['product_stock'];
        $p_title = $stock_data['product_title'];

        $check = mysqli_query($conn, "SELECT * FROM cart WHERE user_id=$uid AND product_id=$pid");
        if(mysqli_num_rows($check) > 0){
            $cart_data = mysqli_fetch_assoc($check);
            $new_qty = $cart_data['quantity'] + $qty;
            
            if($new_qty > $max_stock) {
                $new_qty = $max_stock;
                echo "<script>alert('Bạn đã có sản phẩm này trong giỏ hàng. Tổng số lượng đã được giới hạn tối đa theo tồn kho ($max_stock)!');</script>";
            }
            mysqli_query($conn, "UPDATE cart SET quantity=$new_qty WHERE user_id=$uid AND product_id=$pid");
        } else {
            if($qty > $max_stock) $qty = $max_stock;
            mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES ($uid, $pid, $qty)");
        }
        echo "<script>alert('Sản phẩm đã được thêm vào giỏ hàng!');</script>";
        echo "<script>window.open('product_details.php?product_id=$pid','_self');</script>";
    }
}

if (!isset($_GET['product_id'])) {
    echo "<script>window.open('index.php','_self')</script>";
    exit();
}

$product_id = intval($_GET['product_id']);
$q = "SELECT p.*, u.username as seller_name, c.category_title, c.category_id 
      FROM products p 
      JOIN users u ON p.seller_id = u.user_id 
      LEFT JOIN categories c ON p.category_id = c.category_id
      WHERE p.product_id=$product_id";
$result = mysqli_query($conn, $q);

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Sản phẩm không tồn tại.')</script>";
    echo "<script>window.open('index.php','_self')</script>";
    exit();
}

$row = mysqli_fetch_assoc($result);
$product_title = $row['product_title'];
$description = $row['description'];
$product_price = $row['product_price'];
$formatted_price = number_format($product_price, 0, ',', '.');
$product_image = $row['product_image1'];
$seller_name = $row['seller_name'];

$cat_title = $row['category_title'];
$cat_id    = $row['category_id'];

// Stock count
$stock = $row['product_stock'] ?? 0;
// Sold count
$sold = $row['sold_count'] ?? 0;
// Discount
$discount = intval($row['discount_percent'] ?? 0);

if($discount > 0) {
    $discounted = $product_price * (100 - $discount) / 100;
    $price_html = "<span class='price-original'>₫" . number_format($product_price, 0, ',', '.') . "</span><span class='price-discount'>₫" . number_format($discounted, 0, ',', '.') . " <span class='badge bg-danger fs-6 align-middle ms-2'>-$discount%</span></span>";
} else {
    $price_html = "<span class='price-tag'>₫" . number_format($product_price, 0, ',', '.') . "</span>";
}

// Get extra images
$img_query = mysqli_query($conn, "SELECT * FROM product_images WHERE product_id=$product_id ORDER BY sort_order");
$extra_images = [];
while($img_row = mysqli_fetch_assoc($img_query)) {
    $extra_images[] = $img_row['image_url'];
}
// Combine main + extra (main image is always first)
$all_images = [$product_image]; 
foreach($extra_images as $ei) {
    if($ei !== $product_image) $all_images[] = $ei;
}

// Related products (same category)
$related_query = mysqli_query($conn, "SELECT * FROM products WHERE category_id=$cat_id AND product_id!=$product_id ORDER BY RAND() LIMIT 5");
?>

<div class="container mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">NhatShop</a></li>
        <?php if($cat_title): ?>
        <li class="breadcrumb-item"><a href="index.php?category=<?php echo $cat_id; ?>" class="text-decoration-none"><?php echo htmlspecialchars($cat_title); ?></a></li>
        <?php endif; ?>
        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product_title); ?></li>
      </ol>
    </nav>

    <div class="row bg-white p-4 g-0 mb-4 rounded shadow-sm">
        <!-- IMAGE GALLERY -->
        <div class="col-md-5 pe-md-4">
            <?php 
            $main_img = $all_images[0];
            if(!empty($main_img) && strpos($main_img, 'http') !== 0 && strpos($main_img, 'assets/') !== 0) {
                $main_img = 'assets/images/' . $main_img;
            }
            ?>
            <img src="<?php echo htmlspecialchars($main_img); ?>" class="gallery-main-img" id="mainImage" alt="<?php echo htmlspecialchars($product_title); ?>" onerror="this.src='data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Crect width='200' height='200' fill='%23f0f0f0'/%3E%3Ctext x='100' y='110' text-anchor='middle' fill='%23aaa' font-size='14'%3ENo Image%3C/text%3E%3C/svg%3E'">
            
            <?php if(count($all_images) > 1): ?>
            <div class="gallery-thumbs">
                <?php foreach($all_images as $idx => $img_url): 
                    $thumb_img = $img_url;
                    if(!empty($thumb_img) && strpos($thumb_img, 'http') !== 0 && strpos($thumb_img, 'assets/') !== 0) {
                        $thumb_img = 'assets/images/' . $thumb_img;
                    }
                ?>
                <img src="<?php echo htmlspecialchars($thumb_img); ?>" 
                     class="gallery-thumb <?php echo $idx === 0 ? 'active' : ''; ?>"
                     onclick="changeMainImage(this, '<?php echo htmlspecialchars($thumb_img); ?>')"
                     onerror="this.style.display='none'">
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- PRODUCT INFO -->
        <div class="col-md-7">
            <h1 class="fs-5 fw-normal lh-base mb-2"><?php echo htmlspecialchars($product_title); ?></h1>
            
            <!-- Info bar -->
            <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom">
                 <?php if($sold > 0): ?>
                     <div class="text-muted small border-end pe-3">Đã bán <span class="text-dark fw-bold"><?php echo $sold; ?></span></div>
                 <?php endif; ?>
                <div class="text-muted small">
                    <i class="fas fa-shield-alt me-1"></i> Chính hãng 100%
                </div>
            </div>
            
            <!-- Price -->
            <div class="p-3 mb-3" style="background: #fafafa;">
                <?php echo $price_html; ?>
            </div>
            
            <!-- Policies -->
            <div class="mb-3 small">
                <span class="text-muted me-2">Chính sách</span>
                <span><i class="fas fa-undo me-1 text-muted"></i>Trả hàng 15 ngày</span>
                <span class="ms-3"><i class="fas fa-shield-alt me-1 text-muted"></i>Chính hãng 100%</span>
            </div>
            
            <!-- Seller -->
            <div class="mb-4 small">
                <span class="text-muted me-2">Shop</span>
                <span class="badge bg-light text-dark border"><i class="fas fa-store me-1 text-muted"></i><?php echo htmlspecialchars($seller_name); ?></span>
            </div>
            
            <!-- Add to cart form -->
            <form action="" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                
                <div class="mb-4 d-flex align-items-center gap-3">
                    <span class="text-muted small">Số Lượng</span>
                    <div class="input-group" style="width: 130px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="changeQty(-1)" <?php echo ($stock<=0)?'disabled':''; ?>>−</button>
                        <input type="number" name="quantity" id="qtyInput" class="form-control text-center" value="1" min="1" max="<?php echo $stock; ?>" <?php echo ($stock<=0)?'disabled':''; ?>>
                        <button class="btn btn-outline-secondary" type="button" onclick="changeQty(1)" <?php echo ($stock<=0)?'disabled':''; ?>>+</button>
                    </div>
                    <span class="text-muted small"><?php echo $stock; ?> sản phẩm có sẵn</span>
                </div>
                
                <div class="d-flex gap-3">
                    <?php if($stock <= 0): ?>
                        <button type="button" class="btn btn-secondary px-5 py-2 disabled" style="opacity: 0.6;">
                            Tạm ngừng bán
                        </button>
                    <?php else: ?>
                        <button type="submit" name="add_to_cart" class="btn px-4 py-2" style="color:var(--shopee-primary); border: 1px solid var(--shopee-primary); background:var(--shopee-primary-light);">
                            <i class="fas fa-cart-plus me-1"></i> Thêm Vào Giỏ Hàng
                        </button>
                        <a href="cart.php" class="btn btn-shopee px-5 py-2">
                            Mua Ngay
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    
    <!-- DESCRIPTION -->
    <div class="bg-white p-4 rounded shadow-sm mb-4">
        <h5 class="bg-light p-2 text-uppercase small fw-bold" style="border-left: 4px solid var(--shopee-primary); color:rgba(0,0,0,0.7);">
            Chi Tiết Sản Phẩm
        </h5>
        <?php if($cat_title): ?>
        <div class="row small my-3">
            <div class="col-3 text-muted">Danh Mục</div>
            <div class="col-9"><a href="index.php?category=<?php echo $cat_id; ?>" class="text-decoration-none"><?php echo htmlspecialchars($cat_title); ?></a></div>
        </div>
        <?php endif; ?>
        <h5 class="bg-light p-2 text-uppercase small fw-bold" style="border-left: 4px solid var(--shopee-primary); color:rgba(0,0,0,0.7);">
            Mô Tả Sản Phẩm
        </h5>
        <div class="mt-3" style="white-space: pre-wrap; color:#555; line-height:1.8;">
<?php echo htmlspecialchars($description); ?>
        </div>
    </div>

    <!-- RELATED PRODUCTS -->
    <?php if(mysqli_num_rows($related_query) > 0): ?>
    <div class="section-title"><span>Sản Phẩm Tương Tự</span></div>
    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 g-2 mb-4">
        <?php while($rp = mysqli_fetch_assoc($related_query)): ?>
        <div class="col">
            <a href="product_details.php?product_id=<?php echo $rp['product_id']; ?>" class="text-decoration-none text-dark">
                <div class="card product-card h-100 border-0">
                    <?php 
                    $rel_img = $rp['product_image1'];
                    if(!empty($rel_img) && strpos($rel_img, 'http') !== 0 && strpos($rel_img, 'assets/') !== 0) {
                        $rel_img = 'assets/images/' . $rel_img;
                    }
                    
                    $rel_price = $rp['product_price'];
                    $rel_discount = intval($rp['discount_percent'] ?? 0);
                    $rel_stock = intval($rp['product_stock'] ?? 50);
                    
                    if($rel_discount > 0) {
                        $rel_discounted = $rel_price * (100 - $rel_discount) / 100;
                        $rel_price_html = "<span class='price-original'>" . number_format($rel_price, 0, ',', '.') . " ₫</span><span class='price-discount'>" . number_format($rel_discounted, 0, ',', '.') . " ₫</span>";
                    } else {
                        $rel_price_html = "<span class='price-tag'>" . number_format($rel_price, 0, ',', '.') . " ₫</span>";
                    }
                    $badge_discount_rel = ($rel_discount > 0) ? "<span class='position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 rounded small fw-bold z-1'>-{$rel_discount}%</span>" : "";
                    ?>
                    <div class="position-relative">
                        <?php echo $badge_discount_rel; ?>
                        <img src="<?php echo htmlspecialchars($rel_img); ?>" class="card-img-top product-image <?php echo ($rel_stock <= 0) ? "opacity-50" : ""; ?>" loading="lazy" onerror="this.src='data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Crect width='200' height='200' fill='%23f0f0f0'/%3E%3Ctext x='100' y='110' text-anchor='middle' fill='%23aaa' font-size='14'%3ENo Image%3C/text%3E%3C/svg%3E'">
                    </div>
                    <div class="card-body p-2">
                        <p class="product-title mb-1"><?php echo htmlspecialchars($rp['product_title']); ?></p>
                        <p class="product-price mb-0" style="line-height:1.2;"><?php echo $rel_price_html; ?></p>
                    </div>
                </div>
            </a>
        </div>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>
</div>

<script>
function changeMainImage(thumb, url) {
    document.getElementById('mainImage').src = url;
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}
function changeQty(delta) {
    const input = document.getElementById('qtyInput');
    let max = parseInt(input.getAttribute('max')) || 999;
    let v = parseInt(input.value) + delta;
    if(v < 1) v = 1;
    if(v > max) v = max;
    input.value = v;
}
</script>

<?php include('includes/footer.php'); ?>
