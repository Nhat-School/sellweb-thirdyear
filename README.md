# Hướng dẫn cài đặt NhatShop trên máy mới

Tài liệu này hướng dẫn bạn cách thiết lập và chạy dự án NhatShop trên một máy tính mới sử dụng Docker.

## 1. Yêu cầu hệ thống
Trước khi bắt đầu, hãy đảm bảo máy tính của bạn đã cài đặt:
- **Docker Desktop** (Đã bao gồm Docker Compose).
- **Git**.

## 2. Các bước cài đặt

### Bước 1: Tải mã nguồn về máy
Mở Terminal (hoặc Command Prompt) và chạy lệnh sau để clone dự án:
```bash
git clone https://github.com/Nhat-School/sellweb-thirdyear.git
cd sellweb-thirdyear
```

### Bước 2: Khởi động hệ thống bằng Docker
Chạy lệnh sau để build và khởi động các container:
```bash
docker-compose up -d --build
```
*Lưu ý: Lệnh này sẽ tự động tải các image cần thiết và thiết lập môi trường chạy PHP & MySQL.*

### Bước 3: Kiểm tra ứng dụng
Sau khi các container đã chạy thành công, bạn có thể truy cập:
- **Trang chủ Website:** [http://localhost:8080](http://localhost:8080)
- **Cơ sở dữ liệu (MySQL):** Cổng `3306` (Sử dụng các phần mềm như Navicat, DBeaver để kết nối).

## 3. Hướng dẫn cập nhật dữ liệu từ file Backup
Mặc định khi chạy lần đầu, Docker sẽ khởi tạo cấu trúc database cơ bản. Để cập nhật toàn bộ dữ liệu mới nhất từ file backup bạn vừa tạo (`latest_backup.sql`), hãy chạy lệnh sau:

```bash
# MacOS
docker compose exec -T db mysql -u root -psecurepassword mystore < sql/latest_backup.sql
# Windows ps
Get-Content -Encoding UTF8 sql/latest_backup.sql | docker compose exec -T db mysql --default-character-set=utf8mb4 -u root -psecurepassword mystore
```

## 4. Thông tin quản trị
- **Database Name:** `mystore`
- **Database User:** `root`
- **Database Password:** `securepassword`
- **Thư mục ảnh sản phẩm:** `assets/images/` (Đã được phân quyền 777 trong Docker).

---

