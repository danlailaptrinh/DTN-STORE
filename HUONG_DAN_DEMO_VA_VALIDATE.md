# Demo riêng và validate MVC

## Xem demo
- Client: http://localhost:3000/demo/index.php?route=/
- Admin: http://localhost:3000/demo/index.php?route=/admin
- Nhân viên: http://localhost:3000/demo/index.php?route=/employee

## MVC thật
- Danh sách sản phẩm: http://localhost:3000/admin/index.php?route=/admin/products
- Thêm sản phẩm: http://localhost:3000/admin/index.php?route=/admin/products/create
- Sửa: `/admin/index.php?route=/admin/products/edit/ID`
- Biến thể: `/admin/index.php?route=/admin/products/ID/variants`

## Validation
- HTML + JavaScript: assets/js/mvc-validation.js
- Server PHP: admin/controller/ProductInputValidation.php
- Kiểm tra tên, danh mục, thương hiệu, giá, SKU, RAM, dung lượng, tồn kho, màu, trạng thái và định dạng/kích thước file ảnh.
- Tạo biến thể: POST route `/admin/products/ID/variants/store`; kiểm tra trùng tổ hợp RAM+dung lượng+màu và SKU unique trong MySQL.
- Sửa/xóa biến thể: chưa triển khai nghiệp vụ; các nút vẫn khóa.
- Không sửa hoặc import lại database. Không có kết nối DB trong demo.
