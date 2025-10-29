# 📊 **TRÌNH BÀY THIẾT KẾ VÀ XÂY DỰNG CHỨC NĂNG DASHBOARD ADMIN**

## 📝 **1. MÔ TẢ CHỨC NĂNG:**

Dashboard admin là trang tổng quan hiển thị thống kê toàn bộ hệ thống bao gồm doanh thu, đơn hàng, thành viên và các chỉ số quan trọng khác.

## 👨‍💼 **2. TÁC NHÂN THỰC HIỆN:**

- **Quản trị viên** - Admin có quyền truy cập vào hệ thống quản lý

## 🔍 **3. DẠNG TRUY VẤN:**

- **SELECT COUNT()** - Đếm số lượng records
- **SELECT SUM()** - Tính tổng doanh thu
- **SELECT với WHERE** - Lọc dữ liệu theo điều kiện

## 🗄️ **4. TRUY VẤN VÀO TABLE:**

- **Table:** `Cards` (thống kê doanh thu)
- **Table:** `History` (thống kê đơn hàng)
- **Table:** `Users` (thống kê thành viên)

## 📊 **5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

### **Table Cards:**

- `amount` (int) - Mệnh giá thẻ (tính doanh thu)
- `status` (int) - Trạng thái thẻ (1: thành công)
- `time` (varchar) - Thời gian giao dịch

### **Table History:**

- `status` (int) - Trạng thái đơn hàng (0: chờ, 1: hoàn thành)
- `time` (varchar) - Thời gian đơn hàng

### **Table Users:**

- `id` (int) - Đếm tổng số thành viên
- `time` (varchar) - Thời gian đăng ký

## 🔄 **6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. Admin đăng nhập vào hệ thống (HTTP Basic Auth)
2. Admin truy cập trang dashboard
3. Hệ thống tự động load dữ liệu thống kê
4. Hiển thị các widget thống kê

## 🔄 **7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Thành công:** Hiển thị dashboard với đầy đủ thống kê
2. **Thất bại:** Hiển thị thông báo lỗi hoặc dữ liệu mặc định

## 📊 **8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (Admin Browser)
    ↓ GET Request
    ↓ URL: /Adminstators/index.php
    ↓ HTTP Basic Authentication
Web Server (Apache)
    ↓ Xử lý request
File PHP xử lý
    ↓ Adminstators/index.php
    ↓ include_once RepositoryFactory.php
    ↓ $cardRepo = RepositoryFactory::createCardRepository($connect)
PHP Processing
    ↓ CardRepository->getRevenueStats()
    ↓ HistoryRepository->getOrderStats()
    ↓ UserRepository->getUserStats()
Database (MySQL)
    ↓ Multiple SELECT queries
    ↓ Trả về: Array thống kê
Response
    ↓ Render dashboard với dữ liệu
    ↓ Hiển thị widgets thống kê
```

## 🗃️ **9. BẢNG RECORDSET VÀ ARRAY[KEY]:**

### **Dữ liệu thống kê doanh thu:**

```sql
-- Doanh thu hôm nay
SELECT SUM(amount) as today_revenue FROM Cards WHERE DATE(time) = CURDATE() AND status = 1

-- Doanh thu hôm qua
SELECT SUM(amount) as yesterday_revenue FROM Cards WHERE DATE(time) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND status = 1

-- Doanh thu tháng này
SELECT SUM(amount) as month_revenue FROM Cards WHERE MONTH(time) = MONTH(CURDATE()) AND YEAR(time) = YEAR(CURDATE()) AND status = 1

-- Tổng doanh thu
SELECT SUM(amount) as total_revenue FROM Cards WHERE status = 1
```

### **Dữ liệu thống kê đơn hàng:**

```sql
-- Đơn hàng chờ xử lý
SELECT COUNT(*) as pending_orders FROM History WHERE status = 0

-- Đơn hàng hoàn thành
SELECT COUNT(*) as completed_orders FROM History WHERE status = 1
```

### **Dữ liệu thống kê thành viên:**

```sql
-- Tổng số thành viên
SELECT COUNT(*) as total_users FROM Users

-- Thành viên mới hôm nay
SELECT COUNT(*) as new_users_today FROM Users WHERE DATE(time) = CURDATE()
```

### **Array[key] sử dụng:**

- `$todayRevenue` - Doanh thu hôm nay
- `$yesterdayRevenue` - Doanh thu hôm qua
- `$monthRevenue` - Doanh thu tháng này
- `$totalRevenue` - Tổng doanh thu
- `$pendingOrders` - Đơn hàng chờ xử lý
- `$completedOrders` - Đơn hàng hoàn thành
- `$totalUsers` - Tổng số thành viên
- `$newUsersToday` - Thành viên mới hôm nay

## 🖼️ **10. GIAO DIỆN CHỨC NĂNG:**

### **Dashboard Layout:**

```html
<div class="min-h-screen bg-gray-50">
  <!-- Header -->
  <div class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center py-6">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <div class="text-sm text-gray-500">Chào mừng, Admin</div>
      </div>
    </div>
  </div>

  <!-- Stats Grid -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <!-- Doanh thu hôm nay -->
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-white"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"
                  ></path>
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">
                  Doanh thu hôm nay
                </dt>
                <dd class="text-lg font-medium text-gray-900">
                  <?= number_format($todayRevenue) ?>đ
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <!-- Đơn hàng chờ xử lý -->
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-white"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">
                  Đơn hàng chờ xử lý
                </dt>
                <dd class="text-lg font-medium text-gray-900">
                  <?= $pendingOrders ?>
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <!-- Tổng thành viên -->
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-white"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"
                  ></path>
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">
                  Tổng thành viên
                </dt>
                <dd class="text-lg font-medium text-gray-900">
                  <?= $totalUsers ?>
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <!-- Tổng doanh thu -->
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-white"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    fill-rule="evenodd"
                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">
                  Tổng doanh thu
                </dt>
                <dd class="text-lg font-medium text-gray-900">
                  <?= number_format($totalRevenue) ?>đ
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
```

## 💻 **11. CODE XỬ LÝ:**

### **File: Adminstators/index.php**

```php
<?php
include_once('../Config/Database.php');
include_once('../Repositories/RepositoryFactory.php');

// Tạo repositories
$cardRepo = RepositoryFactory::createCardRepository($connect);
$historyRepo = RepositoryFactory::createHistoryRepository($connect);
$userRepo = RepositoryFactory::createUserRepository($connect);

// Lấy thống kê doanh thu
$todayRevenue = $cardRepo->getTodayRevenue();
$yesterdayRevenue = $cardRepo->getYesterdayRevenue();
$monthRevenue = $cardRepo->getMonthRevenue();
$totalRevenue = $cardRepo->getTotalRevenue();

// Lấy thống kê đơn hàng
$pendingOrders = $historyRepo->getPendingOrdersCount();
$completedOrders = $historyRepo->getCompletedOrdersCount();

// Lấy thống kê thành viên
$totalUsers = $userRepo->getTotalUsersCount();
$newUsersToday = $userRepo->getNewUsersTodayCount();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>
    <!-- Dashboard content như trên -->
</body>
</html>
```

### **Repository: CardRepository->getTodayRevenue()**

```php
public function getTodayRevenue(): int
{
    $stmt = $this->mysqli->prepare("SELECT COALESCE(SUM(amount), 0) as revenue FROM Cards WHERE DATE(time) = CURDATE() AND status = 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return (int)$row['revenue'];
}

public function getYesterdayRevenue(): int
{
    $stmt = $this->mysqli->prepare("SELECT COALESCE(SUM(amount), 0) as revenue FROM Cards WHERE DATE(time) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND status = 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return (int)$row['revenue'];
}

public function getMonthRevenue(): int
{
    $stmt = $this->mysqli->prepare("SELECT COALESCE(SUM(amount), 0) as revenue FROM Cards WHERE MONTH(time) = MONTH(CURDATE()) AND YEAR(time) = YEAR(CURDATE()) AND status = 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return (int)$row['revenue'];
}

public function getTotalRevenue(): int
{
    $stmt = $this->mysqli->prepare("SELECT COALESCE(SUM(amount), 0) as revenue FROM Cards WHERE status = 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return (int)$row['revenue'];
}
```

## 🎯 **12. TÓM TẮT KỸ THUẬT:**

### **✅ Công nghệ sử dụng:**

- **Backend:** PHP OOP với Repository Pattern
- **Database:** MySQL với aggregate functions
- **Frontend:** Tailwind CSS với responsive design
- **Icons:** Lucide icons
- **Security:** HTTP Basic Authentication

### **✅ Kiến trúc:**

- **Repository Pattern** - Tách biệt logic database
- **Factory Pattern** - Tạo repositories
- **OOP Design** - Code có cấu trúc, dễ maintain
- **Responsive Design** - Giao diện đẹp trên mọi thiết bị

### **✅ Tính năng:**

- **Real-time Stats** - Thống kê thời gian thực
- **Multiple Metrics** - Nhiều chỉ số khác nhau
- **Visual Design** - Giao diện trực quan với icons
- **Performance** - Tối ưu query database

## 🎉 **KẾT LUẬN:**

**Dashboard admin đã được thiết kế hoàn chỉnh với thống kê đầy đủ, giao diện đẹp và hiệu suất cao!**
