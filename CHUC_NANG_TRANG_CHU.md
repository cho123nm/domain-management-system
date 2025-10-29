# 🏠 **TRÌNH BÀY THIẾT KẾ VÀ XÂY DỰNG CHỨC NĂNG TRANG CHỦ**

## 📝 **1. MÔ TẢ CHỨC NĂNG:**

Chức năng trang chủ là giao diện chính của website, hiển thị danh sách các loại domain có sẵn, thông tin về dịch vụ, banner quảng cáo và các liên kết điều hướng đến các chức năng khác của hệ thống.

## 👤 **2. TÁC NHÂN THỰC HIỆN:**

- **Tất cả người dùng** - Khách hàng truy cập website (có thể chưa đăng nhập)

## 🔍 **3. DẠNG TRUY VẤN:**

- **SELECT** - Truy vấn lấy danh sách domain có sẵn

## 🗄️ **4. TRUY VẤN VÀO TABLE:**

- **Table:** `ListDomain` (hiển thị danh sách domain)
- **Table:** `CaiDatChung` (lấy thông tin cài đặt website)

## 📊 **5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

### **Table ListDomain:**

- `id` (int) - ID domain
- `image` (varchar) - Hình ảnh domain
- `price` (varchar) - Giá bán domain
- `duoi` (varchar) - Đuôi domain (.com, .net, .org)

### **Table CaiDatChung:**

- `tieude` (varchar) - Tiêu đề website
- `mota` (text) - Mô tả website
- `banner` (varchar) - Banner website
- `logo` (varchar) - Logo website

## 🔄 **6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. Người dùng truy cập website
2. Hệ thống load trang chủ
3. Hiển thị thông tin website và danh sách domain
4. Người dùng có thể xem thông tin và chọn domain

## 🔄 **7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Xem domain:** Hiển thị danh sách domain với giá bán
2. **Chọn domain:** Chuyển hướng đến trang thanh toán
3. **Đăng nhập:** Chuyển hướng đến trang đăng nhập
4. **Đăng ký:** Chuyển hướng đến trang đăng ký

## 📊 **8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (User Browser)
    ↓ GET Request
    ↓ URL: /index.php
Web Server (Apache)
    ↓ Xử lý request
File PHP xử lý
    ↓ index.php
    ↓ include_once DomainRepository.php, SettingsRepository.php
    ↓ $domainRepo = new DomainRepository($connect)
    ↓ $settingsRepo = new SettingsRepository($connect)
PHP Processing
    ↓ DomainRepository->listAll()
    ↓ SELECT * FROM ListDomain ORDER BY id ASC
    ↓ SettingsRepository->getSettings()
    ↓ SELECT * FROM CaiDatChung WHERE id=1
Database (MySQL)
    ↓ Table: ListDomain, CaiDatChung
    ↓ Trả về: Array domain + settings
Response
    ↓ Success: Hiển thị trang chủ với danh sách domain
    ↓ Success: Hiển thị thông tin website
```

## 🗃️ **9. BẢNG RECORDSET VÀ ARRAY[KEY]:**

### **Dữ liệu domain trong database:**

```sql
| id | image        | price  | duoi |
|----|--------------|--------|------|
| 1  | domain1.jpg  | 100000 | .com |
| 2  | domain2.jpg  | 150000 | .net |
| 3  | domain3.jpg  | 200000 | .org |
| 4  | domain4.jpg  | 120000 | .info|
```

### **Dữ liệu cài đặt website:**

```sql
| id | tieude        | mota                    | banner     | logo      |
|----|---------------|-------------------------|------------|-----------|
| 1  | My Domain Shop| Shop domain uy tín      | banner.png | logo.png  |
```

### **Array[key] sử dụng:**

- `$domains` - Danh sách domain từ ListDomain
- `$domain['id']` - ID domain
- `$domain['image']` - Hình ảnh domain
- `$domain['price']` - Giá bán domain
- `$domain['duoi']` - Đuôi domain
- `$settings['tieude']` - Tiêu đề website
- `$settings['mota']` - Mô tả website
- `$settings['banner']` - Banner website
- `$settings['logo']` - Logo website

## 🖼️ **10. GIAO DIỆN CHỨC NĂNG:**

### **Trang chủ hoàn chỉnh:**

```html
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      <?= htmlspecialchars($settings['tieude'] ?? 'My Domain Shop') ?>
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
  </head>
  <body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <div class="flex items-center">
            <img
              class="h-8 w-auto"
              src="<?= htmlspecialchars($settings['logo'] ?? '/images/logo.png') ?>"
              alt="Logo"
            />
            <h1 class="ml-3 text-2xl font-bold text-gray-900">
              <?= htmlspecialchars($settings['tieude'] ?? 'My Domain Shop') ?>
            </h1>
          </div>

          <div class="flex items-center space-x-4">
            <?php if (isset($_SESSION['users'])): ?>
            <div class="text-sm text-gray-500">
              Chào mừng,
              <?= htmlspecialchars($_SESSION['users']) ?>
            </div>
            <a
              href="/Pages/account_profile.php"
              class="text-indigo-600 hover:text-indigo-900"
              >Hồ sơ</a
            >
            <a href="/Pages/logout.php" class="text-red-600 hover:text-red-900"
              >Đăng xuất</a
            >
            <?php else: ?>
            <a
              href="/Pages/login.php"
              class="text-indigo-600 hover:text-indigo-900"
              >Đăng nhập</a
            >
            <a
              href="/Pages/register.php"
              class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700"
              >Đăng ký</a
            >
            <?php endif; ?>
          </div>
        </div>
      </div>
    </header>

    <!-- Banner -->
    <div class="bg-indigo-600">
      <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-4xl font-extrabold text-white sm:text-5xl">
            Mua Domain Chất Lượng Cao
          </h2>
          <p class="mt-4 text-xl text-indigo-200">
            <?= htmlspecialchars($settings['mota'] ?? 'Shop domain uy tín với giá cả hợp lý') ?>
          </p>
          <div class="mt-8">
            <a
              href="#domains"
              class="bg-white text-indigo-600 px-8 py-3 rounded-md text-lg font-medium hover:bg-gray-100"
            >
              Xem Danh Sách Domain
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
      <!-- Features -->
      <div class="text-center mb-16">
        <h3 class="text-3xl font-extrabold text-gray-900 mb-8">
          Tại Sao Chọn Chúng Tôi?
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center">
            <div
              class="bg-indigo-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4"
            >
              <svg
                class="w-8 h-8 text-indigo-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                ></path>
              </svg>
            </div>
            <h4 class="text-xl font-semibold text-gray-900 mb-2">Uy Tín</h4>
            <p class="text-gray-600">
              Domain chất lượng cao, đảm bảo hoạt động ổn định
            </p>
          </div>

          <div class="text-center">
            <div
              class="bg-indigo-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4"
            >
              <svg
                class="w-8 h-8 text-indigo-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"
                ></path>
              </svg>
            </div>
            <h4 class="text-xl font-semibold text-gray-900 mb-2">Giá Rẻ</h4>
            <p class="text-gray-600">
              Giá cả cạnh tranh, phù hợp với mọi ngân sách
            </p>
          </div>

          <div class="text-center">
            <div
              class="bg-indigo-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4"
            >
              <svg
                class="w-8 h-8 text-indigo-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 10V3L4 14h7v7l9-11h-7z"
                ></path>
              </svg>
            </div>
            <h4 class="text-xl font-semibold text-gray-900 mb-2">
              Nhanh Chóng
            </h4>
            <p class="text-gray-600">
              Giao domain ngay sau khi thanh toán thành công
            </p>
          </div>
        </div>
      </div>

      <!-- Domain List -->
      <div id="domains" class="mb-16">
        <h3 class="text-3xl font-extrabold text-gray-900 text-center mb-8">
          Danh Sách Domain
        </h3>

        <?php if (empty($domains)): ?>
        <div class="text-center py-12">
          <svg
            class="mx-auto h-12 w-12 text-gray-400"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
            ></path>
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">
            Chưa có domain nào
          </h3>
          <p class="mt-1 text-sm text-gray-500">
            Hiện tại chưa có domain nào trong hệ thống.
          </p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <?php foreach ($domains as $domain): ?>
          <div
            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
          >
            <div class="p-6">
              <div class="flex items-center justify-center mb-4">
                <img
                  src="<?= htmlspecialchars($domain['image'] ?? '/images/default-domain.png') ?>"
                  alt="Domain <?= htmlspecialchars($domain['duoi']) ?>"
                  class="h-16 w-16 object-cover rounded-lg"
                  onerror="this.src='/images/default-domain.png'"
                />
              </div>

              <h4 class="text-xl font-semibold text-gray-900 text-center mb-2">
                Domain
                <?= htmlspecialchars($domain['duoi']) ?>
              </h4>

              <p class="text-3xl font-bold text-indigo-600 text-center mb-4">
                <?= number_format($domain['price']) ?>đ
              </p>

              <div class="space-y-2 mb-6">
                <div class="flex items-center text-sm text-gray-600">
                  <svg
                    class="w-4 h-4 mr-2 text-green-500"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                  Hoạt động ổn định
                </div>
                <div class="flex items-center text-sm text-gray-600">
                  <svg
                    class="w-4 h-4 mr-2 text-green-500"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                  Hỗ trợ 24/7
                </div>
                <div class="flex items-center text-sm text-gray-600">
                  <svg
                    class="w-4 h-4 mr-2 text-green-500"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                  Giao ngay
                </div>
              </div>

              <div class="space-y-2">
                <a
                  href="/Pages/Checkout.php?domain=example<?= htmlspecialchars($domain['duoi']) ?>&price=<?= $domain['price'] ?>"
                  class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition-colors text-center block"
                >
                  Mua Ngay
                </a>
                <button
                  onclick="checkDomain('example<?= htmlspecialchars($domain['duoi']) ?>')"
                  class="w-full bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300 transition-colors"
                >
                  Kiểm Tra
                </button>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- CTA Section -->
      <div class="bg-indigo-600 rounded-lg p-8 text-center">
        <h3 class="text-2xl font-bold text-white mb-4">Sẵn Sàng Mua Domain?</h3>
        <p class="text-indigo-200 mb-6">
          Tham gia cùng hàng nghìn khách hàng đã tin tưởng chúng tôi
        </p>
        <div class="space-x-4">
          <a
            href="#domains"
            class="bg-white text-indigo-600 px-6 py-3 rounded-md font-medium hover:bg-gray-100"
          >
            Xem Domain
          </a>
          <a
            href="/Pages/register.php"
            class="bg-indigo-500 text-white px-6 py-3 rounded-md font-medium hover:bg-indigo-400"
          >
            Đăng Ký Ngay
          </a>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div>
            <h4 class="text-lg font-semibold mb-4">Về Chúng Tôi</h4>
            <p class="text-gray-400">
              <?= htmlspecialchars($settings['mota'] ?? 'Shop domain uy tín với giá cả hợp lý') ?>
            </p>
          </div>

          <div>
            <h4 class="text-lg font-semibold mb-4">Liên Kết</h4>
            <ul class="space-y-2 text-gray-400">
              <li><a href="/" class="hover:text-white">Trang chủ</a></li>
              <li>
                <a href="/Pages/login.php" class="hover:text-white"
                  >Đăng nhập</a
                >
              </li>
              <li>
                <a href="/Pages/register.php" class="hover:text-white"
                  >Đăng ký</a
                >
              </li>
              <li>
                <a href="/Pages/Recharge.php" class="hover:text-white"
                  >Nạp tiền</a
                >
              </li>
            </ul>
          </div>

          <div>
            <h4 class="text-lg font-semibold mb-4">Liên Hệ</h4>
            <p class="text-gray-400">
              <?= htmlspecialchars($settings['sodienthoai'] ?? '0123456789') ?>
            </p>
          </div>
        </div>

        <div
          class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400"
        >
          <p>
            &copy; 2025
            <?= htmlspecialchars($settings['tieude'] ?? 'My Domain Shop') ?>.
            All rights reserved.
          </p>
        </div>
      </div>
    </footer>

    <script>
      function checkDomain(domain) {
        window.location.href =
          "/Pages/Checkout.php?domain=" + encodeURIComponent(domain);
      }
    </script>
  </body>
</html>
```

## 💻 **11. CODE XỬ LÝ:**

### **File: index.php**

```php
<?php
include_once('Config/Database.php');
include_once('Repositories/DomainRepository.php');
include_once('Repositories/SettingsRepository.php');

// Bắt đầu session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tạo repositories
$domainRepo = new DomainRepository($connect);
$settingsRepo = new SettingsRepository($connect);

// Lấy danh sách domain
$domains = $domainRepo->listAll();

// Lấy cài đặt website
$settings = $settingsRepo->getSettings();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($settings['tieude'] ?? 'My Domain Shop') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>
    <!-- HTML content như trên -->
</body>
</html>
```

### **Repository: DomainRepository->listAll()**

```php
public function listAll(): array
{
    $stmt = $this->mysqli->prepare("SELECT * FROM ListDomain ORDER BY id ASC");
    $stmt->execute();
    $result = $stmt->get_result();
    $domains = [];
    while ($row = $result->fetch_assoc()) {
        $domains[] = $row;
    }
    $stmt->close();
    return $domains;
}
```

## 🎯 **12. TÓM TẮT KỸ THUẬT:**

### **✅ Công nghệ sử dụng:**

- **Backend:** PHP OOP với Repository Pattern
- **Database:** MySQL với prepared statements
- **Frontend:** Tailwind CSS với responsive design
- **Icons:** Lucide icons
- **Session:** PHP session management

### **✅ Kiến trúc:**

- **Repository Pattern** - Tách biệt logic database
- **OOP Design** - Code có cấu trúc, dễ maintain
- **Responsive Design** - Giao diện đẹp trên mọi thiết bị
- **SEO Friendly** - Tối ưu cho công cụ tìm kiếm

### **✅ Tính năng:**

- **Domain Display** - Hiển thị danh sách domain
- **User Authentication** - Kiểm tra trạng thái đăng nhập
- **Settings Integration** - Tích hợp cài đặt website
- **Navigation** - Điều hướng đến các trang khác
- **Responsive Layout** - Giao diện responsive

### **✅ SEO & Performance:**

- **Meta Tags** - Thẻ meta tối ưu
- **Structured Data** - Dữ liệu có cấu trúc
- **Fast Loading** - Tải trang nhanh
- **Mobile Friendly** - Thân thiện với mobile

## 🎉 **KẾT LUẬN:**

**Chức năng trang chủ đã được thiết kế hoàn chỉnh với giao diện đẹp, responsive và tích hợp đầy đủ thông tin website!**
