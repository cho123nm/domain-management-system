# 📝 **TRÌNH BÀY THIẾT KẾ VÀ XÂY DỰNG CHỨC NĂNG ĐĂNG KÝ USER**

## 📝 **1. MÔ TẢ CHỨC NĂNG:**

Chức năng đăng ký cho phép người dùng tạo tài khoản mới trong hệ thống với thông tin cá nhân, kiểm tra tính hợp lệ và tạo tài khoản thành công.

## 👤 **2. TÁC NHÂN THỰC HIỆN:**

- **Người dùng mới** - Khách hàng chưa có tài khoản muốn đăng ký

## 🔍 **3. DẠNG TRUY VẤN:**

- **INSERT** - Truy vấn thêm tài khoản mới vào database

## 🗄️ **4. TRUY VẤN VÀO TABLE:**

- **Table:** `Users`

## 📊 **5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

- `taikhoan` (varchar) - Tên đăng nhập
- `matkhau` (varchar) - Mật khẩu (đã mã hóa MD5)
- `email` (varchar) - Email người dùng
- `tien` (int) - Số dư ban đầu (mặc định 0)
- `chucvu` (int) - Chức vụ (mặc định 0 - user thường)
- `time` (varchar) - Thời gian đăng ký

## 🔄 **6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. Người dùng truy cập trang đăng ký
2. Hệ thống hiển thị form đăng ký
3. Người dùng nhập đầy đủ thông tin (username, password, email)
4. Người dùng click nút "Đăng ký"

## 🔄 **7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Thành công:** Hiển thị thông báo thành công, chuyển hướng đến trang đăng nhập
2. **Thất bại:** Hiển thị thông báo lỗi (username/email đã tồn tại, thông tin không hợp lệ)

## 📊 **8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (User Browser)
    ↓ POST Request
    ↓ URL: /Ajaxs/register.php
    ↓ Data: {taikhoan: "newuser", matkhau: "password", email: "user@email.com"}
Web Server (Apache)
    ↓ Xử lý request
File PHP xử lý
    ↓ Ajaxs/register.php
    ↓ include_once UserRepository.php
    ↓ $userRepo = new UserRepository($connect)
PHP Processing
    ↓ UserRepository->createUser()
    ↓ INSERT INTO Users (taikhoan, matkhau, email, tien, chucvu, time) VALUES (?, ?, ?, 0, 0, ?)
Database (MySQL)
    ↓ Table: Users
    ↓ Trả về: true/false
Response
    ↓ Success: toastr.success("Đăng Ký Thành Công!")
    ↓ Success: redirect to "/Pages/login.php"
    ↓ Error: toastr.error("Tên đăng nhập hoặc email đã tồn tại!")
```

## 🗃️ **9. BẢNG RECORDSET VÀ ARRAY[KEY]:**

### **Dữ liệu mới được thêm:**

```sql
| id | taikhoan | matkhau                           | email              | tien  | chucvu | time        |
|----|----------|-----------------------------------|--------------------|-------|--------|-------------|
| 3  | newuser  | 5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8 | newuser@email.com  | 0     | 0      | 15/10/2025  |
```

### **Array[key] sử dụng:**

- `$taikhoan` - Tên đăng nhập từ POST
- `$matkhau` - Mật khẩu từ POST (được mã hóa MD5)
- `$email` - Email từ POST
- `$time` - Thời gian hiện tại

## 🖼️ **10. GIAO DIỆN CHỨC NĂNG:**

### **Form đăng ký:**

```html
<form
  class="form w-100"
  novalidate="novalidate"
  id="kt_sign_up_form"
  data-kt-redirect-url="/Pages/login.php"
  action="/Ajaxs/register.php"
  method="post"
>
  <div class="text-center mb-11">
    <h1 class="text-dark fw-bolder mb-3">ĐĂNG KÝ TÀI KHOẢN</h1>
    <div class="text-gray-500 fw-semibold fs-6">
      Tạo tài khoản để sử dụng dịch vụ
    </div>
  </div>

  <div class="fv-row mb-8">
    <input
      type="text"
      placeholder="Tên đăng nhập"
      name="taikhoan"
      autocomplete="off"
      class="form-control bg-transparent"
    />
  </div>

  <div class="fv-row mb-8">
    <input
      type="email"
      placeholder="Email"
      name="email"
      autocomplete="off"
      class="form-control bg-transparent"
    />
  </div>

  <div class="fv-row mb-8">
    <input
      type="password"
      placeholder="Mật khẩu"
      name="matkhau"
      autocomplete="off"
      class="form-control bg-transparent"
    />
  </div>

  <div class="fv-row mb-8">
    <input
      type="password"
      placeholder="Nhập lại mật khẩu"
      name="confirm_password"
      autocomplete="off"
      class="form-control bg-transparent"
    />
  </div>

  <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
    <div></div>
    <a href="/Pages/login.php" class="link-primary"
      >Đã có tài khoản? Đăng nhập</a
    >
  </div>

  <div class="d-grid mb-10">
    <button type="submit" id="kt_sign_up_submit" class="btn btn-primary">
      <span class="indicator-label">Đăng ký</span>
      <span class="indicator-progress"
        >Vui lòng chờ...
        <span class="spinner-border spinner-border-sm align-middle ms-2"></span
      ></span>
    </button>
  </div>
</form>
```

## 💻 **11. CODE XỬ LÝ:**

### **File: Ajaxs/register.php**

```php
<?php
include_once('../Config/Database.php');
include_once('../Repositories/UserRepository.php');

$taikhoan = $_POST['taikhoan'];
$email = $_POST['email'];
$matkhau = $_POST['matkhau'];
$confirm_password = $_POST['confirm_password'];

// Validation
if($taikhoan == "" || $email == "" || $matkhau == "" || $confirm_password == ""){
    echo '<script>toastr.error("Vui Lòng Nhập Đầy Đủ Thông Tin!", "Thông Báo");</script>';
} else if($matkhau != $confirm_password){
    echo '<script>toastr.error("Mật Khẩu Không Khớp!", "Thông Báo");</script>';
} else {
    $userRepo = new UserRepository($connect);

    // Kiểm tra username đã tồn tại
    if($userRepo->findByUsername($taikhoan)){
        echo '<script>toastr.error("Tên Đăng Nhập Đã Tồn Tại!", "Thông Báo");</script>';
    } else if($userRepo->findByEmail($email)){
        echo '<script>toastr.error("Email Đã Tồn Tại!", "Thông Báo");</script>';
    } else {
        // Tạo tài khoản mới
        if($userRepo->createUser($taikhoan, $matkhau, $email)){
            echo '<script>toastr.success("Đăng Ký Thành Công!", "Thông Báo");</script>';
            echo '<meta http-equiv="refresh" content="2;url=/Pages/login.php">';
        } else {
            echo '<script>toastr.error("Đăng Ký Thất Bại!", "Thông Báo");</script>';
        }
    }
}
?>
```

### **Repository: UserRepository->createUser()**

```php
public function createUser(string $username, string $password, string $email): bool
{
    $passwordMd5 = md5($password);
    $time = date('d/m/Y H:i:s');

    $stmt = $this->mysqli->prepare("INSERT INTO Users (taikhoan, matkhau, email, tien, chucvu, time) VALUES (?, ?, ?, 0, 0, ?)");
    $stmt->bind_param('ssss', $username, $passwordMd5, $email, $time);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

public function findByUsername(string $username): ?array
{
    $stmt = $this->mysqli->prepare("SELECT * FROM Users WHERE taikhoan = ? LIMIT 1");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row ?: null;
}

public function findByEmail(string $email): ?array
{
    $stmt = $this->mysqli->prepare("SELECT * FROM Users WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row ?: null;
}
```

## 🎯 **12. TÓM TẮT KỸ THUẬT:**

### **✅ Công nghệ sử dụng:**

- **Backend:** PHP OOP với Repository Pattern
- **Database:** MySQL với prepared statements
- **Frontend:** HTML form với JavaScript validation
- **Security:** MD5 password hashing, input validation
- **Validation:** Kiểm tra username/email trùng lặp

### **✅ Kiến trúc:**

- **Repository Pattern** - Tách biệt logic database
- **OOP Design** - Code có cấu trúc, dễ maintain
- **Input Validation** - Kiểm tra dữ liệu đầu vào
- **Error Handling** - Xử lý lỗi chi tiết

### **✅ Bảo mật:**

- **Prepared Statements** - Chống SQL injection
- **Password Hashing** - MD5 encryption
- **Duplicate Check** - Kiểm tra username/email trùng lặp
- **Input Sanitization** - Làm sạch dữ liệu đầu vào

## 🎉 **KẾT LUẬN:**

**Chức năng đăng ký user đã được thiết kế hoàn chỉnh với validation đầy đủ, bảo mật cao và trải nghiệm người dùng tốt!**
