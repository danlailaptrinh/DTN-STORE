# PROJECT_PROGRESS.md — PhoneStore PHP MVC

> Tệp theo dõi tiến độ. Cập nhật sau mỗi bước được xác nhận; không đánh dấu hoàn thành khi chưa kiểm tra thực tế.

## Quy ước cập nhật
- Ghi mã bước, nội dung, trạng thái (Chưa làm / Đang làm / Đã xác nhận hoàn thành / Cần kiểm tra).
- Ghi ngắn gọn file đã thay đổi, kiểm tra đã thực hiện, lỗi còn lại và bước kế tiếp.
- Không coi nội dung bàn giao là bằng chứng thay cho kiểm tra mã nguồn.
- Khi chuyển AI hoặc phiên làm việc, đọc `GEMINI.md` và tệp này trước.

## Trạng thái bàn giao hiện tại

### Mục 6 — Quản lý sản phẩm
- **Trạng thái:** Đã triển khai một số thành phần MVC và đã xử lý một số lỗi; chưa xác minh toàn bộ trong lần bàn giao này.
- **Ghi chú lỗi đã xử lý:** validate JavaScript từng yêu cầu trường `brand` ngay cả khi form sửa không có trường đó; logic đã được điều chỉnh để chỉ validate brand khi trường tồn tại.
- **Việc cần làm:** xác minh trạng thái và các chức năng trực tiếp trên dự án trước khi kết luận.

### Mục 7 — Quản lý danh mục

| Mã bước | Nội dung | Trạng thái | Ghi chú |
|---|---|---|---|
| 7.1.1 | Category Model — phương thức `getAll($keyword = '')` lấy danh sách và số lượng sản phẩm | Đã báo hoàn thành | Kiểm tra code thực tế khi cần |
| 7.1.2 | CategoryController — phương thức `index()` nhận keyword, gọi Model và truyền View | Đã báo hoàn thành | Kiểm tra code thực tế khi cần |
| 7.1.3 | Chuyển/hoàn thiện View danh sách danh mục từ demo sang MVC | Đã xác nhận hoàn thành | Đã sửa `admin/view/categories/index.php` và nạp layout/phân trang ở `CategoryController::index()` |
| 7.1.4 | Kết nối Controller với Router MVC chính thức | Đã xác nhận hoàn thành | Đã kết nối `GET /admin/categories` tới `CategoryController::index()` trong `admin/index.php` |
| 7.1.5 | Kiểm tra danh sách và tìm kiếm | Đã xác nhận hoàn thành | Đã kiểm tra hiển thị danh sách, tìm kiếm và đặt lại |

### Phần 7.2 — Thêm mới danh mục

| Mã bước | Nội dung | Trạng thái | Ghi chú |
|---|---|---|---|
| 7.2.1 | Category Model — kiểm tra/tạo slug duy nhất và phương thức `create()` | Đã xác nhận hoàn thành | Đã thêm `slugExists()`, `generateUniqueSlug()`, `create()` vào `admin/model/Category.php` |
| 7.2.2 | CategoryController — `create()` và `store()` | Đã xác nhận hoàn thành | Đã thêm `create()` và `store()` trong `admin/controller/CategoryController.php` |
| 7.2.3 | Hoàn thiện View `create.php` (và thông báo ở `index.php`) | Đã xác nhận hoàn thành | Đã cập nhật `admin/view/categories/create.php` (`maxlength="150"`), giữ nguyên giao diện demo |
| 7.2.4 | Kết nối Router `GET /admin/categories/create` và `POST /admin/categories/store` | Đã xác nhận hoàn thành | Đã kết nối trong `admin/index.php` |
| 7.2.5 | Kiểm tra chức năng thêm mới danh mục | Đã xác nhận hoàn thành | Đã kiểm tra lưu DB, sinh slug duy nhất, flash và validate |

### Phần 7.3 — Sửa danh mục

| Mã bước | Nội dung | Trạng thái | Ghi chú |
|---|---|---|---|
| 7.3.1 | Category Model — `findById()` và `update()` | Đã xác nhận hoàn thành | Đã thêm `findById()` và `update()` vào `admin/model/Category.php` |
| 7.3.2 | CategoryController — `edit()` và `update()` | Đã xác nhận hoàn thành | Đã thêm `edit()` và `update()` trong `admin/controller/CategoryController.php` |
| 7.3.3 | Hoàn thiện View `admin/view/categories/edit.php` | Đã xác nhận hoàn thành | Đã cập nhật `admin/view/categories/edit.php` (`maxlength="150"`), giữ nguyên giao diện demo |
| 7.3.4 | Kết nối Router `GET /admin/categories/edit/{id}` và `POST /admin/categories/update/{id}` | Đã xác nhận hoàn thành | Đã kết nối trong `admin/index.php` |
| 7.3.5 | Kiểm tra chức năng sửa danh mục | Đã xác nhận hoàn thành | Đã kiểm tra cập nhật tên, slug, mô tả (`description`), trạng thái và tìm kiếm |

### Phần 7.4 — Xóa danh mục

| Mã bước | Nội dung | Trạng thái | Ghi chú |
|---|---|---|---|
| 7.4.1 | Category Model — `countProducts()` và `delete()` | Đã xác nhận hoàn thành | Đã thêm `countProducts()` và `delete()` vào `admin/model/Category.php` |
| 7.4.2 | CategoryController — `delete()` | Đã xác nhận hoàn thành | Đã thêm `delete()` trong `admin/controller/CategoryController.php` |
| 7.4.3 | Kết nối Router `POST /admin/categories/delete` | Đã xác nhận hoàn thành | Đã kết nối trong `admin/index.php` |
| 7.4.4 | Kiểm tra chức năng xóa danh mục | Đang thực hiện | Kiểm tra xóa danh mục trống, chặn xóa danh mục có sản phẩm và thông báo |

### Các bước tiếp theo
1. Thực hiện 7.4.4: kiểm tra thực tế chức năng xóa danh mục trên trình duyệt.
2. Chờ người dùng xác nhận `Xong 7.4.4`.

## Nhật ký công việc
| Ngày | Bước | Nội dung / kết quả | Trạng thái |
|---|---|---|---|
| 2026-11-27 | 7.1.1 | Category Model | Đã báo hoàn thành |
| 2026-11-27 | 7.1.2 | CategoryController | Đã báo hoàn thành |
| 2026-11-27 | 7.1.3 | View danh sách danh mục (`admin/view/categories/index.php`, `admin/controller/CategoryController.php`) | Đã xác nhận hoàn thành |
| 2026-11-27 | 7.1.4 | Kết nối Router MVC (`admin/index.php`) | Đã xác nhận hoàn thành |
| 2026-11-27 | 7.1.5 | Kiểm tra danh sách và tìm kiếm | Đã xác nhận hoàn thành |
| 2026-11-27 | 7.2.1 | Category Model (create, slug) | Đã xác nhận hoàn thành |
| 2026-11-27 | 7.2.2 | CategoryController (create, store) | Đã xác nhận hoàn thành |
| 2026-11-27 | 7.2.3 | View thêm danh mục (`admin/view/categories/create.php`) | Đã xác nhận hoàn thành |
| 2026-11-27 | 7.2.4 | Kết nối Router `create`/`store` (`admin/index.php`) | Đã xác nhận hoàn thành |
| 2026-11-27 | 7.2.5 | Kiểm tra chức năng thêm mới danh mục | Đã xác nhận hoàn thành |
| 2026-11-27 | 7.3.1 | Category Model (`findById`, `update`) | Đã xác nhận hoàn thành |
| 2026-11-27 | 7.3.2 | CategoryController (`edit`, `update`) | Đã xác nhận hoàn thành |
| 2026-11-27 | 7.3.3 | View sửa danh mục (`admin/view/categories/edit.php`) | Đã xác nhận hoàn thành |
| 2026-11-27 | 7.3.4 | Kết nối Router `edit`/`update` (`admin/index.php`) | Đã xác nhận hoàn thành |
| 2026-11-27 | 7.3.5 | Kiểm tra chức năng sửa danh mục (bao gồm `description`) | Đã xác nhận hoàn thành |
| 2026-11-27 | 7.4.1 | Category Model (`countProducts`, `delete`) | Đã xác nhận hoàn thành |
| 2026-11-27 | 7.4.2 | CategoryController (`delete`) | Đã xác nhận hoàn thành |
| 2026-11-27 | 7.4.3 | Kết nối Router `delete` (`admin/index.php`) | Đã xác nhận hoàn thành |
| 2026-11-27 | 7.4.4 | Kiểm tra chức năng xóa danh mục | Đang thực hiện |
