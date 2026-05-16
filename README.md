# WEB QUẦN ÁO NAM

---

## 📌 Tên đề tài
XÂY DỰNG WEBSITE BÁN QUẦN ÁO NAM

---

## 🌐 Giới thiệu website/hệ thống
Đây là một website thương mại điện tử cho phép người dùng xem, tìm kiếm và mua các sản phẩm quần áo nam online.  
Hệ thống hỗ trợ các chức năng như:
- Đăng nhập, đăng ký tài khoản, quên mật khẩu
- Xem danh sách sản phẩm
- Xem chi tiết sản phẩm
- Lọc sản phẩm theo danh mục, giá
- Thêm vào giỏ hàng
- Đặt hàng / thanh toán 
- Cài đặt địa chỉ nhận hàng tại Việt Nam
- Hiển thị thông báo khi add sản phẩm vào giỏ
- Hiển thị thông báo sau khi thanh toán thành công

## TÍNH NĂNG NÂNG CAO: MÃ GIẢM GIÁ, GỬI MAIL SAU KHI ĐƠN HÀNG ĐƯỢC THANH TOÁN THÀNH CÔNG, CHAT VỚI NHÂN VIÊN SHOP
    + PLUGIN MÃ GIẢM GIÁ:
      +  **Giao diện màn ADMIN**: Giao diện quản lý mã giảm giá (CRUD, khởi tạo thời gian, số lượng sản phẩm tối thiểu để áp mã, giá sale,...)
      + **Giao diện màn user**: Thanh tiến trình mã giảm giá khi thêm xóa sp trong giỏ, ưu tiên sử dụng mã giảm giá tốt nhất, checkbox tại giỏ hàng, tự động tính tiền sau checkbox
      + Có mã giảm giá ẩn: XUANHOA_PRO
    + PLUGIN GỬI MAIL XÁC NHẬN ĐƠN HÀNG:
      + THÔNG TIN MAIL GỬI BAO GỒM: Mail chứa thông tin người mua người nhận, số sản phẩm, giá đơn,..., riêng user sẽ có thêm 1 mail trạng thái đơn hàng
    + PLUGIN CHAT: sử dụng tawk.to để thực hiện kết nối chat giữa người dùng với nhân viên shop
    
## 👥 Danh sách thành viên

| Họ và tên | MSSV |
|-----------|------|
| Nguyễn Thị Xuân Hoa | 23810310027 |
| Lê Quỳnh Trang | 23810310003 |

---

## 🧑‍💻 Phân công nhiệm vụ cụ thể

| Thành viên | Nhiệm vụ |
|------------|----------|
| Nguyễn Thị Xuân Hoa | Thiết kế giao diện websitem Code plugin mã giảm giá, gửi mail xác nhận đơn hàng, plugin chat, sử dụng code snippets để tùy biến lại giỏ hàng dạng checkbox, đánh giá |
| Lê Quỳnh Trang | Thiết kế giao diện website, thiết kế giao diện đăng nhập, đăng ký, sử dụng code snippets để tùy biến thông báo thêm vào giỏ hàng, thông báo thanh toán thành công, tích hợp thanh toán: sepay, vietqr, payos |
, tu
---

## ⚙️ Công nghệ sử dụng
- HTML, CSS, JavaScript
- PHP (hoặc Node.js / tùy project bạn dùng)
- MySQL
- Bootstrap (nếu có)
- Git / GitHub

---

## 🛠️ Hướng dẫn cài đặt

1. Clone repository:
```bash
git clone https://github.com/nguyenthixuanhoa2005/web-quan-ao-nam.git
Di chuyển vào thư mục project:
cd web-quan-ao-nam
Import database (nếu có file .sql):
Mở phpMyAdmin
Tạo database mới
Import file .sql
Cấu hình database:
Sửa file config (config.php / .env) theo thông tin máy bạn
▶️ Hướng dẫn chạy project
Nếu dùng XAMPP:
Copy project vào thư mục:
htdocs/
Start Apache & MySQL trong XAMPP
Truy cập:
http://localhost/web-quan-ao-nam
🔐 Tài khoản demo (nếu có)

Admin:

Username: admin
Password: 123456

User:

Username: user
Password: 123456
🖼️ Hình ảnh minh họa hệ thống

Thêm ảnh giao diện tại đây:

Trang chủ
Trang sản phẩm
Giỏ hàng
Trang admin

🎥 Link video demo
https://your-video-link.com
🚀 Link online đã deploy
https://your-deploy-link.com
📌 Ghi chú
Đây là project phục vụ mục đích học tập
Có thể cập nhật thêm tính năng trong tương lai
