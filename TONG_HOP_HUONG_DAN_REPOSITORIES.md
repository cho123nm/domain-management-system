# 📚 **TỔNG HỢP HƯỚNG DẪN TẤT CẢ REPOSITORIES**

## 🎯 **TỔNG QUAN HỆ THỐNG**

Hệ thống sử dụng **Repository Pattern** để tách biệt logic database khỏi business logic. Tất cả các Repository đều được thiết kế với các nguyên tắc:

- **🔒 Bảo mật cao** - Prepared statements chống SQL injection
- **🎯 Logic rõ ràng** - Mỗi method có 1 chức năng cụ thể
- **🔄 Tái sử dụng** - Có thể dùng ở nhiều nơi
- **📝 Dễ maintain** - Code có comment chi tiết

---

## 📋 **DANH SÁCH REPOSITORIES**

### **1. 👤 UserRepository**

**File:** `HUONG_DAN_USER_REPOSITORY.md`
**Chức năng:** Quản lý tất cả thao tác với bảng `Users`
**Số methods:** 11 methods

**Các chức năng chính:**

- ✅ Xác thực đăng nhập (`verifyCredentials`)
- ✅ Quản lý thông tin người dùng (CRUD)
- ✅ Cập nhật số dư tài khoản (`updateBalance`, `incrementBalance`)
- ✅ Tìm kiếm user theo username/email/ID
- ✅ Đăng ký tài khoản mới (`createUser`)

**Ví dụ sử dụng:**

```php
$userRepo = new UserRepository($connect);
if ($userRepo->verifyCredentials('admin', '123456')) {
    $user = $userRepo->findByUsername('admin');
    $userRepo->incrementBalance($user['id'], 100000);
}
```

---

### **2. 🌐 DomainRepository**

**File:** `HUONG_DAN_DOMAIN_REPOSITORY.md`
**Chức năng:** Quản lý tất cả thao tác với bảng `ListDomain`
**Số methods:** 8 methods

**Các chức năng chính:**

- ✅ Quản lý danh sách loại tên miền (`listAll`)
- ✅ Thêm/xóa loại tên miền (`create`, `deleteById`)
- ✅ Cập nhật thông tin tên miền (`updateById`)
- ✅ Tìm kiếm theo ID/đuôi (`findById`, `findByDuoi`)

**Ví dụ sử dụng:**

```php
$domainRepo = new DomainRepository($connect);
$domains = $domainRepo->listAll();
$domainRepo->create(50000, '.com', '/images/com.png');
```

---

### **3. 💳 CardRepository**

**File:** `HUONG_DAN_CARD_REPOSITORY.md`
**Chức năng:** Quản lý tất cả thao tác với bảng `Cards`
**Số methods:** 10 methods

**Các chức năng chính:**

- ✅ Quản lý lịch sử nạp thẻ (`insertCard`, `listByUserId`)
- ✅ Kiểm tra thẻ trùng lặp (`existsByPinSerial`)
- ✅ Thống kê doanh thu (`sumAmountByStatus`, `sumAmountByStatusAndTime2`)
- ✅ Cập nhật trạng thái thẻ (`updateStatusByRequestId`)

**Ví dụ sử dụng:**

```php
$cardRepo = new CardRepository($connect);
if (!$cardRepo->existsByPinSerial($pin, $serial)) {
    $cardRepo->insertCard($userId, $pin, $serial, $type, $amount, $requestId, $time, $time2);
}
```

---

### **4. 📋 HistoryRepository**

**File:** `HUONG_DAN_HISTORY_REPOSITORY.md`
**Chức năng:** Quản lý tất cả thao tác với bảng `History`
**Số methods:** 18 methods

**Các chức năng chính:**

- ✅ Quản lý đơn hàng mua tên miền (`insertPurchase`)
- ✅ Theo dõi trạng thái đơn hàng (`updateStatusById`)
- ✅ Quản lý DNS (`updateDns`)
- ✅ Thống kê đơn hàng (`countByStatus`, `countByUserAndStatus`)
- ✅ Kiểm tra quyền quản lý (`getByUserIdAndMgd`)

**Ví dụ sử dụng:**

```php
$historyRepo = new HistoryRepository($connect);
$mgd = 'MGD' . uniqid();
$historyRepo->insertPurchase($userId, $domain, $ns1, $ns2, $hsd, $mgd, $time);
$historyRepo->updateDns($mgd, $newNs1, $newNs2, $timedns);
```

---

### **5. ⚙️ SettingsRepository**

**File:** `HUONG_DAN_SETTINGS_REPOSITORY.md`
**Chức năng:** Quản lý tất cả thao tác với bảng `CaiDatChung`
**Số methods:** 4 methods

**Các chức năng chính:**

- ✅ Lấy cài đặt website (`getOne`)
- ✅ Cập nhật cài đặt website (`updateWebsiteSettings`)
- ✅ Cấu hình API thẻ cào (`updateCardGateway`)

**Ví dụ sử dụng:**

```php
$settingsRepo = new SettingsRepository($connect);
$settings = $settingsRepo->getOne();
$settingsRepo->updateWebsiteSettings($title, $theme, $keywords, $description, $imagebanner, $phone, $banner, $logo);
```

---

## 🔄 **LUỒNG XỬ LÝ TỔNG QUAN**

### **📋 Quy trình mua tên miền:**

```php
// 1. Kiểm tra user đăng nhập
$userRepo = new UserRepository($connect);
$user = $userRepo->findByUsername($_SESSION['users']);

// 2. Lấy danh sách loại tên miền
$domainRepo = new DomainRepository($connect);
$domains = $domainRepo->listAll();

// 3. Tạo đơn hàng mới
$historyRepo = new HistoryRepository($connect);
$mgd = 'MGD' . uniqid();
$historyRepo->insertPurchase($user['id'], $domain, $ns1, $ns2, $hsd, $mgd, $time);

// 4. Cập nhật trạng thái đơn hàng
$historyRepo->updateStatusById($orderId, '1');

// 5. Cập nhật DNS
$historyRepo->updateDns($mgd, $newNs1, $newNs2, $timedns);
```

### **💳 Quy trình nạp thẻ cào:**

```php
// 1. Kiểm tra thẻ chưa sử dụng
$cardRepo = new CardRepository($connect);
if (!$cardRepo->existsByPinSerial($pin, $serial)) {

    // 2. Thêm thẻ vào hệ thống
    $requestId = uniqid();
    $cardRepo->insertCard($userId, $pin, $serial, $type, $amount, $requestId, $time, $time2);

    // 3. Gửi request đến API thẻ cào
    $apiResponse = callCardAPI($pin, $serial, $type, $requestId);

    // 4. Cập nhật trạng thái theo kết quả API
    if ($apiResponse['success']) {
        $cardRepo->updateStatusByRequestId($requestId, '1');

        // 5. Cập nhật số dư user
        $userRepo = new UserRepository($connect);
        $userRepo->incrementBalance($userId, $amount);
    }
}
```

### **📊 Thống kê hệ thống:**

```php
// Thống kê user
$userRepo = new UserRepository($connect);
$totalUsers = $userRepo->countAll();

// Thống kê đơn hàng
$historyRepo = new HistoryRepository($connect);
$pendingOrders = $historyRepo->countByStatus(0);
$completedOrders = $historyRepo->countByStatus(1);

// Thống kê doanh thu thẻ cào
$cardRepo = new CardRepository($connect);
$todayRevenue = $cardRepo->sumAmountByStatusAndTime2(1, date('d/m/Y'));
$monthRevenue = $cardRepo->sumAmountByStatusAndTime3(1, date('m/Y'));
```

---

## 🔒 **BẢO MẬT TỔNG QUAN**

### **SQL Injection Protection:**

- **100% Prepared Statements** - Tất cả queries đều sử dụng prepared statements
- **Parameter Binding** - Bind parameters an toàn
- **No String Concatenation** - Không có string concatenation trong SQL

### **Data Validation:**

- **Input Sanitization** - Làm sạch dữ liệu đầu vào
- **Type Checking** - Kiểm tra kiểu dữ liệu
- **Range Validation** - Kiểm tra giá trị hợp lệ

### **Authorization:**

- **User Permission** - Kiểm tra quyền user
- **Domain Ownership** - Kiểm tra quyền sở hữu tên miền
- **Admin Access** - Phân quyền admin

---

## 🎯 **DEMO CHO THẦY**

### **💻 Có thể demo các chức năng:**

1. **Authentication Flow:**

   ```php
   // Đăng nhập
   if ($userRepo->verifyCredentials('admin', '123456')) {
       $user = $userRepo->findByUsername('admin');
       echo "Xin chào: " . $user['taikhoan'];
   }
   ```

2. **Domain Management:**

   ```php
   // Lấy danh sách loại tên miền
   $domains = $domainRepo->listAll();
   foreach ($domains as $domain) {
       echo $domain['duoi'] . " - " . number_format($domain['price']) . "Đ";
   }
   ```

3. **Order Processing:**

   ```php
   // Tạo đơn hàng
   $mgd = 'MGD' . uniqid();
   $historyRepo->insertPurchase(1, 'example.com', 'ns1.com', 'ns2.com', '2025-12-15', $mgd, date('Y-m-d H:i:s'));

   // Cập nhật DNS
   $historyRepo->updateDns($mgd, 'new-ns1.com', 'new-ns2.com', date('Y-m-d H:i:s'));
   ```

4. **Card Processing:**

   ```php
   // Kiểm tra thẻ
   if (!$cardRepo->existsByPinSerial('123456789', 'ABC123')) {
       $cardRepo->insertCard(1, '123456789', 'ABC123', 'Viettel', '100000', uniqid(), date('Y-m-d H:i:s'), date('d/m/Y'));
   }
   ```

5. **Statistics:**

   ```php
   // Thống kê tổng quan
   $totalUsers = $userRepo->countAll();
   $pendingOrders = $historyRepo->countByStatus(0);
   $todayRevenue = $cardRepo->sumAmountByStatusAndTime2(1, date('d/m/Y'));

   echo "Tổng user: " . $totalUsers;
   echo "Đơn chờ: " . $pendingOrders;
   echo "Doanh thu hôm nay: " . number_format($todayRevenue) . "Đ";
   ```

---

## 📊 **TỔNG KẾT**

### **🎯 Số lượng methods:**

- **UserRepository:** 11 methods
- **DomainRepository:** 8 methods
- **CardRepository:** 10 methods
- **HistoryRepository:** 18 methods
- **SettingsRepository:** 4 methods
- **Tổng cộng:** 51 methods

### **🔧 Design Patterns sử dụng:**

- **Repository Pattern** - Tách biệt database logic
- **Singleton Pattern** - Database connection
- **Factory Pattern** - Repository factory
- **MVC Pattern** - Model-View-Controller

### **💡 Đặc điểm nổi bật:**

- **🔒 Bảo mật cao** - Prepared statements 100%
- **🎯 Logic rõ ràng** - Mỗi method có 1 chức năng
- **📊 Thống kê đa dạng** - Theo user, thời gian, trạng thái
- **🔄 Tái sử dụng** - Có thể dùng ở nhiều nơi
- **📝 Dễ maintain** - Code có comment chi tiết

### **🚀 Lợi ích:**

- **Dễ bảo trì** - Code có cấu trúc rõ ràng
- **Dễ mở rộng** - Thêm method mới dễ dàng
- **Bảo mật cao** - Chống SQL injection
- **Hiệu suất tốt** - Prepared statements
- **Dễ test** - Logic tách biệt

**Tác giả:** DAM THANH VU  
**Ngày tạo:** 2024  
**Phiên bản:** 1.0
