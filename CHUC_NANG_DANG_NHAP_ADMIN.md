# 🔐 **TRÌNH BÀY THIẾT KẾ VÀ XÂY DỰNG CHỨC NĂNG ĐĂNG NHẬP ADMIN**

## 📝 **1. MÔ TẢ CHỨC NĂNG:**

Chức năng đăng nhập admin sử dụng **HTTP Basic Authentication** của Apache để bảo vệ toàn bộ thư mục admin. Khi người dùng truy cập vào bất kỳ trang admin nào, Apache sẽ hiển thị popup đăng nhập của trình duyệt để xác thực danh tính trước khi cho phép truy cập.

## 👨‍💼 **2. TÁC NHÂN THỰC HIỆN:**

- **Quản trị viên (Admin)** - Người có quyền quản lý hệ thống
- **Hệ thống Apache** - Xử lý xác thực HTTP Basic Authentication

## 🔍 **3. DẠNG TRUY VẤN:**

- **KHÔNG CÓ TRUY VẤN DATABASE** - Sử dụng HTTP Basic Authentication của Apache
- **File-based Authentication** - Xác thực qua file `.htpasswd`

## 🗄️ **4. TRUY VẤN VÀO TABLE:**

- **KHÔNG TRUY VẤN TABLE** - Không sử dụng database MySQL
- **File System** - Đọc thông tin từ file `.htpasswd`

## 📊 **5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

- **KHÔNG SỬ DỤNG TABLE** - Thông tin lưu trong file `.htpasswd`:
  - `username` - Tên đăng nhập admin
  - `password_hash` - Mật khẩu đã mã hóa Apache MD5

## 🔄 **6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. **Admin truy cập URL admin** (ví dụ: `/Adminstators/index.php`)
2. **Apache kiểm tra file `.htaccess`** trong thư mục Adminstators
3. **Apache phát hiện yêu cầu xác thực** từ cấu hình `.htaccess`
4. **Apache hiển thị popup đăng nhập** của trình duyệt
5. **Admin nhập username và password** vào popup

## 🔄 **7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Thành công:** Apache cho phép truy cập trang admin, hiển thị nội dung
2. **Thất bại:** Apache hiển thị lỗi 401 Unauthorized, yêu cầu đăng nhập lại
3. **Hủy bỏ:** Quay lại trang trước hoặc hiển thị lỗi 401

## 📊 **8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (Admin Browser)
    ↓ GET Request
    ↓ URL: /Adminstators/index.php
    ↓ Không có thông tin xác thực
Web Server (Apache)
    ↓ Kiểm tra file .htaccess
    ↓ Phát hiện yêu cầu AuthType Basic
    ↓ Trả về HTTP 401 + WWW-Authenticate header
Client (Admin Browser)
    ↓ Hiển thị popup đăng nhập
    ↓ User nhập: username="admin", password="admin123"
    ↓ Gửi lại request với Authorization header
Web Server (Apache)
    ↓ Đọc file .htpasswd
    ↓ So sánh hash password
    ↓ Kiểm tra username
File System (.htpasswd)
    ↓ admin:$apr1$Yev3/4V/$MEbkzdN2/.1h1S0UTu2ln0
    ↓ Trả về: true/false
Response
    ↓ Success: HTTP 200 + Hiển thị trang admin
    ↓ Error: HTTP 401 + Yêu cầu đăng nhập lại
```

## 🗃️ **9. BẢNG RECORDSET VÀ ARRAY[KEY]:**

### **Dữ liệu trong file .htpasswd:**

```
admin:$apr1$Yev3/4V/$MEbkzdN2/.1h1S0UTu2ln0
```

### **Cấu trúc file .htaccess:**

```
AuthType Basic
AuthName "Protected Admin Area"
AuthUserFile "C:/xampp/htdocs/Adminstators/.htpasswd"
Require valid-user
```

### **Headers HTTP được sử dụng:**

- `WWW-Authenticate: Basic realm="Protected Admin Area"`
- `Authorization: Basic YWRtaW46YWRtaW4xMjM=` (base64 encoded)

## 🖼️ **10. GIAO DIỆN CHỨC NĂNG:**

### **Popup đăng nhập của trình duyệt:**

```
┌─────────────────────────────────────┐
│  🔐 Protected Admin Area            │
├─────────────────────────────────────┤
│  Username: [admin            ]      │
│  Password: [••••••••••••••••]      │
│                                     │
│  [Cancel]              [OK]         │
└─────────────────────────────────────┘
```

### **Giao diện sau khi đăng nhập thành công:**

```html
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body>
    <div class="min-h-screen bg-gray-50">
      <!-- Header -->
      <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center py-6">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
            <div class="text-sm text-gray-500">Chào mừng, Admin</div>
          </div>
        </div>
      </div>

      <!-- Navigation Menu -->
      <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex space-x-8">
            <a
              href="index.php"
              class="border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600"
            >
              Dashboard
            </a>
            <a
              href="danh-sach-san-pham.php"
              class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700"
            >
              Sản phẩm
            </a>
            <a
              href="quan-ly-thanh-vien.php"
              class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700"
            >
              Thành viên
            </a>
            <a
              href="duyet-don-hang.php"
              class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700"
            >
              Đơn hàng
            </a>
          </div>
        </div>
      </nav>

      <!-- Main Content -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <!-- Stats Cards -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div
                    class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center"
                  >
                    <svg
                      class="w-5 h-5 text-white"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"
                      ></path>
                    </svg>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                      Doanh thu hôm nay
                    </dt>
                    <dd class="text-lg font-medium text-gray-900">
                      1,250,000đ
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>

          <!-- More stats cards... -->
        </div>
      </div>
    </div>
  </body>
</html>
```

## 💻 **11. CODE XỬ LÝ:**

### **File: Adminstators/.htaccess**

```apache
AuthType Basic
AuthName "Protected Admin Area"
AuthUserFile "C:/xampp/htdocs/Adminstators/.htpasswd"
Require valid-user
```

### **File: Adminstators/.htpasswd**

```
admin:$apr1$Yev3/4V/$MEbkzdN2/.1h1S0UTu2ln0
```

### **Cách tạo file .htpasswd:**

```bash
# Sử dụng htpasswd command
htpasswd -c .htpasswd admin

# Hoặc tạo thủ công với MD5 hash
# Username: admin
# Password: admin123
# Hash: $apr1$Yev3/4V/$MEbkzdN2/.1h1S0UTu2ln0
```

### **File: Adminstators/index.php (sau khi đăng nhập thành công)**

```php
<?php
// File này chỉ được truy cập sau khi HTTP Basic Auth thành công
include_once('../Config/Database.php');
include_once('../Repositories/RepositoryFactory.php');

// Tạo repositories
$cardRepo = RepositoryFactory::createCardRepository($connect);
$historyRepo = RepositoryFactory::createHistoryRepository($connect);
$userRepo = RepositoryFactory::createUserRepository($connect);

// Lấy thống kê
$todayRevenue = $cardRepo->getTodayRevenue();
$pendingOrders = $historyRepo->getPendingOrdersCount();
$totalUsers = $userRepo->getTotalUsersCount();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <!-- Dashboard content -->
    <div class="min-h-screen bg-gray-50">
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
                    <div class="text-sm text-gray-500">
                        Chào mừng, <?= $_SERVER['PHP_AUTH_USER'] ?? 'Admin' ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Doanh thu hôm nay
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        <?= number_format($todayRevenue) ?>đ
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Đơn hàng chờ xử lý
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        <?= $pendingOrders ?>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Tổng thành viên
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        <?= $totalUsers ?>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
```

## 🎯 **12. TÓM TẮT KỸ THUẬT:**

### **✅ Công nghệ sử dụng:**

- **Web Server:** Apache HTTP Server
- **Authentication:** HTTP Basic Authentication
- **Password Hashing:** Apache MD5 ($apr1$)
- **File System:** .htaccess + .htpasswd
- **Frontend:** HTML + Tailwind CSS

### **✅ Kiến trúc:**

- **Server-level Security** - Bảo mật cấp web server
- **File-based Authentication** - Không cần database
- **Stateless Authentication** - Mỗi request đều xác thực
- **Browser Native UI** - Sử dụng popup đăng nhập của trình duyệt

### **✅ Bảo mật:**

- **HTTP Basic Auth** - Chuẩn RFC 7617
- **Password Hashing** - Apache MD5 với salt
- **Server Protection** - Bảo vệ toàn bộ thư mục admin
- **No Session Management** - Không cần quản lý session

### **✅ Ưu điểm:**

- **Đơn giản** - Không cần code PHP phức tạp
- **Bảo mật cao** - Xác thực cấp server
- **Hiệu suất tốt** - Không truy vấn database
- **Dễ cấu hình** - Chỉ cần 2 file .htaccess và .htpasswd

### **✅ Nhược điểm:**

- **Không linh hoạt** - Khó thay đổi giao diện đăng nhập
- **Không có logout** - Phải đóng trình duyệt
- **Password truyền plaintext** - Cần HTTPS trong production
- **Không có remember me** - Phải nhập lại mỗi lần

## 🎬 **13. DEMO CHỨC NĂNG:**

### **Bước 1: Truy cập trang admin**

```
URL: http://localhost/Adminstators/index.php
```

### **Bước 2: Popup đăng nhập xuất hiện**

```
┌─────────────────────────────────────┐
│  🔐 Protected Admin Area            │
├─────────────────────────────────────┤
│  Username: [admin            ]      │
│  Password: [••••••••••••••••]      │
│                                     │
│  [Cancel]              [OK]         │
└─────────────────────────────────────┘
```

### **Bước 3: Nhập thông tin đăng nhập**

- **Username:** `admin`
- **Password:** `admin123`

### **Bước 4: Kết quả**

- **Thành công:** Hiển thị dashboard admin với thống kê
- **Thất bại:** Hiển thị lỗi 401 Unauthorized

### **Bước 5: Truy cập các trang admin khác**

- Tất cả trang trong thư mục `/Adminstators/` đều được bảo vệ
- Không cần đăng nhập lại trong cùng session trình duyệt

## 🎉 **KẾT LUẬN:**

**Chức năng đăng nhập admin đã được thiết kế hoàn chỉnh với HTTP Basic Authentication, bảo mật cao và dễ triển khai!**

**Đặc điểm nổi bật:**

- ✅ **Bảo mật cấp server** - Apache xử lý xác thực
- ✅ **Không cần database** - File-based authentication
- ✅ **Đơn giản triển khai** - Chỉ cần 2 file cấu hình
- ✅ **Bảo vệ toàn bộ** - Tất cả trang admin được bảo vệ
- ✅ **Giao diện native** - Sử dụng popup trình duyệt
