<div align="center">

# 🛍️ WEB BÁN QUẦN ÁO NAM

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![WordPress](https://img.shields.io/badge/WordPress-21759B?style=for-the-badge&logo=wordpress&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![WooCommerce](https://img.shields.io/badge/WooCommerce-96588A?style=for-the-badge&logo=woocommerce&logoColor=white)

**Đồ án môn học — Xây dựng website thương mại điện tử bán quần áo nam**

🌐 **[shopclothers.kesug.com](http://shopclothers.kesug.com)**

</div>

---

## 📌 Tên đề tài

**XÂY DỰNG WEBSITE BÁN QUẦN ÁO NAM**

---

## 🌐 Giới thiệu hệ thống

Website thương mại điện tử cho phép người dùng xem, tìm kiếm và mua sản phẩm quần áo nam trực tuyến.

### Chức năng cơ bản

| Tính năng | Mô tả |
|-----------|-------|
| 🔐 Tài khoản | Đăng nhập, đăng ký, quên mật khẩu |
| 🛍️ Sản phẩm | Xem danh sách, chi tiết, lọc theo danh mục & khoảng giá, chủng loại sản phẩm|
| 🛒 Giỏ hàng | Thêm sản phẩm, checkbox tùy chọn, tự động tính tiền real-time |
| 💳 Thanh toán | Tích hợp SePay · VietQR · PayOS |
| 🗺️ Địa chỉ | Cài đặt địa chỉ nhận hàng tại Việt Nam |
| 🔔 Thông báo | Pop-up khi thêm giỏ hàng & sau khi thanh toán thành công, chờ khi đang thanh toán |

---

## ✨ Tính năng nâng cao

### 🎟️ Plugin Mã Giảm Giá

**Phía Admin:**
- CRUD mã giảm giá đầy đủ
- Thiết lập thời gian hiệu lực, số lượng sản phẩm tối thiểu để áp mã, mức giảm giá linh hoạt

**Phía User:**
- Thanh tiến trình hiển thị điều kiện áp mã khi thêm/xóa sản phẩm trong giỏ
- Tự động ưu tiên mã giảm giá tốt nhất
- Checkbox tại giỏ hàng, tự động tính tiền sau khi chọn

 **Mã ẩn đặc biệt:** `XUANHOA_PRO`

---

### 📧 Plugin Gửi Mail Xác Nhận Đơn Hàng

Tự động gửi mail sau khi thanh toán thành công với các thông tin:
- Thông tin người mua & người nhận
- Danh sách sản phẩm & số lượng
- Tổng tiền đơn hàng
- *(Riêng user)* Mail cập nhật trạng thái đơn hàng

---

### 💬 Plugin Chat Với Nhân Viên Shop

- Tích hợp **tawk.to** cho phép user chat trực tiếp với nhân viên
- Real-time, không cần đăng nhập từ phía khách hàng


### Sử dụng Plugin Code Snippet để custom giao diện, logic nhỏ
- Giỏ hàng
- Thanh tìm kiếm
- Thông báo, chuyển trang,...
- Trang tài khoản, đơn hàng của tài khoản
- Nút đã nhận được hàng
- Logic đã nhận được hành mới được thanh toán
- ....
---

## 👥 Thành viên nhóm

| Họ và tên | MSSV |
|-----------|------|
| Nguyễn Thị Xuân Hoa | 23810310027 |
| Lê Quỳnh Trang | 23810310003 |

---

## 🧑‍💻 Phân công nhiệm vụ

| Thành viên | Nhiệm vụ |
|------------|----------|
| **Nguyễn Thị Xuân Hoa** | Thiết kế giao diện website · Code Plugin mã giảm giá · Code Plugin gửi mail xác nhận đơn hàng · Tích hợp Plugin chat · Tùy biến giỏ hàng dạng checkbox · Tính năng đánh giá sản phẩm sau khi bấm đã nhận được hàng|
| **Lê Quỳnh Trang** | Thiết kế giao diện website · Trang đăng nhập / đăng ký · Thông báo thêm vào giỏ hàng & thanh toán thành công · Tích hợp thanh toán SePay · VietQR · PayOS |

---

## ⚙️ Công nghệ sử dụng

- **Backend:** PHP, WordPress, WooCommerce
- **Database:** MySQL
- **Frontend:** HTML, CSS, JavaScript
- **Thanh toán:** SePay, VietQR, PayOS
- **Chat:** tawk.to
- **Công cụ:** Git / GitHub, XAMPP

---

## 🛠️ Hướng dẫn cài đặt

### Bước 1 — Clone repository

```bash
git clone https://github.com/nguyenthixuanhoa2005/web-quan-ao-nam.git
cd web-quan-ao-nam
```

### Bước 2 — Import database

1. Mở **phpMyAdmin**
2. Tạo database mới (ví dụ: `web_quan_ao_nam`)
3. Import file `.sql` đi kèm trong project

### Bước 3 — Cấu hình kết nối

Chỉnh sửa file `wp-config.php` theo thông tin database trên máy bạn:

```php
define('DB_NAME', 'web_quan_ao_nam');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');
```

### Bước 4 — Chạy với XAMPP

```
1. Copy project vào thư mục: htdocs/web-quan-ao-nam
2. Khởi động Apache & MySQL trong XAMPP Control Panel
3. Truy cập: http://localhost/web-quan-ao-nam
```

---

## 🔐 Tài khoản demo

| Vai trò | Username | Password |
|---------|----------|----------|
| Admin | `hoa` | `123` |
| User | `hoa123` | `123` |

> 💡 Mã giảm giá ẩn để test: **`XUANHOA_PRO`**

---

## 🖼️ Hình ảnh minh họa

### Trang chủ 
<img width="1919" height="1145" alt="image" src="https://github.com/user-attachments/assets/09714220-717f-4533-b457-7241a996c8e3" />

<img width="606" height="339" alt="image" src="https://github.com/user-attachments/assets/92be2464-4ac9-48f1-ae07-5fd2cdcd9cf4" />

<img width="606" height="335" alt="image" src="https://github.com/user-attachments/assets/b02104e6-9f4b-465f-b4fb-2dd34435afb2" />

<img width="606" height="338" alt="image" src="https://github.com/user-attachments/assets/a959d80e-fd9e-4b94-b68b-e6797332d0b9" />


### Giao diện đăng nhập
<img width="605" height="318" alt="image" src="https://github.com/user-attachments/assets/a755d94f-fb8c-4324-a61a-75e12c66baf0" />

### Giao diện quên mật khẩu
<img width="607" height="297" alt="image" src="https://github.com/user-attachments/assets/3e245c9f-043b-4c9f-8253-cd571b3dca18" />

### Giao diện đăng ký
<img width="605" height="330" alt="image" src="https://github.com/user-attachments/assets/299dad10-b347-4568-8bd1-5548ea615a69" />

### Giao diện trang sản phẩm
<img width="606" height="322" alt="image" src="https://github.com/user-attachments/assets/f5729eae-b6cd-44c1-945c-ea20bcea8b5c" />

### Giao diện khi tìm kiếm sản phẩm
<img width="606" height="300" alt="image" src="https://github.com/user-attachments/assets/a565913d-13a2-4c92-8158-4eb7399918db" />

### Giao diện khi trang chi tiết sản phẩm
<img width="606" height="341" alt="image" src="https://github.com/user-attachments/assets/eb328426-7cf1-4a20-943f-c9ee310f216a" />

### Giao diện đánh giá đơn hàng
<img width="606" height="332" alt="image" src="https://github.com/user-attachments/assets/4f144746-1f2f-4fb0-80ae-dce62e27bec8" />

###Giao diện trang tin tức 
<img width="605" height="350" alt="image" src="https://github.com/user-attachments/assets/efb8b477-5383-4937-b301-fbeceb0d60ef" />

<img width="606" height="361" alt="image" src="https://github.com/user-attachments/assets/5763f829-ee51-4e93-ad2a-f88aa4de7c28" />

<img width="604" height="314" alt="image" src="https://github.com/user-attachments/assets/0279420d-eeaf-4452-b36f-c3c454c21c7a" />

### Giao diện trang Về chúng tôi
<img width="605" height="341" alt="image" src="https://github.com/user-attachments/assets/ebd7787d-e316-4cc2-a354-f1770aa1e793" />

<img width="606" height="319" alt="image" src="https://github.com/user-attachments/assets/05e01558-1b10-4b7b-8f62-ba738f721f8d" />

<img width="605" height="341" alt="image" src="https://github.com/user-attachments/assets/8d547fac-94f9-469c-b019-9e84525402ac" />


### Giao diện trang liên hệ
<img width="604" height="302" alt="image" src="https://github.com/user-attachments/assets/ee48cad4-76ce-404f-9c4d-abdd468740ba" />

### Giao diện trang tài khoản
<img width="606" height="303" alt="image" src="https://github.com/user-attachments/assets/e775dc6a-48af-423a-857b-e9decb337802" />

<img width="605" height="350" alt="image" src="https://github.com/user-attachments/assets/64cf4169-b7f2-4029-b86b-7226d3944c70" />


### Giao diện trang giỏ hàng: Thanh tiến trình + Checkbox cho sản phẩm

<img width="606" height="302" alt="image" src="https://github.com/user-attachments/assets/5ef42080-b4e0-4656-a85b-dc8ff1b3afae" />

### Giao diện trang thanh toán sau khi đổi địa chỉ thành địa chỉ tại Việt Nam

<img width="606" height="309" alt="image" src="https://github.com/user-attachments/assets/ab0a5f74-66c3-42f4-aa9b-16ecf6c7285d" />


### Giao diện trang thanh toán qua ví momo + VNPay

<img width="606" height="344" alt="image" src="https://github.com/user-attachments/assets/4e701d17-68fe-4ad0-af1b-fa6c793e1cfa" />

### Giao diện trang thanh toán qua VietQR
<img width="604" height="299" alt="image" src="https://github.com/user-attachments/assets/aaf319a0-a3a7-4cac-aa42-1d58e7e01620" />
---> THANH TOÁN THÀNH CÔNG:
<img width="605" height="321" alt="image" src="https://github.com/user-attachments/assets/b4b46b7e-0962-4306-b7ff-5c127ad0193a" />
### Giao diện trang thanh toán qua Sepay
<img width="1057" height="648" alt="image" src="https://github.com/user-attachments/assets/e29fd361-e5da-4906-9ae1-484e8b20225e" />
---> THANH TOÁN THÀNH CÔNG:
<img width="605" height="328" alt="image" src="https://github.com/user-attachments/assets/bf45ba2a-cec3-4b5e-8eda-44287d91a192" />


### Giao diện trang thanh toán qua PayOS
<img width="915" height="588" alt="image" src="https://github.com/user-attachments/assets/dd5ef33e-d9c9-4539-aae6-72a324201bbe" />

---> THANH TOÁN THÀNH CÔNG:
<img width="605" height="327" alt="image" src="https://github.com/user-attachments/assets/22b12099-f195-43cc-b9cd-2050bad9bc64" />

### KẾT QUẢ EMAIL KHÁCH XÁC NHẬN ĐẶT HÀNG 
<img width="606" height="305" alt="image" src="https://github.com/user-attachments/assets/04b76961-1c1d-40c0-b505-b1aa88b18f75" />

<img width="606" height="373" alt="image" src="https://github.com/user-attachments/assets/0fecaea4-4754-4911-9027-d7d776ac0325" />

### KẾT QUẢ EMAIL ADMIN XÁC NHẬN ĐƠN KHÁCH ĐẶT
<img width="606" height="362" alt="image" src="https://github.com/user-attachments/assets/d78e3ba3-3d13-4c2d-aa93-accadf1e2b91" />

### THANH TIẾN TRÌNH (VS PLUGIN MÃ SALE 3 SẢN PHẨM GIẢM GẦN 20%)
--> KHI ADD 3 SẢN PHẨM VÀO GIỎ:
<img width="606" height="325" alt="image" src="https://github.com/user-attachments/assets/52350824-0f8c-4c18-a441-457ad2901eed" />

--> BỎ CHỌN 1 SẢN PHẨM TRONG 3 SẢN PHẨM:
<img width="606" height="328" alt="image" src="https://github.com/user-attachments/assets/c59bbcd4-f8df-46b4-a009-1cef548317c5" />

--> KẾT QUẢ SAU KHI ADD 1 MÃ GIẢM GIÁ MINI: XUANHOA_PRO
<img width="605" height="327" alt="image" src="https://github.com/user-attachments/assets/3f812d40-56cb-4701-ab5f-3f179d8c95db" />

---

## 🎥 Video demo

🎬 https://drive.google.com/drive/folders/10pP82xKOUdJPdUK9bl6-osY80i3PX3LT?usp=sharing

---

## 🚀 Website đã deploy

🌐 [http://shopclothers.kesug.com](http://shopclothers.kesug.com)

---

<div align="center">

Made with ❤️ by **Xuân Hoa** & **Quỳnh Trang** · 2026

</div>
