# 👥 **TRÌNH BÀY THIẾT KẾ VÀ XÂY DỰNG CHỨC NĂNG QUẢN LÝ THÀNH VIÊN**

## 📝 **1. MÔ TẢ CHỨC NĂNG:**

Chức năng quản lý thành viên cho phép admin xem danh sách tất cả người dùng, chỉnh sửa thông tin cá nhân, cập nhật số dư tài khoản và quản lý toàn bộ thành viên trong hệ thống.

## 👨‍💼 **2. TÁC NHÂN THỰC HIỆN:**

- **Quản trị viên** - Admin có quyền quản lý thành viên

## 🔍 **3. DẠNG TRUY VẤN:**

- **SELECT** - Truy vấn lấy danh sách thành viên
- **UPDATE** - Truy vấn cập nhật thông tin thành viên

## 🗄️ **4. TRUY VẤN VÀO TABLE:**

- **Table:** `Users` (lưu thông tin thành viên)

## 📊 **5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

- `id` (int) - ID thành viên
- `taikhoan` (varchar) - Tên đăng nhập
- `matkhau` (varchar) - Mật khẩu (đã mã hóa)
- `email` (varchar) - Email thành viên
- `tien` (int) - Số dư tài khoản
- `chucvu` (int) - Chức vụ (0: user, 1: admin)
- `time` (varchar) - Thời gian đăng ký

## 🔄 **6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. Admin đăng nhập vào hệ thống
2. Admin truy cập trang quản lý thành viên
3. Hệ thống hiển thị danh sách tất cả thành viên
4. Admin xem thông tin chi tiết từng thành viên

## 🔄 **7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Cập nhật số dư:** Hiển thị modal chỉnh sửa, cập nhật số dư thành viên
2. **Xem thông tin:** Hiển thị chi tiết thông tin thành viên
3. **Thống kê:** Hiển thị tổng số thành viên và thống kê

## 📊 **8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (Admin Browser)
    ↓ GET Request (Xem danh sách)
    ↓ URL: /Adminstators/quan-ly-thanh-vien.php
    ↓ POST Request (Cập nhật số dư)
    ↓ URL: /Adminstators/quan-ly-thanh-vien.php
    ↓ Data: {id: "2", tien: "100000"}
Web Server (Apache)
    ↓ Xử lý request
File PHP xử lý
    ↓ Adminstators/quan-ly-thanh-vien.php
    ↓ include_once UserRepository.php
    ↓ $userRepo = new UserRepository($connect)
PHP Processing
    ↓ UserRepository->listAll() (Xem danh sách)
    ↓ UserRepository->updateBalance() (Cập nhật số dư)
    ↓ UPDATE Users SET tien = ? WHERE id = ?
Database (MySQL)
    ↓ Table: Users
    ↓ Trả về: true/false
Response
    ↓ Success: Hiển thị danh sách thành viên
    ↓ Success: toastr.success("Cập nhật số dư thành công!")
    ↓ Error: toastr.error("Cập nhật thất bại!")
```

## 🗃️ **9. BẢNG RECORDSET VÀ ARRAY[KEY]:**

### **Dữ liệu thành viên trong database:**

```sql
| id | taikhoan | matkhau                           | email              | tien  | chucvu | time        |
|----|----------|-----------------------------------|--------------------|-------|--------|-------------|
| 1  | admin    | 5d41402abc4b2a76b9719d911017c592 | admin@example.com  | 100000| 1      | 01/01/2025  |
| 2  | user1    | 098f6bcd4621d373cade4e832627b4f6 | user1@example.com  | 50000 | 0      | 15/10/2025  |
| 3  | user2    | 5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8 | user2@example.com  | 75000 | 0      | 16/10/2025  |
```

### **Array[key] sử dụng:**

- `$user['id']` - ID thành viên
- `$user['taikhoan']` - Tên đăng nhập
- `$user['email']` - Email thành viên
- `$user['tien']` - Số dư tài khoản
- `$user['chucvu']` - Chức vụ
- `$user['time']` - Thời gian đăng ký

## 🖼️ **10. GIAO DIỆN CHỨC NĂNG:**

### **Bảng danh sách thành viên:**

```html
<div class="min-h-screen bg-gray-50">
  <!-- Header -->
  <div class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center py-6">
        <h1 class="text-3xl font-bold text-gray-900">Quản Lý Thành Viên</h1>
        <div class="text-sm text-gray-500">
          Tổng số thành viên: <?= count($users) ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Content -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          Danh Sách Thành Viên
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
          Quản lý thông tin và số dư thành viên
        </p>
      </div>

      <div class="border-t border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                UID
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Tài Khoản
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Mật Khẩu
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Số Dư
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Thời Gian
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Thao Tác
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($users as $user): ?>
            <tr>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                <?= $user['id'] ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <?= htmlspecialchars($user['taikhoan']) ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <?= substr($user['matkhau'], 0, 10) ?>...
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <?= number_format($user['tien']) ?>đ
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <?= $user['time'] ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  onclick="openEditModal(<?= $user['id'] ?>, '<?= htmlspecialchars($user['taikhoan']) ?>', <?= $user['tien'] ?>)"
                  class="text-indigo-600 hover:text-indigo-900"
                >
                  Chỉnh sửa số dư
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal chỉnh sửa số dư -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
  <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
      <form method="post">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
            Chỉnh Sửa Số Dư
          </h3>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Tài khoản</label>
            <input
              type="text"
              id="editUsername"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
              readonly
            />
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Số dư hiện tại</label>
            <input
              type="text"
              id="editCurrentBalance"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
              readonly
            />
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Số dư mới</label>
            <input
              type="number"
              name="tien"
              id="editNewBalance"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
              required
            />
            <input type="hidden" name="id" id="editUserId" />
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button
            type="submit"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
          >
            Cập nhật
          </button>
          <button
            type="button"
            onclick="closeEditModal()"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
          >
            Hủy
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function openEditModal(id, username, currentBalance) {
  document.getElementById('editUserId').value = id;
  document.getElementById('editUsername').value = username;
  document.getElementById('editCurrentBalance').value = currentBalance.toLocaleString() + 'đ';
  document.getElementById('editNewBalance').value = currentBalance;
  document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
  document.getElementById('editModal').classList.add('hidden');
}
</script>
```

## 💻 **11. CODE XỬ LÝ:**

### **File: Adminstators/quan-ly-thanh-vien.php**

```php
<?php
include_once('../Config/Database.php');
include_once('../Repositories/UserRepository.php');

$userRepo = new UserRepository($connect);

// Xử lý cập nhật số dư
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['tien'])) {
    $id = (int)$_POST['id'];
    $tien = (int)$_POST['tien'];
    
    if ($userRepo->updateBalance($id, $tien)) {
        echo '<script>toastr.success("Cập nhật số dư thành công!", "Thông Báo");</script>';
    } else {
        echo '<script>toastr.error("Cập nhật thất bại!", "Thông Báo");</script>';
    }
}

// Lấy danh sách thành viên
$users = $userRepo->listAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Thành Viên - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body>
    <!-- Dashboard content như trên -->
</body>
</html>
```

### **Repository: UserRepository->updateBalance()**

```php
public function updateBalance(int $id, int $balance): bool
{
    $stmt = $this->mysqli->prepare("UPDATE Users SET tien = ? WHERE id = ?");
    $stmt->bind_param('ii', $balance, $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

public function listAll(): array
{
    $stmt = $this->mysqli->prepare("SELECT * FROM Users ORDER BY time DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    $stmt->close();
    return $users;
}
```

## 🎯 **12. TÓM TẮT KỸ THUẬT:**

### **✅ Công nghệ sử dụng:**

- **Backend:** PHP OOP với Repository Pattern
- **Database:** MySQL với prepared statements
- **Frontend:** Tailwind CSS với responsive design
- **Modal:** JavaScript modal cho chỉnh sửa
- **Notifications:** Toastr.js

### **✅ Kiến trúc:**

- **Repository Pattern** - Tách biệt logic database
- **OOP Design** - Code có cấu trúc, dễ maintain
- **Modal System** - Giao diện chỉnh sửa thân thiện
- **Form Handling** - Xử lý form POST

### **✅ Tính năng:**

- **User Listing** - Hiển thị danh sách thành viên
- **Balance Update** - Cập nhật số dư tài khoản
- **User Info** - Hiển thị thông tin chi tiết
- **Statistics** - Thống kê tổng số thành viên

## 🎉 **KẾT LUẬN:**

**Chức năng quản lý thành viên đã được thiết kế hoàn chỉnh với giao diện quản lý chuyên nghiệp và khả năng chỉnh sửa linh hoạt!**
