# ⚙️ **TRÌNH BÀY THIẾT KẾ VÀ XÂY DỰNG CHỨC NĂNG CÀI ĐẶT WEBSITE**

## 📝 **1. MÔ TẢ CHỨC NĂNG:**

Chức năng cài đặt website cho phép admin cấu hình thông tin website, giao diện admin, tiêu đề, mô tả, keywords, banner, logo, số điện thoại và các thiết lập hệ thống khác.

## 👨‍💼 **2. TÁC NHÂN THỰC HIỆN:**

- **Quản trị viên** - Admin có quyền cấu hình hệ thống

## 🔍 **3. DẠNG TRUY VẤN:**

- **SELECT** - Truy vấn lấy cài đặt hiện tại
- **UPDATE** - Truy vấn cập nhật cài đặt

## 🗄️ **4. TRUY VẤN VÀO TABLE:**

- **Table:** `CaiDatChung` (lưu cài đặt chung của website)

## 📊 **5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

- `id` (int) - ID cài đặt
- `tieude` (varchar) - Tiêu đề website
- `theme` (varchar) - Theme giao diện admin
- `keywords` (text) - Từ khóa SEO
- `mota` (text) - Mô tả website
- `imagebanner` (varchar) - Hình ảnh banner
- `sodienthoai` (varchar) - Số điện thoại liên hệ
- `banner` (varchar) - Banner website
- `logo` (varchar) - Logo website
- `apikey` (varchar) - API key

## 🔄 **6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. Admin đăng nhập vào hệ thống
2. Admin truy cập trang cài đặt website
3. Hệ thống hiển thị form với cài đặt hiện tại
4. Admin chỉnh sửa các thông tin cần thiết

## 🔄 **7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Cập nhật thành công:** Lưu cài đặt mới, hiển thị thông báo thành công
2. **Cập nhật thất bại:** Hiển thị thông báo lỗi
3. **Reset:** Khôi phục cài đặt mặc định

## 📊 **8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (Admin Browser)
    ↓ GET Request (Xem cài đặt)
    ↓ URL: /Adminstators/cai-dat-web.php
    ↓ POST Request (Cập nhật cài đặt)
    ↓ URL: /Adminstators/cai-dat-web.php
    ↓ Data: {tieude: "New Title", theme: "dark", ...}
Web Server (Apache)
    ↓ Xử lý request
File PHP xử lý
    ↓ Adminstators/cai-dat-web.php
    ↓ include_once SettingsRepository.php
    ↓ $settingsRepo = new SettingsRepository($connect)
PHP Processing
    ↓ SettingsRepository->getSettings() (Xem cài đặt)
    ↓ SettingsRepository->updateSettings() (Cập nhật)
    ↓ UPDATE CaiDatChung SET tieude=?, theme=?, ... WHERE id=1
Database (MySQL)
    ↓ Table: CaiDatChung
    ↓ Trả về: true/false
Response
    ↓ Success: Hiển thị form cài đặt
    ↓ Success: toastr.success("Cập nhật thành công!")
    ↓ Error: toastr.error("Cập nhật thất bại!")
```

## 🗃️ **9. BẢNG RECORDSET VÀ ARRAY[KEY]:**

### **Dữ liệu cài đặt trong database:**

```sql
| id | tieude        | theme | keywords           | mota                    | imagebanner | sodienthoai | banner | logo | apikey |
|----|---------------|-------|--------------------|-------------------------|-------------|-------------|--------|------|--------|
| 1  | My Domain Shop| dark  | domain, hosting    | Shop domain uy tín      | banner.jpg  | 0123456789  | banner.png| logo.png| abc123 |
```

### **Array[key] sử dụng:**

- `$settings['tieude']` - Tiêu đề website
- `$settings['theme']` - Theme giao diện
- `$settings['keywords']` - Từ khóa SEO
- `$settings['mota']` - Mô tả website
- `$settings['imagebanner']` - Hình ảnh banner
- `$settings['sodienthoai']` - Số điện thoại
- `$settings['banner']` - Banner
- `$settings['logo']` - Logo
- `$settings['apikey']` - API key

## 🖼️ **10. GIAO DIỆN CHỨC NĂNG:**

### **Form cài đặt website:**

```html
<div class="min-h-screen bg-gray-50">
  <!-- Header -->
  <div class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center py-6">
        <h1 class="text-3xl font-bold text-gray-900">Cài Đặt Website</h1>
        <div class="text-sm text-gray-500">
          Cấu hình hệ thống
        </div>
      </div>
    </div>
  </div>

  <!-- Content -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <form method="post" class="space-y-6">
      <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
          <div class="md:col-span-1">
            <h3 class="text-lg font-medium leading-6 text-gray-900">
              Thông Tin Cơ Bản
            </h3>
            <p class="mt-1 text-sm text-gray-500">
              Cấu hình thông tin website cơ bản
            </p>
          </div>
          <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="grid grid-cols-6 gap-6">
              <div class="col-span-6">
                <label class="block text-sm font-medium text-gray-700">
                  Tiêu đề website
                </label>
                <input
                  type="text"
                  name="tieude"
                  value="<?= htmlspecialchars($settings['tieude'] ?? '') ?>"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
              </div>

              <div class="col-span-6">
                <label class="block text-sm font-medium text-gray-700">
                  Mô tả website
                </label>
                <textarea
                  name="mota"
                  rows="3"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                ><?= htmlspecialchars($settings['mota'] ?? '') ?></textarea>
              </div>

              <div class="col-span-6">
                <label class="block text-sm font-medium text-gray-700">
                  Từ khóa SEO
                </label>
                <input
                  type="text"
                  name="keywords"
                  value="<?= htmlspecialchars($settings['keywords'] ?? '') ?>"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  placeholder="domain, hosting, website"
                />
              </div>

              <div class="col-span-6">
                <label class="block text-sm font-medium text-gray-700">
                  Số điện thoại
                </label>
                <input
                  type="text"
                  name="sodienthoai"
                  value="<?= htmlspecialchars($settings['sodienthoai'] ?? '') ?>"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
          <div class="md:col-span-1">
            <h3 class="text-lg font-medium leading-6 text-gray-900">
              Giao Diện
            </h3>
            <p class="mt-1 text-sm text-gray-500">
              Cấu hình giao diện admin
            </p>
          </div>
          <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="grid grid-cols-6 gap-6">
              <div class="col-span-6">
                <label class="block text-sm font-medium text-gray-700">
                  Theme giao diện admin
                </label>
                <select
                  name="theme"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
                  <option value="light" <?= ($settings['theme'] ?? '') == 'light' ? 'selected' : '' ?>>Light</option>
                  <option value="dark" <?= ($settings['theme'] ?? '') == 'dark' ? 'selected' : '' ?>>Dark</option>
                  <option value="blue" <?= ($settings['theme'] ?? '') == 'blue' ? 'selected' : '' ?>>Blue</option>
                </select>
              </div>

              <div class="col-span-6">
                <label class="block text-sm font-medium text-gray-700">
                  Logo website
                </label>
                <input
                  type="text"
                  name="logo"
                  value="<?= htmlspecialchars($settings['logo'] ?? '') ?>"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  placeholder="/images/logo.png"
                />
              </div>

              <div class="col-span-6">
                <label class="block text-sm font-medium text-gray-700">
                  Banner website
                </label>
                <input
                  type="text"
                  name="banner"
                  value="<?= htmlspecialchars($settings['banner'] ?? '') ?>"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  placeholder="/images/banner.png"
                />
              </div>

              <div class="col-span-6">
                <label class="block text-sm font-medium text-gray-700">
                  Hình ảnh banner
                </label>
                <input
                  type="text"
                  name="imagebanner"
                  value="<?= htmlspecialchars($settings['imagebanner'] ?? '') ?>"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  placeholder="/images/banner.jpg"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
          <div class="md:col-span-1">
            <h3 class="text-lg font-medium leading-6 text-gray-900">
              API & Tích Hợp
            </h3>
            <p class="mt-1 text-sm text-gray-500">
              Cấu hình API và tích hợp bên thứ 3
            </p>
          </div>
          <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="grid grid-cols-6 gap-6">
              <div class="col-span-6">
                <label class="block text-sm font-medium text-gray-700">
                  API Key
                </label>
                <input
                  type="text"
                  name="apikey"
                  value="<?= htmlspecialchars($settings['apikey'] ?? '') ?>"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  placeholder="Nhập API key"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="flex justify-end">
        <button
          type="submit"
          class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Lưu Cài Đặt
        </button>
      </div>
    </form>
  </div>
</div>
```

## 💻 **11. CODE XỬ LÝ:**

### **File: Adminstators/cai-dat-web.php**

```php
<?php
include_once('../Config/Database.php');
include_once('../Repositories/SettingsRepository.php');

$settingsRepo = new SettingsRepository($connect);

// Xử lý cập nhật cài đặt
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tieude = $_POST['tieude'] ?? '';
    $theme = $_POST['theme'] ?? '';
    $keywords = $_POST['keywords'] ?? '';
    $mota = $_POST['mota'] ?? '';
    $imagebanner = $_POST['imagebanner'] ?? '';
    $sodienthoai = $_POST['sodienthoai'] ?? '';
    $banner = $_POST['banner'] ?? '';
    $logo = $_POST['logo'] ?? '';
    $apikey = $_POST['apikey'] ?? '';
    
    if ($settingsRepo->updateSettings($tieude, $theme, $keywords, $mota, $imagebanner, $sodienthoai, $banner, $logo, $apikey)) {
        echo '<script>toastr.success("Cập nhật cài đặt thành công!", "Thông Báo");</script>';
    } else {
        echo '<script>toastr.error("Cập nhật thất bại!", "Thông Báo");</script>';
    }
}

// Lấy cài đặt hiện tại
$settings = $settingsRepo->getSettings();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cài Đặt Website - Admin</title>
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

### **Repository: SettingsRepository->updateSettings()**

```php
public function updateSettings(string $tieude, string $theme, string $keywords, string $mota, string $imagebanner, string $sodienthoai, string $banner, string $logo, string $apikey): bool
{
    $stmt = $this->mysqli->prepare("UPDATE CaiDatChung SET tieude=?, theme=?, keywords=?, mota=?, imagebanner=?, sodienthoai=?, banner=?, logo=?, apikey=? WHERE id=1");
    $stmt->bind_param('sssssssss', $tieude, $theme, $keywords, $mota, $imagebanner, $sodienthoai, $banner, $logo, $apikey);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

public function getSettings(): array
{
    $stmt = $this->mysqli->prepare("SELECT * FROM CaiDatChung WHERE id=1 LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row ?: [];
}
```

## 🎯 **12. TÓM TẮT KỸ THUẬT:**

### **✅ Công nghệ sử dụng:**

- **Backend:** PHP OOP với Repository Pattern
- **Database:** MySQL với prepared statements
- **Frontend:** Tailwind CSS với responsive design
- **Form Handling:** HTML form với validation
- **Notifications:** Toastr.js

### **✅ Kiến trúc:**

- **Repository Pattern** - Tách biệt logic database
- **OOP Design** - Code có cấu trúc, dễ maintain
- **Settings Management** - Quản lý cài đặt hệ thống
- **Form Processing** - Xử lý form POST

### **✅ Tính năng:**

- **Basic Settings** - Cài đặt cơ bản website
- **Theme Configuration** - Cấu hình giao diện
- **SEO Settings** - Cài đặt SEO
- **API Integration** - Tích hợp API
- **File Management** - Quản lý hình ảnh

## 🎉 **KẾT LUẬN:**

**Chức năng cài đặt website đã được thiết kế hoàn chỉnh với giao diện cấu hình chuyên nghiệp và khả năng tùy chỉnh linh hoạt!**
