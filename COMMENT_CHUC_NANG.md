# 📋 **COMMENT CHI TIẾT CHỨC NĂNG CÁC FILE TRONG HỆ THỐNG CLOUDSTOREVN**

## 🎯 **CONTROLLERS - XỬ LÝ LOGIC NGHIỆP VỤ**

### **1. BaseController.php**

```php
/**
 * BaseController - Lớp cơ sở cho tất cả controllers
 *
 * CHỨC NĂNG CHÍNH:
 * - Cung cấp các phương thức cơ bản cho tất cả controllers
 * - Xử lý HTTP methods (GET, POST, PUT, DELETE)
 * - Quản lý authentication và authorization
 * - Rate limiting để chống spam/brute force
 * - Logging và error handling tập trung
 * - CORS headers cho API requests
 */
```

**Các method chính:**

- `handleRequest()` - Phân loại request theo HTTP method
- `handleGet()` - Xử lý GET request (hiển thị trang, lấy dữ liệu)
- `handlePost()` - Xử lý POST request (form submit, tạo mới)
- `handlePut()` - Xử lý PUT request (cập nhật dữ liệu)
- `handleDelete()` - Xử lý DELETE request (xóa dữ liệu)
- `requireAuth()` - Kiểm tra authentication
- `getCurrentUser()` - Lấy thông tin user hiện tại
- `checkRateLimit()` - Chống spam/brute force
- `logAction()` - Ghi log hoạt động

### **2. AuthController.php**

```php
/**
 * AuthController - Xử lý xác thực người dùng
 *
 * CHỨC NĂNG CHÍNH:
 * - Đăng nhập (login) - Xác thực thông tin đăng nhập
 * - Đăng ký (register) - Tạo tài khoản mới
 * - Đăng xuất (logout) - Hủy session và redirect
 * - Rate limiting - Chống brute force attacks
 * - Session management - Quản lý phiên đăng nhập
 * - Input validation - Kiểm tra dữ liệu đầu vào
 */
```

**Các method chính:**

- `login()` - Xử lý đăng nhập với rate limiting
- `register()` - Xử lý đăng ký với validation
- `logout()` - Xử lý đăng xuất và redirect

### **3. ViewController.php**

```php
/**
 * ViewController - Xử lý tất cả các trang view
 *
 * CHỨC NĂNG CHÍNH:
 * - Render các trang frontend
 * - Xử lý dữ liệu cho view
 * - Quản lý authentication cho các trang
 * - Truyền dữ liệu từ database đến view
 */
```

**Các method chính:**

- `renderHome()` - Hiển thị trang chủ với danh sách domain
- `renderLogin()` - Hiển thị trang đăng nhập
- `renderRegister()` - Hiển thị trang đăng ký
- `renderCheckout()` - Hiển thị trang thanh toán
- `renderProfile()` - Hiển thị trang profile user
- `renderManagers()` - Hiển thị trang quản lý domain

### **4. AdminController.php**

```php
/**
 * AdminController - Xử lý tất cả các trang admin
 *
 * CHỨC NĂNG CHÍNH:
 * - Render các trang admin
 * - Quản lý dashboard với thống kê
 * - Xử lý dữ liệu cho admin panel
 * - Bảo vệ các trang admin
 */
```

**Các method chính:**

- `renderDashboard()` - Dashboard với thống kê tổng quan
- `renderProducts()` - Quản lý danh sách sản phẩm
- `renderMembers()` - Quản lý thành viên
- `renderOrders()` - Duyệt đơn hàng
- `renderCards()` - Quản lý thẻ cào
- `renderSettings()` - Cài đặt hệ thống

### **5. DomainController.php**

```php
/**
 * DomainController - Xử lý domain logic
 *
 * CHỨC NĂNG CHÍNH:
 * - Kiểm tra domain có sẵn
 * - Xử lý mua domain
 * - Cập nhật DNS
 * - Quản lý giao dịch domain
 */
```

**Các method chính:**

- `checkDomain()` - Kiểm tra domain có sẵn
- `purchaseDomain()` - Xử lý mua domain
- `updateDns()` - Cập nhật DNS cho domain

### **6. CardController.php**

```php
/**
 * CardController - Xử lý logic thẻ cào
 *
 * CHỨC NĂNG CHÍNH:
 * - Xử lý nạp thẻ cào
 * - Tích hợp API CardVIP
 * - Quản lý giao dịch thẻ
 * - Cập nhật số dư user
 */
```

**Các method chính:**

- `processCard()` - Xử lý nạp thẻ cào
- `validateCard()` - Kiểm tra thông tin thẻ
- `updateBalance()` - Cập nhật số dư

### **7. AjaxController.php**

```php
/**
 * AjaxController - Xử lý tất cả các yêu cầu AJAX
 *
 * CHỨC NĂNG CHÍNH:
 * - Xử lý AJAX requests
 * - Tích hợp các controller khác
 * - Trả về JSON response
 * - Xử lý authentication cho AJAX
 */
```

**Các method chính:**

- `handleLogin()` - AJAX login
- `handleRegister()` - AJAX register
- `handleCheckDomain()` - AJAX check domain
- `handleBuyDomain()` - AJAX buy domain
- `handleProcessCard()` - AJAX process card
- `handleUpdateDns()` - AJAX update DNS

---

## 🗄️ **REPOSITORIES - QUẢN LÝ DATABASE**

### **1. UserRepository.php**

```php
/**
 * UserRepository - Repository quản lý tất cả thao tác với bảng Users
 *
 * CHỨC NĂNG CHÍNH:
 * - Xác thực đăng nhập (verifyCredentials)
 * - Quản lý thông tin người dùng (CRUD operations)
 * - Cập nhật số dư tài khoản (updateBalance)
 * - Đăng ký tài khoản mới (createUser)
 * - Tìm kiếm user theo username/email
 * - Cập nhật profile user
 * - Đếm số lượng user
 *
 * PATTERN: Repository Pattern - Tách biệt logic database khỏi business logic
 * SECURITY: Sử dụng prepared statements để chống SQL injection
 */
```

**Các method chính:**

- `findByUsername()` - Tìm user theo username
- `findByEmail()` - Tìm user theo email
- `verifyCredentials()` - Xác thực đăng nhập
- `createUser()` - Tạo user mới
- `updateBalance()` - Cập nhật số dư
- `updateProfile()` - Cập nhật profile
- `countAll()` - Đếm tổng số user

### **2. DomainRepository.php**

```php
/**
 * DomainRepository - Quản lý tất cả thao tác với bảng ListDomain
 *
 * CHỨC NĂNG CHÍNH:
 * - Quản lý danh sách tên miền có sẵn
 * - Cập nhật giá bán và hình ảnh
 * - Thêm/xóa loại tên miền
 * - Tìm kiếm thông tin tên miền
 */
```

**Các method chính:**

- `listAll()` - Lấy danh sách tất cả domain
- `findById()` - Tìm domain theo ID
- `create()` - Tạo domain mới
- `updateById()` - Cập nhật domain
- `deleteById()` - Xóa domain
- `findByDomainName()` - Tìm domain theo tên

### **3. HistoryRepository.php**

```php
/**
 * HistoryRepository - Quản lý lịch sử giao dịch
 *
 * CHỨC NĂNG CHÍNH:
 * - Quản lý lịch sử mua domain
 * - Theo dõi trạng thái giao dịch
 * - Cập nhật DNS cho domain
 * - Thống kê giao dịch theo user
 */
```

**Các method chính:**

- `insertPurchase()` - Thêm giao dịch mua domain
- `listByUserId()` - Lấy lịch sử theo user
- `updateDns()` - Cập nhật DNS
- `countByUserAndStatus()` - Đếm giao dịch theo trạng thái
- `listRecentByUser()` - Lấy giao dịch gần đây

### **4. CardRepository.php**

```php
/**
 * CardRepository - Quản lý thẻ cào
 *
 * CHỨC NĂNG CHÍNH:
 * - Quản lý lịch sử nạp thẻ
 * - Theo dõi trạng thái thẻ
 * - Tích hợp API CardVIP
 * - Xử lý callback từ API
 */
```

**Các method chính:**

- `insertCard()` - Thêm thẻ cào
- `updateStatus()` - Cập nhật trạng thái thẻ
- `findByRequestId()` - Tìm thẻ theo request ID
- `listByUserId()` - Lấy lịch sử thẻ theo user

### **5. SettingsRepository.php**

```php
/**
 * SettingsRepository - Quản lý cài đặt hệ thống
 *
 * CHỨC NĂNG CHÍNH:
 * - Quản lý cài đặt website
 * - Cập nhật thông tin hệ thống
 * - Quản lý API keys
 * - Cài đặt theme và giao diện
 */
```

**Các method chính:**

- `getOne()` - Lấy cài đặt hiện tại
- `update()` - Cập nhật cài đặt
- `getApiKey()` - Lấy API key
- `updateTheme()` - Cập nhật theme

---

## 🛠️ **UTILS - TIỆN ÍCH HỖ TRỢ**

### **1. ResponseHelper.php**

```php
/**
 * ResponseHelper - Utility class xử lý response và output
 *
 * CHỨC NĂNG CHÍNH:
 * - Gửi JSON response cho API/AJAX
 * - Tạo success/error response chuẩn
 * - Redirect với flash messages
 * - Tạo toastr notifications
 * - Xử lý CORS headers
 * - Quản lý flash messages
 *
 * PATTERN: Utility/Helper Pattern - Cung cấp các hàm tiện ích
 * USAGE: Static methods - Gọi trực tiếp không cần khởi tạo
 */
```

**Các method chính:**

- `json()` - Gửi JSON response
- `success()` - Tạo success response
- `error()` - Tạo error response
- `redirect()` - Redirect với message
- `setCORS()` - Thiết lập CORS headers
- `toastrSuccess()` - Tạo toastr success
- `toastrError()` - Tạo toastr error

### **2. ValidationHelper.php**

```php
/**
 * ValidationHelper - Utility class xử lý validation
 *
 * CHỨC NĂNG CHÍNH:
 * - Validate dữ liệu đầu vào
 * - Kiểm tra format email, username
 * - Validate password strength
 * - Kiểm tra dữ liệu domain
 * - Validate thông tin thẻ cào
 *
 * PATTERN: Utility/Helper Pattern
 * USAGE: Static methods
 */
```

**Các method chính:**

- `validateLogin()` - Validate dữ liệu đăng nhập
- `validateRegister()` - Validate dữ liệu đăng ký
- `validateEmail()` - Validate email format
- `validateUsername()` - Validate username format
- `validatePassword()` - Validate password strength
- `validateDomain()` - Validate thông tin domain
- `validateCard()` - Validate thông tin thẻ cào

---

## 🚦 **CORE - HỆ THỐNG CORE**

### **1. Router.php**

```php
/**
 * Router - Hệ thống routing tập trung cho toàn bộ ứng dụng
 *
 * CHỨC NĂNG CHÍNH:
 * - Phân tích URL request và chuyển hướng đến controller phù hợp
 * - Quản lý routes cho frontend, admin, và AJAX
 * - Hỗ trợ cả legacy routes và modern routes
 * - Xử lý 404 errors và error handling
 * - Dynamic route matching với parameters
 *
 * ROUTES HỖ TRỢ:
 * - Frontend routes: /, /profile, /auth/login, /Checkout, etc.
 * - Admin routes: /admin, /admin/products, /admin/members, etc.
 * - AJAX routes: /Ajaxs/login.php, /Ajaxs/register.php, etc.
 * - Legacy routes: /Pages/login.php, /Adminstators/index.php, etc.
 *
 * PATTERN: Front Controller Pattern - Tất cả requests đi qua Router
 */
```

**Các method chính:**

- `route()` - Xử lý routing chính
- `initializeRoutes()` - Khởi tạo danh sách routes
- `findMatchingRoute()` - Tìm route phù hợp
- `executeRoute()` - Thực thi route
- `handle404()` - Xử lý 404 error
- `handleError()` - Xử lý lỗi routing

---

## 🔧 **CONFIG - CẤU HÌNH HỆ THỐNG**

### **1. Database.php**

```php
/**
 * Database.php - Cấu hình database và khởi tạo hệ thống
 *
 * CHỨC NĂNG CHÍNH:
 * - Khởi tạo session
 * - Kết nối database
 * - Khởi tạo ErrorHandler
 * - Load các repositories
 * - Khởi tạo biến global
 * - Cấu hình timezone
 */
```

### **2. DatabaseConnection.php**

```php
/**
 * DatabaseConnection - Singleton pattern cho database connection
 *
 * CHỨC NĂNG CHÍNH:
 * - Quản lý kết nối database duy nhất
 * - Singleton pattern để tránh multiple connections
 * - Cấu hình database connection
 * - Error handling cho connection
 */
```

### **3. ErrorHandler.php**

```php
/**
 * ErrorHandler - Xử lý lỗi và exception toàn diện
 *
 * CHỨC NĂNG CHÍNH:
 * - Xử lý PHP errors và exceptions
 * - Ghi log lỗi vào file
 * - Hiển thị lỗi cho development/production
 * - Logging system với các level khác nhau
 * - Singleton pattern
 */
```

---

## 📊 **TỔNG KẾT KIẾN TRÚC**

### **🎯 PATTERNS ĐƯỢC SỬ DỤNG:**

1. **MVC Pattern** - Model-View-Controller
2. **Repository Pattern** - Tách biệt database logic
3. **Singleton Pattern** - DatabaseConnection, ErrorHandler
4. **Factory Pattern** - RepositoryFactory
5. **Front Controller Pattern** - Router
6. **Utility/Helper Pattern** - ResponseHelper, ValidationHelper

### **🔒 BẢO MẬT:**

- Prepared statements chống SQL injection
- Rate limiting chống brute force
- Session management
- Input validation
- CORS headers
- Error handling tập trung

### **📈 HIỆU SUẤT:**

- Singleton database connection
- Repository pattern giảm code duplication
- Centralized routing
- Efficient error handling
- Logging system

### **🛠️ BẢO TRÌ:**

- Code được comment chi tiết
- Tách biệt concerns rõ ràng
- Modular architecture
- Easy to extend và modify

---

**Tác giả:** DAM THANH VU  
**Ngày tạo:** 2024  
**Phiên bản:** 1.0
