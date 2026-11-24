# Cách chạy không vào giao diện demo

- Mở `http://localhost:3000/` hoặc `http://localhost:3000/admin/` sẽ được đưa thẳng vào `/admin/index.php?route=/admin/products`.
- Chỉ các chức năng sản phẩm đã kết nối MVC (danh sách, thêm, sửa, trang danh sách biến thể) mới chạy được.
- Các module Client, Nhân viên, danh mục, đơn hàng, người dùng, đánh giá chưa có Controller nghiệp vụ thực sẽ báo 501 thay vì hiển thị demo. Điều này có chủ đích để tránh nhầm dữ liệu mẫu với dữ liệu thật.
- Các view trong `giao_dien` vẫn được giữ làm tài liệu tham khảo, không được Router gọi.
- Giữ database `dtd_shop_en` đã có; không import lại SQL.
- Chạy PHP server từ thư mục gốc `PhoneStore_PHP_MVC` tại cổng 3000 (chẳng hạn `php -S localhost:3000`).
