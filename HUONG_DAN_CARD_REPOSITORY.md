# 📚 **HƯỚNG DẪN CHI TIẾT CARDREPOSITORY**

## 🎯 **TỔNG QUAN**

**CardRepository** là class quản lý tất cả thao tác với bảng `Cards` trong database. Repository này chịu trách nhiệm quản lý lịch sử nạp thẻ cào, xử lý giao dịch thẻ, và thống kê doanh thu từ thẻ.

### **🔧 Design Pattern:**

- **Repository Pattern** - Tách biệt database logic
- **Prepared Statements** - Chống SQL injection
- **Transaction Management** - Quản lý giao dịch thẻ

---

## 📋 **CHI TIẾT TỪNG HÀM**

### **1. Constructor**

```php
public function __construct(mysqli $mysqli)
```

**Chức năng:** Khởi tạo CardRepository với kết nối database
**Tham số:** `mysqli $mysqli` - Kết nối database
**Logic:** Lưu kết nối database vào thuộc tính private để sử dụng trong các method khác

---

### **2. existsByPinSerial()**

```php
public function existsByPinSerial(string $pin, string $serial): bool
```

**Chức năng:** Kiểm tra thẻ cào đã tồn tại chưa (tránh trùng lặp)
**Tham số:**

- `string $pin` - Mã PIN của thẻ
- `string $serial` - Số serial của thẻ
  **Trả về:** `bool` - True nếu thẻ đã tồn tại, false nếu chưa

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT id FROM Cards WHERE pin = ? AND serial = ? LIMIT 1`
2. Bind tham số pin và serial
3. Thực thi query
4. Kiểm tra kết quả: có dữ liệu = true, không có = false

**Ví dụ sử dụng:**

```php
if ($cardRepo->existsByPinSerial('123456789', 'ABC123')) {
    echo "Thẻ này đã được sử dụng";
} else {
    echo "Thẻ hợp lệ, có thể sử dụng";
}
```

---

### **3. insertCard()**

```php
public function insertCard(int $userId, string $pin, string $serial, string $type, string $amount, string $requestId, string $time, string $time2): bool
```

**Chức năng:** Thêm thẻ cào mới vào hệ thống
**Tham số:**

- `int $userId` - ID người dùng nạp thẻ
- `string $pin` - Mã PIN thẻ
- `string $serial` - Số serial thẻ
- `string $type` - Loại thẻ (Viettel, Mobifone, Vinaphone...)
- `string $amount` - Mệnh giá thẻ
- `string $requestId` - ID request từ API
- `string $time` - Thời gian nạp
- `string $time2` - Thời gian định dạng khác
  **Trả về:** `bool` - True nếu thêm thành công

**Logic xử lý:**

1. Chuẩn bị prepared statement: `INSERT INTO Cards (uid,pin,serial,type,amount,status,requestid,time,time2) VALUES (?,?,?,?,?,'0',?,?,?)`
2. Bind tất cả tham số (status mặc định = '0' = chờ xử lý)
3. Thực thi query
4. Trả về kết quả thành công/thất bại

**Ví dụ sử dụng:**

```php
$requestId = uniqid();
$time = date('Y-m-d H:i:s');
$time2 = date('d/m/Y');

if ($cardRepo->insertCard(1, '123456789', 'ABC123', 'Viettel', '100000', $requestId, $time, $time2)) {
    echo "Thêm thẻ thành công, đang chờ xử lý";
}
```

---

### **4. listByUserId()**

```php
public function listByUserId(int $userId): array
```

**Chức năng:** Lấy danh sách thẻ cào của một người dùng
**Tham số:** `int $userId` - ID người dùng
**Trả về:** `array` - Danh sách thẻ cào của user

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT * FROM Cards WHERE uid = ?`
2. Bind tham số userId
3. Thực thi query
4. Lấy tất cả kết quả dưới dạng associative array
5. Trả về mảng chứa tất cả thẻ của user

**Ví dụ sử dụng:**

```php
$userCards = $cardRepo->listByUserId(1);
foreach ($userCards as $card) {
    echo "Thẻ: " . $card['pin'] . " - " . $card['amount'] . " - " . $card['status'];
}
```

---

### **5. sumAmountByStatusAndTime2()**

```php
public function sumAmountByStatusAndTime2(int $status, string $time2): int
```

**Chức năng:** Tính tổng doanh thu theo trạng thái và ngày (định dạng d/m/Y)
**Tham số:**

- `int $status` - Trạng thái thẻ (0=chờ, 1=thành công, 2=thất bại)
- `string $time2` - Ngày cần tính (định dạng d/m/Y)
  **Trả về:** `int` - Tổng doanh thu

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT SUM(amount) as total FROM Cards WHERE status = ? AND time2 = ?`
2. Bind tham số status và time2
3. Thực thi query
4. Lấy kết quả SUM và convert sang int
5. Trả về tổng doanh thu

**Ví dụ sử dụng:**

```php
$todayRevenue = $cardRepo->sumAmountByStatusAndTime2(1, '15/12/2024');
echo "Doanh thu hôm nay: " . number_format($todayRevenue) . "Đ";
```

---

### **6. sumAmountByStatusAndTime3()**

```php
public function sumAmountByStatusAndTime3(int $status, string $time3): int
```

**Chức năng:** Tính tổng doanh thu theo trạng thái và tháng (định dạng m/Y)
**Tham số:**

- `int $status` - Trạng thái thẻ
- `string $time3` - Tháng cần tính (định dạng m/Y)
  **Trả về:** `int` - Tổng doanh thu

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT SUM(amount) as total FROM Cards WHERE status = ? AND time3 = ?`
2. Bind tham số status và time3
3. Thực thi query
4. Lấy kết quả SUM và convert sang int
5. Trả về tổng doanh thu

**Ví dụ sử dụng:**

```php
$monthRevenue = $cardRepo->sumAmountByStatusAndTime3(1, '12/2024');
echo "Doanh thu tháng 12: " . number_format($monthRevenue) . "Đ";
```

---

### **7. sumAmountByStatus()**

```php
public function sumAmountByStatus(int $status): int
```

**Chức năng:** Tính tổng doanh thu theo trạng thái (tất cả thời gian)
**Tham số:** `int $status` - Trạng thái thẻ
**Trả về:** `int` - Tổng doanh thu

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT SUM(amount) as total FROM Cards WHERE status = ?`
2. Bind tham số status
3. Thực thi query
4. Lấy kết quả SUM và convert sang int
5. Trả về tổng doanh thu

**Ví dụ sử dụng:**

```php
$totalRevenue = $cardRepo->sumAmountByStatus(1);
echo "Tổng doanh thu thành công: " . number_format($totalRevenue) . "Đ";
```

---

### **8. listAll()**

```php
public function listAll(): array
```

**Chức năng:** Lấy danh sách tất cả thẻ cào
**Trả về:** `array` - Danh sách tất cả thẻ

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT * FROM Cards`
2. Thực thi query
3. Lấy tất cả kết quả dưới dạng associative array
4. Trả về mảng chứa tất cả thẻ

**Ví dụ sử dụng:**

```php
$allCards = $cardRepo->listAll();
echo "Tổng số thẻ: " . count($allCards);
```

---

### **9. getUidByRequestId()**

```php
public function getUidByRequestId(string $requestId): ?int
```

**Chức năng:** Lấy ID người dùng theo request ID (dùng cho callback API)
**Tham số:** `string $requestId` - ID request từ API
**Trả về:** `int|null` - ID người dùng hoặc null

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT uid FROM Cards WHERE requestid = ? LIMIT 1`
2. Bind tham số requestId
3. Thực thi query
4. Lấy kết quả uid và convert sang int
5. Trả về uid hoặc null

**Ví dụ sử dụng:**

```php
$userId = $cardRepo->getUidByRequestId('req_123456');
if ($userId) {
    echo "User ID: " . $userId;
}
```

---

### **10. updateStatusByRequestId()**

```php
public function updateStatusByRequestId(string $requestId, string $status): bool
```

**Chức năng:** Cập nhật trạng thái thẻ theo request ID (dùng cho callback API)
**Tham số:**

- `string $requestId` - ID request từ API
- `string $status` - Trạng thái mới
  **Trả về:** `bool` - True nếu cập nhật thành công

**Logic xử lý:**

1. Chuẩn bị prepared statement: `UPDATE Cards SET status = ? WHERE requestid = ?`
2. Bind tham số status và requestId
3. Thực thi query
4. Trả về kết quả thành công/thất bại

**Ví dụ sử dụng:**

```php
// Callback từ API thẻ cào
if ($cardRepo->updateStatusByRequestId('req_123456', '1')) {
    echo "Cập nhật trạng thái thẻ thành công";
}
```

---

## 🔄 **LUỒNG XỬ LÝ THỰC TẾ**

### **📋 Quy trình nạp thẻ cào:**

```php
// 1. Kiểm tra thẻ đã tồn tại chưa
if (!$cardRepo->existsByPinSerial($pin, $serial)) {

    // 2. Thêm thẻ vào hệ thống (status = 0 = chờ xử lý)
    $requestId = uniqid();
    $time = date('Y-m-d H:i:s');
    $time2 = date('d/m/Y');

    if ($cardRepo->insertCard($userId, $pin, $serial, $type, $amount, $requestId, $time, $time2)) {

        // 3. Gửi request đến API thẻ cào
        $apiResponse = callCardAPI($pin, $serial, $type, $requestId);

        // 4. Cập nhật trạng thái theo kết quả API
        if ($apiResponse['success']) {
            $cardRepo->updateStatusByRequestId($requestId, '1'); // Thành công
        } else {
            $cardRepo->updateStatusByRequestId($requestId, '2'); // Thất bại
        }
    }
}
```

### **📊 Thống kê doanh thu:**

```php
// Doanh thu hôm nay
$todayRevenue = $cardRepo->sumAmountByStatusAndTime2(1, date('d/m/Y'));

// Doanh thu tháng này
$monthRevenue = $cardRepo->sumAmountByStatusAndTime3(1, date('m/Y'));

// Tổng doanh thu
$totalRevenue = $cardRepo->sumAmountByStatus(1);

// Số thẻ chờ xử lý
$pendingCards = $cardRepo->sumAmountByStatus(0);
```

---

## 🔒 **BẢO MẬT**

### **SQL Injection Protection:**

- Tất cả queries đều sử dụng **Prepared Statements**
- Không có string concatenation trong SQL
- Bind parameters an toàn

### **Duplicate Prevention:**

- Kiểm tra thẻ đã tồn tại trước khi thêm
- Sử dụng unique request ID cho mỗi giao dịch

---

## 🎯 **VÍ DỤ SỬ DỤNG HOÀN CHỈNH**

```php
// Khởi tạo repository
$cardRepo = new CardRepository($connect);

// Nạp thẻ cào
$pin = '123456789';
$serial = 'ABC123';
$type = 'Viettel';
$amount = '100000';
$userId = 1;

// Kiểm tra thẻ chưa sử dụng
if (!$cardRepo->existsByPinSerial($pin, $serial)) {

    // Thêm thẻ vào hệ thống
    $requestId = uniqid();
    $time = date('Y-m-d H:i:s');
    $time2 = date('d/m/Y');

    if ($cardRepo->insertCard($userId, $pin, $serial, $type, $amount, $requestId, $time, $time2)) {
        echo "Thẻ đã được thêm, đang xử lý...";

        // Giả lập API callback
        sleep(2);
        $cardRepo->updateStatusByRequestId($requestId, '1');
        echo "Thẻ đã được xử lý thành công!";
    }
} else {
    echo "Thẻ này đã được sử dụng";
}

// Thống kê doanh thu
$todayRevenue = $cardRepo->sumAmountByStatusAndTime2(1, date('d/m/Y'));
echo "Doanh thu hôm nay: " . number_format($todayRevenue) . "Đ";
```

---

## 📊 **TỔNG KẾT**

**CardRepository** cung cấp đầy đủ các chức năng quản lý thẻ cào:

- ✅ **Create** - Thêm thẻ cào mới
- ✅ **Read** - Lấy danh sách thẻ, thống kê doanh thu
- ✅ **Update** - Cập nhật trạng thái thẻ
- ✅ **Validation** - Kiểm tra thẻ trùng lặp

**Đặc điểm:**

- 🔒 **Bảo mật cao** - Prepared statements
- 🎯 **Logic rõ ràng** - Mỗi method có 1 chức năng
- 📊 **Thống kê đa dạng** - Theo ngày, tháng, trạng thái
- 🔄 **API Integration** - Hỗ trợ callback từ API thẻ cào

**Use Cases:**

- Quản lý lịch sử nạp thẻ
- Thống kê doanh thu theo thời gian
- Xử lý callback từ API thẻ cào
- Kiểm tra thẻ trùng lặp

**Tác giả:** DAM THANH VU  
**Ngày tạo:** 2024  
**Phiên bản:** 1.0
