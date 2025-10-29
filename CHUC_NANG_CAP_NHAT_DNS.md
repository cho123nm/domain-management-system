# 🔄 **TRÌNH BÀY THIẾT KẾ VÀ XÂY DỰNG CHỨC NĂNG CẬP NHẬT DNS**

## 📝 **1. MÔ TẢ CHỨC NĂNG:**

Chức năng cập nhật DNS cho phép người dùng thay đổi thông tin nameserver cho domain đã mua. Người dùng có thể cập nhật NS1 và NS2 để trỏ domain đến hosting/server của họ. Hệ thống sẽ lưu thông tin DNS mới và thông báo kết quả.

## 👤 **2. TÁC NHÂN THỰC HIỆN:**

- **Người dùng đã đăng nhập** - User có domain đã mua và muốn cập nhật DNS

## 🔍 **3. DẠNG TRUY VẤN:**

- **UPDATE** - Cập nhật thông tin DNS trong database
- **SELECT** - Kiểm tra quyền sở hữu domain

## 🗄️ **4. TRUY VẤN VÀO TABLE:**

- **Table:** `History` (lưu thông tin domain và DNS)

## 📊 **5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

- `id` (int) - ID giao dịch
- `uid` (int) - ID người dùng
- `domain` (varchar) - Tên domain
- `ns1` (varchar) - Nameserver 1
- `ns2` (varchar) - Nameserver 2
- `status` (int) - Trạng thái (0: chờ, 1: hoàn thành)

## 🔄 **6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. **User đăng nhập vào hệ thống** (đã có session)
2. **User truy cập trang quản lý domain** hoặc profile
3. **User chọn domain cần cập nhật DNS**
4. **User nhập thông tin NS1 và NS2 mới**

## 🔄 **7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Thành công:** Cập nhật DNS trong database, hiển thị thông báo thành công
2. **Thất bại:** Hiển thị thông báo lỗi (domain không tồn tại, không có quyền)
3. **Validation:** Kiểm tra định dạng nameserver hợp lệ
4. **Redirect:** Chuyển hướng về trang quản lý domain

## 📊 **8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (User Browser)
    ↓ POST Request
    ↓ URL: /Ajaxs/UpdateDns.php
    ↓ Data: {domain: "example.com", ns1: "ns1.new.com", ns2: "ns2.new.com"}
Web Server (Apache)
    ↓ Xử lý request
File PHP xử lý
    ↓ Ajaxs/UpdateDns.php
    ↓ include_once HistoryRepository.php
    ↓ $historyRepo = new HistoryRepository($connect)
PHP Processing
    ↓ Kiểm tra quyền sở hữu domain
    ↓ HistoryRepository->updateDns()
    ↓ UPDATE History SET ns1=?, ns2=? WHERE domain=? AND uid=?
Database (MySQL)
    ↓ Table: History
    ↓ Trả về: true/false
Response
    ↓ Success: Hiển thị thông báo thành công
    ↓ Error: Hiển thị thông báo lỗi
```

## 🗃️ **9. BẢNG RECORDSET VÀ ARRAY[KEY]:**

### **Dữ liệu thực tế trong database:**

```sql
-- Truy vấn kiểm tra quyền sở hữu
SELECT * FROM History WHERE domain = ? AND uid = ? AND status = 1;

-- Truy vấn cập nhật DNS
UPDATE History SET ns1 = ?, ns2 = ? WHERE domain = ? AND uid = ?;

-- Kết quả mẫu:
| id | uid | domain        | ns1              | ns2              | status |
|----|-----|---------------|------------------|------------------|--------|
| 1  | 2   | example.com   | ns1.new.com      | ns2.new.com      | 1      |
```

### **Array[key] sử dụng trong PHP:**

```php
// Dữ liệu POST từ form
$domain = $_POST['domain'] ?? '';        // Tên domain
$ns1 = $_POST['ns1'] ?? '';              // Nameserver 1
$ns2 = $_POST['ns2'] ?? '';              // Nameserver 2
$uid = $_SESSION['user_id'] ?? 0;        // ID người dùng

// Kết quả kiểm tra quyền sở hữu
$domainInfo = [
    'id' => 1,
    'uid' => 2,
    'domain' => 'example.com',
    'ns1' => 'ns1.old.com',
    'ns2' => 'ns2.old.com',
    'status' => 1
];

// Sử dụng trong validation
if ($domainInfo && $domainInfo['uid'] == $uid) {
    // Có quyền cập nhật
    $result = $historyRepo->updateDns($domain, $ns1, $ns2, $uid);
}
```

## 🖼️ **10. GIAO DIỆN CHỨC NĂNG:**

### **Form cập nhật DNS:**

```html
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cập Nhật DNS - Quản Lý Domain</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body>
    <div class="min-h-screen bg-gray-50">
      <!-- Header -->
      <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center py-6">
            <h1 class="text-3xl font-bold text-gray-900">Cập Nhật DNS</h1>
            <div class="text-sm text-gray-500">
              Quản lý nameserver cho domain
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Form cập nhật DNS -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
              Cập nhật Nameserver
            </h3>
            <p class="mt-1 text-sm text-gray-500">
              Thay đổi thông tin nameserver cho domain của bạn
            </p>
          </div>
          <div class="p-6">
            <form
              id="updateDnsForm"
              method="POST"
              action="/Ajaxs/UpdateDns.php"
            >
              <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Domain
                </label>
                <select
                  id="domainSelect"
                  name="domain"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="">Chọn domain...</option>
                  <option value="example.com">example.com</option>
                  <option value="test.net">test.net</option>
                </select>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nameserver 1 (NS1)
                  </label>
                  <input
                    type="text"
                    name="ns1"
                    id="ns1"
                    required
                    placeholder="ns1.yourhosting.com"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                  <p class="mt-1 text-xs text-gray-500">
                    Ví dụ: ns1.yourhosting.com
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nameserver 2 (NS2)
                  </label>
                  <input
                    type="text"
                    name="ns2"
                    id="ns2"
                    required
                    placeholder="ns2.yourhosting.com"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                  <p class="mt-1 text-xs text-gray-500">
                    Ví dụ: ns2.yourhosting.com
                  </p>
                </div>
              </div>

              <div class="mt-6">
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                  <div class="flex">
                    <div class="flex-shrink-0">
                      <svg
                        class="h-5 w-5 text-blue-400"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                          clip-rule="evenodd"
                        ></path>
                      </svg>
                    </div>
                    <div class="ml-3">
                      <h3 class="text-sm font-medium text-blue-800">
                        Lưu ý quan trọng
                      </h3>
                      <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                          <li>
                            DNS có thể mất 24-48 giờ để cập nhật hoàn toàn
                          </li>
                          <li>
                            Đảm bảo nameserver của bạn đã được cấu hình đúng
                          </li>
                          <li>
                            Kiểm tra với nhà cung cấp hosting về thông tin
                            nameserver
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-6 flex justify-end space-x-3">
                <button
                  type="button"
                  onclick="resetForm()"
                  class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                  Đặt lại
                </button>
                <button
                  type="submit"
                  id="submitBtn"
                  class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Cập nhật DNS
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Danh sách domain hiện tại -->
        <div class="bg-white shadow rounded-lg mt-6">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Domain hiện tại</h3>
          </div>
          <div class="overflow-x-auto">
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
                    Trạng thái
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">
                      example.com
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">ns1.old.com</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">ns2.old.com</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800"
                    >
                      Hoạt động
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <script>
      // Xử lý form submit
      document
        .getElementById("updateDnsForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();

          const formData = new FormData(this);
          const submitBtn = document.getElementById("submitBtn");

          // Disable button và hiển thị loading
          submitBtn.disabled = true;
          submitBtn.innerHTML = "Đang cập nhật...";

          // Gửi AJAX request
          fetch("/Ajaxs/UpdateDns.php", {
            method: "POST",
            body: formData,
          })
            .then((response) => response.text())
            .then((data) => {
              // Hiển thị kết quả
              document.body.insertAdjacentHTML("beforeend", data);

              // Reset button
              submitBtn.disabled = false;
              submitBtn.innerHTML = "Cập nhật DNS";

              // Reload trang sau 2 giây nếu thành công
              if (data.includes("success")) {
                setTimeout(() => {
                  window.location.reload();
                }, 2000);
              }
            })
            .catch((error) => {
              console.error("Error:", error);
              alert("Có lỗi xảy ra khi cập nhật DNS");

              // Reset button
              submitBtn.disabled = false;
              submitBtn.innerHTML = "Cập nhật DNS";
            });
        });

      // Reset form
      function resetForm() {
        document.getElementById("updateDnsForm").reset();
      }

      // Load domain list khi trang load
      document.addEventListener("DOMContentLoaded", function () {
        // Có thể load danh sách domain từ AJAX
        loadUserDomains();
      });

      function loadUserDomains() {
        // AJAX call để load danh sách domain của user
        // Implementation tùy theo backend
      }
    </script>
  </body>
</html>
```

## 💻 **11. CODE XỬ LÝ:**

### **File: Ajaxs/UpdateDns.php**

```php
<?php
session_start();
include_once('../Config/Database.php');
include_once('../Repositories/HistoryRepository.php');

// Kiểm tra đăng nhập
if (!isset($_SESSION['users'])) {
    echo '<script>toastr.error("Vui lòng đăng nhập để sử dụng chức năng này!", "Thông Báo");</script>';
    exit;
}

// Lấy dữ liệu POST
$domain = trim($_POST['domain'] ?? '');
$ns1 = trim($_POST['ns1'] ?? '');
$ns2 = trim($_POST['ns2'] ?? '');

// Validation dữ liệu đầu vào
if (empty($domain) || empty($ns1) || empty($ns2)) {
    echo '<script>toastr.error("Vui lòng nhập đầy đủ thông tin!", "Thông Báo");</script>';
    exit;
}

// Validation định dạng nameserver
if (!preg_match('/^[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $ns1) ||
    !preg_match('/^[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $ns2)) {
    echo '<script>toastr.error("Định dạng nameserver không hợp lệ!", "Thông Báo");</script>';
    exit;
}

try {
    // Lấy thông tin user
    $username = $_SESSION['users'];
    $userRepo = new UserRepository($connect);
    $user = $userRepo->findByUsername($username);

    if (!$user) {
        echo '<script>toastr.error("Không tìm thấy thông tin người dùng!", "Thông Báo");</script>';
        exit;
    }

    $uid = $user['id'];

    // Kiểm tra quyền sở hữu domain
    $historyRepo = new HistoryRepository($connect);
    $domainInfo = $historyRepo->findByDomainAndUser($domain, $uid);

    if (!$domainInfo) {
        echo '<script>toastr.error("Bạn không có quyền cập nhật DNS cho domain này!", "Thông Báo");</script>';
        exit;
    }

    // Cập nhật DNS
    $result = $historyRepo->updateDns($domain, $ns1, $ns2, $uid);

    if ($result) {
        // Log hoạt động
        $logMessage = "User {$username} đã cập nhật DNS cho domain {$domain}: {$ns1}, {$ns2}";
        error_log($logMessage);

        echo '<script>toastr.success("Cập nhật DNS thành công! DNS sẽ có hiệu lực trong 24-48 giờ.", "Thông Báo");</script>';
    } else {
        echo '<script>toastr.error("Có lỗi xảy ra khi cập nhật DNS. Vui lòng thử lại!", "Thông Báo");</script>';
    }

} catch (Exception $e) {
    error_log("DNS Update Error: " . $e->getMessage());
    echo '<script>toastr.error("Có lỗi xảy ra trong hệ thống. Vui lòng thử lại sau!", "Thông Báo");</script>';
}
?>
```

### **Repository: HistoryRepository->updateDns()**

```php
public function updateDns(string $domain, string $ns1, string $ns2, int $uid): bool
{
    try {
        // Kiểm tra quyền sở hữu domain
        $stmt = $this->mysqli->prepare("SELECT id FROM History WHERE domain = ? AND uid = ? AND status = 1 LIMIT 1");
        $stmt->bind_param('si', $domain, $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if (!$row) {
            return false; // Không có quyền sở hữu
        }

        // Cập nhật DNS
        $stmt = $this->mysqli->prepare("UPDATE History SET ns1 = ?, ns2 = ? WHERE domain = ? AND uid = ? AND status = 1");
        $stmt->bind_param('sssi', $ns1, $ns2, $domain, $uid);
        $result = $stmt->execute();
        $stmt->close();

        return $result;

    } catch (Exception $e) {
        error_log("HistoryRepository->updateDns() Error: " . $e->getMessage());
        return false;
    }
}

public function findByDomainAndUser(string $domain, int $uid): ?array
{
    $stmt = $this->mysqli->prepare("SELECT * FROM History WHERE domain = ? AND uid = ? AND status = 1 LIMIT 1");
    $stmt->bind_param('si', $domain, $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return $row ?: null;
}

public function listByUserId(int $uid): array
{
    $stmt = $this->mysqli->prepare("SELECT * FROM History WHERE uid = ? AND status = 1 ORDER BY id DESC");
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $rows;
}
```

## 🎯 **12. TÓM TẮT KỸ THUẬT:**

### **✅ Công nghệ sử dụng:**

- **Backend:** PHP OOP với Repository Pattern
- **Database:** MySQL với prepared statements
- **Frontend:** HTML + Tailwind CSS + JavaScript
- **AJAX:** Fetch API cho cập nhật không reload trang
- **Session:** PHP session management

### **✅ Kiến trúc:**

- **Repository Pattern** - Tách biệt logic database
- **MVC Pattern** - Model-View-Controller
- **AJAX Pattern** - Cập nhật không reload trang
- **Responsive Design** - Giao diện đẹp trên mọi thiết bị

### **✅ Tính năng:**

- **Cập nhật DNS** - Thay đổi nameserver cho domain
- **Validation** - Kiểm tra quyền sở hữu và định dạng
- **Real-time feedback** - Thông báo kết quả ngay lập tức
- **Security** - Kiểm tra quyền sở hữu domain
- **User-friendly** - Giao diện thân thiện, dễ sử dụng

### **✅ Bảo mật:**

- **Session Authentication** - Kiểm tra đăng nhập
- **Ownership Validation** - Kiểm tra quyền sở hữu domain
- **Prepared Statements** - Chống SQL injection
- **Input Validation** - Kiểm tra định dạng nameserver
- **Error Handling** - Xử lý lỗi an toàn

## 🎬 **13. DEMO CHỨC NĂNG:**

### **Bước 1: Truy cập trang cập nhật DNS**

```
URL: http://localhost/Pages/managers.php
```

### **Bước 2: Chọn domain và nhập thông tin DNS**

```
┌─────────────────────────────────────────────────────────────┐
│  🔄 Cập Nhật DNS                                          │
├─────────────────────────────────────────────────────────────┤
│  Domain: [example.com ▼]                                  │
│  NS1: [ns1.newhosting.com                    ]             │
│  NS2: [ns2.newhosting.com                    ]             │
│                                                             │
│  [Đặt lại]                    [Cập nhật DNS]              │
├─────────────────────────────────────────────────────────────┤
│  Domain hiện tại:                                         │
│  Domain      │ NS1           │ NS2           │ Trạng thái  │
│  example.com │ ns1.old.com   │ ns2.old.com   │ Hoạt động   │
└─────────────────────────────────────────────────────────────┘
```

### **Bước 3: Submit form**

- Click **"Cập nhật DNS"** → AJAX request gửi đi
- Button chuyển thành **"Đang cập nhật..."**
- Hiển thị thông báo kết quả

### **Bước 4: Kết quả**

- **Thành công:** "Cập nhật DNS thành công! DNS sẽ có hiệu lực trong 24-48 giờ."
- **Thất bại:** "Bạn không có quyền cập nhật DNS cho domain này!"

### **Bước 5: Cập nhật giao diện**

- Trang tự động reload sau 2 giây
- Hiển thị thông tin DNS mới

## 🎉 **KẾT LUẬN:**

**Chức năng cập nhật DNS đã được thiết kế hoàn chỉnh với giao diện đẹp, bảo mật cao và trải nghiệm người dùng tốt!**

**Đặc điểm nổi bật:**

- ✅ **Bảo mật cao** - Kiểm tra quyền sở hữu domain
- ✅ **User-friendly** - Giao diện thân thiện, dễ sử dụng
- ✅ **Real-time feedback** - Thông báo kết quả ngay lập tức
- ✅ **AJAX update** - Không cần reload trang
- ✅ **Validation đầy đủ** - Kiểm tra định dạng và quyền
- ✅ **Responsive design** - Tương thích mọi thiết bị
- ✅ **Error handling** - Xử lý lỗi an toàn và thông báo rõ ràng
