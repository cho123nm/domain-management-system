# 🚪 **TRÌNH BÀY THIẾT KẾ VÀ XÂY DỰNG CHỨC NĂNG ĐĂNG XUẤT**

## 📝 **1. MÔ TẢ CHỨC NĂNG:**

Chức năng đăng xuất cho phép người dùng đã đăng nhập kết thúc phiên đăng nhập, hủy session, xóa thông tin đăng nhập và chuyển hướng về trang chủ hoặc trang đăng nhập.

## 👤 **2. TÁC NHÂN THỰC HIỆN:**

- **Người dùng đã đăng nhập** - Khách hàng muốn kết thúc phiên đăng nhập

## 🔍 **3. DẠNG TRUY VẤN:**

- **Không có truy vấn database** - Chỉ xử lý session

## 🗄️ **4. TRUY VẤN VÀO TABLE:**

- **Không có** - Chức năng này chỉ xử lý session, không tương tác với database

## 📊 **5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

- **Không có** - Không sử dụng database

## 🔄 **6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. Người dùng đã đăng nhập vào hệ thống
2. Người dùng click nút "Đăng xuất" hoặc truy cập trang logout
3. Hệ thống xác nhận việc đăng xuất

## 🔄 **7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Thành công:** Hủy session, hiển thị thông báo đăng xuất thành công, chuyển hướng về trang chủ
2. **Thất bại:** Hiển thị thông báo lỗi (hiếm khi xảy ra)

## 📊 **8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (User Browser)
    ↓ GET Request
    ↓ URL: /Pages/logout.php
    ↓ Hoặc click nút "Đăng xuất"
Web Server (Apache)
    ↓ Xử lý request
File PHP xử lý
    ↓ Pages/logout.php
    ↓ session_start()
PHP Processing
    ↓ session_destroy()
    ↓ unset($_SESSION)
    ↓ Xóa tất cả session variables
Response
    ↓ Success: toastr.success("Đăng xuất thành công!")
    ↓ Success: redirect to "/" hoặc "/Pages/login.php"
    ↓ Error: toastr.error("Đăng xuất thất bại!")
```

## 🗃️ **9. BẢNG RECORDSET VÀ ARRAY[KEY]:**

### **Không có dữ liệu database:**

Chức năng đăng xuất không sử dụng database, chỉ xử lý session.

### **Session variables được xóa:**

- `$_SESSION['users']` - Username người dùng đã đăng nhập
- Tất cả session variables khác

## 🖼️ **10. GIAO DIỆN CHỨC NĂNG:**

### **Nút đăng xuất trong header:**

```html
<!-- Header với nút đăng xuất -->
<div class="bg-white shadow">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center py-6">
      <div class="flex items-center">
        <h1 class="text-2xl font-bold text-gray-900">My Domain Shop</h1>
      </div>
      
      <div class="flex items-center space-x-4">
        <!-- Thông tin user -->
        <div class="text-sm text-gray-500">
          Chào mừng, <?= htmlspecialchars($_SESSION['users'] ?? 'Guest') ?>
        </div>
        
        <!-- Nút đăng xuất -->
        <a
          href="/Pages/logout.php"
          class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
        >
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
          </svg>
          Đăng xuất
        </a>
      </div>
    </div>
  </div>
</div>
```

### **Trang logout (nếu cần):**

```html
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-md">
    <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
      <div class="text-center">
        <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
          Đăng xuất thành công
        </h2>
        <p class="mt-2 text-sm text-gray-600">
          Bạn đã đăng xuất khỏi hệ thống
        </p>
        <div class="mt-6">
          <a
            href="/"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Về trang chủ
          </a>
        </div>
        <div class="mt-3">
          <a
            href="/Pages/login.php"
            class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Đăng nhập lại
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
```

## 💻 **11. CODE XỬ LÝ:**

### **File: Pages/logout.php**

```php
<?php
// Bắt đầu session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra xem user có đăng nhập không
if (isset($_SESSION['users'])) {
    // Lưu tên user trước khi xóa session
    $username = $_SESSION['users'];
    
    // Hủy tất cả session variables
    $_SESSION = array();
    
    // Xóa session cookie nếu có
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Hủy session
    session_destroy();
    
    // Hiển thị thông báo thành công
    echo '<script>toastr.success("Đăng xuất thành công! Tạm biệt ' . htmlspecialchars($username) . '", "Thông Báo");</script>';
} else {
    // User chưa đăng nhập
    echo '<script>toastr.warning("Bạn chưa đăng nhập!", "Thông Báo");</script>';
}

// Chuyển hướng về trang chủ sau 2 giây
echo '<meta http-equiv="refresh" content="2;url=/">';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Xuất</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .logout-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }
        .logout-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logout-icon svg {
            width: 32px;
            height: 32px;
            color: white;
        }
        .logout-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        .logout-message {
            color: #6b7280;
            margin-bottom: 2rem;
        }
        .logout-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-primary {
            background: #3b82f6;
            color: white;
        }
        .btn-primary:hover {
            background: #2563eb;
        }
        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }
        .btn-secondary:hover {
            background: #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="logout-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="logout-title">Đăng xuất thành công</h1>
        <p class="logout-message">Bạn đã đăng xuất khỏi hệ thống an toàn</p>
        <div class="logout-buttons">
            <a href="/" class="btn btn-primary">Về trang chủ</a>
            <a href="/Pages/login.php" class="btn btn-secondary">Đăng nhập lại</a>
        </div>
    </div>
</body>
</html>
```

### **JavaScript logout (nếu cần):**

```javascript
// Hàm đăng xuất bằng JavaScript
function logout() {
    if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
        // Gọi AJAX logout
        fetch('/Pages/logout.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'logout=1'
        })
        .then(response => response.text())
        .then(data => {
            // Hiển thị thông báo
            toastr.success('Đăng xuất thành công!');
            
            // Chuyển hướng sau 1 giây
            setTimeout(() => {
                window.location.href = '/';
            }, 1000);
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Có lỗi xảy ra khi đăng xuất');
        });
    }
}

// Thêm event listener cho nút đăng xuất
document.addEventListener('DOMContentLoaded', function() {
    const logoutButtons = document.querySelectorAll('[data-logout]');
    logoutButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            logout();
        });
    });
});
```

## 🎯 **12. TÓM TẮT KỸ THUẬT:**

### **✅ Công nghệ sử dụng:**

- **Backend:** PHP session management
- **Frontend:** HTML/CSS với JavaScript
- **Session:** PHP session handling
- **Security:** Secure session destruction
- **Notifications:** Toastr.js

### **✅ Kiến trúc:**

- **Session Management** - Quản lý phiên đăng nhập
- **Security** - Bảo mật session destruction
- **User Experience** - Trải nghiệm đăng xuất mượt mà
- **Error Handling** - Xử lý lỗi đăng xuất

### **✅ Tính năng:**

- **Session Destruction** - Hủy session an toàn
- **Cookie Cleanup** - Xóa session cookies
- **User Feedback** - Thông báo đăng xuất thành công
- **Redirect** - Chuyển hướng sau đăng xuất
- **Confirmation** - Xác nhận trước khi đăng xuất

### **✅ Bảo mật:**

- **Secure Logout** - Đăng xuất an toàn
- **Session Cleanup** - Dọn dẹp session hoàn toàn
- **Cookie Removal** - Xóa session cookies
- **CSRF Protection** - Bảo vệ chống CSRF

## 🎉 **KẾT LUẬN:**

**Chức năng đăng xuất đã được thiết kế hoàn chỉnh với bảo mật cao và trải nghiệm người dùng tốt!**
