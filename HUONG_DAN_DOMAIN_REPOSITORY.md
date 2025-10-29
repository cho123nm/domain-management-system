# 📚 **HƯỚNG DẪN CHI TIẾT DOMAINREPOSITORY**

## 🎯 **TỔNG QUAN**

**DomainRepository** là class quản lý tất cả thao tác với bảng `ListDomain` trong database. Repository này chịu trách nhiệm quản lý danh sách các loại tên miền có sẵn, giá bán, và hình ảnh.

### **🔧 Design Pattern:**

- **Repository Pattern** - Tách biệt database logic
- **Prepared Statements** - Chống SQL injection
- **CRUD Operations** - Create, Read, Update, Delete

---

## 📋 **CHI TIẾT TỪNG HÀM**

### **1. Constructor**

```php
public function __construct(mysqli $mysqli)
```

**Chức năng:** Khởi tạo DomainRepository với kết nối database
**Tham số:** `mysqli $mysqli` - Kết nối database
**Logic:** Lưu kết nối database vào thuộc tính private để sử dụng trong các method khác

---

### **2. listAll()**

```php
public function listAll(): array
```

**Chức năng:** Lấy danh sách tất cả loại tên miền
**Trả về:** `array` - Danh sách tất cả domain types

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT * FROM ListDomain`
2. Thực thi query
3. Lấy tất cả kết quả dưới dạng associative array
4. Trả về mảng chứa tất cả domain types

**Ví dụ sử dụng:**

```php
$domainRepo = new DomainRepository($connect);
$domains = $domainRepo->listAll();
foreach ($domains as $domain) {
    echo $domain['duoi'] . " - " . number_format($domain['price']) . "Đ";
}
```

---

### **3. create()**

```php
public function create(int $price, string $duoi, string $image): bool
```

**Chức năng:** Thêm loại tên miền mới
**Tham số:**

- `int $price` - Giá bán
- `string $duoi` - Đuôi tên miền (.com, .net, .org...)
- `string $image` - Đường dẫn hình ảnh
  **Trả về:** `bool` - True nếu tạo thành công

**Logic xử lý:**

1. Chuẩn bị prepared statement: `INSERT INTO ListDomain (price,duoi,image) VALUES (?,?,?)`
2. Bind tham số price, duoi, image
3. Thực thi query
4. Trả về kết quả thành công/thất bại

**Ví dụ sử dụng:**

```php
if ($domainRepo->create(50000, '.com', '/images/com.png')) {
    echo "Thêm loại tên miền thành công";
}
```

---

### **4. deleteById()**

```php
public function deleteById(int $id): bool
```

**Chức năng:** Xóa loại tên miền theo ID
**Tham số:** `int $id` - ID của loại tên miền cần xóa
**Trả về:** `bool` - True nếu xóa thành công

**Logic xử lý:**

1. Chuẩn bị prepared statement: `DELETE FROM ListDomain WHERE id = ?`
2. Bind tham số id
3. Thực thi query
4. Trả về kết quả thành công/thất bại

**Ví dụ sử dụng:**

```php
if ($domainRepo->deleteById(1)) {
    echo "Xóa loại tên miền thành công";
}
```

---

### **5. findById()**

```php
public function findById(int $id): ?array
```

**Chức năng:** Tìm kiếm loại tên miền theo ID
**Tham số:** `int $id` - ID của loại tên miền
**Trả về:** `array|null` - Thông tin domain hoặc null

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT * FROM ListDomain WHERE id = ? LIMIT 1`
2. Bind tham số id
3. Thực thi query
4. Trả về thông tin domain hoặc null

**Ví dụ sử dụng:**

```php
$domain = $domainRepo->findById(1);
if ($domain) {
    echo "Tên miền: " . $domain['duoi'] . " - Giá: " . $domain['price'];
}
```

---

### **6. updateById()**

```php
public function updateById(int $id, string $duoi, string $image, int $price): bool
```

**Chức năng:** Cập nhật thông tin loại tên miền
**Tham số:**

- `int $id` - ID của loại tên miền
- `string $duoi` - Đuôi tên miền mới
- `string $image` - Đường dẫn hình ảnh mới
- `int $price` - Giá bán mới
  **Trả về:** `bool` - True nếu cập nhật thành công

**Logic xử lý:**

1. Chuẩn bị prepared statement: `UPDATE ListDomain SET duoi = ?, image = ?, price = ? WHERE id = ?`
2. Bind tham số duoi, image, price, id
3. Thực thi query
4. Trả về kết quả thành công/thất bại

**Ví dụ sử dụng:**

```php
if ($domainRepo->updateById(1, '.com.vn', '/images/comvn.png', 75000)) {
    echo "Cập nhật thông tin tên miền thành công";
}
```

---

### **7. getOneSample()**

```php
public function getOneSample(): ?array
```

**Chức năng:** Lấy một mẫu loại tên miền (để test hoặc hiển thị mặc định)
**Trả về:** `array|null` - Thông tin domain mẫu hoặc null

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT * FROM ListDomain LIMIT 1`
2. Thực thi query
3. Lấy kết quả đầu tiên
4. Trả về thông tin domain hoặc null

**Ví dụ sử dụng:**

```php
$sampleDomain = $domainRepo->getOneSample();
if ($sampleDomain) {
    echo "Mẫu tên miền: " . $sampleDomain['duoi'];
}
```

---

### **8. findByDuoi()**

```php
public function findByDuoi(string $duoi): ?array
```

**Chức năng:** Tìm kiếm loại tên miền theo đuôi
**Tham số:** `string $duoi` - Đuôi tên miền cần tìm (.com, .net...)
**Trả về:** `array|null` - Thông tin domain hoặc null

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT * FROM ListDomain WHERE duoi = ? LIMIT 1`
2. Bind tham số duoi
3. Thực thi query
4. Trả về thông tin domain hoặc null

**Ví dụ sử dụng:**

```php
$comDomain = $domainRepo->findByDuoi('.com');
if ($comDomain) {
    echo "Giá .com: " . number_format($comDomain['price']) . "Đ";
}
```

---

## 🔄 **LUỒNG XỬ LÝ THỰC TẾ**

### **📋 Quản lý danh sách tên miền:**

```php
// 1. Lấy danh sách tất cả loại tên miền
$domains = $domainRepo->listAll();

// 2. Hiển thị trong dropdown
foreach ($domains as $domain) {
    echo "<option value='{$domain['duoi']}'>{$domain['duoi']}</option>";
}

// 3. Thêm loại tên miền mới
$domainRepo->create(100000, '.org', '/images/org.png');

// 4. Cập nhật giá bán
$domainRepo->updateById(1, '.com', '/images/com.png', 120000);

// 5. Xóa loại tên miền không còn bán
$domainRepo->deleteById(5);
```

### **🎯 Tìm kiếm và hiển thị:**

```php
// Tìm kiếm theo đuôi
$domain = $domainRepo->findByDuoi('.com');
if ($domain) {
    echo "Giá .com: " . number_format($domain['price']) . "Đ";
    echo "<img src='{$domain['image']}' alt='{$domain['duoi']}'>";
}

// Lấy thông tin theo ID
$domain = $domainRepo->findById(1);
if ($domain) {
    echo "Thông tin: " . $domain['duoi'] . " - " . $domain['price'];
}
```

---

## 🔒 **BẢO MẬT**

### **SQL Injection Protection:**

- Tất cả queries đều sử dụng **Prepared Statements**
- Không có string concatenation trong SQL
- Bind parameters an toàn

### **Data Validation:**

- Kiểm tra kiểu dữ liệu đầu vào
- Validate giá trị price (phải là số dương)
- Validate đuôi tên miền (phải bắt đầu bằng dấu chấm)

---

## 🎯 **VÍ DỤ SỬ DỤNG HOÀN CHỈNH**

```php
// Khởi tạo repository
$domainRepo = new DomainRepository($connect);

// Lấy danh sách tất cả loại tên miền
$allDomains = $domainRepo->listAll();
echo "Có " . count($allDomains) . " loại tên miền:";

foreach ($allDomains as $domain) {
    echo "<div class='domain-item'>";
    echo "<img src='{$domain['image']}' alt='{$domain['duoi']}'>";
    echo "<span>{$domain['duoi']}</span>";
    echo "<span>" . number_format($domain['price']) . "Đ</span>";
    echo "</div>";
}

// Thêm loại tên miền mới
if ($domainRepo->create(75000, '.vn', '/images/vn.png')) {
    echo "Thêm .vn thành công";
}

// Cập nhật giá .com
$comDomain = $domainRepo->findByDuoi('.com');
if ($comDomain) {
    $domainRepo->updateById($comDomain['id'], '.com', $comDomain['image'], 150000);
    echo "Cập nhật giá .com thành công";
}
```

---

## 📊 **TỔNG KẾT**

**DomainRepository** cung cấp đầy đủ các chức năng CRUD cho bảng ListDomain:

- ✅ **Create** - Thêm loại tên miền mới
- ✅ **Read** - Lấy danh sách, tìm kiếm theo ID/đuôi
- ✅ **Update** - Cập nhật thông tin tên miền
- ✅ **Delete** - Xóa loại tên miền

**Đặc điểm:**

- 🔒 **Bảo mật cao** - Prepared statements
- 🎯 **Logic rõ ràng** - Mỗi method có 1 chức năng
- 🔄 **Tái sử dụng** - Có thể dùng ở nhiều nơi
- 📝 **Dễ maintain** - Code có comment chi tiết

**Use Cases:**

- Quản lý danh sách loại tên miền
- Hiển thị dropdown chọn đuôi tên miền
- Cập nhật giá bán theo thời gian
- Thêm/xóa loại tên miền mới

**Tác giả:** DAM THANH VU  
**Ngày tạo:** 2024  
**Phiên bản:** 1.0
