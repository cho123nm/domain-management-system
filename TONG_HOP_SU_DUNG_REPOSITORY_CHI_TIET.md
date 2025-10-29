# 📚 **TỔNG HỢP CHI TIẾT SỬ DỤNG REPOSITORY TRONG HỆ THỐNG**

## 🎯 **TỔNG QUAN**

Tài liệu này tổng hợp chi tiết tất cả các hàm Repository được sử dụng trong từng file của hệ thống, bao gồm:

- **File nào sử dụng hàm nào**
- **Chức năng cụ thể của từng hàm**
- **Luồng xử lý và mục đích sử dụng**

---

## 👤 **USERREPOSITORY - QUẢN LÝ NGƯỜI DÙNG**

### **📋 Danh sách các hàm:**

| Hàm                   | Mô tả                     | Tham số                                                              | Trả về        |
| --------------------- | ------------------------- | -------------------------------------------------------------------- | ------------- |
| `findByUsername()`    | Tìm user theo username    | `string $username`                                                   | `array\|null` |
| `verifyCredentials()` | Xác thực đăng nhập        | `string $username, string $password`                                 | `bool`        |
| `listAll()`           | Lấy danh sách tất cả user | -                                                                    | `array`       |
| `countAll()`          | Đếm tổng số user          | -                                                                    | `int`         |
| `updateBalance()`     | Cập nhật số dư tuyệt đối  | `int $userId, int $amount`                                           | `bool`        |
| `incrementBalance()`  | Tăng số dư                | `int $userId, int $delta`                                            | `bool`        |
| `createUser()`        | Tạo user mới              | `string $username, string $passwordMd5, string $email, string $time` | `bool`        |
| `findById()`          | Tìm user theo ID          | `int $userId`                                                        | `array\|null` |
| `findByEmail()`       | Tìm user theo email       | `string $email`                                                      | `array\|null` |
| `updateProfile()`     | Cập nhật profile          | `int $userId, string $email, string $newUsername`                    | `bool`        |

### **📁 Các file sử dụng UserRepository:**

#### **1. Adminstators/don-nap-vi.php**

```php
$userRepo = new UserRepository($connect);
$checkus = $userRepo->findById((int)$id);  // Kiểm tra user tồn tại
$thanhright = $userRepo->incrementBalance((int)$id, (int)$price);  // Cộng tiền
```

**Chức năng:** Quản lý nạp tiền thủ công cho user

#### **2. Adminstators/quan-ly-thanh-vien.php**

```php
$userRepo = new UserRepository($connect);
$resultRows = $userRepo->listAll();  // Lấy danh sách tất cả user
$userRepo->updateBalance($userId, $newBalance);  // Cập nhật số dư
```

**Chức năng:** Quản lý danh sách thành viên và cập nhật số dư

#### **3. Adminstators/index.php**

```php
$factory = new RepositoryFactory($connect);
$userRepo = $factory->users();
$totalUsers = $userRepo->countAll();  // Đếm tổng số user
```

**Chức năng:** Dashboard thống kê tổng quan

#### **4. Pages/account_profile.php**

```php
$userRepo = new UserRepository($connect);
$user = $userRepo->findByUsername($currentUsername);  // Lấy thông tin user hiện tại
```

**Chức năng:** Hiển thị thông tin cá nhân

#### **5. Ajaxs/login.php**

```php
$userRepo = new UserRepository($connect);
if ($userRepo->verifyCredentials($taikhoan, $matkhau)) {  // Xác thực đăng nhập
    $_SESSION['users'] = $taikhoan;
}
```

**Chức năng:** Xử lý đăng nhập AJAX

#### **6. Ajaxs/register.php**

```php
$userRepo = new UserRepository($connect);
if($userRepo->findByUsername($taikhoan)) {  // Kiểm tra username đã tồn tại
    // Báo lỗi
} else if($userRepo->findByEmail($email)) {  // Kiểm tra email đã tồn tại
    // Báo lỗi
} else {
    $userRepo->createUser($taikhoan, $matkhau, $email);  // Tạo user mới
}
```

**Chức năng:** Xử lý đăng ký AJAX

#### **7. Controllers/ViewController.php**

```php
$this->userRepo = new UserRepository($connect);
$user = $this->userRepo->findByUsername($_SESSION['users']);  // Lấy thông tin user
```

**Chức năng:** Controller xử lý view

#### **8. Controllers/DomainController.php**

```php
$this->userRepo = new UserRepository($connect);
$user = $this->userRepo->findByUsername($_SESSION['users']);  // Lấy thông tin user
```

**Chức năng:** Controller xử lý domain

#### **9. Controllers/AjaxController.php**

```php
$this->userRepo = new UserRepository($connect);
$user = $this->userRepo->findByUsername($_SESSION['users']);  // Lấy thông tin user
```

**Chức năng:** Controller xử lý AJAX

#### **10. Controllers/AdminController.php**

```php
$this->userRepo = new UserRepository($connect);
$users = $this->userRepo->listAll();  // Lấy danh sách user
```

**Chức năng:** Controller xử lý admin

#### **11. Controllers/AuthController.php**

```php
$this->userRepo = new UserRepository($connect);
if ($this->userRepo->verifyCredentials($username, $password)) {  // Xác thực
    // Xử lý đăng nhập
}
```

**Chức năng:** Controller xử lý xác thực

#### **12. Controllers/BaseController.php**

```php
$userRepo = new UserRepository($this->connect);
return $userRepo->findByUsername($_SESSION['users']);  // Lấy user hiện tại
```

**Chức năng:** Controller cơ sở

#### **13. Controllers/CardController.php**

```php
$this->userRepo = new UserRepository($connect);
$user = $this->userRepo->findByUsername($_SESSION['users']);  // Lấy thông tin user
```

**Chức năng:** Controller xử lý thẻ cào

#### **14. Config/Database.php**

```php
$userRepo = new UserRepository($connect);
$users = $userRepo->findByUsername($_SESSION['users']);  // Lấy thông tin user
```

**Chức năng:** Cấu hình database

#### **15. Pages/ManagesWhois.php**

```php
$userRepo = new UserRepository($connect);
$currentUser = $userRepo->findByUsername($_SESSION['users']);  // Lấy user hiện tại
```

**Chức năng:** Quản lý Whois

#### **16. callback.php**

```php
$userRepo = new UserRepository($connect);
$userRepo->incrementBalance($uid, (int)$value_customer_receive);  // Cộng tiền từ callback
```

**Chức năng:** Xử lý callback từ API thẻ cào

#### **17. Ajaxs/UpdateDns.php**

```php
$userRepo = new UserRepository($connect);
$currentUser = $userRepo->findByUsername($_SESSION['users']);  // Lấy user hiện tại
```

**Chức năng:** Cập nhật DNS

---

## 🌐 **DOMAINREPOSITORY - QUẢN LÝ TÊN MIỀN**

### **📋 Danh sách các hàm:**

| Hàm              | Mô tả                       | Tham số                                            | Trả về        |
| ---------------- | --------------------------- | -------------------------------------------------- | ------------- |
| `listAll()`      | Lấy danh sách tất cả domain | -                                                  | `array`       |
| `create()`       | Tạo domain mới              | `int $price, string $duoi, string $image`          | `bool`        |
| `deleteById()`   | Xóa domain theo ID          | `int $id`                                          | `bool`        |
| `findById()`     | Tìm domain theo ID          | `int $id`                                          | `array\|null` |
| `updateById()`   | Cập nhật domain             | `int $id, string $duoi, string $image, int $price` | `bool`        |
| `getOneSample()` | Lấy một mẫu domain          | -                                                  | `array\|null` |
| `findByDuoi()`   | Tìm domain theo đuôi        | `string $duoi`                                     | `array\|null` |

### **📁 Các file sử dụng DomainRepository:**

#### **1. Adminstators/danh-sach-san-pham.php**

```php
$domainRepo = new DomainRepository($connect);
$resultRows = $domainRepo->listAll();  // Lấy danh sách tất cả domain
$domainRepo->deleteById((int)$id);  // Xóa domain
```

**Chức năng:** Quản lý danh sách sản phẩm

#### **2. Adminstators/them-san-pham.php**

```php
$domainRepo = new DomainRepository($connect);
$ok = $domainRepo->create((int)$price, $duoi, $image);  // Tạo domain mới
```

**Chức năng:** Thêm sản phẩm mới

#### **3. Adminstators/Edit.php**

```php
$domainRepo = new DomainRepository($connect);
$cloudstorevn12 = $domainRepo->findById($domainId);  // Lấy thông tin domain
$ok = $domainRepo->updateById($domainId, $tieude, $image, (int)$price);  // Cập nhật domain
```

**Chức năng:** Chỉnh sửa sản phẩm

#### **4. Adminstators/index.php**

```php
$factory = new RepositoryFactory($connect);
$domainRepo = $factory->domains();
```

**Chức năng:** Dashboard admin

#### **5. Controllers/ViewController.php**

```php
$this->domainRepo = new DomainRepository($connect);
$domains = $this->domainRepo->listAll();  // Lấy danh sách domain
```

**Chức năng:** Controller xử lý view

#### **6. Controllers/DomainController.php**

```php
$this->domainRepo = new DomainRepository($connect);
$domain = $this->domainRepo->findByDuoi($duoi);  // Tìm domain theo đuôi
```

**Chức năng:** Controller xử lý domain

#### **7. Controllers/AjaxController.php**

```php
$this->domainRepo = new DomainRepository($connect);
$domains = $this->domainRepo->listAll();  // Lấy danh sách domain
```

**Chức năng:** Controller xử lý AJAX

#### **8. Controllers/AdminController.php**

```php
$this->domainRepo = new DomainRepository($connect);
$domains = $this->domainRepo->listAll();  // Lấy danh sách domain
```

**Chức năng:** Controller xử lý admin

#### **9. Controllers/CardController.php**

```php
$this->domainRepo = new DomainRepository($connect);
```

**Chức năng:** Controller xử lý thẻ cào

#### **10. index.php**

```php
$domainRepo = new DomainRepository($connect);
$domains = $domainRepo->listAll();  // Lấy danh sách domain để hiển thị
```

**Chức năng:** Trang chủ hiển thị sản phẩm

#### **11. Config/Database.php**

```php
$domainRepo = new DomainRepository($connect);
$domainname = $domainRepo->getOneSample();  // Lấy mẫu domain
```

**Chức năng:** Cấu hình database

#### **12. Pages/Checkout.php**

```php
$domainRepo = new DomainRepository($connect);
$fetch = $domainRepo->findByDuoi($duoimien);  // Tìm domain theo đuôi
```

**Chức năng:** Trang thanh toán

---

## 📋 **HISTORYREPOSITORY - QUẢN LÝ LỊCH SỬ GIAO DỊCH**

### **📋 Danh sách các hàm:**

| Hàm                          | Mô tả                                 | Tham số                                                                                         | Trả về        |
| ---------------------------- | ------------------------------------- | ----------------------------------------------------------------------------------------------- | ------------- |
| `getByDomain()`              | Tìm đơn hàng theo domain              | `string $domain`                                                                                | `array\|null` |
| `getByMgd()`                 | Tìm đơn hàng theo MGD                 | `string $mgd`                                                                                   | `array\|null` |
| `listAll()`                  | Lấy danh sách tất cả đơn hàng         | -                                                                                               | `array`       |
| `countByStatus()`            | Đếm đơn hàng theo trạng thái          | `int $status`                                                                                   | `int`         |
| `countAhihiOne()`            | Đếm đơn hàng đã cập nhật DNS          | -                                                                                               | `int`         |
| `listByAhihi()`              | Lấy đơn hàng theo trạng thái DNS      | `string $value`                                                                                 | `array`       |
| `countByUserAndStatus()`     | Đếm đơn hàng của user theo trạng thái | `int $userId, int $status`                                                                      | `int`         |
| `listRecentByUser()`         | Lấy đơn hàng gần đây của user         | `int $userId, int $limit`                                                                       | `array`       |
| `listByUser()`               | Lấy tất cả đơn hàng của user          | `int $userId`                                                                                   | `array`       |
| `updateStatusById()`         | Cập nhật trạng thái đơn hàng          | `int $id, string $status`                                                                       | `bool`        |
| `updateAhihiAndStatusById()` | Cập nhật DNS và trạng thái            | `int $id, string $ahihi, string $status`                                                        | `bool`        |
| `deleteById()`               | Xóa đơn hàng                          | `int $id`                                                                                       | `bool`        |
| `insertPurchase()`           | Thêm đơn hàng mới                     | `int $userId, string $domain, string $ns1, string $ns2, string $hsd, string $mgd, string $time` | `bool`        |
| `updateDns()`                | Cập nhật DNS                          | `string $mgd, string $ns1, string $ns2, string $timedns`                                        | `bool`        |
| `getByTimedns()`             | Tìm đơn hàng theo thời gian DNS       | `string $timedns`                                                                               | `array\|null` |
| `resetTimednsById()`         | Reset thời gian DNS                   | `int $id`                                                                                       | `bool`        |
| `getByUserIdAndMgd()`        | Kiểm tra quyền quản lý                | `int $userId, string $mgd`                                                                      | `array\|null` |

### **📁 Các file sử dụng HistoryRepository:**

#### **1. Adminstators/duyet-don-hang.php**

```php
$historyRepo = new HistoryRepository($connect);
$historyRepo->updateStatusById((int)$_GET['true'], '1');  // Duyệt đơn hàng
$historyRepo->updateStatusById((int)$_GET['cho'], '0');   // Chờ xử lý
$historyRepo->updateStatusById((int)$_GET['false'], '2'); // Từ chối
$resultRows = $historyRepo->listAll();  // Lấy danh sách đơn hàng
$historyRepo->deleteById((int)$id);  // Xóa đơn hàng
```

**Chức năng:** Duyệt đơn hàng

#### **2. Adminstators/DNS.php**

```php
$historyRepo = new HistoryRepository($connect);
$resultRows = $historyRepo->listByAhihi('1');  // Lấy đơn hàng đã cập nhật DNS
```

**Chức năng:** Quản lý DNS

#### **3. Adminstators/index.php**

```php
$factory = new RepositoryFactory($connect);
$historyRepo = $factory->history();
$pendingOrders = $historyRepo->countByStatus(0);  // Đếm đơn chờ
$completedOrders = $historyRepo->countByStatus(1);  // Đếm đơn hoàn thành
```

**Chức năng:** Dashboard thống kê

#### **4. Pages/account_profile.php**

```php
$historyRepo = new HistoryRepository($connect);
$userOrders = $historyRepo->listByUser($user['id']);  // Lấy đơn hàng của user
```

**Chức năng:** Thông tin cá nhân

#### **5. Controllers/ViewController.php**

```php
$this->historyRepo = new HistoryRepository($connect);
$orders = $this->historyRepo->listByUser($user['id']);  // Lấy đơn hàng của user
```

**Chức năng:** Controller xử lý view

#### **6. Controllers/DomainController.php**

```php
$this->historyRepo = new HistoryRepository($connect);
$mgd = 'MGD' . uniqid();
$this->historyRepo->insertPurchase($user['id'], $domain, $ns1, $ns2, $hsd, $mgd, $time);  // Tạo đơn hàng
```

**Chức năng:** Controller xử lý domain

#### **7. Controllers/AjaxController.php**

```php
$this->historyRepo = new HistoryRepository($connect);
$orders = $this->historyRepo->listByUser($user['id']);  // Lấy đơn hàng của user
```

**Chức năng:** Controller xử lý AJAX

#### **8. Controllers/AdminController.php**

```php
$this->historyRepo = new HistoryRepository($connect);
$orders = $this->historyRepo->listAll();  // Lấy tất cả đơn hàng
```

**Chức năng:** Controller xử lý admin

#### **9. Config/Database.php**

```php
$historyRepo = new HistoryRepository($connect);
$checkhsd = $historyRepo->getByTimedns($today);  // Kiểm tra đơn hàng theo ngày
```

**Chức năng:** Cấu hình database

#### **10. Pages/ManagesWhois.php**

```php
$historyRepo = new HistoryRepository($connect);
$rows = $historyRepo->listByUser((int)$currentUser['id']);  // Lấy đơn hàng của user
```

**Chức năng:** Quản lý Whois

#### **11. Pages/managers.php**

```php
$historyRepo = new HistoryRepository($connect);
$rows = $historyRepo->listByUser((int)$currentUser['id']);  // Lấy đơn hàng của user
```

**Chức năng:** Quản lý tên miền

#### **12. Ajaxs/BuyDomain.php**

```php
$historyRepo = new HistoryRepository($connect);
$mgd = 'MGD' . uniqid();
$historyRepo->insertPurchase($user['id'], $domain, $ns1, $ns2, $hsd, $mgd, $time);  // Tạo đơn hàng
```

**Chức năng:** Mua domain AJAX

#### **13. Ajaxs/UpdateDns.php**

```php
$historyRepo = new HistoryRepository($connect);
$order = $historyRepo->getByUserIdAndMgd($currentUser['id'], $mgd);  // Kiểm tra quyền
$historyRepo->updateDns($mgd, $ns1, $ns2, $timedns);  // Cập nhật DNS
```

**Chức năng:** Cập nhật DNS AJAX

---

## 💳 **CARDREPOSITORY - QUẢN LÝ THẺ CÀO**

### **📋 Danh sách các hàm:**

| Hàm                           | Mô tả                               | Tham số                                                                                                                  | Trả về      |
| ----------------------------- | ----------------------------------- | ------------------------------------------------------------------------------------------------------------------------ | ----------- |
| `existsByPinSerial()`         | Kiểm tra thẻ đã tồn tại             | `string $pin, string $serial`                                                                                            | `bool`      |
| `insertCard()`                | Thêm thẻ cào                        | `int $userId, string $pin, string $serial, string $type, string $amount, string $requestId, string $time, string $time2` | `bool`      |
| `listByUserId()`              | Lấy thẻ của user                    | `int $userId`                                                                                                            | `array`     |
| `sumAmountByStatusAndTime2()` | Tính doanh thu theo ngày            | `int $status, string $time2`                                                                                             | `int`       |
| `sumAmountByStatusAndTime3()` | Tính doanh thu theo tháng           | `int $status, string $time3`                                                                                             | `int`       |
| `sumAmountByStatus()`         | Tính doanh thu theo trạng thái      | `int $status`                                                                                                            | `int`       |
| `listAll()`                   | Lấy tất cả thẻ                      | -                                                                                                                        | `array`     |
| `getUidByRequestId()`         | Lấy UID theo request ID             | `string $requestId`                                                                                                      | `int\|null` |
| `updateStatusByRequestId()`   | Cập nhật trạng thái theo request ID | `string $requestId, string $status`                                                                                      | `bool`      |

### **📁 Các file sử dụng CardRepository:**

#### **1. Adminstators/Gach-Cards.php**

```php
$cardRepo = new CardRepository($connect);
$resultRows = $cardRepo->listAll();  // Lấy danh sách tất cả thẻ
```

**Chức năng:** Quản lý thẻ cào

#### **2. Adminstators/index.php**

```php
$factory = new RepositoryFactory($connect);
$cardRepo = $factory->cards();
$todayRevenue = $cardRepo->sumAmountByStatusAndTime2(1, date('d/m/Y'));  // Doanh thu hôm nay
$monthRevenue = $cardRepo->sumAmountByStatusAndTime3(1, date('m/Y'));  // Doanh thu tháng
$totalRevenue = $cardRepo->sumAmountByStatus(1);  // Tổng doanh thu
```

**Chức năng:** Dashboard thống kê doanh thu

#### **3. Controllers/ViewController.php**

```php
$this->cardRepo = new CardRepository($connect);
$cards = $this->cardRepo->listByUserId($user['id']);  // Lấy thẻ của user
```

**Chức năng:** Controller xử lý view

#### **4. Controllers/AjaxController.php**

```php
$this->cardRepo = new CardRepository($connect);
$cards = $this->cardRepo->listByUserId($user['id']);  // Lấy thẻ của user
```

**Chức năng:** Controller xử lý AJAX

#### **5. Controllers/AdminController.php**

```php
$this->cardRepo = new CardRepository($connect);
$cards = $this->cardRepo->listAll();  // Lấy tất cả thẻ
```

**Chức năng:** Controller xử lý admin

#### **6. Controllers/CardController.php**

```php
$this->cardRepo = new CardRepository($connect);
if (!$this->cardRepo->existsByPinSerial($pin, $serial)) {  // Kiểm tra thẻ chưa sử dụng
    $this->cardRepo->insertCard($userId, $pin, $serial, $type, $amount, $requestId, $time, $time2);  // Thêm thẻ
}
```

**Chức năng:** Controller xử lý thẻ cào

#### **7. Pages/Recharge.php**

```php
$cardRepo = new CardRepository($connect);
$resultRows = $cardRepo->listByUserId((int)$users['id']);  // Lấy thẻ của user
```

**Chức năng:** Trang nạp tiền

#### **8. callback.php**

```php
$cardRepo = new CardRepository($connect);
$uid = $cardRepo->getUidByRequestId($requestid);  // Lấy UID theo request ID
$cardRepo->updateStatusByRequestId($requestid, 1);  // Cập nhật trạng thái thành công
```

**Chức năng:** Xử lý callback từ API thẻ cào

#### **9. Ajaxs/Cards.php**

```php
$cardRepo = new CardRepository($connect);
if (!$cardRepo->existsByPinSerial($pin, $serial)) {  // Kiểm tra thẻ chưa sử dụng
    $cardRepo->insertCard($user['id'], $pin, $serial, $type, $amount, $requestId, $time, $time2);  // Thêm thẻ
}
```

**Chức năng:** Xử lý nạp thẻ AJAX

---

## ⚙️ **SETTINGSREPOSITORY - QUẢN LÝ CÀI ĐẶT**

### **📋 Danh sách các hàm:**

| Hàm                       | Mô tả                        | Tham số                                                                                                                                 | Trả về        |
| ------------------------- | ---------------------------- | --------------------------------------------------------------------------------------------------------------------------------------- | ------------- |
| `getOne()`                | Lấy cài đặt hiện tại         | -                                                                                                                                       | `array\|null` |
| `updateWebsiteSettings()` | Cập nhật cài đặt website     | `string $title, string $theme, string $keywords, string $description, string $imagebanner, string $phone, string $banner, string $logo` | `bool`        |
| `updateCardGateway()`     | Cập nhật cài đặt API thẻ cào | `string $apikey, string $callback, string $webgach`                                                                                     | `bool`        |

### **📁 Các file sử dụng SettingsRepository:**

#### **1. Adminstators/cai-dat-web.php**

```php
$settingsRepo = new SettingsRepository($connect);
$settings = $settingsRepo->getOne();  // Lấy cài đặt hiện tại
$settingsRepo->updateWebsiteSettings($tieude, $theme, $keywords, $mota, $imagebanner, $sodienthoai, $banner, $logo);  // Cập nhật cài đặt
```

**Chức năng:** Cài đặt website

#### **2. Controllers/ViewController.php**

```php
$settingsRepo = new SettingsRepository($this->connect);
$settings = $settingsRepo->getOne();  // Lấy cài đặt để hiển thị
```

**Chức năng:** Controller xử lý view

#### **3. Controllers/AdminController.php**

```php
$this->settingsRepo = new SettingsRepository($connect);
$settings = $this->settingsRepo->getOne();  // Lấy cài đặt
```

**Chức năng:** Controller xử lý admin

#### **4. Controllers/CardController.php**

```php
$this->settingsRepo = new SettingsRepository($connect);
$settings = $this->settingsRepo->getOne();  // Lấy cài đặt API
```

**Chức năng:** Controller xử lý thẻ cào

#### **5. Config/Database.php**

```php
$settingsRepo = new SettingsRepository($connect);
$CaiDatChung = $settingsRepo->getOne();  // Lấy cài đặt toàn cục
```

**Chức năng:** Cấu hình database

---

## 📊 **TỔNG KẾT SỬ DỤNG**

### **🎯 Thống kê sử dụng Repository:**

| Repository             | Số file sử dụng | Số hàm được gọi | Mục đích chính                         |
| ---------------------- | --------------- | --------------- | -------------------------------------- |
| **UserRepository**     | 17 files        | 10/11 hàm       | Xác thực, quản lý user, cập nhật số dư |
| **DomainRepository**   | 12 files        | 7/7 hàm         | Quản lý sản phẩm, CRUD domain          |
| **HistoryRepository**  | 13 files        | 18/18 hàm       | Quản lý đơn hàng, DNS, thống kê        |
| **CardRepository**     | 9 files         | 9/9 hàm         | Quản lý thẻ cào, doanh thu             |
| **SettingsRepository** | 5 files         | 3/3 hàm         | Cài đặt hệ thống                       |

### **🔍 Phân tích mức độ sử dụng:**

#### **1. UserRepository - Sử dụng nhiều nhất:**

- **17 files** sử dụng
- Chủ yếu cho **xác thực** và **quản lý user**
- Hàm `findByUsername()` được dùng nhiều nhất

#### **2. HistoryRepository - Chức năng phong phú nhất:**

- **18 hàm** được implement
- **13 files** sử dụng
- Chủ yếu cho **quản lý đơn hàng** và **DNS**

#### **3. DomainRepository - CRUD đầy đủ:**

- **12 files** sử dụng
- **7 hàm** CRUD đầy đủ
- Chủ yếu cho **quản lý sản phẩm**

#### **4. CardRepository - Tích hợp API:**

- **9 files** sử dụng
- **9 hàm** chuyên về thẻ cào
- Chủ yếu cho **nạp tiền** và **callback API**

#### **5. SettingsRepository - Cài đặt hệ thống:**

- **5 files** sử dụng
- **3 hàm** cài đặt
- Chủ yếu cho **cấu hình website**

### **🎯 Kết luận:**

**Repository Pattern đã được sử dụng rất hiệu quả trong hệ thống:**

- ✅ **Tách biệt logic** - Database logic tách khỏi business logic
- ✅ **Tái sử dụng cao** - Mỗi repository được dùng ở nhiều nơi
- ✅ **Chức năng đầy đủ** - CRUD operations hoàn chỉnh
- ✅ **Bảo mật tốt** - Prepared statements 100%
- ✅ **Dễ maintain** - Code có cấu trúc rõ ràng

**Tác giả:** DAM THANH VU  
**Ngày tạo:** 2024  
**Phiên bản:** 1.0
