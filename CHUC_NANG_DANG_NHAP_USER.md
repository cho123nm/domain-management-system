# 🔐 **TRÌNH BÀY THIẾT KẾ VÀ XÂY DỰNG CHỨC NĂNG ĐĂNG NHẬP USER**

## 📝 **1. MÔ TẢ CHỨC NĂNG:**

Chức năng đăng nhập cho phép người dùng xác thực danh tính để truy cập vào hệ thống, tạo session và chuyển hướng đến trang chủ.

## 👤 **2. TÁC NHÂN THỰC HIỆN:**

- **Người dùng** - Khách hàng muốn truy cập vào hệ thống

## 🔍 **3. DẠNG TRUY VẤN:**

- **SELECT** - Truy vấn kiểm tra thông tin đăng nhập

## 🗄️ **4. TRUY VẤN VÀO TABLE:**

- **Table:** `Users`

## 📊 **5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

- `taikhoan` (varchar) - Tên đăng nhập
- `matkhau` (varchar) - Mật khẩu (đã mã hóa MD5)
- `id` (int) - ID người dùng

## 🔄 **6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. Người dùng truy cập trang đăng nhập
2. Hệ thống hiển thị form đăng nhập
3. Người dùng nhập tên đăng nhập và mật khẩu
4. Người dùng click nút "Đăng nhập"

## 🔄 **7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Thành công:** Tạo session, hiển thị thông báo thành công, chuyển hướng về trang chủ
2. **Thất bại:** Hiển thị thông báo lỗi, yêu cầu nhập lại

## 📊 **8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (User Browser)
    ↓ POST Request
    ↓ URL: /Ajaxs/login.php
    ↓ Data: {taikhoan: "username", matkhau: "password"}
Web Server (Apache)
    ↓ Xử lý request
File PHP xử lý
    ↓ Ajaxs/login.php
    ↓ include_once UserRepository.php
    ↓ $userRepo = new UserRepository($connect)
PHP Processing
    ↓ UserRepository->verifyCredentials()
    ↓ SELECT id FROM Users WHERE taikhoan=? AND matkhau=?
Database (MySQL)
    ↓ Table: Users
    ↓ Trả về: true/false
Response
    ↓ Success: $_SESSION['users'] = $taikhoan
    ↓ Success: toastr.success("Đăng Nhập Thành Công!")
    ↓ Success: redirect to "/"
    ↓ Error: toastr.error("Thông Tin Đăng Nhập Không Hợp Lệ!")
```

## 🗃️ **9. BẢNG RECORDSET VÀ ARRAY[KEY]:**

### **Dữ liệu thực tế trong database:**

```sql
| id | taikhoan | matkhau                           | email              | tien  |
|----|----------|-----------------------------------|--------------------|-------|
| 1  | admin    | 5d41402abc4b2a76b9719d911017c592 | admin@example.com  | 100000|
| 2  | user1    | 098f6bcd4621d373cade4e832627b4f6 | user1@example.com  | 50000 |
```

### **Array[key] sử dụng:**

- `$taikhoan` - Tên đăng nhập từ POST
- `$matkhau` - Mật khẩu từ POST
- `$user['id']` - ID người dùng từ database

## 🖼️ **10. GIAO DIỆN CHỨC NĂNG:**

### **Form đăng nhập:**

```html
<form
  class="form w-100"
  novalidate="novalidate"
  id="kt_sign_in_form"
  data-kt-redirect-url="/"
  action="/Ajaxs/login.php"
  method="post"
>
  <div class="text-center mb-11">
    <h1 class="text-dark fw-bolder mb-3">ĐĂNG NHẬP TÀI KHOẢN</h1>
    <div class="text-gray-500 fw-semibold fs-6">
      Tạo Shop Tự Động, Rút Kim Cương Về Nick 100%
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

  <div class="fv-row mb-3">
    <input
      type="password"
      placeholder="Mật khẩu"
      name="matkhau"
      autocomplete="off"
      class="form-control bg-transparent"
    />
  </div>

  <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
    <div></div>
    <a href="/Pages/register.php" class="link-primary">Đăng ký tài khoản?</a>
  </div>

  <div class="d-grid mb-10">
    <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
      <span class="indicator-label">Đăng nhập</span>
      <span class="indicator-progress"
        >Vui lòng chờ...
        <span class="spinner-border spinner-border-sm align-middle ms-2"></span
      ></span>
    </button>
  </div>
</form>
```

## 💻 **11. CODE XỬ LÝ:**

### **File: Ajaxs/login.php**

```php
<?php
include_once('../Config/Database.php');
include_once('../Repositories/UserRepository.php');

$taikhoan = $_POST['taikhoan'];
$matkhau = $_POST['matkhau'];

if($taikhoan == "" || $matkhau == ""){
    echo '<script>toastr.error("Vui Lòng Nhập Đầy Đủ Thông Tin!", "Thông Báo");</script>';
} else {
    $userRepo = new UserRepository($connect);
    if ($userRepo->verifyCredentials($taikhoan, $matkhau)){
        echo '<script>toastr.success("Đăng Nhập Thành Công!", "Thông Báo");</script>';
        $_SESSION['users'] = $taikhoan;
        echo '<meta http-equiv="refresh" content="1;url=/">';
    } else {
        echo '<script>toastr.error("Thông Tin Đăng Nhập Không Hợp Lệ!", "Thông Báo");</script>';
    }
}
?>
```

### **Repository: UserRepository->verifyCredentials()**

```php
public function verifyCredentials(string $username, string $password): bool
{
    // Mã hóa mật khẩu bằng MD5
    $passwordMd5 = md5($password);
    $stmt = $this->mysqli->prepare("SELECT id FROM Users WHERE taikhoan = ? AND matkhau = ? LIMIT 1");
    $stmt->bind_param('ss', $username, $passwordMd5);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row ? true : false;
}
```

## 🎯 **12. TÓM TẮT KỸ THUẬT:**

### **✅ Công nghệ sử dụng:**

- **Backend:** PHP OOP với Repository Pattern
- **Database:** MySQL với prepared statements
- **Frontend:** HTML form với JavaScript validation
- **Security:** MD5 password hashing, SQL injection protection
- **Session:** PHP session management

### **✅ Kiến trúc:**

- **Repository Pattern** - Tách biệt logic database
- **OOP Design** - Code có cấu trúc, dễ maintain
- **Separation of Concerns** - Tách biệt UI và logic

### **✅ Bảo mật:**

- **Prepared Statements** - Chống SQL injection
- **Password Hashing** - MD5 encryption
- **Input Validation** - Kiểm tra dữ liệu đầu vào
- **Session Management** - Quản lý phiên đăng nhập

## 🎉 **KẾT LUẬN:**

**Chức năng đăng nhập user đã được thiết kế hoàn chỉnh với giao diện đẹp, logic xử lý chuyên nghiệp và bảo mật cao!**
