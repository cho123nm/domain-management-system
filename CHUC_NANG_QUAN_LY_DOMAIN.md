# 🌐 **TRÌNH BÀY THIẾT KẾ VÀ XÂY DỰNG CHỨC NĂNG QUẢN LÝ DOMAIN**

## 📝 **1. MÔ TẢ CHỨC NĂNG:**

Chức năng quản lý domain cho phép người dùng xem danh sách các domain đã mua, cập nhật thông tin DNS, xem lịch sử giao dịch và quản lý các domain của mình. Người dùng có thể thay đổi nameserver, xem trạng thái domain và thực hiện các thao tác quản lý khác.

## 👤 **2. TÁC NHÂN THỰC HIỆN:**

- **Người dùng đã đăng nhập** - User có tài khoản và đã mua domain

## 🔍 **3. DẠNG TRUY VẤN:**

- **SELECT** - Lấy danh sách domain của user, thông tin DNS
- **UPDATE** - Cập nhật thông tin DNS cho domain
- **INSERT** - Thêm ghi chú hoặc lịch sử thay đổi

## 🗄️ **4. TRUY VẤN VÀO TABLE:**

- **Table:** `History` (lưu thông tin domain đã mua và DNS)
- **Table:** `Users` (thông tin người dùng)
- **Table:** `ListDomain` (thông tin loại domain)

## 📊 **5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

### **Table History:**

- `id` (int) - ID giao dịch
- `uid` (int) - ID người dùng
- `domain` (varchar) - Tên domain
- `ns1` (varchar) - Nameserver 1
- `ns2` (varchar) - Nameserver 2
- `status` (int) - Trạng thái (0: chờ, 1: hoàn thành)
- `time` (datetime) - Thời gian mua
- `note` (text) - Ghi chú

### **Table Users:**

- `id` (int) - ID người dùng
- `taikhoan` (varchar) - Tên tài khoản
- `sodu` (int) - Số dư hiện tại

## 🔄 **6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. **User đăng nhập vào hệ thống** (đã có session)
2. **User truy cập trang quản lý domain** hoặc URL `/Pages/managers.php`
3. **Hệ thống kiểm tra quyền truy cập** (đã đăng nhập)
4. **Hệ thống load danh sách domain** của user từ database

## 🔄 **7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Thành công:** Hiển thị danh sách domain với thông tin DNS, cho phép cập nhật
2. **Cập nhật DNS:** Lưu thông tin nameserver mới vào database
3. **Xem chi tiết:** Hiển thị thông tin chi tiết domain
4. **Thông báo:** Hiển thị kết quả cập nhật thành công/thất bại

## 📊 **8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (User Browser)
    ↓ GET Request
    ↓ URL: /Pages/managers.php
    ↓ Session Authentication
Web Server (Apache)
    ↓ Xác thực session
    ↓ Xử lý request
File PHP xử lý
    ↓ Pages/managers.php
    ↓ include_once HistoryRepository.php
    ↓ $historyRepo = new HistoryRepository($connect)
PHP Processing
    ↓ HistoryRepository->listByUserId()
    ↓ SELECT * FROM History WHERE uid = ? AND status = 1
Database (MySQL)
    ↓ Table: History
    ↓ Trả về: Array domain của user
Response
    ↓ Render HTML table với dữ liệu
    ↓ Hiển thị danh sách quản lý domain
```

## 🗃️ **9. BẢNG RECORDSET VÀ ARRAY[KEY]:**

### **Dữ liệu thực tế trong database:**

```sql
-- Truy vấn chính
SELECT h.*, u.taikhoan, u.sodu
FROM History h
LEFT JOIN Users u ON h.uid = u.id
WHERE h.uid = ? AND h.status = 1
ORDER BY h.time DESC;

-- Kết quả mẫu:
| id | uid | domain        | ns1              | ns2              | status | time       | taikhoan | sodu |
|----|-----|---------------|------------------|------------------|--------|------------|----------|------|
| 1  | 2   | example.com   | ns1.example.com  | ns2.example.com  | 1      | 2024-01-01 | user1    | 400000|
| 2  | 2   | test.net      | ns1.test.net     | ns2.test.net     | 1      | 2024-01-02 | user1    | 400000|
```

### **Array[key] sử dụng trong PHP:**

```php
// Kết quả từ HistoryRepository->listByUserId()
$userDomains = [
    [
        'id' => 1,
        'uid' => 2,
        'domain' => 'example.com',
        'ns1' => 'ns1.example.com',
        'ns2' => 'ns2.example.com',
        'status' => 1,
        'time' => '2024-01-01 10:30:00',
        'taikhoan' => 'user1',
        'sodu' => 400000
    ],
    [
        'id' => 2,
        'uid' => 2,
        'domain' => 'test.net',
        'ns1' => 'ns1.test.net',
        'ns2' => 'ns2.test.net',
        'status' => 1,
        'time' => '2024-01-02 14:20:00',
        'taikhoan' => 'user1',
        'sodu' => 400000
    ]
];

// Sử dụng trong vòng lặp
foreach ($userDomains as $domain) {
    echo $domain['domain'];     // Tên domain
    echo $domain['ns1'];        // Nameserver 1
    echo $domain['ns2'];        // Nameserver 2
    echo $domain['time'];       // Thời gian mua
}
```

## 🖼️ **10. GIAO DIỆN CHỨC NĂNG:**

### **Trang quản lý domain:**

```html
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản Lý Domain - Tài Khoản</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body>
    <div class="min-h-screen bg-gray-50">
      <!-- Header -->
      <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center py-6">
            <h1 class="text-3xl font-bold text-gray-900">Quản Lý Domain</h1>
            <div class="text-sm text-gray-500">Quản lý domain của bạn</div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Thông tin tài khoản -->
        <div class="bg-white shadow rounded-lg mb-6">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
              Thông tin tài khoản
            </h3>
          </div>
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div class="text-center">
                <div
                  class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3"
                >
                  <svg
                    class="w-8 h-8 text-blue-600"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                </div>
                <h4 class="text-sm font-medium text-gray-900">Tài khoản</h4>
                <p class="text-sm text-gray-500">user1</p>
              </div>
              <div class="text-center">
                <div
                  class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3"
                >
                  <svg
                    class="w-8 h-8 text-green-600"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                    ></path>
                  </svg>
                </div>
                <h4 class="text-sm font-medium text-gray-900">Số dư</h4>
                <p class="text-sm text-gray-500">400,000đ</p>
              </div>
              <div class="text-center">
                <div
                  class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3"
                >
                  <svg
                    class="w-8 h-8 text-purple-600"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                </div>
                <h4 class="text-sm font-medium text-gray-900">Tổng domain</h4>
                <p class="text-sm text-gray-500">2 domain</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Danh sách domain -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
              Danh sách domain của bạn
            </h3>
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
                    Nameserver
                  </th>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Trạng thái
                  </th>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Ngày mua
                  </th>
                  <th
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Hành động
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <!-- Domain 1 -->
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <img
                          class="h-10 w-10 rounded-full"
                          src="images/com.png"
                          alt=".com"
                        />
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                          example.com
                        </div>
                        <div class="text-sm text-gray-500">Tên miền .com</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">ns1.example.com</div>
                    <div class="text-sm text-gray-500">ns2.example.com</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800"
                    >
                      Hoạt động
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    01/01/2024
                  </td>
                  <td
                    class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium"
                  >
                    <button
                      onclick="editDNS(1, 'example.com', 'ns1.example.com', 'ns2.example.com')"
                      class="text-indigo-600 hover:text-indigo-900 mr-3"
                    >
                      Sửa DNS
                    </button>
                    <button
                      onclick="viewDetails(1)"
                      class="text-blue-600 hover:text-blue-900"
                    >
                      Chi tiết
                    </button>
                  </td>
                </tr>

                <!-- Domain 2 -->
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <img
                          class="h-10 w-10 rounded-full"
                          src="images/net.png"
                          alt=".net"
                        />
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                          test.net
                        </div>
                        <div class="text-sm text-gray-500">Tên miền .net</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">ns1.test.net</div>
                    <div class="text-sm text-gray-500">ns2.test.net</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800"
                    >
                      Hoạt động
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    02/01/2024
                  </td>
                  <td
                    class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium"
                  >
                    <button
                      onclick="editDNS(2, 'test.net', 'ns1.test.net', 'ns2.test.net')"
                      class="text-indigo-600 hover:text-indigo-900 mr-3"
                    >
                      Sửa DNS
                    </button>
                    <button
                      onclick="viewDetails(2)"
                      class="text-blue-600 hover:text-blue-900"
                    >
                      Chi tiết
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Hướng dẫn sử dụng -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
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
                Hướng dẫn quản lý domain
              </h3>
              <div class="mt-2 text-sm text-blue-700">
                <ul class="list-disc list-inside space-y-1">
                  <li>
                    <strong>Sửa DNS:</strong> Thay đổi nameserver để trỏ domain
                    đến hosting của bạn
                  </li>
                  <li>
                    <strong>Chi tiết:</strong> Xem thông tin chi tiết về domain
                    và lịch sử thay đổi
                  </li>
                  <li>
                    <strong>DNS có hiệu lực:</strong> Thay đổi DNS thường có
                    hiệu lực trong 24-48 giờ
                  </li>
                  <li>
                    <strong>Hỗ trợ:</strong> Liên hệ support nếu cần hỗ trợ kỹ
                    thuật
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal chỉnh sửa DNS -->
    <div
      id="editDNSModal"
      class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden"
    >
      <div
        class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
      >
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Chỉnh sửa DNS</h3>
          <form id="editDNSForm" method="POST" action="/Ajaxs/UpdateDns.php">
            <input type="hidden" id="edit_domain_id" name="domain_id" />
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2"
                >Domain</label
              >
              <input
                type="text"
                id="edit_domain_name"
                readonly
                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100"
              />
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2"
                >Nameserver 1</label
              >
              <input
                type="text"
                id="edit_ns1"
                name="ns1"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2"
                >Nameserver 2</label
              >
              <input
                type="text"
                id="edit_ns2"
                name="ns2"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            <div class="flex justify-end space-x-2">
              <button
                type="button"
                onclick="closeEditModal()"
                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600"
              >
                Hủy
              </button>
              <button
                type="submit"
                id="updateDNSBtn"
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
              >
                Cập nhật
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script>
      function editDNS(id, domain, ns1, ns2) {
        document.getElementById("edit_domain_id").value = id;
        document.getElementById("edit_domain_name").value = domain;
        document.getElementById("edit_ns1").value = ns1;
        document.getElementById("edit_ns2").value = ns2;
        document.getElementById("editDNSModal").classList.remove("hidden");
      }

      function closeEditModal() {
        document.getElementById("editDNSModal").classList.add("hidden");
      }

      function viewDetails(id) {
        window.location.href = "domain-detail.php?id=" + id;
      }

      // Xử lý form cập nhật DNS
      document
        .getElementById("editDNSForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();

          const updateBtn = document.getElementById("updateDNSBtn");
          const formData = new FormData(this);

          // Disable button và hiển thị loading
          updateBtn.disabled = true;
          updateBtn.innerHTML = "Đang cập nhật...";

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
              updateBtn.disabled = false;
              updateBtn.innerHTML = "Cập nhật";

              // Đóng modal và reload trang sau 2 giây nếu thành công
              if (data.includes("success")) {
                setTimeout(() => {
                  closeEditModal();
                  window.location.reload();
                }, 2000);
              }
            })
            .catch((error) => {
              console.error("Error:", error);
              alert("Có lỗi xảy ra khi cập nhật DNS");

              // Reset button
              updateBtn.disabled = false;
              updateBtn.innerHTML = "Cập nhật";
            });
        });
    </script>
  </body>
</html>
```

## 💻 **11. CODE XỬ LÝ:**

### **File: Pages/managers.php**

```php
<?php
session_start();
include_once('../Config/Header.php');

// Kiểm tra đăng nhập
if (!isset($_SESSION['users'])) {
    echo '<script>alert("Vui lòng đăng nhập!"); window.location.href="/Pages/login.php";</script>';
    exit;
}

// Lấy thông tin user
$username = $_SESSION['users'];
include_once(__DIR__.'/../Repositories/UserRepository.php');
$userRepo = new UserRepository($connect);
$user = $userRepo->findByUsername($username);

if (!$user) {
    echo '<script>alert("Không tìm thấy thông tin người dùng!"); window.location.href="/Pages/login.php";</script>';
    exit;
}

$uid = $user['id'];

// Lấy danh sách domain của user
include_once(__DIR__.'/../Repositories/HistoryRepository.php');
$historyRepo = new HistoryRepository($connect);
$userDomains = $historyRepo->listByUserId($uid);

// Thống kê
$totalDomains = count($userDomains);
$activeDomains = count(array_filter($userDomains, fn($d) => $d['status'] == 1));
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <h1 class="text-3xl font-bold text-gray-900">Quản Lý Domain</h1>
                <div class="text-sm text-gray-500">Quản lý domain của bạn</div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Thông tin tài khoản -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Thông tin tài khoản</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h4 class="text-sm font-medium text-gray-900">Tài khoản</h4>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($user['taikhoan']) ?></p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"></path>
                            </svg>
                        </div>
                        <h4 class="text-sm font-medium text-gray-900">Số dư</h4>
                        <p class="text-sm text-gray-500"><?= number_format($user['sodu']) ?>đ</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h4 class="text-sm font-medium text-gray-900">Tổng domain</h4>
                        <p class="text-sm text-gray-500"><?= $totalDomains ?> domain</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách domain -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Danh sách domain của bạn</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Domain</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nameserver</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày mua</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($userDomains)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                Bạn chưa có domain nào. <a href="/" class="text-blue-600 hover:underline">Mua domain ngay</a>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($userDomains as $domain): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <?php
                                            $domainType = '.' . explode('.', $domain['domain'])[1];
                                            $imagePath = "images/" . str_replace('.', '', $domainType) . ".png";
                                            ?>
                                            <img class="h-10 w-10 rounded-full" src="<?= $imagePath ?>" alt="<?= $domainType ?>">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($domain['domain']) ?></div>
                                            <div class="text-sm text-gray-500">Tên miền <?= htmlspecialchars($domainType) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= htmlspecialchars($domain['ns1']) ?></div>
                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($domain['ns2']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $statusClass = $domain['status'] == 1 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                                    $statusText = $domain['status'] == 1 ? 'Hoạt động' : 'Chờ xử lý';
                                    ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>">
                                        <?= $statusText ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('d/m/Y', strtotime($domain['time'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <button onclick="editDNS(<?= $domain['id'] ?>, '<?= htmlspecialchars($domain['domain']) ?>', '<?= htmlspecialchars($domain['ns1']) ?>', '<?= htmlspecialchars($domain['ns2']) ?>')"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        Sửa DNS
                                    </button>
                                    <button onclick="viewDetails(<?= $domain['id'] ?>)"
                                            class="text-blue-600 hover:text-blue-900">
                                        Chi tiết
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Hướng dẫn sử dụng -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        Hướng dẫn quản lý domain
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Sửa DNS:</strong> Thay đổi nameserver để trỏ domain đến hosting của bạn</li>
                            <li><strong>Chi tiết:</strong> Xem thông tin chi tiết về domain và lịch sử thay đổi</li>
                            <li><strong>DNS có hiệu lực:</strong> Thay đổi DNS thường có hiệu lực trong 24-48 giờ</li>
                            <li><strong>Hỗ trợ:</strong> Liên hệ support nếu cần hỗ trợ kỹ thuật</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal chỉnh sửa DNS -->
<div id="editDNSModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Chỉnh sửa DNS</h3>
            <form id="editDNSForm" method="POST" action="/Ajaxs/UpdateDns.php">
                <input type="hidden" id="edit_domain_id" name="domain_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Domain</label>
                    <input type="text" id="edit_domain_name" readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nameserver 1</label>
                    <input type="text" id="edit_ns1" name="ns1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nameserver 2</label>
                    <input type="text" id="edit_ns2" name="ns2" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Hủy
                    </button>
                    <button type="submit" id="updateDNSBtn"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editDNS(id, domain, ns1, ns2) {
    document.getElementById('edit_domain_id').value = id;
    document.getElementById('edit_domain_name').value = domain;
    document.getElementById('edit_ns1').value = ns1;
    document.getElementById('edit_ns2').value = ns2;
    document.getElementById('editDNSModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editDNSModal').classList.add('hidden');
}

function viewDetails(id) {
    window.location.href = 'domain-detail.php?id=' + id;
}

// Xử lý form cập nhật DNS
document.getElementById('editDNSForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const updateBtn = document.getElementById('updateDNSBtn');
    const formData = new FormData(this);

    // Disable button và hiển thị loading
    updateBtn.disabled = true;
    updateBtn.innerHTML = 'Đang cập nhật...';

    // Gửi AJAX request
    fetch('/Ajaxs/UpdateDns.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Hiển thị kết quả
        document.body.insertAdjacentHTML('beforeend', data);

        // Reset button
        updateBtn.disabled = false;
        updateBtn.innerHTML = 'Cập nhật';

        // Đóng modal và reload trang sau 2 giây nếu thành công
        if (data.includes('success')) {
            setTimeout(() => {
                closeEditModal();
                window.location.reload();
            }, 2000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật DNS');

        // Reset button
        updateBtn.disabled = false;
        updateBtn.innerHTML = 'Cập nhật';
    });
});
</script>

<?php
include_once('../Config/Footer.php');
?>
```

### **Repository: HistoryRepository->listByUserId()**

```php
public function listByUserId(int $uid): array
{
    try {
        $stmt = $this->mysqli->prepare("
            SELECT h.*, u.taikhoan, u.sodu
            FROM History h
            LEFT JOIN Users u ON h.uid = u.id
            WHERE h.uid = ? AND h.status = 1
            ORDER BY h.time DESC
        ");
        $stmt->bind_param('i', $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $rows;

    } catch (Exception $e) {
        error_log("HistoryRepository->listByUserId() Error: " . $e->getMessage());
        return [];
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
```

## 🎯 **12. TÓM TẮT KỸ THUẬT:**

### **✅ Công nghệ sử dụng:**

- **Backend:** PHP OOP với Repository Pattern
- **Database:** MySQL với prepared statements
- **Frontend:** HTML + Tailwind CSS + JavaScript
- **Session:** PHP session management
- **AJAX:** Fetch API cho cập nhật DNS

### **✅ Kiến trúc:**

- **Repository Pattern** - Tách biệt logic database
- **MVC Pattern** - Model-View-Controller
- **AJAX Pattern** - Cập nhật DNS không reload trang
- **Responsive Design** - Giao diện đẹp trên mọi thiết bị

### **✅ Tính năng:**

- **Quản lý domain** - Xem danh sách domain đã mua
- **Cập nhật DNS** - Thay đổi nameserver cho domain
- **Thông tin tài khoản** - Hiển thị số dư và thống kê
- **Modal system** - Chỉnh sửa DNS inline
- **User-friendly** - Giao diện thân thiện, dễ sử dụng

### **✅ Bảo mật:**

- **Session Authentication** - Kiểm tra đăng nhập
- **Ownership Validation** - Chỉ hiển thị domain của user
- **Prepared Statements** - Chống SQL injection
- **Input Validation** - Kiểm tra dữ liệu đầu vào
- **XSS Protection** - Escape output với htmlspecialchars

## 🎬 **13. DEMO CHỨC NĂNG:**

### **Bước 1: Truy cập trang quản lý domain**

```
URL: http://localhost/Pages/managers.php
```

### **Bước 2: Xem thông tin tài khoản và danh sách domain**

```
┌─────────────────────────────────────────────────────────────┐
│  🌐 Quản Lý Domain                                        │
├─────────────────────────────────────────────────────────────┤
│  📊 Thông tin tài khoản:                                  │
│  Tài khoản: user1    Số dư: 400,000đ    Tổng domain: 2    │
├─────────────────────────────────────────────────────────────┤
│  📋 Danh sách domain:                                     │
│  Domain      │ Nameserver        │ Trạng thái │ Hành động │
│  example.com │ ns1.example.com   │ Hoạt động  │ [Sửa DNS][Chi tiết] │
│              │ ns2.example.com   │            │           │
│  test.net    │ ns1.test.net      │ Hoạt động  │ [Sửa DNS][Chi tiết] │
│              │ ns2.test.net      │            │           │
└─────────────────────────────────────────────────────────────┘
```

### **Bước 3: Chỉnh sửa DNS**

- Click **"Sửa DNS"** → Hiển thị modal
- Thay đổi NS1, NS2 → Click **"Cập nhật"**
- AJAX request gửi đi → Hiển thị thông báo kết quả

### **Bước 4: Kết quả**

- **Thành công:** "Cập nhật DNS thành công! DNS sẽ có hiệu lực trong 24-48 giờ."
- **Thất bại:** "Có lỗi xảy ra khi cập nhật DNS"

### **Bước 5: Cập nhật giao diện**

- Trang tự động reload sau 2 giây
- Hiển thị thông tin DNS mới

## 🎉 **KẾT LUẬN:**

**Chức năng quản lý domain đã được thiết kế hoàn chỉnh với giao diện đẹp, tính năng đầy đủ và bảo mật cao!**

**Đặc điểm nổi bật:**

- ✅ **Quản lý domain chuyên nghiệp** - Xem và quản lý domain dễ dàng
- ✅ **Cập nhật DNS trực quan** - Modal chỉnh sửa thân thiện
- ✅ **Thông tin tài khoản** - Dashboard với thống kê
- ✅ **Bảo mật cao** - Chỉ hiển thị domain của user
- ✅ **Responsive design** - Tương thích mọi thiết bị
- ✅ **AJAX update** - Cập nhật DNS không reload trang
- ✅ **User-friendly** - Giao diện thân thiện, dễ sử dụng
- ✅ **Hướng dẫn chi tiết** - Giúp user hiểu cách sử dụng
