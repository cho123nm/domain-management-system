# 📚 **HƯỚNG DẪN CHI TIẾT USERREPOSITORY**

## 🎯 **TỔNG QUAN**

**UserRepository** là class quản lý tất cả thao tác với bảng `Users` trong database. Đây là Repository Pattern được thiết kế để tách biệt logic database khỏi business logic.

### **🔧 Design Pattern:**

- **Repository Pattern** - Tách biệt database logic
- **Prepared Statements** - Chống SQL injection
- **Single Responsibility** - Mỗi method có 1 chức năng cụ thể

---

## 📋 **CHI TIẾT TỪNG HÀM**

### **1. Constructor**

```php
public function __construct(mysqli $mysqli)
```

**Chức năng:** Khởi tạo UserRepository với kết nối database
**Tham số:** `mysqli $mysqli` - Kết nối database
**Logic:** Lưu kết nối database vào thuộc tính private để sử dụng trong các method khác

---

### **2. findByUsername()**

```php
public function findByUsername(string $username): ?array
```

**Chức năng:** Tìm kiếm người dùng theo tên đăng nhập
**Tham số:** `string $username` - Tên đăng nhập cần tìm
**Trả về:** `array|null` - Thông tin user hoặc null nếu không tìm thấy

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT * FROM Users WHERE taikhoan = ? LIMIT 1`
2. Bind tham số `$username` vào câu query
3. Thực thi query
4. Lấy kết quả và trả về array hoặc null

**Ví dụ sử dụng:**

```php
$userRepo = new UserRepository($connect);
$user = $userRepo->findByUsername('admin');
if ($user) {
    echo "Tìm thấy user: " . $user['email'];
}
```

---

### **3. verifyCredentials()**

```php
public function verifyCredentials(string $username, string $password): bool
```

**Chức năng:** Xác thực thông tin đăng nhập
**Tham số:**

- `string $username` - Tên đăng nhập
- `string $password` - Mật khẩu (sẽ được mã hóa MD5)
  **Trả về:** `bool` - True nếu đăng nhập thành công, false nếu thất bại

**Logic xử lý:**

1. Mã hóa mật khẩu bằng MD5: `md5($password)`
2. Chuẩn bị prepared statement: `SELECT id FROM Users WHERE taikhoan = ? AND matkhau = ? LIMIT 1`
3. Bind tham số username và password đã mã hóa
4. Thực thi query
5. Kiểm tra kết quả: có dữ liệu = true, không có = false

**Ví dụ sử dụng:**

```php
if ($userRepo->verifyCredentials('admin', '123456')) {
    echo "Đăng nhập thành công";
} else {
    echo "Sai thông tin đăng nhập";
}
```

---

### **4. listAll()**

```php
public function listAll(): array
```

**Chức năng:** Lấy danh sách tất cả người dùng
**Trả về:** `array` - Danh sách tất cả user

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT * FROM Users`
2. Thực thi query
3. Lấy tất cả kết quả dưới dạng associative array
4. Trả về mảng chứa tất cả user

**Ví dụ sử dụng:**

```php
$allUsers = $userRepo->listAll();
foreach ($allUsers as $user) {
    echo $user['taikhoan'] . " - " . $user['email'];
}
```

---

### **5. countAll()**

```php
public function countAll(): int
```

**Chức năng:** Đếm tổng số người dùng
**Trả về:** `int` - Số lượng user

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT COUNT(*) as c FROM Users`
2. Thực thi query
3. Lấy kết quả count và convert sang int
4. Trả về số lượng user

**Ví dụ sử dụng:**

```php
$totalUsers = $userRepo->countAll();
echo "Tổng số user: " . $totalUsers;
```

---

### **6. updateBalance()**

```php
public function updateBalance(int $userId, int $amount): bool
```

**Chức năng:** Cập nhật số dư tài khoản (set giá trị tuyệt đối)
**Tham số:**

- `int $userId` - ID người dùng
- `int $amount` - Số tiền mới
  **Trả về:** `bool` - True nếu cập nhật thành công

**Logic xử lý:**

1. Chuẩn bị prepared statement: `UPDATE Users SET tien = ? WHERE id = ?`
2. Bind tham số userId và amount
3. Thực thi query
4. Trả về kết quả thành công/thất bại

**Ví dụ sử dụng:**

```php
if ($userRepo->updateBalance(1, 100000)) {
    echo "Cập nhật số dư thành công";
}
```

---

### **7. incrementBalance()**

```php
public function incrementBalance(int $userId, int $delta): bool
```

**Chức năng:** Tăng số dư tài khoản (cộng thêm)
**Tham số:**

- `int $userId` - ID người dùng
- `int $delta` - Số tiền cần cộng thêm
  **Trả về:** `bool` - True nếu cập nhật thành công

**Logic xử lý:**

1. Chuẩn bị prepared statement: `UPDATE Users SET tien = tien + ? WHERE id = ?`
2. Bind tham số userId và delta
3. Thực thi query (cộng thêm vào số dư hiện tại)
4. Trả về kết quả thành công/thất bại

**Ví dụ sử dụng:**

```php
// Cộng thêm 50,000 vào số dư user ID = 1
if ($userRepo->incrementBalance(1, 50000)) {
    echo "Nạp tiền thành công";
}
```

---

### **8. createUser()**

```php
public function createUser(string $username, string $passwordMd5, string $email, string $time): bool
```

**Chức năng:** Tạo tài khoản người dùng mới
**Tham số:**

- `string $username` - Tên đăng nhập
- `string $passwordMd5` - Mật khẩu đã mã hóa MD5
- `string $email` - Email
- `string $time` - Thời gian tạo
  **Trả về:** `bool` - True nếu tạo thành công

**Logic xử lý:**

1. Chuẩn bị prepared statement: `INSERT INTO Users (taikhoan,matkhau,email,time) VALUES (?,?,?,?)`
2. Bind tất cả tham số
3. Thực thi query
4. Trả về kết quả thành công/thất bại

**Ví dụ sử dụng:**

```php
$passwordMd5 = md5('123456');
$time = date('Y-m-d H:i:s');
if ($userRepo->createUser('newuser', $passwordMd5, 'user@email.com', $time)) {
    echo "Tạo tài khoản thành công";
}
```

---

### **9. findById()**

```php
public function findById(int $userId): ?array
```

**Chức năng:** Tìm kiếm người dùng theo ID
**Tham số:** `int $userId` - ID người dùng
**Trả về:** `array|null` - Thông tin user hoặc null

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT * FROM Users WHERE id = ? LIMIT 1`
2. Bind tham số userId
3. Thực thi query
4. Trả về thông tin user hoặc null

---

### **10. findByEmail()**

```php
public function findByEmail(string $email): ?array
```

**Chức năng:** Tìm kiếm người dùng theo email
**Tham số:** `string $email` - Email cần tìm
**Trả về:** `array|null` - Thông tin user hoặc null

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT * FROM Users WHERE email = ? LIMIT 1`
2. Bind tham số email
3. Thực thi query
4. Trả về thông tin user hoặc null

---

### **11. updateProfile()**

```php
public function updateProfile(int $userId, string $email, string $newUsername): bool
```

**Chức năng:** Cập nhật thông tin profile người dùng
**Tham số:**

- `int $userId` - ID người dùng
- `string $email` - Email mới
- `string $newUsername` - Tên đăng nhập mới
  **Trả về:** `bool` - True nếu cập nhật thành công

**Logic xử lý:**

1. Chuẩn bị prepared statement: `UPDATE Users SET email = ?, taikhoan = ? WHERE id = ?`
2. Bind tham số email, newUsername, userId
3. Thực thi query
4. Trả về kết quả thành công/thất bại

---

## 🔒 **BẢO MẬT**

### **SQL Injection Protection:**

- Tất cả queries đều sử dụng **Prepared Statements**
- Không có string concatenation trong SQL
- Bind parameters an toàn

### **Password Security:**

- Mật khẩu được mã hóa MD5 (có thể nâng cấp lên bcrypt)
- Không lưu mật khẩu dạng plain text

---

## 🎯 **VÍ DỤ SỬ DỤNG HOÀN CHỈNH**

```php
// Khởi tạo repository
$userRepo = new UserRepository($connect);

// Đăng nhập
if ($userRepo->verifyCredentials('admin', '123456')) {
    $user = $userRepo->findByUsername('admin');
    echo "Xin chào: " . $user['taikhoan'];

    // Cập nhật số dư
    $userRepo->incrementBalance($user['id'], 100000);

    // Cập nhật profile
    $userRepo->updateProfile($user['id'], 'newemail@test.com', 'newusername');
}

// Thống kê
$totalUsers = $userRepo->countAll();
echo "Tổng số user: " . $totalUsers;
```

---

## 📊 **TỔNG KẾT**

**UserRepository** cung cấp đầy đủ các chức năng CRUD cho bảng Users:

- ✅ **Create** - Tạo user mới
- ✅ **Read** - Tìm kiếm user theo username/email/ID
- ✅ **Update** - Cập nhật profile, số dư
- ✅ **Delete** - (Không có method delete, chỉ có update)

**Đặc điểm:**

- 🔒 **Bảo mật cao** - Prepared statements
- 🎯 **Logic rõ ràng** - Mỗi method có 1 chức năng
- 🔄 **Tái sử dụng** - Có thể dùng ở nhiều nơi
- 📝 **Dễ maintain** - Code có comment chi tiết

**Tác giả:** DAM THANH VU  
**Ngày tạo:** 2024  
**Phiên bản:** 1.0
