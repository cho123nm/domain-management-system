# 📚 **HƯỚNG DẪN CHI TIẾT HISTORYREPOSITORY**

## 🎯 **TỔNG QUAN**

**HistoryRepository** là class quản lý tất cả thao tác với bảng `History` trong database. Repository này chịu trách nhiệm quản lý lịch sử mua tên miền, theo dõi trạng thái đơn hàng, quản lý DNS và nameserver.

### **🔧 Design Pattern:**

- **Repository Pattern** - Tách biệt database logic
- **Prepared Statements** - Chống SQL injection
- **Order Management** - Quản lý đơn hàng tên miền

---

## 📋 **CHI TIẾT TỪNG HÀM**

### **1. Constructor**

```php
public function __construct(mysqli $mysqli)
```

**Chức năng:** Khởi tạo HistoryRepository với kết nối database
**Tham số:** `mysqli $mysqli` - Kết nối database
**Logic:** Lưu kết nối database vào thuộc tính private để sử dụng trong các method khác

---

### **2. getByDomain()**

```php
public function getByDomain(string $domain): ?array
```

**Chức năng:** Tìm kiếm đơn hàng theo tên miền
**Tham số:** `string $domain` - Tên miền cần tìm
**Trả về:** `array|null` - Thông tin đơn hàng hoặc null

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT * FROM History WHERE domain = ? LIMIT 1`
2. Bind tham số domain
3. Thực thi query
4. Trả về thông tin đơn hàng hoặc null

**Ví dụ sử dụng:**

```php
$order = $historyRepo->getByDomain('example.com');
if ($order) {
    echo "Tên miền: " . $order['domain'] . " - Trạng thái: " . $order['status'];
}
```

---

### **3. getByMgd()**

```php
public function getByMgd(string $mgd): ?array
```

**Chức năng:** Tìm kiếm đơn hàng theo Mã Giao Dịch (MGD)
**Tham số:** `string $mgd` - Mã giao dịch
**Trả về:** `array|null` - Thông tin đơn hàng hoặc null

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT * FROM History WHERE mgd = ? LIMIT 1`
2. Bind tham số mgd
3. Thực thi query
4. Trả về thông tin đơn hàng hoặc null

**Ví dụ sử dụng:**

```php
$order = $historyRepo->getByMgd('MGD123456');
if ($order) {
    echo "MGD: " . $order['mgd'] . " - Tên miền: " . $order['domain'];
}
```

---

### **4. listAll()**

```php
public function listAll(): array
```

**Chức năng:** Lấy danh sách tất cả đơn hàng
**Trả về:** `array` - Danh sách tất cả đơn hàng

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT * FROM History`
2. Thực thi query
3. Lấy tất cả kết quả dưới dạng associative array
4. Trả về mảng chứa tất cả đơn hàng

**Ví dụ sử dụng:**

```php
$allOrders = $historyRepo->listAll();
echo "Tổng số đơn hàng: " . count($allOrders);
```

---

### **5. countByStatus()**

```php
public function countByStatus(int $status): int
```

**Chức năng:** Đếm số đơn hàng theo trạng thái
**Tham số:** `int $status` - Trạng thái đơn hàng (0=chờ, 1=hoàn thành, 2=từ chối, 3=đã cập nhật DNS)
**Trả về:** `int` - Số lượng đơn hàng

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT COUNT(*) as c FROM History WHERE status = ?`
2. Bind tham số status
3. Thực thi query
4. Lấy kết quả count và convert sang int
5. Trả về số lượng đơn hàng

**Ví dụ sử dụng:**

```php
$pendingOrders = $historyRepo->countByStatus(0);
$completedOrders = $historyRepo->countByStatus(1);
echo "Đơn chờ: " . $pendingOrders . " - Đơn hoàn thành: " . $completedOrders;
```

---

### **6. countAhihiOne()**

```php
public function countAhihiOne(): int
```

**Chức năng:** Đếm số đơn hàng đã cập nhật DNS (ahihi = '1')
**Trả về:** `int` - Số lượng đơn hàng đã cập nhật DNS

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT COUNT(*) as c FROM History WHERE ahihi = '1'`
2. Thực thi query
3. Lấy kết quả count và convert sang int
4. Trả về số lượng đơn hàng

**Ví dụ sử dụng:**

```php
$dnsUpdated = $historyRepo->countAhihiOne();
echo "Số đơn đã cập nhật DNS: " . $dnsUpdated;
```

---

### **7. listByAhihi()**

```php
public function listByAhihi(string $value): array
```

**Chức năng:** Lấy danh sách đơn hàng theo trạng thái DNS
**Tham số:** `string $value` - Giá trị ahihi ('0'=chưa cập nhật, '1'=đã cập nhật)
**Trả về:** `array` - Danh sách đơn hàng

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT * FROM History WHERE ahihi = ?`
2. Bind tham số value
3. Thực thi query
4. Lấy tất cả kết quả dưới dạng associative array
5. Trả về mảng chứa đơn hàng

**Ví dụ sử dụng:**

```php
$dnsPending = $historyRepo->listByAhihi('0');
$dnsUpdated = $historyRepo->listByAhihi('1');
```

---

### **8. countByUserAndStatus()**

```php
public function countByUserAndStatus(int $userId, int $status): int
```

**Chức năng:** Đếm số đơn hàng của user theo trạng thái
**Tham số:**

- `int $userId` - ID người dùng
- `int $status` - Trạng thái đơn hàng
  **Trả về:** `int` - Số lượng đơn hàng

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT COUNT(*) as total FROM History WHERE uid = ? AND status = ?`
2. Bind tham số userId và status
3. Thực thi query
4. Lấy kết quả count và convert sang int
5. Trả về số lượng đơn hàng

**Ví dụ sử dụng:**

```php
$userPendingOrders = $historyRepo->countByUserAndStatus(1, 0);
$userCompletedOrders = $historyRepo->countByUserAndStatus(1, 1);
echo "Đơn chờ của user: " . $userPendingOrders;
```

---

### **9. listRecentByUser()**

```php
public function listRecentByUser(int $userId, int $limit): array
```

**Chức năng:** Lấy danh sách đơn hàng gần đây của user
**Tham số:**

- `int $userId` - ID người dùng
- `int $limit` - Số lượng đơn hàng tối đa
  **Trả về:** `array` - Danh sách đơn hàng gần đây

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT * FROM History WHERE uid = ? ORDER BY id DESC LIMIT ?`
2. Bind tham số userId và limit
3. Thực thi query
4. Lấy kết quả dưới dạng associative array
5. Trả về mảng chứa đơn hàng gần đây

**Ví dụ sử dụng:**

```php
$recentOrders = $historyRepo->listRecentByUser(1, 5);
foreach ($recentOrders as $order) {
    echo "Tên miền: " . $order['domain'] . " - " . $order['time'];
}
```

---

### **10. listByUser()**

```php
public function listByUser(int $userId): array
```

**Chức năng:** Lấy tất cả đơn hàng của một user
**Tham số:** `int $userId` - ID người dùng
**Trả về:** `array` - Danh sách tất cả đơn hàng của user

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT * FROM History WHERE uid = ?`
2. Bind tham số userId
3. Thực thi query
4. Lấy tất cả kết quả dưới dạng associative array
5. Trả về mảng chứa tất cả đơn hàng của user

---

### **11. updateStatusById()**

```php
public function updateStatusById(int $id, string $status): bool
```

**Chức năng:** Cập nhật trạng thái đơn hàng theo ID
**Tham số:**

- `int $id` - ID đơn hàng
- `string $status` - Trạng thái mới
  **Trả về:** `bool` - True nếu cập nhật thành công

**Logic xử lý:**

1. Chuẩn bị prepared statement: `UPDATE History SET status = ? WHERE id = ?`
2. Bind tham số status và id
3. Thực thi query
4. Trả về kết quả thành công/thất bại

**Ví dụ sử dụng:**

```php
if ($historyRepo->updateStatusById(1, '1')) {
    echo "Cập nhật trạng thái đơn hàng thành công";
}
```

---

### **12. updateAhihiAndStatusById()**

```php
public function updateAhihiAndStatusById(int $id, string $ahihi, string $status): bool
```

**Chức năng:** Cập nhật cả trạng thái DNS và trạng thái đơn hàng
**Tham số:**

- `int $id` - ID đơn hàng
- `string $ahihi` - Trạng thái DNS ('0'=chưa, '1'=đã cập nhật)
- `string $status` - Trạng thái đơn hàng
  **Trả về:** `bool` - True nếu cập nhật thành công

**Logic xử lý:**

1. Chuẩn bị prepared statement: `UPDATE History SET ahihi = ?, status = ? WHERE id = ?`
2. Bind tham số ahihi, status, id
3. Thực thi query
4. Trả về kết quả thành công/thất bại

---

### **13. deleteById()**

```php
public function deleteById(int $id): bool
```

**Chức năng:** Xóa đơn hàng theo ID
**Tham số:** `int $id` - ID đơn hàng cần xóa
**Trả về:** `bool` - True nếu xóa thành công

**Logic xử lý:**

1. Chuẩn bị prepared statement: `DELETE FROM History WHERE id = ?`
2. Bind tham số id
3. Thực thi query
4. Trả về kết quả thành công/thất bại

---

### **14. insertPurchase()**

```php
public function insertPurchase(int $userId, string $domain, string $ns1, string $ns2, string $hsd, string $mgd, string $time): bool
```

**Chức năng:** Thêm đơn hàng mua tên miền mới
**Tham số:**

- `int $userId` - ID người dùng
- `string $domain` - Tên miền
- `string $ns1` - Nameserver 1
- `string $ns2` - Nameserver 2
- `string $hsd` - Hạn sử dụng
- `string $mgd` - Mã giao dịch
- `string $time` - Thời gian mua
  **Trả về:** `bool` - True nếu thêm thành công

**Logic xử lý:**

1. Chuẩn bị prepared statement: `INSERT INTO History (uid,domain,ns1,ns2,hsd,status,mgd,time,timedns) VALUES (?,?,?,?,?,'0',?,?,'0')`
2. Bind tất cả tham số (status mặc định = '0' = chờ xử lý, timedns = '0')
3. Thực thi query
4. Trả về kết quả thành công/thất bại

**Ví dụ sử dụng:**

```php
$mgd = 'MGD' . uniqid();
$time = date('Y-m-d H:i:s');

if ($historyRepo->insertPurchase(1, 'example.com', 'ns1.example.com', 'ns2.example.com', '2025-12-15', $mgd, $time)) {
    echo "Thêm đơn hàng thành công, MGD: " . $mgd;
}
```

---

### **15. updateDns()**

```php
public function updateDns(string $mgd, string $ns1, string $ns2, string $timedns): bool
```

**Chức năng:** Cập nhật DNS cho tên miền
**Tham số:**

- `string $mgd` - Mã giao dịch
- `string $ns1` - Nameserver 1 mới
- `string $ns2` - Nameserver 2 mới
- `string $timedns` - Thời gian cập nhật DNS
  **Trả về:** `bool` - True nếu cập nhật thành công

**Logic xử lý:**

1. Chuẩn bị prepared statement: `UPDATE History SET ns1 = ?, ns2 = ?, ahihi = '1', status = '3', timedns = ? WHERE mgd = ?`
2. Bind tham số ns1, ns2, timedns, mgd
3. Thực thi query (tự động set ahihi='1', status='3')
4. Trả về kết quả thành công/thất bại

**Ví dụ sử dụng:**

```php
$timedns = date('Y-m-d H:i:s');
if ($historyRepo->updateDns('MGD123456', 'new-ns1.com', 'new-ns2.com', $timedns)) {
    echo "Cập nhật DNS thành công";
}
```

---

### **16. getByTimedns()**

```php
public function getByTimedns(string $timedns): ?array
```

**Chức năng:** Tìm kiếm đơn hàng theo thời gian cập nhật DNS
**Tham số:** `string $timedns` - Thời gian cập nhật DNS
**Trả về:** `array|null` - Thông tin đơn hàng hoặc null

---

### **17. resetTimednsById()**

```php
public function resetTimednsById(int $id): bool
```

**Chức năng:** Reset thời gian cập nhật DNS về '0'
**Tham số:** `int $id` - ID đơn hàng
**Trả về:** `bool` - True nếu reset thành công

---

### **18. getByUserIdAndMgd()**

```php
public function getByUserIdAndMgd(int $userId, string $mgd): ?array
```

**Chức năng:** Kiểm tra quyền quản lý tên miền của người dùng
**Tham số:**

- `int $userId` - ID người dùng
- `string $mgd` - Mã giao dịch
  **Trả về:** `array|null` - Thông tin tên miền nếu có quyền, null nếu không

**Logic xử lý:**

1. Chuẩn bị prepared statement: `SELECT * FROM History WHERE uid = ? AND mgd = ? LIMIT 1`
2. Bind tham số userId và mgd
3. Thực thi query
4. Trả về thông tin đơn hàng nếu user có quyền quản lý

**Ví dụ sử dụng:**

```php
$order = $historyRepo->getByUserIdAndMgd(1, 'MGD123456');
if ($order) {
    echo "User có quyền quản lý tên miền: " . $order['domain'];
} else {
    echo "User không có quyền quản lý tên miền này";
}
```

---

## 🔄 **LUỒNG XỬ LÝ THỰC TẾ**

### **📋 Quy trình mua tên miền:**

```php
// 1. Tạo đơn hàng mới
$mgd = 'MGD' . uniqid();
$time = date('Y-m-d H:i:s');

if ($historyRepo->insertPurchase($userId, $domain, $ns1, $ns2, $hsd, $mgd, $time)) {
    echo "Đơn hàng đã được tạo, MGD: " . $mgd;

    // 2. Xử lý đơn hàng (giả lập)
    sleep(2);

    // 3. Cập nhật trạng thái thành hoàn thành
    $historyRepo->updateStatusById($orderId, '1');

    // 4. Cập nhật DNS
    $timedns = date('Y-m-d H:i:s');
    $historyRepo->updateDns($mgd, $newNs1, $newNs2, $timedns);
}
```

### **📊 Thống kê đơn hàng:**

```php
// Thống kê tổng quan
$pendingOrders = $historyRepo->countByStatus(0);
$completedOrders = $historyRepo->countByStatus(1);
$dnsUpdated = $historyRepo->countAhihiOne();

// Thống kê của user
$userPending = $historyRepo->countByUserAndStatus($userId, 0);
$userCompleted = $historyRepo->countByUserAndStatus($userId, 1);
$userRecentOrders = $historyRepo->listRecentByUser($userId, 5);
```

---

## 🔒 **BẢO MẬT**

### **SQL Injection Protection:**

- Tất cả queries đều sử dụng **Prepared Statements**
- Không có string concatenation trong SQL
- Bind parameters an toàn

### **Authorization:**

- Kiểm tra quyền quản lý tên miền qua `getByUserIdAndMgd()`
- Chỉ user sở hữu mới có thể quản lý tên miền

---

## 🎯 **VÍ DỤ SỬ DỤNG HOÀN CHỈNH**

```php
// Khởi tạo repository
$historyRepo = new HistoryRepository($connect);

// Mua tên miền mới
$mgd = 'MGD' . uniqid();
$time = date('Y-m-d H:i:s');

if ($historyRepo->insertPurchase(1, 'example.com', 'ns1.example.com', 'ns2.example.com', '2025-12-15', $mgd, $time)) {
    echo "Đơn hàng đã được tạo, MGD: " . $mgd;

    // Lấy thông tin đơn hàng
    $order = $historyRepo->getByMgd($mgd);
    if ($order) {
        echo "Tên miền: " . $order['domain'];
        echo "Trạng thái: " . $order['status'];

        // Cập nhật DNS
        $timedns = date('Y-m-d H:i:s');
        if ($historyRepo->updateDns($mgd, 'new-ns1.com', 'new-ns2.com', $timedns)) {
            echo "DNS đã được cập nhật";
        }
    }
}

// Thống kê
$pendingOrders = $historyRepo->countByStatus(0);
$completedOrders = $historyRepo->countByStatus(1);
echo "Đơn chờ: " . $pendingOrders . " - Đơn hoàn thành: " . $completedOrders;
```

---

## 📊 **TỔNG KẾT**

**HistoryRepository** cung cấp đầy đủ các chức năng quản lý đơn hàng tên miền:

- ✅ **Create** - Tạo đơn hàng mới
- ✅ **Read** - Lấy danh sách, tìm kiếm theo domain/MGD/user
- ✅ **Update** - Cập nhật trạng thái, DNS
- ✅ **Delete** - Xóa đơn hàng
- ✅ **Statistics** - Thống kê đơn hàng

**Đặc điểm:**

- 🔒 **Bảo mật cao** - Prepared statements
- 🎯 **Logic rõ ràng** - Mỗi method có 1 chức năng
- 📊 **Thống kê đa dạng** - Theo user, trạng thái, thời gian
- 🔐 **Authorization** - Kiểm tra quyền quản lý tên miền

**Use Cases:**

- Quản lý đơn hàng mua tên miền
- Theo dõi trạng thái đơn hàng
- Cập nhật DNS cho tên miền
- Thống kê doanh thu và đơn hàng

**Tác giả:** DAM THANH VU  
**Ngày tạo:** 2024  
**Phiên bản:** 1.0
