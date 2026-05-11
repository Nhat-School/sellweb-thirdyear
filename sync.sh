#!/bin/bash

# 1. Xuất Database ra file sql
echo "--- Đang xuất Database..."
docker compose exec db mysqldump -u root -psecurepassword mystore > sql/latest_backup.sql

# 2. Đẩy toàn bộ lên Git
echo "--- Đang đẩy lên Git..."
git add .
git commit -m "Auto backup data & code: $(date)"
git push origin main

echo "=== ĐÃ XONG! Code và Dữ liệu đã an toàn trên GitHub ==="
