# Hướng dẫn cài đặt NhatShop trên máy mới bằng XAMPP

Tài liệu này hướng dẫn bạn cách thiết lập và chạy dự án NhatShop trên một máy tính mới sử dụng **XAMPP** (phù hợp cho các máy không thể cài đặt Docker).

## 1. Yêu cầu hệ thống
Trước khi bắt đầu, hãy đảm bảo máy tính của bạn đã cài đặt:
- **XAMPP** (phiên bản có chứa PHP 8.x).
- **Git** (nếu bạn muốn clone mã nguồn từ GitHub).

## 2. Các bước cài đặt

### Bước 1: Tải mã nguồn về thư mục của XAMPP
Bạn cần đặt thư mục mã nguồn vào đúng vị trí để XAMPP có thể đọc được (thư mục `htdocs`):
- **Windows:** Thường là `C:\xampp\htdocs\`
- **MacOS:** Thường là `/Applications/XAMPP/xamppfiles/htdocs/`

**Cách đơn giản nhất (Dùng file ZIP):**
1. Tải file ZIP của dự án NhatShop về máy.
2. Giải nén file ZIP đó.
3. Copy toàn bộ thư mục vừa giải nén (ví dụ tên là `NhatShop`) và dán trực tiếp vào thư mục `htdocs` ở trên.

*(Nếu dùng Git, bạn có thể chạy lệnh: `git clone https://github.com/Nhat-School/sellweb-thirdyear.git NhatShop` ngay tại thư mục htdocs).*

### Bước 2: Khởi động XAMPP
Mở phần mềm **XAMPP Control Panel** lên và nhấn **Start** ở 2 dịch vụ:
- **Apache** (Web Server).
- **MySQL** (Database).

### Bước 3: Cấu hình Cơ sở dữ liệu (Database)
1. Mở trình duyệt và truy cập: [http://localhost:8080/phpmyadmin](http://localhost:8080/phpmyadmin)
2. Nhấn vào **Mới (New)** ở cột bên trái để tạo Database mới.
3. Nhập tên Database là: `mystore` (Chọn Bảng mã Collation là `utf8mb4_unicode_ci` hoặc `utf8mb4_general_ci`).
4. Bấm **Tạo (Create)**.
5. Sau khi tạo xong, chọn database `mystore` vừa tạo, chuyển sang tab **Nhập (Import)**.
6. Bấm **Choose File**, tìm đến file backup nằm trong dự án của bạn (đường dẫn `sellweb-thirdyear/sql/latest_backup.sql`).
7. Bấm **Thực hiện (Go)** ở dưới cùng để nạp dữ liệu.

### Bước 4: Sửa file kết nối Database
Do trước đây dự án dùng Docker nên file kết nối cần được chỉnh lại cho phù hợp với XAMPP.
Mở file `includes/connect.php` (hoặc file cấu hình DB tương tự) và sửa lại thông tin như sau:
```php
// Thông tin cho XAMPP mặc định
$host = "localhost:3307";
$user = "root";
$pass = ""; // Mặc định XAMPP không có mật khẩu
$db   = "mystore";

$conn = mysqli_connect($host, $user, $pass, $db);
```

### Bước 5: Truy cập Website
Mở trình duyệt và truy cập vào đường dẫn thư mục dự án của bạn. Nếu bạn giữ nguyên tên khi clone, đường dẫn sẽ là:
[http://localhost:8080]

---

httpd.conf :Listen 80 - 8080,ServerName localhost:80
-----
phpmyadmin config: $cfg['Servers'][$i]['port'] = '3307';
and port of mysql 3307
-----
httpd-ssl.conf Listen 443 - 4433,<VirtualHost _default_:443>

Tìm và nhấn đúp chuột vào tệp: setup_xampp.bat
Tìm và nhấn đúp chuột vào tệp: xampp-control.exe