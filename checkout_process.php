<?php
include('includes/connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['confirm_payment'])) {
    $user_id = $_SESSION['user_id'];

    // 1. Lấy thông tin giỏ hàng và group theo người bán
    $cart_query = "SELECT c.*, p.seller_id, p.product_price, p.product_stock 
                   FROM cart c 
                   JOIN products p ON c.product_id = p.product_id 
                   WHERE c.user_id = $user_id";
    $result_cart = mysqli_query($conn, $cart_query);

    if (mysqli_num_rows($result_cart) == 0) {
        header("Location: cart.php");
        exit();
    }

    $orders_by_seller = [];
    while ($row = mysqli_fetch_assoc($result_cart)) {
        $seller_id = $row['seller_id'];
        if (!isset($orders_by_seller[$seller_id])) {
            $orders_by_seller[$seller_id] = [
                'items' => [],
                'total' => 0
            ];
        }
        $orders_by_seller[$seller_id]['items'][] = $row;
        $orders_by_seller[$seller_id]['total'] += $row['product_price'] * $row['quantity'];
    }

    // Bắt đầu transaction
    mysqli_begin_transaction($conn);

    try {
        foreach ($orders_by_seller as $seller_id => $order_data) {
            $total_amount = $order_data['total'];
            
            // 2. Tạo bản ghi trong bảng orders
            $invoice_number = "INV-" . time() . "-" . rand(1000, 9999);
            $insert_order = "INSERT INTO orders (buyer_id, seller_id, total_amount, status, invoice_number) 
                             VALUES ($user_id, $seller_id, $total_amount, 'Chờ xác nhận', '$invoice_number')";
            if(!mysqli_query($conn, $insert_order)) throw new Exception("Lỗi tạo đơn hàng: " . mysqli_error($conn));
            $order_id = mysqli_insert_id($conn);

            foreach ($order_data['items'] as $item) {
                $product_id = $item['product_id'];
                $qty = $item['quantity'];
                $price = $item['product_price'];

                // 3. Kiểm tra tồn kho lần cuối trước khi trừ (Lock row)
                $check_q = "SELECT product_title, product_stock FROM products WHERE product_id = $product_id FOR UPDATE";
                $check_res = mysqli_query($conn, $check_q);
                if(!$check_res) throw new Exception("Lỗi kiểm tra kho: " . mysqli_error($conn));
                $p_data = mysqli_fetch_assoc($check_res);
                
                if ($p_data['product_stock'] < $qty) {
                    throw new Exception("Sản phẩm '" . $p_data['product_title'] . "' không đủ hàng trong kho (Còn lại: " . $p_data['product_stock'] . ")");
                }

                // 4. Thêm chi tiết vào order_items
                $insert_item = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                VALUES ($order_id, $product_id, $qty, $price)";
                if(!mysqli_query($conn, $insert_item)) throw new Exception("Lỗi thêm chi tiết đơn hàng: " . mysqli_error($conn));

                // 5. Trừ tồn kho và tăng doanh số (nếu người mua không phải người bán)
                $sold_count_update = ($user_id != $seller_id) ? ", sold_count = sold_count + $qty" : "";
                $update_stock = "UPDATE products SET product_stock = product_stock - $qty $sold_count_update 
                                 WHERE product_id = $product_id";
                if(!mysqli_query($conn, $update_stock)) throw new Exception("Lỗi cập nhật kho: " . mysqli_error($conn));
            }
        }

        // 6. Xoá giỏ hàng sau khi đặt thành công
        $delete_cart = "DELETE FROM cart WHERE user_id = $user_id";
        if(!mysqli_query($conn, $delete_cart)) throw new Exception("Lỗi xóa giỏ hàng: " . mysqli_error($conn));

        mysqli_commit($conn);
        
        // Hiển thị trang trung gian để khách hàng chủ động mở Zalo (Tránh bị trình duyệt chặn Popup)
        ?>
        <!DOCTYPE html>
        <html lang="vi">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Thanh Toán Thành Công</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        </head>
        <body class="bg-light d-flex align-items-center justify-content-center vh-100">
            <div class="text-center p-5 bg-white rounded shadow" style="max-width: 500px; width: 90%;">
                <i class="fas fa-check-circle text-success mb-3" style="font-size: 5rem;"></i>
                <h3 class="fw-bold mb-3">Đặt Hàng Thành Công!</h3>
                <p class="text-muted mb-4">Hệ thống đã ghi nhận đơn hàng. Để người bán duyệt đơn nhanh nhất, bạn vui lòng gửi ảnh chụp màn hình chuyển khoản qua Zalo nhé.</p>
                
                <a href="https://zalo.me/0966917942" target="_blank" class="btn btn-primary btn-lg w-100 mb-3 rounded-pill" style="background: #0068ff; border:none;" onclick="setTimeout(() => { window.location.href = 'profile.php?tab=orders'; }, 1500);">
                    <i class="fas fa-paper-plane me-2"></i>Mở Zalo Gửi Biên Lai Ngay
                </a>
                
                <a href="profile.php?tab=orders" class="text-decoration-none text-muted small">
                    <i class="fas fa-arrow-left me-1"></i> Trở về Quản lý đơn hàng
                </a>
            </div>
        </body>
        </html>
        <?php
        exit();

    } catch (Exception $e) {
        mysqli_rollback($conn);
        set_flash('error', "LỖI THANH TOÁN: " . $e->getMessage());
        header("Location: cart.php");
        exit();
    }
} else {
    header("Location: cart.php");
    exit();
}
?>
