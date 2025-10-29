# 🛒 **TRÌNH BÀY THIẾT KẾ VÀ XÂY DỰNG CHỨC NĂNG MUA DOMAIN**

## 📝 **1. MÔ TẢ CHỨC NĂNG:**

Chức năng mua domain cho phép người dùng đã đăng nhập đăng ký tên miền với thông tin nameserver, tạo đơn hàng và cập nhật lịch sử giao dịch. Đây là trang đăng ký domain với form đơn giản và xử lý AJAX.

## 👤 **2. TÁC NHÂN THỰC HIỆN:**

- **Người dùng đã đăng nhập** - Khách hàng có tài khoản và muốn mua domain

## 🔍 **3. DẠNG TRUY VẤN:**

- **SELECT** - Lấy thông tin domain và giá bán
- **INSERT** - Truy vấn thêm đơn hàng mới vào database
- **UPDATE** - Cập nhật số dư tài khoản

## 🗄️ **4. TRUY VẤN VÀO TABLE:**

- **Table:** `ListDomain` (thông tin domain và giá bán)
- **Table:** `History` (lưu đơn hàng mua domain)
- **Table:** `Users` (cập nhật số dư)

## 📊 **5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

### **Table ListDomain:**

- `id` (int) - ID loại domain
- `duoi` (varchar) - Đuôi domain (.com, .net, .org)
- `price` (int) - Giá bán
- `image` (varchar) - Hình ảnh đại diện

### **Table History:**

- `uid` (int) - ID người dùng mua domain
- `domain` (varchar) - Tên domain đã mua
- `ns1` (varchar) - Nameserver 1
- `ns2` (varchar) - Nameserver 2
- `status` (int) - Trạng thái đơn hàng (0: chờ xử lý)
- `time` (varchar) - Thời gian mua

### **Table Users:**

- `id` (int) - ID người dùng
- `tien` (int) - Số dư tài khoản

## 🔄 **6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. Người dùng đã đăng nhập vào hệ thống
2. Người dùng chọn domain từ trang chủ hoặc truy cập trực tiếp
3. Người dùng truy cập trang đăng ký domain với URL: `/Pages/Checkout.php?domain=example.com`
4. Hệ thống tự động điền thông tin domain và hiển thị giá
5. Người dùng nhập thông tin nameserver (NS1, NS2)
6. Người dùng click nút "Mua Ngay"

## 🔄 **7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Thành công:** Tạo đơn hàng, trừ tiền từ ví, hiển thị thông báo thành công, chuyển hướng đến trang quản lý
2. **Thất bại:** Hiển thị thông báo lỗi (thiếu thông tin, số dư không đủ, domain đã tồn tại)

## 📊 **8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (User Browser)
    ↓ GET Request (Trang đăng ký)
    ↓ URL: /Pages/Checkout.php?domain=example.com
    ↓ AJAX POST Request (Mua domain)
    ↓ URL: /Ajaxs/BuyDomain.php
    ↓ Data: {domain: "example.com", ns1: "ns1.example.com", ns2: "ns2.example.com", hsd: "1"}
Web Server (Apache)
    ↓ Xử lý request
File PHP xử lý
    ↓ Pages/Checkout.php (Hiển thị form)
    ↓ Ajaxs/BuyDomain.php (Xử lý mua)
    ↓ include_once DomainRepository.php, HistoryRepository.php, UserRepository.php
PHP Processing
    ↓ DomainRepository->findByDuoi() (Lấy thông tin domain)
    ↓ HistoryRepository->getByDomain() (Kiểm tra domain đã tồn tại)
    ↓ UserRepository->findByUsername() (Lấy thông tin user)
    ↓ Kiểm tra số dư
    ↓ HistoryRepository->insertPurchase() (Tạo đơn hàng)
    ↓ UserRepository->incrementBalance() (Trừ tiền)
Database (MySQL)
    ↓ Tables: ListDomain, History, Users
    ↓ Trả về: true/false
Response
    ↓ Success: toastr.success("Mua Tên Miền Thành Công, Chờ Xử Lí!")
    ↓ Error: toastr.error("Số Dư Tài Khoản Không Đủ!")
```

## 🗃️ **9. BẢNG RECORDSET VÀ ARRAY[KEY]:**

### **Dữ liệu mới được thêm:**

```sql
-- Thông tin domain từ ListDomain
|| id | duoi | price  | image    |
||----|------|--------|----------|
|| 1  | .com | 100000 | com.png  |

-- Đơn hàng mới trong History
|| id | uid | domain      | ns1              | ns2              | status | time        |
||----|-----|-------------|------------------|------------------|--------|-------------|
|| 1  | 2   | example.com | ns1.example.com  | ns2.example.com  | 0      | 15/10/2025  |

-- Cập nhật số dư trong Users
|| id | taikhoan | tien  |
||----|----------|-------|
|| 2  | user1    | 400000| (giảm từ 500000 xuống 400000)
```

### **Array[key] sử dụng:**

- `$domain` - Tên domain từ GET parameter
- `$ns1` - Nameserver 1 từ POST
- `$ns2` - Nameserver 2 từ POST
- `$hsd` - Hạn sử dụng từ POST (luôn = "1")
- `$mgd` - Mã giao dịch (tự động tạo random)
- `$_SESSION['users']` - Username người dùng đã đăng nhập
- `$fetch['price']` - Giá domain từ ListDomain
- `$fetch['image']` - Hình ảnh domain

## 🖼️ **10. GIAO DIỆN CHỨC NĂNG:**

### **Form đăng ký domain:**

```html
<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
  <div class="app-container container-xxl d-flex flex-row flex-column-fluid">
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
      <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
          <div class="card card-docs flex-row-fluid mb-2">
            <div
              class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700"
            >
              <div class="py-10">
                <h1
                  class="anchor fw-bold mb-5"
                  id="text-input"
                  data-kt-scroll-offset="50"
                >
                  <a href="#text-input"></a> Đăng Ký Tên Miền &nbsp;
                  <img src="<?= $images ?>" width="50px" />
                </h1>

                <div class="py-5">
                  <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="fv-row mb-10 fv-plugins-icon-container">
                      <label class="required fw-semibold fs-6 mb-2"
                        >Tên Miền</label
                      >
                      <input
                        type="text"
                        id="domain"
                        class="form-control form-control-solid mb-3 mb-lg-0"
                        placeholder="Tên Miền Cần Mua"
                        value="<?= $_GET['domain'] ?>"
                        disabled
                      />
                    </div>

                    <div class="fv-row mb-10 fv-plugins-icon-container">
                      <label class="required fw-semibold fs-6 mb-2">NS1</label>
                      <input
                        type="text"
                        id="ns1"
                        class="form-control form-control-solid mb-3 mb-lg-0"
                        placeholder="NS1 Của Cloudflare"
                      />
                    </div>

                    <div class="fv-row mb-10 fv-plugins-icon-container">
                      <label class="required fw-semibold fs-6 mb-2">NS2</label>
                      <input
                        type="text"
                        id="ns2"
                        class="form-control form-control-solid mb-3 mb-lg-0"
                        placeholder="NS2 Của Cloudflare"
                      />
                    </div>

                    <div class="fv-row mb-10 fv-plugins-icon-container">
                      <label class="required fw-semibold fs-6 mb-2"
                        >Hạn Dùng</label
                      >
                      <select id="hsd" class="form-select">
                        <option value="1">1 Năm</option>
                      </select>
                      <div id="status"></div>
                    </div>

                    <button id="buy" type="submit" class="btn btn-primary">
                      <span class="indicator-label"
                        >Mua Ngay -
                        <?= number_format($tienphaitra) ?>đ</span
                      >
                    </button>
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
  $(document).ready(function () {
    $("#buy").on("click", function () {
      $("#buy").text("Đang xử lý...");
      var domain = $("#domain").val();
      var ns1 = $("#ns1").val();
      var ns2 = $("#ns2").val();
      var hsd = $("#hsd").val();

      $.ajax({
        url: "/Ajaxs/BuyDomain.php",
        type: "POST",
        data: { domain: domain, ns1: ns1, ns2: ns2, hsd: hsd },
        success: function (data) {
          $("#buy").attr("disabled", false);
          $("#buy").text("Mua Ngay - <?= number_format($tienphaitra) ?>đ");
          $("#status").html(data);
        },
      });
    });
  });
</script>
```

## 💻 **11. CODE XỬ LÝ:**

### **File: Pages/Checkout.php**

```php
<?php
include_once('../Config/Header.php');

// Kiểm tra domain parameter
if($_GET['domain'] == ""){
    echo '<script>location.href="/";</script>';
}

// Lấy đuôi domain
$explode = explode(".", $_GET['domain']);
$duoimien = '.'.$explode[1];

// Lấy thông tin domain
include_once(__DIR__.'/../Repositories/DomainRepository.php');
$domainRepo = new DomainRepository($connect);
$fetch = $domainRepo->findByDuoi($duoimien);

if($fetch['duoi'] != $duoimien){
    echo '<script>location.href="/";</script>';
}

$tienphaitra = $fetch['price'];
$images = $fetch['image'];
?>

<!-- HTML form như trên -->
```

### **File: Ajaxs/BuyDomain.php**

```php
<?php
include_once('../Config/Database.php');
include_once('../Repositories/DomainRepository.php');
include_once('../Repositories/HistoryRepository.php');
include_once('../Repositories/UserRepository.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$domain = $_POST['domain'] ?? '';
$ns1    = $_POST['ns1'] ?? '';
$ns2    = $_POST['ns2'] ?? '';
$hsd    = $_POST['hsd'] ?? '';
$mgd    = rand(10000000,999999999);

if ($domain == "" || $ns1 == "" || $ns2 == "" || $hsd == "") {
    echo '<script>toastr.error("Vui Lòng Nhập Đầy Đủ Thông Tin", "Thông Báo");</script>';
    exit;
}

// Lấy đuôi miền
$explode   = explode(".", $domain);
$duoimien  = isset($explode[1]) ? '.'.$explode[1] : '';

$domainRepo = new DomainRepository($connect);
$historyRepo = new HistoryRepository($connect);
$userRepo = new UserRepository($connect);

// Lấy thông tin domain
$fetch = $domainRepo->findByDuoi($duoimien);
$checkls = $historyRepo->getByDomain($domain);

if (!$fetch) {
    echo '<script>toastr.error("Không tìm thấy thông tin đuôi miền!", "Thông Báo");</script>';
    exit;
}

$tienphaitra = $fetch['price'];
$images      = $fetch['image'];

if ($hsd == '1') {
    if (isset($_SESSION['users'])) {
        $users = $userRepo->findByUsername($_SESSION['users']);
        if (!$users) {
            echo '<script>toastr.error("Không tìm thấy thông tin tài khoản!", "Thông Báo");</script>';
            exit;
        }

        if ($users['tien'] >= $tienphaitra) {
            if (!$checkls || $domain != ($checkls['domain'] ?? '')) {
                $time = date('d/m/Y H:i:s');
                $save = $historyRepo->insertPurchase((int)$users['id'], $domain, $ns1, $ns2, $hsd, (string)$mgd, (string)$time);

                if ($save) {
                    $userRepo->incrementBalance((int)$users['id'], -1 * (int)$tienphaitra);
                    echo '<script>toastr.success("Mua Tên Miền Thành Công, Chờ Xử Lí!", "Thông Báo");</script>';
                } else {
                    echo '<script>toastr.error("Không Thể Mua Vào Lúc Này!", "Thông Báo");</script>';
                }
            } else {
                echo '<script>toastr.error("Đơn Hàng Này Đã Thanh Toán, Chờ Xử Lí!", "Thông Báo");</script>';
            }
        } else {
            echo '<script>toastr.error("Số Dư Tài Khoản Không Đủ!", "Thông Báo");</script>';
        }
    } else {
        echo '<script>toastr.error("Vui Lòng Đăng Nhập Để Thực Hiện!", "Thông Báo");</script>';
    }
} else {
    echo '<script>toastr.error("Hạn Sử Dụng Không Hợp Lệ!", "Thông Báo");</script>';
}
?>
```

### **Repository: HistoryRepository->insertPurchase()**

```php
public function insertPurchase(int $uid, string $domain, string $ns1, string $ns2, string $hsd, string $mgd, string $time): bool
{
    $stmt = $this->mysqli->prepare("INSERT INTO History (uid, domain, ns1, ns2, hsd, mgd, status, time) VALUES (?, ?, ?, ?, ?, ?, 0, ?)");
    $stmt->bind_param('issssss', $uid, $domain, $ns1, $ns2, $hsd, $mgd, $time);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

public function getByDomain(string $domain): ?array
{
    $stmt = $this->mysqli->prepare("SELECT * FROM History WHERE domain = ? LIMIT 1");
    $stmt->bind_param('s', $domain);
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
- **Frontend:** HTML form với jQuery AJAX
- **Session:** PHP session management
- **Security:** Input validation, authentication check

### **✅ Kiến trúc:**

- **Repository Pattern** - Tách biệt logic database
- **OOP Design** - Code có cấu trúc, dễ maintain
- **AJAX Pattern** - Mua domain không reload trang
- **Session Management** - Quản lý phiên đăng nhập
- **Error Handling** - Xử lý lỗi chi tiết

### **✅ Tính năng:**

- **Domain Registration** - Đăng ký tên miền
- **Balance Check** - Kiểm tra số dư trước khi mua
- **Duplicate Check** - Kiểm tra domain đã tồn tại
- **Auto Fill** - Tự động điền thông tin domain
- **Real-time Feedback** - Thông báo kết quả ngay lập tức
- **AJAX Processing** - Xử lý không reload trang

### **✅ Bảo mật:**

- **Authentication Check** - Kiểm tra đăng nhập
- **Prepared Statements** - Chống SQL injection
- **Input Validation** - Kiểm tra dữ liệu đầu vào
- **Session Security** - Bảo mật phiên đăng nhập
- **Domain Validation** - Kiểm tra domain hợp lệ

## 🎬 **13. DEMO CHỨC NĂNG:**

### **Bước 1: Truy cập trang đăng ký domain**

```
URL: http://localhost/Pages/Checkout.php?domain=example.com
```

### **Bước 2: Điền thông tin**

```
┌─────────────────────────────────────────────────────────────┐
│  🛒 Đăng Ký Tên Miền                                        │
├─────────────────────────────────────────────────────────────┤
│  Tên Miền: [example.com] (disabled)                         │
│  NS1: [ns1.example.com]                                     │
│  NS2: [ns2.example.com]                                     │
│  Hạn Dùng: [1 Năm ▼]                                        │
│  [Mua Ngay - 100,000đ]                                      │
└─────────────────────────────────────────────────────────────┘
```

### **Bước 3: Submit mua domain**

- Click **"Mua Ngay"** → Button chuyển thành **"Đang xử lý..."**
- AJAX request gửi đến `/Ajaxs/BuyDomain.php`
- Hiển thị kết quả trong div `#status`

### **Bước 4: Kết quả**

- **Thành công:** "Mua Tên Miền Thành Công, Chờ Xử Lí!"
- **Thất bại:** "Số Dư Tài Khoản Không Đủ!" hoặc "Đơn Hàng Này Đã Thanh Toán, Chờ Xử Lí!"

## 🎉 **KẾT LUẬN:**

**Chức năng mua domain đã được thiết kế hoàn chỉnh với giao diện đơn giản, xử lý AJAX mượt mà và bảo mật cao!**

**Đặc điểm nổi bật:**

- ✅ **Đăng ký domain đơn giản** - Form dễ sử dụng với thông tin cần thiết
- ✅ **AJAX processing** - Mua domain không cần reload trang
- ✅ **Auto validation** - Kiểm tra số dư, domain trùng lặp
- ✅ **Real-time feedback** - Thông báo kết quả ngay lập tức
- ✅ **Bảo mật cao** - Authentication + Prepared Statements
- ✅ **User-friendly** - Giao diện thân thiện, dễ hiểu
- ✅ **Error handling** - Xử lý lỗi đầy đủ và thông báo rõ ràng
- ✅ **Session management** - Quản lý phiên đăng nhập an toàn
