# 📚 **HƯỚNG DẪN CHI TIẾT SETTINGSREPOSITORY**

## 🎯 **TỔNG QUAN**

**SettingsRepository** là class quản lý tất cả thao tác với bảng `CaiDatChung` trong database. Repository này chịu trách nhiệm quản lý cài đặt website, cập nhật thông tin liên hệ, cấu hình API keys, và quản lý banner/logo.

### **🔧 Design Pattern:**

- **Repository Pattern** - Tách biệt database logic
- **Prepared Statements** - Chống SQL injection
- **Configuration Management** - Quản lý cài đặt hệ thống

---

## 📋 **CHI TIẾT TỪNG HÀM**

### **1. Constructor**

```php
public function __construct(mysqli $mysqli)
```

**Chức năng:** Khởi tạo SettingsRepository với kết nối database
**Tham số:** `mysqli $mysqli` - Kết nối database
**Logic:** Lưu kết nối database vào thuộc tính private để sử dụng trong các method khác

---

### **2. getOne()**

```php
public function getOne(): ?array
```

**Chức năng:** Lấy cài đặt website hiện tại
**Trả về:** `array|null` - Thông tin cài đặt hoặc null

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT * FROM CaiDatChung LIMIT 1`
2. Thực thi query
3. Lấy kết quả đầu tiên
4. Trả về thông tin cài đặt hoặc null

**Ví dụ sử dụng:**

```php
$settings = $settingsRepo->getOne();
if ($settings) {
    echo "Tiêu đề website: " . $settings['tieude'];
    echo "Mô tả: " . $settings['mota'];
    echo "Keywords: " . $settings['keywords'];
}
```

---

### **3. updateWebsiteSettings()**

```php
public function updateWebsiteSettings(string $title, string $theme, string $keywords, string $description, string $imagebanner, string $phone, string $banner, string $logo): bool
```

**Chức năng:** Cập nhật cài đặt website
**Tham số:**

- `string $title` - Tiêu đề website
- `string $theme` - Theme (light/dark)
- `string $keywords` - Keywords SEO
- `string $description` - Mô tả website
- `string $imagebanner` - Hình ảnh banner
- `string $phone` - Số điện thoại liên hệ
- `string $banner` - Banner text
- `string $logo` - Logo website
  **Trả về:** `bool` - True nếu cập nhật thành công

**Logic xử lý:**

1. Chuẩn bị prepared statement: `UPDATE CaiDatChung SET tieude = ?, theme = ?, keywords = ?, mota = ?, imagebanner = ?, sodienthoai = ?, banner = ?, logo = ? WHERE id = '1'`
2. Bind tất cả tham số
3. Thực thi query
4. Trả về kết quả thành công/thất bại

**Ví dụ sử dụng:**

```php
if ($settingsRepo->updateWebsiteSettings(
    'CloudStoreVN - Tên Miền Giá Rẻ',
    'light',
    'tên miền, domain, giá rẻ, hosting',
    'Cung cấp tên miền giá rẻ, hosting chất lượng',
    '/images/banner.jpg',
    '0123456789',
    'Chào mừng đến với CloudStoreVN',
    '/images/logo.png'
)) {
    echo "Cập nhật cài đặt website thành công";
}
```

---

### **4. updateCardGateway()**

```php
public function updateCardGateway(string $apikey, string $callback, string $webgach): bool
```

**Chức năng:** Cập nhật cài đặt API thẻ cào
**Tham số:**

- `string $apikey` - API key của gateway thẻ cào
- `string $callback` - URL callback
- `string $webgach` - Website gateway
  **Trả về:** `bool` - True nếu cập nhật thành công

**Logic xử lý:**

1. Chuẩn bị prepared statement: `UPDATE CaiDatChung SET apikey = ?, callback = ?, webgach = ? WHERE id = '1'`
2. Bind tham số apikey, callback, webgach
3. Thực thi query
4. Trả về kết quả thành công/thất bại

**Ví dụ sử dụng:**

```php
if ($settingsRepo->updateCardGateway(
    'your-api-key-here',
    'https://yoursite.com/callback',
    'https://cardvip.vn'
)) {
    echo "Cập nhật cài đặt API thẻ cào thành công";
}
```

---

## 🔄 **LUỒNG XỬ LÝ THỰC TẾ**

### **📋 Quản lý cài đặt website:**

```php
// 1. Lấy cài đặt hiện tại
$settings = $settingsRepo->getOne();

// 2. Hiển thị form cài đặt
echo "<form method='POST'>";
echo "<input name='title' value='" . $settings['tieude'] . "'>";
echo "<input name='description' value='" . $settings['mota'] . "'>";
echo "<input name='keywords' value='" . $settings['keywords'] . "'>";
echo "<input name='phone' value='" . $settings['sodienthoai'] . "'>";
echo "<button type='submit'>Cập nhật</button>";
echo "</form>";

// 3. Xử lý cập nhật
if ($_POST) {
    $settingsRepo->updateWebsiteSettings(
        $_POST['title'],
        $_POST['theme'],
        $_POST['keywords'],
        $_POST['description'],
        $_POST['imagebanner'],
        $_POST['phone'],
        $_POST['banner'],
        $_POST['logo']
    );
}
```

### **🔧 Cấu hình API thẻ cào:**

```php
// 1. Lấy cài đặt API hiện tại
$settings = $settingsRepo->getOne();

// 2. Hiển thị form API
echo "<form method='POST'>";
echo "<input name='apikey' value='" . $settings['apikey'] . "'>";
echo "<input name='callback' value='" . $settings['callback'] . "'>";
echo "<input name='webgach' value='" . $settings['webgach'] . "'>";
echo "<button type='submit'>Cập nhật API</button>";
echo "</form>";

// 3. Xử lý cập nhật API
if ($_POST) {
    $settingsRepo->updateCardGateway(
        $_POST['apikey'],
        $_POST['callback'],
        $_POST['webgach']
    );
}
```

---

## 🎯 **CẤU TRÚC BẢNG CAIDATCHUNG**

### **📊 Các trường dữ liệu:**

```sql
CREATE TABLE CaiDatChung (
    id INT PRIMARY KEY,
    tieude VARCHAR(255),        -- Tiêu đề website
    theme VARCHAR(50),          -- Theme (light/dark)
    keywords TEXT,              -- Keywords SEO
    mota TEXT,                  -- Mô tả website
    imagebanner VARCHAR(255),   -- Hình ảnh banner
    sodienthoai VARCHAR(20),    -- Số điện thoại
    banner TEXT,                -- Banner text
    logo VARCHAR(255),          -- Logo website
    apikey VARCHAR(255),        -- API key thẻ cào
    callback VARCHAR(255),      -- URL callback
    webgach VARCHAR(255)        -- Website gateway
);
```

---

## 🔒 **BẢO MẬT**

### **SQL Injection Protection:**

- Tất cả queries đều sử dụng **Prepared Statements**
- Không có string concatenation trong SQL
- Bind parameters an toàn

### **Data Validation:**

- Validate URL callback
- Validate API key format
- Sanitize input data

---

## 🎯 **VÍ DỤ SỬ DỤNG HOÀN CHỈNH**

```php
// Khởi tạo repository
$settingsRepo = new SettingsRepository($connect);

// Lấy cài đặt hiện tại
$settings = $settingsRepo->getOne();

if ($settings) {
    // Hiển thị thông tin website
    echo "<h1>" . $settings['tieude'] . "</h1>";
    echo "<p>" . $settings['mota'] . "</p>";
    echo "<p>Liên hệ: " . $settings['sodienthoai'] . "</p>";

    // Hiển thị logo
    if ($settings['logo']) {
        echo "<img src='" . $settings['logo'] . "' alt='Logo'>";
    }

    // Hiển thị banner
    if ($settings['banner']) {
        echo "<div class='banner'>" . $settings['banner'] . "</div>";
    }
}

// Cập nhật cài đặt website
if ($_POST['action'] == 'update_website') {
    $settingsRepo->updateWebsiteSettings(
        $_POST['title'],
        $_POST['theme'],
        $_POST['keywords'],
        $_POST['description'],
        $_POST['imagebanner'],
        $_POST['phone'],
        $_POST['banner'],
        $_POST['logo']
    );
    echo "Cập nhật cài đặt website thành công";
}

// Cập nhật API thẻ cào
if ($_POST['action'] == 'update_api') {
    $settingsRepo->updateCardGateway(
        $_POST['apikey'],
        $_POST['callback'],
        $_POST['webgach']
    );
    echo "Cập nhật API thẻ cào thành công";
}
```

---

## 📊 **TỔNG KẾT**

**SettingsRepository** cung cấp các chức năng quản lý cài đặt hệ thống:

- ✅ **Read** - Lấy cài đặt hiện tại
- ✅ **Update** - Cập nhật cài đặt website
- ✅ **Update** - Cập nhật cài đặt API thẻ cào

**Đặc điểm:**

- 🔒 **Bảo mật cao** - Prepared statements
- 🎯 **Logic rõ ràng** - Mỗi method có 1 chức năng
- ⚙️ **Configuration** - Quản lý cài đặt tập trung
- 🔧 **API Management** - Cấu hình API thẻ cào

**Use Cases:**

- Quản lý thông tin website
- Cấu hình SEO (title, description, keywords)
- Quản lý logo, banner, theme
- Cấu hình API thẻ cào
- Quản lý thông tin liên hệ

**Các trường dữ liệu quan trọng:**

- **Website Info:** tieude, mota, keywords, theme
- **Media:** logo, imagebanner, banner
- **Contact:** sodienthoai
- **API:** apikey, callback, webgach

**Tác giả:** DAM THANH VU  
**Ngày tạo:** 2024  
**Phiên bản:** 1.0
