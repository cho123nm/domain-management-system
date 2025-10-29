# 📚 **TÀI LIỆU TỔNG HỢP TẤT CẢ CHỨC NĂNG HỆ THỐNG**

## 🎯 **TỔNG QUAN HỆ THỐNG**

Hệ thống quản lý domain/tên miền được thiết kế theo kiến trúc OOP với 2 phần chính:

- **👤 USER INTERFACE** - Giao diện người dùng cuối
- **👨‍💼 ADMIN INTERFACE** - Giao diện quản trị viên

---

## 👤 **PHẦN 1: CHỨC NĂNG NGƯỜI DÙNG (USER)**

### **🏠 1.1. TRANG CHỦ (Home Page)**

**File:** `index.php`
**Mô tả:** Trang chủ hiển thị danh sách các loại domain có sẵn
**Chức năng:**

- Hiển thị danh sách domain với giá bán
- Tìm kiếm domain
- Chuyển hướng đến trang mua domain

### **🔐 1.2. ĐĂNG NHẬP (Login)**

**File:** `Pages/login.php`
**Mô tả:** Trang đăng nhập cho người dùng
**Chức năng:**

- Form đăng nhập với username/password
- Xác thực thông tin đăng nhập
- Tạo session cho người dùng
- Chuyển hướng sau khi đăng nhập thành công

**Luồng xử lý:**

```
User → Pages/login.php → Ajaxs/login.php → UserRepository → Database
```

### **📝 1.3. ĐĂNG KÝ (Register)**

**File:** `Pages/register.php`
**Mô tả:** Trang đăng ký tài khoản mới
**Chức năng:**

- Form đăng ký với thông tin cá nhân
- Kiểm tra username/email đã tồn tại
- Tạo tài khoản mới
- Gửi email xác nhận (nếu có)

**Luồng xử lý:**

```
User → Pages/register.php → Ajaxs/register.php → UserRepository → Database
```

### **🛒 1.4. THANH TOÁN (Checkout)**

**File:** `Pages/Checkout.php`
**Mô tả:** Trang thanh toán mua domain
**Chức năng:**

- Hiển thị thông tin domain đã chọn
- Form nhập thông tin thanh toán
- Xử lý thanh toán qua thẻ cào
- Tạo đơn hàng mới

**Luồng xử lý:**

```
User → Pages/Checkout.php → Ajaxs/BuyDomain.php → HistoryRepository → Database
```

### **💰 1.5. NẠP TIỀN (Recharge)**

**File:** `Pages/Recharge.php`
**Mô tả:** Trang nạp tiền vào tài khoản
**Chức năng:**

- Form nhập thông tin thẻ cào
- Xử lý thẻ cào qua API
- Cập nhật số dư tài khoản
- Lịch sử giao dịch

**Luồng xử lý:**

```
User → Pages/Recharge.php → Ajaxs/Cards.php → CardRepository → Database
```

### **👤 1.6. HỒ SƠ CÁ NHÂN (Profile)**

**File:** `Pages/account_profile.php`
**Mô tả:** Trang quản lý thông tin cá nhân
**Chức năng:**

- Xem thông tin tài khoản
- Cập nhật thông tin cá nhân
- Xem lịch sử giao dịch
- Quản lý domain đã mua

### **🚪 1.7. ĐĂNG XUẤT (Logout)**

**File:** `Pages/logout.php`
**Mô tả:** Xử lý đăng xuất người dùng
**Chức năng:**

- Hủy session người dùng
- Chuyển hướng về trang chủ
- Xóa thông tin đăng nhập

---

## 👨‍💼 **PHẦN 2: CHỨC NĂNG QUẢN TRỊ VIÊN (ADMIN)**

### **🔐 2.1. ĐĂNG NHẬP ADMIN**

**File:** `.htaccess` + `.htpasswd`
**Mô tả:** Xác thực HTTP Basic Authentication
**Chức năng:**

- Popup đăng nhập của trình duyệt
- Username: `admin`
- Password: Mã hóa Apache MD5
- Bảo mật cấp server

### **📊 2.2. DASHBOARD (Trang Tổng Quan)**

**File:** `Adminstators/index.php`
**Mô tả:** Trang tổng quan thống kê hệ thống
**Chức năng:**

- Thống kê doanh thu (hôm nay, hôm qua, tháng, tổng)
- Số lượng đơn hàng (chờ xử lý, hoàn thành)
- Số lượng thành viên
- Số lượng cần cập nhật DNS

**Luồng xử lý:**

```
Admin → Adminstators/index.php → RepositoryFactory → CardRepository/HistoryRepository/UserRepository → Database
```

### **📋 2.3. QUẢN LÝ SẢN PHẨM**

**File:** `Adminstators/danh-sach-san-pham.php`
**Mô tả:** Hiển thị danh sách tất cả sản phẩm/domain
**Chức năng:**

- Hiển thị bảng danh sách sản phẩm
- Thông tin: Hình ảnh, tên miền, giá bán
- Thao tác: Edit, Delete
- Export Excel/PDF

### **➕ 2.4. THÊM SẢN PHẨM**

**File:** `Adminstators/them-san-pham.php`
**Mô tả:** Form thêm sản phẩm mới
**Chức năng:**

- Form nhập thông tin sản phẩm
- Chọn hình ảnh từ dropdown
- Preview hình ảnh
- Validation dữ liệu đầu vào

### **✏️ 2.5. SỬA SẢN PHẨM**

**File:** `Adminstators/Edit.php`
**Mô tả:** Form chỉnh sửa sản phẩm
**Chức năng:**

- Form với dữ liệu hiện tại đã điền sẵn
- Cập nhật thông tin sản phẩm
- Chọn hình ảnh mới
- Validation và xử lý lỗi

### **👥 2.6. QUẢN LÝ THÀNH VIÊN**

**File:** `Adminstators/quan-ly-thanh-vien.php`
**Mô tả:** Quản lý danh sách người dùng
**Chức năng:**

- Hiển thị danh sách thành viên
- Thông tin: UID, tài khoản, mật khẩu, số dư, thời gian
- Modal chỉnh sửa số dư
- Cập nhật thông tin thành viên

### **📦 2.7. DUYỆT ĐƠN HÀNG**

**File:** `Adminstators/duyet-don-hang.php`
**Mô tả:** Quản lý và duyệt đơn hàng
**Chức năng:**

- Hiển thị danh sách đơn hàng
- Thông tin: ID, tên miền, NS1, NS2, UID, trạng thái, thời gian
- Thao tác: Duyệt, Chờ, Hủy
- Cập nhật trạng thái đơn hàng

### **💳 2.8. QUẢN LÝ THẺ CÀO**

**File:** `Adminstators/Gach-Cards.php`
**Mô tả:** Quản lý lịch sử thẻ cào
**Chức năng:**

- Hiển thị danh sách thẻ cào
- Thông tin: UID, mã thẻ, serial, mệnh giá, loại thẻ, trạng thái
- Theo dõi trạng thái xử lý thẻ
- Thống kê thẻ thành công/thất bại

### **⚙️ 2.9. CÀI ĐẶT WEBSITE**

**File:** `Adminstators/cai-dat-web.php`
**Mô tả:** Cấu hình thông tin website
**Chức năng:**

- Cài đặt giao diện admin (theme)
- Cấu hình tiêu đề, mô tả, keywords
- Cài đặt banner, logo, số điện thoại
- Cấu hình API key

### **🌐 2.10. QUẢN LÝ DNS**

**File:** `Adminstators/DNS.php`
**Mô tả:** Quản lý cấu hình DNS
**Chức năng:**

- Cấu hình nameserver
- Quản lý DNS records
- Cập nhật DNS cho domain

### **💰 2.11. QUẢN LÝ NẠP TIỀN**

**File:** `Adminstators/don-nap-vi.php`
**Mô tả:** Quản lý đơn nạp tiền
**Chức năng:**

- Hiển thị danh sách đơn nạp tiền
- Duyệt/từ chối đơn nạp tiền
- Cập nhật số dư người dùng

---

## 🔄 **PHẦN 3: CHỨC NĂNG AJAX (XỬ LÝ BẤNG ĐỘNG)**

### **🔐 3.1. AJAX ĐĂNG NHẬP**

**File:** `Ajaxs/login.php`
**Mô tả:** Xử lý đăng nhập qua AJAX
**Chức năng:**

- Nhận dữ liệu POST từ form
- Xác thực thông tin đăng nhập
- Tạo session
- Trả về response JSON/HTML

### **📝 3.2. AJAX ĐĂNG KÝ**

**File:** `Ajaxs/register.php`
**Mô tả:** Xử lý đăng ký qua AJAX
**Chức năng:**

- Validate dữ liệu đầu vào
- Kiểm tra username/email trùng lặp
- Tạo tài khoản mới
- Trả về kết quả

### **🛒 3.3. AJAX MUA DOMAIN**

**File:** `Ajaxs/BuyDomain.php`
**Mô tả:** Xử lý mua domain qua AJAX
**Chức năng:**

- Validate thông tin domain
- Kiểm tra số dư người dùng
- Tạo đơn hàng mới
- Cập nhật lịch sử giao dịch

### **🔍 3.4. AJAX KIỂM TRA DOMAIN**

**File:** `Ajaxs/CheckDomain.php`
**Mô tả:** Kiểm tra tính khả dụng của domain
**Chức năng:**

- Kiểm tra domain có sẵn không
- Trả về thông tin domain
- Hiển thị giá bán

### **💳 3.5. AJAX XỬ LÝ THẺ CÀO**

**File:** `Ajaxs/Cards.php`
**Mô tả:** Xử lý thẻ cào qua AJAX
**Chức năng:**

- Validate thông tin thẻ cào
- Gọi API xử lý thẻ
- Cập nhật số dư
- Lưu lịch sử giao dịch

### **🌐 3.6. AJAX CẬP NHẬT DNS**

**File:** `Ajaxs/UpdateDns.php`
**Mô tả:** Cập nhật DNS qua AJAX
**Chức năng:**

- Cập nhật nameserver
- Validate cấu hình DNS
- Lưu thay đổi

---

## 🏗️ **PHẦN 4: KIẾN TRÚC HỆ THỐNG**

### **📁 4.1. CẤU TRÚC THƯ MỤC**

```
htdocs/
├── Config/                 # Cấu hình hệ thống
│   ├── Database.php        # Khởi tạo database
│   ├── DatabaseConnection.php # Singleton connection
│   ├── ErrorHandler.php    # Xử lý lỗi
│   ├── Header.php          # Header chung
│   └── Footer.php          # Footer chung
├── Repositories/           # Data Access Layer
│   ├── UserRepository.php  # Quản lý Users
│   ├── DomainRepository.php # Quản lý ListDomain
│   ├── HistoryRepository.php # Quản lý History
│   ├── CardRepository.php  # Quản lý Cards
│   └── SettingsRepository.php # Quản lý CaiDatChung
├── Controllers/            # Business Logic Layer
│   ├── BaseController.php  # Controller cơ sở
│   ├── AuthController.php  # Xử lý xác thực
│   ├── AdminController.php # Xử lý admin
│   ├── ViewController.php  # Xử lý view
│   └── AjaxController.php  # Xử lý AJAX
├── Pages/                  # User Interface
│   ├── login.php          # Trang đăng nhập
│   ├── register.php       # Trang đăng ký
│   ├── Checkout.php       # Trang thanh toán
│   └── account_profile.php # Trang hồ sơ
├── Adminstators/          # Admin Interface
│   ├── index.php          # Dashboard
│   ├── danh-sach-san-pham.php # Danh sách sản phẩm
│   └── them-san-pham.php  # Thêm sản phẩm
├── Ajaxs/                 # AJAX Handlers
│   ├── login.php          # AJAX đăng nhập
│   ├── register.php       # AJAX đăng ký
│   └── BuyDomain.php      # AJAX mua domain
└── Core/                  # Core System
    └── Router.php         # Routing system
```

### **🎯 4.2. DESIGN PATTERNS**

- **Singleton Pattern** - DatabaseConnection
- **Repository Pattern** - Data Access Layer
- **MVC Pattern** - Model-View-Controller
- **Factory Pattern** - RepositoryFactory
- **Dependency Injection** - Controllers → Repositories

### **🔒 4.3. BẢO MẬT**

- **HTTP Basic Authentication** - Admin panel
- **Session Management** - User authentication
- **Prepared Statements** - SQL injection protection
- **Input Validation** - Data sanitization
- **Error Handling** - Centralized error management

### **📊 4.4. DATABASE SCHEMA**

```sql
-- Bảng Users
CREATE TABLE `Users` (
  `id` int(11) NOT NULL,
  `taikhoan` varchar(255) DEFAULT NULL,
  `matkhau` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `tien` int(11) DEFAULT 0,
  `chucvu` int(11) DEFAULT 0,
  `time` varchar(255) DEFAULT NULL
);

-- Bảng ListDomain
CREATE TABLE `ListDomain` (
  `id` int(11) NOT NULL,
  `image` varchar(2655) DEFAULT NULL,
  `price` varchar(2555) DEFAULT NULL,
  `duoi` varchar(255) DEFAULT NULL
);

-- Bảng History
CREATE TABLE `History` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `ns1` varchar(255) DEFAULT NULL,
  `ns2` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `time` varchar(255) DEFAULT NULL
);

-- Bảng Cards
CREATE TABLE `Cards` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `pin` varchar(255) DEFAULT NULL,
  `serial` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `time` varchar(255) DEFAULT NULL
);

-- Bảng CaiDatChung
CREATE TABLE `CaiDatChung` (
  `id` int(11) NOT NULL,
  `tieude` varchar(255) DEFAULT NULL,
  `theme` varchar(255) DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `mota` text DEFAULT NULL,
  `imagebanner` varchar(255) DEFAULT NULL,
  `sodienthoai` varchar(255) DEFAULT NULL,
  `banner` varchar(2555) DEFAULT NULL,
  `logo` varchar(2555) DEFAULT NULL,
  `apikey` varchar(2555) DEFAULT NULL
);
```

---

## 🎯 **PHẦN 5: TÓM TẮT CHỨC NĂNG THEO NHÓM**

### **👤 CHỨC NĂNG NGƯỜI DÙNG:**

1. **Đăng ký/Đăng nhập** - Tạo và xác thực tài khoản
2. **Mua domain** - Tìm kiếm và mua domain
3. **Nạp tiền** - Nạp tiền qua thẻ cào
4. **Quản lý hồ sơ** - Cập nhật thông tin cá nhân
5. **Xem lịch sử** - Lịch sử giao dịch và domain

### **👨‍💼 CHỨC NĂNG ADMIN:**

1. **Dashboard** - Thống kê tổng quan hệ thống
2. **Quản lý sản phẩm** - CRUD domain/sản phẩm
3. **Quản lý thành viên** - Quản lý người dùng
4. **Duyệt đơn hàng** - Xử lý đơn hàng
5. **Quản lý thẻ cào** - Theo dõi giao dịch thẻ
6. **Cài đặt hệ thống** - Cấu hình website

### **🔄 CHỨC NĂNG AJAX:**

1. **Xác thực** - Login/Register real-time
2. **Giao dịch** - Mua domain, nạp tiền
3. **Kiểm tra** - Domain availability
4. **Cập nhật** - DNS, thông tin

---

## 🎉 **KẾT LUẬN**

**Hệ thống đã được thiết kế hoàn chỉnh với:**

- ✅ **Kiến trúc OOP** - Code có cấu trúc, dễ maintain
- ✅ **Bảo mật cao** - HTTP Basic Auth, Prepared Statements
- ✅ **Giao diện đẹp** - Tailwind CSS, responsive design
- ✅ **Chức năng đầy đủ** - Từ user đến admin
- ✅ **Hiệu suất tốt** - Singleton Pattern, Repository Pattern
- ✅ **Dễ mở rộng** - Modular design, separation of concerns

**Đây là một hệ thống quản lý domain chuyên nghiệp với đầy đủ tính năng cần thiết!** 🚀
