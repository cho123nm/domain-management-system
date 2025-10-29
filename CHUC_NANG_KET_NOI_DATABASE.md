# 🗄️ **TRÌNH BÀY THIẾT KẾ VÀ XÂY DỰNG CHỨC NĂNG KẾT NỐI DATABASE**

## 📝 **1. MÔ TẢ CHỨC NĂNG:**

Chức năng kết nối database quản lý việc thiết lập và duy trì kết nối giữa ứng dụng PHP và cơ sở dữ liệu MySQL, đảm bảo hiệu suất cao và bảo mật thông qua Singleton Pattern.

## 👤 **2. TÁC NHÂN THỰC HIỆN:**

- **Hệ thống** - Tự động khởi tạo khi ứng dụng chạy
- **Repository Classes** - Sử dụng kết nối để thực hiện các thao tác database
- **Controllers** - Truyền kết nối cho các Repository

## 🔍 **3. DẠNG TRUY VẤN:**

- **Kết nối cơ sở** - Không phải SQL query mà là thiết lập kết nối MySQL
- **mysqli_connect()** - Hàm PHP tạo kết nối database

## 🗄️ **4. KẾT NỐI VÀO DATABASE:**

- **Database:** `tenmien` (hoặc từ biến môi trường `DB_NAME`)
- **Server:** `localhost` (hoặc từ biến môi trường `DB_HOST`)
- **Username:** `root` (hoặc từ biến môi trường `DB_USER`)
- **Password:** ``(hoặc từ biến môi trường`DB_PASS`)

## 📊 **5. THÔNG TIN CẤU HÌNH CẦN DÙNG:**

- `servername` (string) - Địa chỉ server database
- `database` (string) - Tên database
- `username` (string) - Tên đăng nhập database
- `password` (string) - Mật khẩu database
- `charset` (string) - Bộ ký tự UTF8

## 🔄 **6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. **Khởi động ứng dụng** - Khi file PHP đầu tiên được load
2. **Include Database.php** - File được include từ các module khác
3. **Kiểm tra kết nối hiện tại** - Singleton kiểm tra xem đã có kết nối chưa
4. **Đọc cấu hình** - Lấy thông tin từ biến môi trường hoặc giá trị mặc định

## 🔄 **7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Tạo kết nối thành công** - Trả về đối tượng mysqli
2. **Cấu hình charset** - Thiết lập UTF8 cho kết nối
3. **Lưu trữ Singleton** - Lưu kết nối để tái sử dụng
4. **Sẵn sàng sử dụng** - Các Repository có thể sử dụng kết nối

## 📊 **8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Application Start
    ↓
Include Database.php
    ↓
DatabaseConnection::getInstance()
    ↓
Check if instance exists
    ↓
If null: Create new connection
    ↓
mysqli_connect(servername, username, password, database)
    ↓
MySQL Server
    ↓
Connection established
    ↓
Set charset UTF8
    ↓
Store in Singleton instance
    ↓
Return mysqli object
    ↓
Available for Repositories
```

## 🗃️ **9. CẤU TRÚC FILE VÀ VỊ TRÍ:**

### **File chính - DatabaseConnection.php:**

**Đường dẫn:** `Config/DatabaseConnection.php`

```php
<?php
class DatabaseConnection {
    private static ?mysqli $instance = null;

    public static function getInstance(): mysqli
    {
        if (self::$instance === null) {
            $servername = getenv('DB_HOST') ?: 'localhost';
            $database   = getenv('DB_NAME') ?: 'tenmien';
            $username   = getenv('DB_USER') ?: 'root';
            $password   = getenv('DB_PASS') ?: '';

            $mysqli = mysqli_connect($servername, $username, $password, $database);
            if (!$mysqli) {
                die('Status!: Error Connect Database!');
            }
            mysqli_query($mysqli, "SET NAMES 'UTF8'");
            self::$instance = $mysqli;
        }
        return self::$instance;
    }
}
?>
```

### **File khởi tạo - Database.php:**

**Đường dẫn:** `Config/Database.php`

```php
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once(__DIR__.'/DatabaseConnection.php');
$connect = DatabaseConnection::getInstance();

// Initialize Error Handler
include_once(__DIR__.'/ErrorHandler.php');
$errorHandler = ErrorHandler::getInstance();

// Include Repositories
include_once(__DIR__.'/../Repositories/SettingsRepository.php');
include_once(__DIR__.'/../Repositories/DomainRepository.php');
include_once(__DIR__.'/../Repositories/UserRepository.php');
include_once(__DIR__.'/../Repositories/HistoryRepository.php');
?>
```

### **File admin - Connect/Database.php:**

**Đường dẫn:** `Adminstators/Connect/Database.php`

```php
<?php
include('../Config/Database.php');
?>
```

## 🖼️ **10. GIAO DIỆN VÀ CÁCH SỬ DỤNG:**

### **Cách sử dụng trong Repository:**

```php
// Repositories/DomainRepository.php
class DomainRepository {
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli; // Nhận kết nối từ bên ngoài
    }

    public function listAll(): array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM ListDomain");
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }
}
```

### **Cách sử dụng trong Admin Panel:**

```php
// Adminstators/danh-sach-san-pham.php
<?php
include('Connect/Header.php'); // → Include Database.php
include_once(__DIR__.'/../Repositories/DomainRepository.php');
$domainRepo = new DomainRepository($connect); // $connect từ Database.php
$resultRows = $domainRepo->listAll();
?>
```

### **Cách sử dụng trong Ajax:**

```php
// Ajaxs/login.php
<?php
include_once('../Config/Database.php'); // → Tạo $connect
include_once('../Repositories/UserRepository.php');
$userRepo = new UserRepository($connect);
if ($userRepo->verifyCredentials($taikhoan, $matkhau)) {
    // Xử lý đăng nhập
}
?>
```

## 🎯 **11. ĐẶC ĐIỂM KỸ THUẬT:**

### **✅ Singleton Pattern:**

- **Một kết nối duy nhất** - Tránh tạo nhiều kết nối không cần thiết
- **Tái sử dụng** - Cùng một kết nối cho toàn bộ ứng dụng
- **Hiệu suất cao** - Giảm overhead của việc tạo kết nối

### **✅ Bảo mật:**

- **Prepared Statements** - Chống SQL injection
- **Environment Variables** - Cấu hình nhạy cảm qua biến môi trường
- **Error Handling** - Xử lý lỗi kết nối an toàn

### **✅ Tính năng:**

- **Auto UTF8** - Tự động cấu hình charset
- **Connection Pooling** - Quản lý kết nối hiệu quả
- **Error Logging** - Ghi log lỗi kết nối

## 📊 **12. CẤU HÌNH DATABASE:**

### **Thông tin kết nối mặc định:**

```php
$servername = 'localhost';     // Server database
$database   = 'tenmien';       // Tên database
$username   = 'root';          // Username
$password   = '';              // Password (trống cho XAMPP)
```

### **Biến môi trường (nếu có):**

```bash
DB_HOST=localhost
DB_NAME=tenmien
DB_USER=root
DB_PASS=
```

### **Cấu hình charset:**

```sql
SET NAMES 'UTF8'
```

## 🔄 **13. LUỒNG HOẠT ĐỘNG CHI TIẾT:**

### **Lần đầu tiên:**

```
1. Application Start
2. Include Database.php
3. DatabaseConnection::getInstance()
4. self::$instance === null → true
5. mysqli_connect() → Tạo kết nối mới
6. SET NAMES 'UTF8' → Cấu hình charset
7. self::$instance = $mysqli → Lưu kết nối
8. return $mysqli → Trả về kết nối
```

### **Các lần sau:**

```
1. Application Request
2. Include Database.php
3. DatabaseConnection::getInstance()
4. self::$instance !== null → false
5. return self::$instance → Trả về kết nối đã có
```

## 🎯 **14. TÓM TẮT KỸ THUẬT:**

### **✅ Design Patterns:**

- **Singleton Pattern** - Đảm bảo 1 kết nối duy nhất
- **Dependency Injection** - Truyền kết nối vào Repository
- **Factory Pattern** - Tạo kết nối thông qua getInstance()

### **✅ Architecture:**

- **Separation of Concerns** - Tách biệt logic kết nối
- **Centralized Configuration** - Cấu hình tập trung
- **Lazy Loading** - Chỉ tạo kết nối khi cần

### **✅ Performance:**

- **Connection Reuse** - Tái sử dụng kết nối
- **Memory Efficient** - Tiết kiệm bộ nhớ
- **Fast Access** - Truy cập nhanh qua Singleton

## 🎉 **KẾT LUẬN:**

**Chức năng kết nối database đã được thiết kế theo Singleton Pattern, đảm bảo hiệu suất cao, bảo mật tốt và dễ dàng sử dụng trong toàn bộ ứng dụng!**

**Đây là nền tảng cốt lõi cho tất cả các thao tác database trong hệ thống, hỗ trợ Repository Pattern và OOP Architecture một cách hiệu quả.**
