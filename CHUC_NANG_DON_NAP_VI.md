# 💰 **TRÌNH BÀY THIẾT KẾ VÀ XÂY DỰNG CHỨC NĂNG ĐƠN NẠP VÍ**

## 📝 **1. MÔ TẢ CHỨC NĂNG:**

Chức năng đơn nạp ví cho phép admin **cộng tiền trực tiếp** vào tài khoản của người dùng thông qua ID người dùng. Đây là tính năng quản trị để admin có thể hỗ trợ người dùng hoặc thực hiện các giao dịch đặc biệt.

**⚠️ LƯU Ý QUAN TRỌNG:**

- Trang này **KHÔNG** hiển thị danh sách đơn nạp thẻ cào
- Đây là trang **cộng tiền thủ công** cho admin
- Danh sách đơn nạp thẻ cào được quản lý ở trang **"Đơn Gạch Thẻ"** (`Gach-Cards.php`)

## 👨‍💼 **2. TÁC NHÂN THỰC HIỆN:**

- **Admin/Quản trị viên** - Người có quyền truy cập vào hệ thống quản trị thông qua HTTP Basic Authentication

## 🔍 **3. DẠNG TRUY VẤN:**

- **SELECT** - Kiểm tra tồn tại của người dùng theo ID
- **UPDATE** - Cộng tiền vào số dư người dùng

## 🗄️ **4. TRUY VẤN VÀO TABLE:**

- **Table:** `Users` (thông tin người dùng và số dư)

## 📊 **5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

### **Table Users:**

- `id` (int) - ID người dùng
- `taikhoan` (varchar) - Tên tài khoản
- `tien` (int) - Số dư hiện tại

## 🔄 **6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. **Admin đăng nhập vào hệ thống** (HTTP Basic Authentication)
2. **Admin truy cập menu "Đơn nạp ví"** hoặc URL `/Adminstators/don-nap-vi.php`
3. **Hệ thống kiểm tra quyền truy cập** (đã đăng nhập admin)
4. **Hiển thị form cộng tiền** với các trường ID và số tiền

## 🔄 **7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Kiểm tra dữ liệu đầu vào** - ID và số tiền không được để trống
2. **Kiểm tra tồn tại người dùng** - Tìm kiếm user theo ID
3. **Thành công:** Cộng tiền vào ví user và hiển thị thông báo thành công
4. **Thất bại:** Hiển thị thông báo lỗi tương ứng

## 📊 **8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (Admin Browser)
    ↓ POST Request
    ↓ URL: /Adminstators/don-nap-vi.php
    ↓ Form Data: idc, price
    ↓ HTTP Basic Authentication
Web Server (Apache)
    ↓ Xác thực thành công
    ↓ Xử lý request
File PHP xử lý
    ↓ Adminstators/don-nap-vi.php
    ↓ include_once UserRepository.php
    ↓ $userRepo = new UserRepository($connect)
PHP Processing
    ↓ UserRepository->findById($id)
    ↓ SELECT * FROM Users WHERE id = ?
Database (MySQL)
    ↓ Table: Users
    ↓ Trả về: User data hoặc null
Response
    ↓ Nếu user tồn tại: UserRepository->incrementBalance()
    ↓ UPDATE Users SET tien = tien + ? WHERE id = ?
    ↓ Hiển thị thông báo thành công/thất bại
```

## 🗃️ **9. BẢNG RECORDSET VÀ ARRAY[KEY]:**

### **Dữ liệu thực tế trong database:**

```sql
-- Truy vấn kiểm tra user
SELECT * FROM Users WHERE id = ?;

-- Kết quả mẫu:
| id | taikhoan | tien  | email           |
|----|----------|-------|-----------------|
| 1  | user1    | 50000 | user1@email.com |
| 2  | user2    | 100000| user2@email.com |

-- Truy vấn cộng tiền
UPDATE Users SET tien = tien + ? WHERE id = ?;
```

### **Array[key] sử dụng trong PHP:**

```php
// Kết quả từ UserRepository->findById()
$user = [
        'id' => 1,
        'taikhoan' => 'user1',
    'tien' => 50000,
    'email' => 'user1@email.com'
];

// Sử dụng trong code
if($user === null) {
    // User không tồn tại
    echo "Không tìm thấy người dùng";
} else {
    // User tồn tại, có thể cộng tiền
    echo "Tìm thấy user: " . $user['taikhoan'];
}
```

## 🖼️ **10. GIAO DIỆN CHỨC NĂNG:**

### **Giao diện cộng tiền thành viên:**

```html
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cộng Tiền Thành Viên - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body>
    <div class="min-h-screen bg-gray-50">
      <!-- Header -->
      <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center py-6">
            <h1 class="text-3xl font-bold text-gray-900">
              Cộng Tiền Thành Viên
            </h1>
            <div class="text-sm text-gray-500">Quản trị viên</div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Form cộng tiền -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
              Cộng Tiền Thành Viên
            </h3>
          </div>
          <div class="p-6">
            <form action="" method="post">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    ID Thành Viên
                  </label>
                  <input
                    type="text"
                    name="idc"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Nhập ID thành viên"
                    required
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Số Tiền
                  </label>
                  <input
                    type="number"
                    name="price"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Nhập số tiền cần cộng"
                    required
                  />
                </div>
              </div>

              <div class="mt-6">
                <button
                  type="submit"
                  name="congtien"
                  class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  Cộng Tiền Ngay
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Thông tin quan trọng -->
        <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg
                class="h-5 w-5 text-yellow-400"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fill-rule="evenodd"
                  d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                  clip-rule="evenodd"
                />
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-yellow-800">
                Lưu ý quan trọng
              </h3>
              <div class="mt-2 text-sm text-yellow-700">
                <ul class="list-disc pl-5 space-y-1">
                  <li>
                    Chức năng này dùng để cộng tiền trực tiếp vào tài khoản
                    người dùng
                  </li>
                  <li>
                    Để xem danh sách đơn nạp thẻ cào, vui lòng truy cập
                    <strong>"Đơn Gạch Thẻ"</strong>
                  </li>
                  <li>
                    Thẻ cào được xử lý tự động thông qua callback từ nhà cung
                    cấp
                  </li>
                  <li>Admin không cần duyệt thẻ cào thủ công</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
```

## 💻 **11. CODE XỬ LÝ:**

### **File: Adminstators/don-nap-vi.php**

```php
<?php
include('Connect/Header.php');

// Xử lý cộng tiền thành viên
if(isset($_POST['congtien'])){
    $id = $_POST['idc'];
    $price = $_POST['price'];

    // Kiểm tra dữ liệu đầu vào
    if($id == "" || $price == "" ){
        echo '<script>swal("Thông Báo", "Vui Lòng Nhập Đầy Đủ Thông Tin!", "error");</script>';
    } else {
        include_once(__DIR__.'/../Repositories/UserRepository.php');
        $userRepo = new UserRepository($connect);

        // Kiểm tra tồn tại của user trước khi cộng tiền
        $checkus = $userRepo->findById((int)$id);
        if($checkus === null){
            echo '<script>swal("Thông Báo", "Không Tìm Thấy Người Dùng Với ID '.$id.'!", "error");</script>';
        } else {
            // Cộng tiền vào tài khoản
            $thanhright = $userRepo->incrementBalance((int)$id, (int)$price);
            if($thanhright){
                echo '<script>swal("Thông Báo", "Giao Dịch Cộng '.number_format($price).'đ Thành Công Cho Người Dùng '.$checkus['taikhoan'].' ", "success");</script>';
                echo '<meta http-equiv="refresh" content="1;url=">';
            } else {
                echo '<script>swal("Thông Báo", "Không Thể Thực Hiện Giao Dịch!", "error");</script>';
            }
        }
    }
}
?>

<div class="col-span-12 mt-6">
    <div class="intro-y box mt-5">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
            <h2 class="font-medium text-base mr-auto">Cộng Tiền Thành Viên</h2>
                        </div>
        <div id="horizontal-form" class="p-5">
            <div class="preview">
                <form action="" method="post">
                    <div class="form-inline">
                        <label for="horizontal-form-1" class="form-label sm:w-20">ID Thành Viên</label>
                        <input id="horizontal-form-1" type="text" name="idc" class="form-control" placeholder="Mã Số Thành Viên" required>
        </div>

                    <div class="form-inline mt-5">
                        <label for="horizontal-form-2" class="form-label sm:w-20">Số Tiền</label>
                        <input id="horizontal-form-2" type="number" class="form-control" placeholder="Tiền Cần Cộng" name="price" required>
        </div>

                    <div class="sm:ml-20 sm:pl-5 mt-5">
                        <button type="submit" name="congtien" class="btn btn-primary">Cộng Ngay</button>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>

<?php
include('Connect/Footer.php');
?>
```

## 🔄 **12. LUỒNG XỬ LÝ THẺ CÀO THỰC TẾ:**

### **Quy trình tự động:**

1. **User nạp thẻ cào** → Gửi thông tin thẻ qua `Ajaxs/Cards.php`
2. **Hệ thống lưu thẻ** → Insert vào bảng `Cards` với status = 0 (chờ xử lý)
3. **Gửi API đến CardVIP** → Xử lý thẻ thông qua nhà cung cấp
4. **Callback tự động** → `callback.php` nhận kết quả từ CardVIP
5. **Cập nhật tự động:**
   - Status = 200 (thẻ đúng) → Cộng tiền tự động vào ví user
   - Status = 100 (thẻ sai) → Đánh dấu thẻ sai
6. **Admin xem kết quả** → Trang "Đơn Gạch Thẻ" hiển thị tất cả thẻ đã xử lý

### **Admin KHÔNG cần duyệt thẻ cào thủ công!**

## 🎯 **13. TÓM TẮT KỸ THUẬT:**

### **✅ Công nghệ sử dụng:**

- **Backend:** PHP OOP với Repository Pattern
- **Database:** MySQL với prepared statements
- **Frontend:** HTML + Tailwind CSS + JavaScript
- **Authentication:** HTTP Basic Authentication
- **Validation:** Kiểm tra tồn tại user trước khi cộng tiền

### **✅ Kiến trúc:**

- **Repository Pattern** - Tách biệt logic database
- **Error Handling** - Xử lý lỗi đầy đủ
- **Input Validation** - Kiểm tra dữ liệu đầu vào
- **User Verification** - Xác minh tồn tại user

### **✅ Tính năng:**

- **Cộng tiền trực tiếp** - Admin có thể cộng tiền cho user
- **Kiểm tra tồn tại** - Xác minh user có tồn tại không
- **Thông báo rõ ràng** - Hiển thị kết quả thành công/thất bại
- **Bảo mật** - Chỉ admin mới có quyền truy cập

### **✅ Bảo mật:**

- **HTTP Basic Auth** - Bảo vệ cấp server
- **Prepared Statements** - Chống SQL injection
- **Input Validation** - Kiểm tra dữ liệu đầu vào
- **User Verification** - Xác minh tồn tại user

## 🎬 **14. DEMO CHỨC NĂNG:**

### **Bước 1: Truy cập trang cộng tiền**

```
URL: http://localhost/Adminstators/don-nap-vi.php
```

### **Bước 2: Nhập thông tin**

```
┌─────────────────────────────────────────────────────────────┐
│  💰 Cộng Tiền Thành Viên                                   │
├─────────────────────────────────────────────────────────────┤
│  ID Thành Viên: [123]                                      │
│  Số Tiền: [50000]                                          │
│  [Cộng Ngay]                                               │
└─────────────────────────────────────────────────────────────┘
```

### **Bước 3: Kết quả**

- **Thành công:** "Giao Dịch Cộng 50,000đ Thành Công Cho Người Dùng user123"
- **Thất bại:** "Không Tìm Thấy Người Dùng Với ID 123!"

## ⚠️ **15. PHÂN BIỆT VỚI CÁC CHỨC NĂNG KHÁC:**

### **Trang "Đơn Nạp Ví" (`don-nap-vi.php`):**

- ✅ Cộng tiền thủ công cho user
- ✅ Form đơn giản: ID + Số tiền
- ❌ KHÔNG hiển thị danh sách đơn nạp

### **Trang "Đơn Gạch Thẻ" (`Gach-Cards.php`):**

- ✅ Hiển thị tất cả thẻ cào đã nạp
- ✅ Xem trạng thái thẻ (Đang Duyệt/Thẻ Đúng/Thẻ Sai)
- ✅ Thông tin chi tiết: UID, Mã thẻ, Serial, Mệnh giá, Loại thẻ
- ❌ KHÔNG có chức năng duyệt thẻ (tự động)

### **Luồng thẻ cào tự động:**

- ✅ User nạp thẻ → API CardVIP → Callback → Tự động cộng tiền
- ✅ Admin chỉ cần xem kết quả ở "Đơn Gạch Thẻ"

## 🎉 **KẾT LUẬN:**

**Chức năng đơn nạp ví đã được thiết kế đúng với mục đích sử dụng:**

**Đặc điểm nổi bật:**

- ✅ **Cộng tiền thủ công** - Admin có thể hỗ trợ user
- ✅ **Kiểm tra tồn tại** - Xác minh user trước khi cộng tiền
- ✅ **Xử lý lỗi đầy đủ** - Thông báo rõ ràng khi user không tồn tại
- ✅ **Bảo mật cao** - HTTP Basic Auth + Prepared Statements
- ✅ **Giao diện đơn giản** - Form dễ sử dụng
- ✅ **Tách biệt rõ ràng** - Không nhầm lẫn với quản lý thẻ cào

**⚠️ Lưu ý quan trọng:**

- Trang này KHÔNG phải để duyệt đơn nạp thẻ cào
- Thẻ cào được xử lý tự động qua callback
- Để xem thẻ cào, admin vào trang "Đơn Gạch Thẻ"
