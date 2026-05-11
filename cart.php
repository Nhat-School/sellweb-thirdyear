<?php
include('includes/connect.php');
include('includes/flash.php');
include('includes/header.php');

if(!isset($_SESSION['user_id'])){
    echo "<script>alert('Vui lòng đăng nhập để xem giỏ hàng!');</script>";
    echo "<script>window.open('login.php','_self');</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Xoá sản phẩm khỏi giỏ hàng
if(isset($_GET['remove'])){
    $remove_id = intval($_GET['remove']);
    $delete_query = "DELETE FROM cart WHERE cart_id=$remove_id AND user_id=$user_id";
    mysqli_query($conn, $delete_query);
    echo "<script>window.open('cart.php','_self')</script>";
}

// Cập nhật số lượng
if(isset($_POST['update_cart'])){
    $quantities = $_POST['quantity'];
    foreach($quantities as $cart_id => $qty){
        $cart_id = intval($cart_id);
        $qty = intval($qty);
        
        // Kiểm tra tồn kho trước khi cập nhật
        $check_stock = mysqli_query($conn, "SELECT p.product_stock FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.cart_id=$cart_id");
        $stock_row = mysqli_fetch_assoc($check_stock);
        $max_stock = $stock_row['product_stock'] ?? 0;

        if($qty > 0){
            if($qty > $max_stock) {
                $_SESSION['flash_message'] = "Số lượng yêu cầu vượt quá tồn kho hiện có!";
                $_SESSION['flash_type'] = "warning";
                $qty = $max_stock; // Giới hạn về mức tối đa
            }
            $update_query = "UPDATE cart SET quantity=$qty WHERE cart_id=$cart_id AND user_id=$user_id";
            mysqli_query($conn, $update_query);
        }
    }
    echo "<script>window.open('cart.php','_self')</script>";
}

?>

<div class="container mt-4">
    <h3 class="mb-4" style="color:var(--shopee-primary)"><i class="fas fa-shopping-cart"></i> Giỏ Hàng Của Bạn</h3>
    
    <div class="card shadow-sm border-0">
        <form action="" method="post">
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-4">Sản Phẩm</th>
                            <th scope="col" class="text-center">Đơn Giá</th>
                            <th scope="col" class="text-center">Số Lượng</th>
                            <th scope="col" class="text-center">Số Tiền</th>
                            <th scope="col" class="text-center">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_price = 0;
                        $cart_query = "SELECT c.cart_id, c.quantity, p.product_id, p.product_title, p.product_image1, p.product_price, p.product_stock 
                                       FROM cart c 
                                       JOIN products p ON c.product_id = p.product_id 
                                       WHERE c.user_id = $user_id";
                        $result_cart = mysqli_query($conn, $cart_query);
                        
                        if(mysqli_num_rows($result_cart) > 0){
                            while($row = mysqli_fetch_array($result_cart)){
                                $cart_id = $row['cart_id'];
                                $product_id = $row['product_id'];
                                $product_title = $row['product_title'];
                                $product_image = $row['product_image1'];
                                $product_price = $row['product_price'];
                                $quantity = $row['quantity'];
                                $p_stock = $row['product_stock'];
                                
                                $subtotal = $product_price * $quantity;
                                $total_price += $subtotal;
                        ?>
                        <tr>
                            <td class="ps-4 py-3">
                                 <div class="d-flex align-items-center">
                                    <?php 
                                    $img_src = $product_image;
                                    if(!empty($img_src) && strpos($img_src, 'http') !== 0 && strpos($img_src, 'assets/') !== 0) {
                                        $img_src = 'assets/images/' . $img_src;
                                    }
                                    ?>
                                    <img src="<?php echo htmlspecialchars($img_src); ?>" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;" onerror="this.src='data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Crect width='200' height='200' fill='%23f0f0f0'/%3E%3Ctext x='100' y='110' text-anchor='middle' fill='%23aaa' font-size='14'%3ENo Image%3C/text%3E%3C/svg%3E'">
                                    <a href="product_details.php?product_id=<?php echo $product_id; ?>" class="text-decoration-none text-dark fw-medium">
                                        <?php echo htmlspecialchars($product_title); ?>
                                    </a>
                                </div>
                            </td>
                            <td class="text-center"><?php echo number_format($product_price, 0, ',', '.'); ?> ₫</td>
                            <td class="text-center" style="width: 15%">
                                <input type="number" name="quantity[<?php echo $cart_id; ?>]" value="<?php echo $quantity; ?>" min="1" max="<?php echo $p_stock; ?>" class="form-control text-center mx-auto" style="width: 80px;">
                                <div class="text-muted" style="font-size: 0.7rem;">Kho: <?php echo $p_stock; ?></div>
                            </td>
                            <td class="text-center text-danger fw-medium"><?php echo number_format($subtotal, 0, ',', '.'); ?> ₫</td>
                            <td class="text-center">
                                <a href="cart.php?remove=<?php echo $cart_id; ?>" class="text-muted text-decoration-none hover-danger">Xóa</a>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center py-5'>Giỏ hàng của bạn còn trống.<br><a href='index.php' class='btn btn-shopee mt-3'>Mua Sắm Ngay</a></td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <?php if(mysqli_num_rows($result_cart) > 0): ?>
            <div class="card-footer bg-white d-flex justify-content-between align-items-center p-4">
                <div>
                    <button type="submit" name="update_cart" class="btn btn-outline-secondary">Cập Nhật Giỏ Hàng</button>
                    <a href="index.php" class="btn btn-light ms-2">Tiếp Tục Mua Sắm</a>
                </div>
                <div class="d-flex align-items-center">
                    <span class="fs-5 me-3">Tổng thanh toán: 
                        <strong class="text-danger fs-4"><?php echo number_format($total_price, 0, ',', '.'); ?> ₫</strong>
                    </span>
                    <button type="button" class="btn btn-primary px-5 py-2" style="background:var(--shopee-primary); border:none;" data-bs-toggle="modal" data-bs-target="#paymentModal">Mua Hàng</button>
                </div>
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header" style="background:var(--shopee-primary);">
        <h5 class="modal-title text-white fw-bold" id="paymentModalLabel">
          <i class="fas fa-qrcode me-2"></i>Thanh Toán Qua Ngân Hàng
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center p-4">
        <p class="text-muted mb-3">Quét mã QR để chuyển tiền</p>
        <div class="mb-2 fw-bold fs-6">PHAM VAN NHAT</div>
        <div class="text-muted mb-3">STK: <strong>10431021</strong> — Ngân hàng ACB</div>
        <img src="https://img.vietqr.io/image/ACB-10431021-compact2.png?amount=<?php echo $total_price; ?>&addInfo=Thanh+toan+don+hang&accountName=PHAM+VAN+NHAT"
             alt="QR Thanh Toán ACB"
             class="img-fluid rounded shadow-sm"
             style="max-width: 280px;"
             onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=280x280&data=ACB-10431021-PHAM+VAN+NHAT'">
        <p class="mt-3 text-muted small">Tổng tiền: <strong class="text-danger fs-5"><?php echo number_format($total_price, 0, ',', '.'); ?> ₫</strong></p>
        <hr>
        <p class="small text-muted">Sau khi chuyển khoản thành công, nhấn nút bên dưới để gửi ảnh xác nhận cho người bán qua Zalo.</p>
      </div>
      <div class="modal-footer justify-content-center border-0 pb-4">
        <form action="checkout_process.php" method="POST" id="checkoutForm">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <button type="submit" name="confirm_payment" class="btn btn-success btn-lg px-5" style="background: #0068ff; border:none; border-radius: 8px;">
                <i class="fas fa-check-circle me-2"></i>Đã Thanh Toán — Xác Nhận Đơn Hàng
            </button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>
