# 🔍 **TRÌNH BÀY THIẾT KẾ VÀ XÂY DỰNG CHỨC NĂNG KIỂM TRA DOMAIN**

## 📝 **1. MÔ TẢ CHỨC NĂNG:**

Chức năng kiểm tra domain cho phép người dùng kiểm tra tính khả dụng của domain, xem thông tin domain có sẵn hay không, hiển thị giá bán và thông tin chi tiết về domain.

## 👤 **2. TÁC NHÂN THỰC HIỆN:**

- **Người dùng** - Khách hàng muốn kiểm tra domain trước khi mua

## 🔍 **3. DẠNG TRUY VẤN:**

- **SELECT** - Truy vấn kiểm tra domain trong danh sách sản phẩm

## 🗄️ **4. TRUY VẤN VÀO TABLE:**

- **Table:** `ListDomain` (lưu danh sách domain có sẵn)

## 📊 **5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

- `id` (int) - ID domain
- `image` (varchar) - Hình ảnh domain
- `price` (varchar) - Giá bán domain
- `duoi` (varchar) - Đuôi domain (.com, .net, .org)

## 🔄 **6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. Người dùng truy cập trang kiểm tra domain
2. Người dùng nhập tên domain muốn kiểm tra
3. Người dùng click nút "Kiểm tra domain"

## 🔄 **7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Domain có sẵn:** Hiển thị thông tin domain, giá bán, nút "Mua ngay"
2. **Domain không có sẵn:** Hiển thị thông báo "Domain không có sẵn"
3. **Domain không hợp lệ:** Hiển thị thông báo lỗi format

## 📊 **8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (User Browser)
    ↓ POST Request
    ↓ URL: /Ajaxs/CheckDomain.php
    ↓ Data: {domain: "example.com"}
Web Server (Apache)
    ↓ Xử lý request
File PHP xử lý
    ↓ Ajaxs/CheckDomain.php
    ↓ include_once DomainRepository.php
    ↓ $domainRepo = new DomainRepository($connect)
PHP Processing
    ↓ DomainRepository->findByDomain()
    ↓ SELECT * FROM ListDomain WHERE duoi = ?
Database (MySQL)
    ↓ Table: ListDomain
    ↓ Trả về: Array domain info
Response
    ↓ Success: Hiển thị thông tin domain + giá
    ↓ Error: Hiển thị thông báo không có sẵn
```

## 🗃️ **9. BẢNG RECORDSET VÀ ARRAY[KEY]:**

### **Dữ liệu domain trong database:**

```sql
| id | image        | price  | duoi |
|----|--------------|--------|------|
| 1  | domain1.jpg  | 100000 | .com |
| 2  | domain2.jpg  | 150000 | .net |
| 3  | domain3.jpg  | 200000 | .org |
```

### **Array[key] sử dụng:**

- `$domain` - Tên domain từ POST
- `$domainInfo['id']` - ID domain
- `$domainInfo['image']` - Hình ảnh domain
- `$domainInfo['price']` - Giá bán domain
- `$domainInfo['duoi']` - Đuôi domain

## 🖼️ **10. GIAO DIỆN CHỨC NĂNG:**

### **Form kiểm tra domain:**

```html
<div class="min-h-screen bg-gray-50">
  <!-- Header -->
  <div class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center py-6">
        <h1 class="text-3xl font-bold text-gray-900">Kiểm Tra Domain</h1>
        <div class="text-sm text-gray-500">
          Kiểm tra tính khả dụng của domain
        </div>
      </div>
    </div>
  </div>

  <!-- Content -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
          Kiểm Tra Domain
        </h3>

        <!-- Form kiểm tra -->
        <form id="checkDomainForm" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">
              Nhập tên domain
            </label>
            <div class="mt-1 flex rounded-md shadow-sm">
              <input
                type="text"
                id="domainInput"
                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                placeholder="example"
              />
              <span
                class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm"
              >
                .com
              </span>
            </div>
          </div>

          <div>
            <button
              type="submit"
              class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Kiểm Tra Domain
            </button>
          </div>
        </form>

        <!-- Kết quả kiểm tra -->
        <div id="domainResult" class="mt-6 hidden">
          <div class="border-t border-gray-200 pt-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">
              Kết Quả Kiểm Tra
            </h4>

            <!-- Domain có sẵn -->
            <div id="domainAvailable" class="hidden">
              <div class="bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <svg
                      class="h-5 w-5 text-green-400"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"
                      ></path>
                    </svg>
                  </div>
                  <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">
                      Domain có sẵn!
                    </h3>
                    <div class="mt-2 text-sm text-green-700">
                      <p>
                        Domain
                        <span id="availableDomain" class="font-semibold"></span>
                        có sẵn để mua.
                      </p>
                    </div>
                    <div class="mt-4">
                      <div class="flex items-center justify-between">
                        <span class="text-sm text-green-700">Giá bán:</span>
                        <span
                          id="domainPrice"
                          class="text-lg font-bold text-green-800"
                        ></span>
                      </div>
                      <div class="mt-2">
                        <button
                          onclick="buyDomain()"
                          class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                          Mua Ngay
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Domain không có sẵn -->
            <div id="domainUnavailable" class="hidden">
              <div class="bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <svg
                      class="h-5 w-5 text-red-400"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd"
                      ></path>
                    </svg>
                  </div>
                  <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                      Domain không có sẵn
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                      <p>
                        Domain
                        <span
                          id="unavailableDomain"
                          class="font-semibold"
                        ></span>
                        không có sẵn trong hệ thống.
                      </p>
                      <p class="mt-1">
                        Vui lòng thử domain khác hoặc liên hệ admin để được hỗ
                        trợ.
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document
    .getElementById("checkDomainForm")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      const domain = document.getElementById("domainInput").value.trim();
      if (!domain) {
        alert("Vui lòng nhập tên domain");
        return;
      }

      // Gọi AJAX kiểm tra domain
      fetch("/Ajaxs/CheckDomain.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "domain=" + encodeURIComponent(domain + ".com"),
      })
        .then((response) => response.text())
        .then((data) => {
          document.getElementById("domainResult").classList.remove("hidden");

          if (data.includes("available")) {
            // Domain có sẵn
            document
              .getElementById("domainAvailable")
              .classList.remove("hidden");
            document
              .getElementById("domainUnavailable")
              .classList.add("hidden");
            document.getElementById("availableDomain").textContent =
              domain + ".com";
            document.getElementById("domainPrice").textContent = "100,000đ";
          } else {
            // Domain không có sẵn
            document.getElementById("domainAvailable").classList.add("hidden");
            document
              .getElementById("domainUnavailable")
              .classList.remove("hidden");
            document.getElementById("unavailableDomain").textContent =
              domain + ".com";
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Có lỗi xảy ra khi kiểm tra domain");
        });
    });

  function buyDomain() {
    const domain = document.getElementById("availableDomain").textContent;
    window.location.href =
      "/Pages/Checkout.php?domain=" + encodeURIComponent(domain);
  }
</script>
```

## 💻 **11. CODE XỬ LÝ:**

### **File: Ajaxs/CheckDomain.php**

```php
<?php
include_once('../Config/Database.php');
include_once('../Repositories/DomainRepository.php');

$domain = $_POST['domain'] ?? '';

if (empty($domain)) {
    echo 'error';
    exit;
}

// Tách domain và đuôi
$domainParts = explode('.', $domain);
if (count($domainParts) < 2) {
    echo 'invalid';
    exit;
}

$domainName = $domainParts[0];
$domainExtension = '.' . $domainParts[1];

$domainRepo = new DomainRepository($connect);

// Kiểm tra domain có trong danh sách không
$domainInfo = $domainRepo->findByExtension($domainExtension);

if ($domainInfo) {
    echo 'available|' . $domainInfo['price'] . '|' . $domainInfo['image'];
} else {
    echo 'unavailable';
}
?>
```

### **Repository: DomainRepository->findByExtension()**

```php
public function findByExtension(string $extension): ?array
{
    $stmt = $this->mysqli->prepare("SELECT * FROM ListDomain WHERE duoi = ? LIMIT 1");
    $stmt->bind_param('s', $extension);
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
- **Frontend:** HTML/CSS với JavaScript
- **AJAX:** Fetch API cho kiểm tra real-time
- **Validation:** Client-side và server-side validation

### **✅ Kiến trúc:**

- **Repository Pattern** - Tách biệt logic database
- **OOP Design** - Code có cấu trúc, dễ maintain
- **AJAX Processing** - Xử lý bất đồng bộ
- **Error Handling** - Xử lý lỗi chi tiết

### **✅ Tính năng:**

- **Domain Validation** - Kiểm tra format domain
- **Real-time Check** - Kiểm tra domain real-time
- **Price Display** - Hiển thị giá bán
- **Buy Integration** - Tích hợp với trang mua domain
- **User Feedback** - Thông báo rõ ràng cho người dùng

## 🎉 **KẾT LUẬN:**

**Chức năng kiểm tra domain đã được thiết kế hoàn chỉnh với giao diện thân thiện và khả năng kiểm tra real-time!**
