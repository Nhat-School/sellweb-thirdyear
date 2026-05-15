<?php
$seller_id = $_SESSION['user_id'];
$is_admin = false;
$admin_q = mysqli_query($conn, "SELECT is_admin FROM users WHERE user_id = $seller_id");
if ($admin_q && $row = mysqli_fetch_assoc($admin_q)) {
    $is_admin = ($row['is_admin'] == 1);
}
$cats_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_id");
$success_msg = '';
$error_msg   = '';
if(isset($_POST['insert_product'])){
    $title  = mysqli_real_escape_string($conn, trim($_POST['product_title']));
    $desc   = mysqli_real_escape_string($conn, trim($_POST['description']));
    $price  = floatval($_POST['product_price']);
    $cat_id = intval($_POST['category_id']);
    $stock    = isset($_POST['product_stock']) ? intval($_POST['product_stock']) : 50;
    $discount = isset($_POST['discount_percent']) ? intval($_POST['discount_percent']) : 0;
    $img1_name = $_FILES['product_image1']['name'] ?? '';
    $img1_tmp  = $_FILES['product_image1']['tmp_name'] ?? '';
    $img1_err  = $_FILES['product_image1']['error'] ?? 4;
    if($title == '' || $desc == '' || $price <= 0){
        $error_msg = 'Vui lòng điền đầy đủ tên sản phẩm, mô tả và giá!';
    } elseif($img1_err !== UPLOAD_ERR_OK || $img1_name == ''){
        $error_msg = 'Vui lòng chọn ảnh bìa cho sản phẩm!';
    } else {
        $target = "./assets/images/";
        if(!is_dir($target)) mkdir($target, 0777, true);
        $ext = strtolower(pathinfo($img1_name, PATHINFO_EXTENSION));
        if(!in_array($ext, ['jpg','jpeg','png','gif','webp'])){
            $error_msg = 'Định dạng ảnh không hợp lệ! Chỉ chấp nhận JPG, PNG, GIF, WEBP.';
        } else {
            $new_name = 'product_' . $seller_id . '_' . time() . '_1.' . $ext;
            if(!move_uploaded_file($img1_tmp, $target . $new_name)){
                $error_msg = 'Lỗi upload ảnh bìa. (Thư mục: ' . realpath($target) . ', Quyền ghi: ' . (is_writable($target) ? 'OK' : 'KHÔNG') . ')';
            } else {
                $insert = "INSERT INTO products (seller_id, product_title, description, product_price, product_stock, discount_percent, product_image1, category_id, date_added) 
                           VALUES ('$seller_id', '$title', '$desc', '$price', $stock, $discount, 'assets/images/$new_name', '$cat_id', NOW())";
                $result = mysqli_query($conn, $insert);
                if($result){
                    $new_pid = mysqli_insert_id($conn);
                    if(isset($_FILES['extra_images']) && !empty($_FILES['extra_images']['name'][0])){
                        $files = $_FILES['extra_images'];
                        for($i = 0; $i < count($files['name']); $i++){
                            if($files['name'][$i] != '' && $files['error'][$i] === UPLOAD_ERR_OK){
                                $ext2  = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                                $fname = 'product_' . $seller_id . '_' . time() . '_extra' . ($i+1) . '.' . $ext2;
                                if(move_uploaded_file($files['tmp_name'][$i], $target . $fname)){
                                    mysqli_query($conn, "INSERT INTO product_images (product_id, image_url, sort_order) VALUES ($new_pid, 'assets/images/$fname', ".($i+1).")");
                                }
                            }
                        }
                    }
                    $success_msg = 'Thêm sản phẩm thành công!';
                } else {
                    $error_msg = 'Lỗi thêm sản phẩm: ' . mysqli_error($conn);
                }
            }
        }
    }
}
if(isset($_POST['insert_category']) && $is_admin){
    $cat_title = mysqli_real_escape_string($conn, trim($_POST['cat_title']));
    $cat_img = '';
    if(isset($_FILES['cat_img_file']) && $_FILES['cat_img_file']['error'] == UPLOAD_ERR_OK){
        $target = "./assets/images/";
        if(!is_dir($target)) mkdir($target, 0777, true);
        $ext = strtolower(pathinfo($_FILES['cat_img_file']['name'], PATHINFO_EXTENSION));
        $new_name = 'cat_' . time() . '.' . $ext;
        if(move_uploaded_file($_FILES['cat_img_file']['tmp_name'], $target . $new_name)){
            $cat_img = 'assets/images/' . $new_name;
        }
    }
    if($cat_title == ''){
        $error_msg = 'Tên danh mục không được để trống!';
    } else {
        $insert_cat = "INSERT INTO categories (category_title, category_icon, category_image) VALUES ('$cat_title', 'fas fa-box', '$cat_img')";
        if(mysqli_query($conn, $insert_cat)){
            $success_msg = 'Thêm danh mục thành công!';
            $cats_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_id");
        } else {
            $error_msg = 'Lỗi thêm danh mục: ' . mysqli_error($conn);
        }
    }
}
if(isset($_GET['delete_category']) && $is_admin){
    $del_cat_id = intval($_GET['delete_category']);
    $check_prod = mysqli_query($conn, "SELECT product_id FROM products WHERE category_id=$del_cat_id");
    if(mysqli_num_rows($check_prod) > 0){
        $error_msg = 'Không thể xóa danh mục này vì vẫn còn sản phẩm thuộc danh mục này!';
    } else {
        if(mysqli_query($conn, "DELETE FROM categories WHERE category_id=$del_cat_id")){
            $success_msg = 'Xóa danh mục thành công!';
            $cats_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_id");
        } else {
            $error_msg = 'Lỗi xóa danh mục: ' . mysqli_error($conn);
        }
    }
}
if(isset($_GET['delete_product'])){
    $del_pid = intval($_GET['delete_product']);
    $check_owner = mysqli_query($conn, "SELECT product_id FROM products WHERE product_id=$del_pid AND seller_id=$seller_id");
    if(mysqli_num_rows($check_owner) > 0){
        mysqli_query($conn, "DELETE FROM product_images WHERE product_id=$del_pid");
        if(mysqli_query($conn, "DELETE FROM products WHERE product_id=$del_pid")){
            $success_msg = 'Xóa sản phẩm thành công!';
        } else {
            $error_msg = 'Lỗi xóa sản phẩm: ' . mysqli_error($conn);
        }
    } else {
        $error_msg = 'Bạn không có quyền xóa sản phẩm này!';
    }
}
if(isset($_POST['update_product'])){
    $pid      = intval($_POST['product_id']);
    $title    = mysqli_real_escape_string($conn, trim($_POST['product_title']));
    $desc     = mysqli_real_escape_string($conn, trim($_POST['description']));
    $price    = floatval($_POST['product_price']);
    $cat_id   = intval($_POST['category_id']);
    $stock    = isset($_POST['product_stock']) ? intval($_POST['product_stock']) : 50;
    $discount_update = "";
    if (isset($_POST['discount_percent'])) {
        $discount = intval($_POST['discount_percent']);
        $discount_update = ", discount_percent='$discount'";
    }
    $check = mysqli_query($conn, "SELECT product_image1 FROM products WHERE product_id=$pid AND seller_id=$seller_id");
    if(mysqli_num_rows($check) > 0){
        $old_p = mysqli_fetch_assoc($check);
        $img1 = $old_p['product_image1'];
        if(isset($_FILES['product_image1']) && $_FILES['product_image1']['error'] == UPLOAD_ERR_OK){
            $target = "./assets/images/";
            $ext = strtolower(pathinfo($_FILES['product_image1']['name'], PATHINFO_EXTENSION));
            $new_name = 'product_' . $seller_id . '_' . time() . '_u1.' . $ext;
            if(move_uploaded_file($_FILES['product_image1']['tmp_name'], $target . $new_name)){
                $img1 = 'assets/images/' . $new_name;
            }
        }
        $update = "UPDATE products SET product_title='$title', description='$desc', product_price='$price', product_stock='$stock'$discount_update, product_image1='$img1', category_id='$cat_id' WHERE product_id=$pid";
        if(mysqli_query($conn, $update)){
            $success_msg = 'Cập nhật sản phẩm thành công!';
        } else {
            $error_msg = 'Lỗi cập nhật sản phẩm: ' . mysqli_error($conn);
        }
    } else {
        $error_msg = 'Lỗi: Không tìm thấy sản phẩm hoặc bạn không có quyền chỉnh sửa!';
    }
}
if(isset($_POST['update_category']) && $is_admin){
    $cid   = intval($_POST['category_id']);
    $title = mysqli_real_escape_string($conn, trim($_POST['cat_title']));
    $check = mysqli_query($conn, "SELECT category_image FROM categories WHERE category_id=$cid");
    if(mysqli_num_rows($check) > 0){
        $old_c = mysqli_fetch_assoc($check);
        $c_img = $old_c['category_image'];
        if(isset($_FILES['cat_img_file']) && $_FILES['cat_img_file']['error'] == UPLOAD_ERR_OK){
            $target = "./assets/images/";
            $ext = strtolower(pathinfo($_FILES['cat_img_file']['name'], PATHINFO_EXTENSION));
            $new_name = 'cat_' . time() . '_u.' . $ext;
            if(move_uploaded_file($_FILES['cat_img_file']['tmp_name'], $target . $new_name)){
                $c_img = 'assets/images/' . $new_name;
            }
        }
        $update = "UPDATE categories SET category_title='$title', category_image='$c_img' WHERE category_id=$cid";
        if(mysqli_query($conn, $update)){
            $success_msg = 'Cập nhật danh mục thành công!';
            $cats_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_id");
        } else {
            $error_msg = 'Lỗi cập nhật danh mục: ' . mysqli_error($conn);
        }
    }
}
$my_products = mysqli_query($conn, "
    SELECT p.*, c.category_title
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.category_id
    WHERE p.seller_id = $seller_id 
    ORDER BY p.date_added DESC
");
if(isset($_POST['update_order_status'])){
    $order_id = intval($_POST['order_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    mysqli_query($conn, "UPDATE orders SET status='$new_status' WHERE order_id=$order_id AND seller_id=$seller_id");
}
$my_orders = mysqli_query($conn, "
    SELECT o.*, u.username as buyer_name 
    FROM orders o 
    JOIN users u ON o.buyer_id = u.user_id 
    WHERE o.seller_id = $seller_id 
    ORDER BY o.order_date DESC
");
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'products';
if (!$is_admin && in_array($tab, ['categories', 'contacts'])) {
    $tab = 'products';
}
$edit_p = null;
if($tab == 'edit_product' && isset($_GET['product_id'])){
    $epid = intval($_GET['product_id']);
    $ep_res = mysqli_query($conn, "SELECT * FROM products WHERE product_id=$epid AND seller_id=$seller_id");
    $edit_p = mysqli_fetch_assoc($ep_res);
}
$edit_c = null;
if(isset($_GET['edit_category'])){
    $ecid = intval($_GET['edit_category']);
    $ec_res = mysqli_query($conn, "SELECT * FROM categories WHERE category_id=$ecid");
    $edit_c = mysqli_fetch_assoc($ec_res);
}
if(isset($_POST['update_contact_status']) && $is_admin){
    $cid = intval($_POST['contact_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    mysqli_query($conn, "UPDATE contacts SET status='$new_status' WHERE id=$cid");
}
if(isset($_GET['delete_contact']) && $is_admin){
    $del_cid = intval($_GET['delete_contact']);
    mysqli_query($conn, "DELETE FROM contacts WHERE id=$del_cid");
    header("Location: seller_center.php?tab=contacts");
    exit();
}
$my_contacts = mysqli_query($conn, "SELECT * FROM contacts ORDER BY created_at DESC");
?>
