# 👤 **TRÌNH BÀY THIẾT KẾ VÀ XÂY DỰNG CHỨC NĂNG HỒ SƠ CÁ NHÂN**

## 📝 **1. MÔ TẢ CHỨC NĂNG:**

Chức năng hồ sơ cá nhân cho phép người dùng đã đăng nhập xem thông tin tài khoản, cập nhật thông tin cá nhân (tên đăng nhập, email), xem lịch sử giao dịch, quản lý domain đã mua và theo dõi số dư tài khoản.

## 👤 **2. TÁC NHÂN THỰC HIỆN:**

- **Người dùng đã đăng nhập** - Khách hàng có tài khoản và muốn quản lý thông tin cá nhân

## 🔍 **3. DẠNG TRUY VẤN:**

- **SELECT** - Truy vấn lấy thông tin user và lịch sử giao dịch
- **UPDATE** - Truy vấn cập nhật thông tin cá nhân

## 🗄️ **4. TRUY VẤN VÀO TABLE:**

- **Table:** `Users` (thông tin tài khoản)
- **Table:** `History` (lịch sử mua domain)
- **Table:** `Cards` (lịch sử nạp tiền)

## 📊 **5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

### **Table Users:**

- `id` (int) - ID người dùng
- `taikhoan` (varchar) - Tên đăng nhập
- `email` (varchar) - Email người dùng
- `tien` (int) - Số dư tài khoản
- `time` (varchar) - Thời gian đăng ký

### **Table History:**

- `domain` (varchar) - Domain đã mua
- `ns1` (varchar) - Nameserver 1
- `ns2` (varchar) - Nameserver 2
- `status` (int) - Trạng thái đơn hàng
- `time` (varchar) - Thời gian mua

### **Table Cards:**

- `pin` (varchar) - Mã thẻ cào
- `type` (varchar) - Loại thẻ
- `amount` (int) - Mệnh giá thẻ
- `status` (int) - Trạng thái thẻ
- `time` (varchar) - Thời gian nạp

## 🔄 **6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. Người dùng đã đăng nhập vào hệ thống
2. Người dùng truy cập trang hồ sơ cá nhân
3. Hệ thống hiển thị thông tin tài khoản và lịch sử giao dịch
4. Người dùng có thể chỉnh sửa thông tin cá nhân

## 🔄 **7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Cập nhật thông tin:** Lưu thông tin mới, hiển thị thông báo thành công
2. **Xem lịch sử:** Hiển thị danh sách giao dịch và domain đã mua
3. **Thống kê:** Hiển thị tổng số dư và thống kê giao dịch

## 📊 **8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (User Browser)
    ↓ GET Request (Xem hồ sơ)
    ↓ URL: /Pages/account_profile.php
    ↓ POST Request (Cập nhật thông tin)
    ↓ URL: /Pages/account_profile.php
    ↓ Data: {taikhoan: "newusername", email: "newemail@example.com"}
Web Server (Apache)
    ↓ Xử lý request
File PHP xử lý
    ↓ Pages/account_profile.php
    ↓ include_once UserRepository.php, HistoryRepository.php, CardRepository.php
    ↓ $userRepo = new UserRepository($connect)
PHP Processing
    ↓ UserRepository->findByUsername() (Lấy thông tin user)
    ↓ HistoryRepository->getByUserId() (Lấy lịch sử domain)
    ↓ CardRepository->getByUserId() (Lấy lịch sử thẻ cào)
    ↓ UserRepository->updateProfile() (Cập nhật thông tin)
Database (MySQL)
    ↓ Table: Users, History, Cards
    ↓ Trả về: Array dữ liệu
Response
    ↓ Success: Hiển thị hồ sơ cá nhân
    ↓ Success: toastr.success("Cập nhật thành công!")
    ↓ Error: toastr.error("Cập nhật thất bại!")
```

## 🗃️ **9. BẢNG RECORDSET VÀ ARRAY[KEY]:**

### **Dữ liệu user trong database:**

```sql
| id | taikhoan | email              | tien  | time        |
|----|----------|--------------------|-------|-------------|
| 2  | user1    | user1@example.com  | 50000 | 15/10/2025  |
```

### **Dữ liệu lịch sử domain:**

```sql
| id | domain      | ns1              | ns2              | status | time        |
|----|-------------|------------------|------------------|--------|-------------|
| 1  | example.com | ns1.example.com  | ns2.example.com  | 1      | 15/10/2025  |
```

### **Dữ liệu lịch sử thẻ cào:**

```sql
| id | pin        | type    | amount | status | time        |
|----|------------|---------|--------|--------|-------------|
| 1  | 123456789  | Viettel | 100000 | 1      | 15/10/2025  |
```

### **Array[key] sử dụng:**

- `$user['taikhoan']` - Tên đăng nhập
- `$user['email']` - Email người dùng
- `$user['tien']` - Số dư tài khoản
- `$user['time']` - Thời gian đăng ký
- `$domains` - Danh sách domain đã mua
- `$cards` - Danh sách thẻ cào đã nạp

## 🖼️ **10. GIAO DIỆN CHỨC NĂNG:**

### **Trang hồ sơ cá nhân:**

```html
<div class="min-h-screen bg-gray-50">
  <!-- Header -->
  <div class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center py-6">
        <h1 class="text-3xl font-bold text-gray-900">Hồ Sơ Cá Nhân</h1>
        <div class="text-sm text-gray-500">
          Chào mừng,
          <?= htmlspecialchars($user['taikhoan']) ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Content -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Thông tin tài khoản -->
      <div class="lg:col-span-1">
        <div class="bg-white shadow rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
              Thông Tin Tài Khoản
            </h3>

            <!-- Số dư -->
            <div class="mb-6">
              <div class="bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <svg
                      class="h-8 w-8 text-green-400"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"
                      ></path>
                    </svg>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                      Số dư tài khoản
                    </p>
                    <p class="text-2xl font-bold text-green-900">
                      <?= number_format($user['tien']) ?>đ
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Form chỉnh sửa thông tin -->
            <form method="post" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">
                  Tên đăng nhập
                </label>
                <input
                  type="text"
                  name="taikhoan"
                  value="<?= htmlspecialchars($user['taikhoan']) ?>"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">
                  Email
                </label>
                <input
                  type="email"
                  name="email"
                  value="<?= htmlspecialchars($user['email']) ?>"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
              </div>

              <div>
                <button
                  type="submit"
                  class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  Cập Nhật Thông Tin
                </button>
              </div>
            </form>

            <!-- Thông tin đăng ký -->
            <div class="mt-6 pt-6 border-t border-gray-200">
              <p class="text-sm text-gray-500">
                Thành viên từ:
                <?= $user['time'] ?>
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Lịch sử giao dịch -->
      <div class="lg:col-span-2">
        <div class="space-y-6">
          <!-- Domain đã mua -->
          <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Domain Đã Mua
              </h3>

              <?php if (empty($domains)): ?>
              <div class="text-center py-6">
                <svg
                  class="mx-auto h-12 w-12 text-gray-400"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                  />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">
                  Chưa có domain nào
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                  Bạn chưa mua domain nào.
                </p>
                <div class="mt-6">
                  <a
                    href="/"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
                  >
                    Mua Domain Ngay
                  </a>
                </div>
              </div>
              <?php else: ?>
              <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        Domain
                      </th>
                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        NS1
                      </th>
                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        NS2
                      </th>
                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        Trạng Thái
                      </th>
                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        Thời Gian
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($domains as $domain): ?>
                    <tr>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                      >
                        <?= htmlspecialchars($domain['domain']) ?>
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                      >
                        <?= htmlspecialchars($domain['ns1']) ?>
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                      >
                        <?= htmlspecialchars($domain['ns2']) ?>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <?php
                        $statusClass = $domain['status'] == 1 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                        $statusText = $domain['status'] == 1 ? 'Hoàn thành' : 'Chờ xử lý';
                        ?>
                        <span
                          class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>"
                        >
                          <?= $statusText ?>
                        </span>
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                      >
                        <?= $domain['time'] ?>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Lịch sử nạp tiền -->
          <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Lịch Sử Nạp Tiền
              </h3>

              <?php if (empty($cards)): ?>
              <div class="text-center py-6">
                <svg
                  class="mx-auto h-12 w-12 text-gray-400"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                  />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">
                  Chưa có giao dịch nạp tiền
                </h3>
                <p class="mt-1 text-sm text-gray-500">Bạn chưa nạp tiền nào.</p>
                <div class="mt-6">
                  <a
                    href="/Pages/Recharge.php"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700"
                  >
                    Nạp Tiền Ngay
                  </a>
                </div>
              </div>
              <?php else: ?>
              <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        Mã Thẻ
                      </th>
                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        Loại Thẻ
                      </th>
                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        Mệnh Giá
                      </th>
                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        Trạng Thái
                      </th>
                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        Thời Gian
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($cards as $card): ?>
                    <tr>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                      >
                        <?= htmlspecialchars($card['pin']) ?>
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                      >
                        <?= htmlspecialchars($card['type']) ?>
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                      >
                        <?= number_format($card['amount']) ?>đ
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <?php
                        $statusClass = '';
                        $statusText = '';
                        switch($card['status']) {
                            case 0:
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                $statusText = 'Chờ xử lý';
                                break;
                            case 1:
                                $statusClass = 'bg-green-100 text-green-800';
                                $statusText = 'Thành công';
                                break;
                            case 2:
                                $statusClass = 'bg-red-100 text-red-800';
                                $statusText = 'Thất bại';
                                break;
                        }
                        ?>
                        <span
                          class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>"
                        >
                          <?= $statusText ?>
                        </span>
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                      >
                        <?= $card['time'] ?>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
```

## 💻 **11. CODE XỬ LÝ:**

### **File: Pages/account_profile.php**

```php
<?php
include_once('../Config/Database.php');
include_once('../Repositories/UserRepository.php');
include_once('../Repositories/HistoryRepository.php');
include_once('../Repositories/CardRepository.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['users'])) {
    header('Location: /Pages/login.php');
    exit;
}

$userRepo = new UserRepository($connect);
$historyRepo = new HistoryRepository($connect);
$cardRepo = new CardRepository($connect);

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taikhoan = $_POST['taikhoan'] ?? '';
    $email = $_POST['email'] ?? '';

    if ($userRepo->updateProfile($_SESSION['users'], $taikhoan, $email)) {
        $_SESSION['users'] = $taikhoan; // Cập nhật session
        echo '<script>toastr.success("Cập nhật thông tin thành công!", "Thông Báo");</script>';
    } else {
        echo '<script>toastr.error("Cập nhật thất bại!", "Thông Báo");</script>';
    }
}

// Lấy thông tin user
$user = $userRepo->findByUsername($_SESSION['users']);
if (!$user) {
    header('Location: /Pages/login.php');
    exit;
}

// Lấy lịch sử domain
$domains = $historyRepo->getByUserId($user['id']);

// Lấy lịch sử thẻ cào
$cards = $cardRepo->getByUserId($user['id']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ Sơ Cá Nhân</title>
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

### **Repository: UserRepository->updateProfile()**

```php
public function updateProfile(string $oldUsername, string $newUsername, string $email): bool
{
    $stmt = $this->mysqli->prepare("UPDATE Users SET taikhoan = ?, email = ? WHERE taikhoan = ?");
    $stmt->bind_param('sss', $newUsername, $email, $oldUsername);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}
```

## 🎯 **12. TÓM TẮT KỸ THUẬT:**

### **✅ Công nghệ sử dụng:**

- **Backend:** PHP OOP với Repository Pattern
- **Database:** MySQL với prepared statements
- **Frontend:** Tailwind CSS với responsive design
- **Session:** PHP session management
- **Notifications:** Toastr.js

### **✅ Kiến trúc:**

- **Repository Pattern** - Tách biệt logic database
- **OOP Design** - Code có cấu trúc, dễ maintain
- **Session Management** - Quản lý phiên đăng nhập
- **Data Integration** - Tích hợp nhiều bảng dữ liệu

### **✅ Tính năng:**

- **Profile Management** - Quản lý hồ sơ cá nhân
- **Transaction History** - Lịch sử giao dịch
- **Domain Management** - Quản lý domain đã mua
- **Balance Display** - Hiển thị số dư tài khoản
- **Update Profile** - Cập nhật thông tin cá nhân

## 🎉 **KẾT LUẬN:**

**Chức năng hồ sơ cá nhân đã được thiết kế hoàn chỉnh với giao diện quản lý chuyên nghiệp và tích hợp đầy đủ thông tin người dùng!**
