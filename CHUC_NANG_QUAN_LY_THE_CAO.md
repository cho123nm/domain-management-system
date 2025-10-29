# 💳 **TRÌNH BÀY THIẾT KẾ VÀ XÂY DỰNG CHỨC NĂNG QUẢN LÝ THẺ CÀO**

## 📝 **1. MÔ TẢ CHỨC NĂNG:**

Chức năng quản lý thẻ cào cho phép admin xem danh sách tất cả giao dịch thẻ cào, theo dõi trạng thái xử lý thẻ, thống kê thẻ thành công/thất bại và quản lý toàn bộ quy trình nạp tiền qua thẻ cào.

## 👨‍💼 **2. TÁC NHÂN THỰC HIỆN:**

- **Quản trị viên** - Admin có quyền quản lý thẻ cào

## 🔍 **3. DẠNG TRUY VẤN:**

- **SELECT** - Truy vấn lấy danh sách thẻ cào
- **UPDATE** - Truy vấn cập nhật trạng thái thẻ

## 🗄️ **4. TRUY VẤN VÀO TABLE:**

- **Table:** `Cards` (lưu thông tin thẻ cào)

## 📊 **5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

- `id` (int) - ID giao dịch thẻ cào
- `uid` (int) - ID người dùng nạp tiền
- `pin` (varchar) - Mã thẻ cào
- `serial` (varchar) - Serial thẻ cào
- `type` (varchar) - Loại thẻ (Viettel, Mobifone, Vinaphone)
- `amount` (int) - Mệnh giá thẻ
- `status` (int) - Trạng thái thẻ (0: chờ xử lý, 1: thành công, 2: thất bại)
- `time` (varchar) - Thời gian nạp

## 🔄 **6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. Admin đăng nhập vào hệ thống
2. Admin truy cập trang quản lý thẻ cào
3. Hệ thống hiển thị danh sách tất cả giao dịch thẻ cào
4. Admin xem thông tin chi tiết từng giao dịch

## 🔄 **7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Xem danh sách:** Hiển thị tất cả giao dịch thẻ cào với trạng thái
2. **Thống kê:** Hiển thị số lượng thẻ thành công/thất bại
3. **Theo dõi:** Theo dõi quá trình xử lý thẻ cào

## 📊 **8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (Admin Browser)
    ↓ GET Request
    ↓ URL: /Adminstators/Gach-Cards.php
Web Server (Apache)
    ↓ Xử lý request
File PHP xử lý
    ↓ Adminstators/Gach-Cards.php
    ↓ include_once CardRepository.php
    ↓ $cardRepo = new CardRepository($connect)
PHP Processing
    ↓ CardRepository->listAll()
    ↓ SELECT * FROM Cards ORDER BY time DESC
Database (MySQL)
    ↓ Table: Cards
    ↓ Trả về: Array thẻ cào
Response
    ↓ Success: Hiển thị danh sách thẻ cào
    ↓ Success: Hiển thị thống kê
```

## 🗃️ **9. BẢNG RECORDSET VÀ ARRAY[KEY]:**

### **Dữ liệu thẻ cào trong database:**

```sql
| id | uid | pin        | serial     | type    | amount | status | time        |
|----|-----|------------|------------|---------|--------|--------|-------------|
| 1  | 2   | 123456789  | 987654321  | Viettel | 100000 | 1      | 15/10/2025  |
| 2  | 3   | 987654321  | 123456789  | Mobifone| 100000 | 0      | 16/10/2025  |
| 3  | 4   | 555666777  | 888999000  | Vinaphone| 100000| 2      | 17/10/2025  |
```

### **Array[key] sử dụng:**

- `$card['id']` - ID giao dịch
- `$card['uid']` - ID người dùng
- `$card['pin']` - Mã thẻ cào
- `$card['serial']` - Serial thẻ cào
- `$card['type']` - Loại thẻ
- `$card['amount']` - Mệnh giá thẻ
- `$card['status']` - Trạng thái thẻ
- `$card['time']` - Thời gian nạp

## 🖼️ **10. GIAO DIỆN CHỨC NĂNG:**

### **Bảng danh sách thẻ cào:**

```html
<div class="min-h-screen bg-gray-50">
  <!-- Header -->
  <div class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center py-6">
        <h1 class="text-3xl font-bold text-gray-900">Quản Lý Thẻ Cào</h1>
        <div class="text-sm text-gray-500">
          Theo dõi giao dịch thẻ cào
        </div>
      </div>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <!-- Thẻ thành công -->
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Thẻ thành công</dt>
                <dd class="text-lg font-medium text-gray-900"><?= $successCount ?></dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <!-- Thẻ thất bại -->
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Thẻ thất bại</dt>
                <dd class="text-lg font-medium text-gray-900"><?= $failedCount ?></dd>
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
              <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Tổng doanh thu</dt>
                <dd class="text-lg font-medium text-gray-900"><?= number_format($totalRevenue) ?>đ</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Content -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          Danh Sách Thẻ Cào
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
          Theo dõi tất cả giao dịch thẻ cào
        </p>
      </div>

      <div class="border-t border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                UID
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Mã Thẻ
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Serial
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Mệnh Giá
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Loại Thẻ
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Trạng Thái
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Thời Gian
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($cards as $card): ?>
            <tr>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                <?= $card['uid'] ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <?= htmlspecialchars($card['pin']) ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <?= htmlspecialchars($card['serial']) ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <?= number_format($card['amount']) ?>đ
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <?= htmlspecialchars($card['type']) ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <?php
                $statusClass = '';
                $statusText = '';
                switch($card['status']) {
                    case 0:
                        $statusClass = 'bg-yellow-100 text-yellow-800';
                        $statusText = 'Chờ xử lý';
                        break;
                    case 1:
                        $statusClass = 'bg-green-100 text-green-800';
                        $statusText = 'Thành công';
                        break;
                    case 2:
                        $statusClass = 'bg-red-100 text-red-800';
                        $statusText = 'Thất bại';
                        break;
                }
                ?>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>">
                  <?= $statusText ?>
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <?= $card['time'] ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
```

## 💻 **11. CODE XỬ LÝ:**

### **File: Adminstators/Gach-Cards.php**

```php
<?php
include_once('../Config/Database.php');
include_once('../Repositories/CardRepository.php');

$cardRepo = new CardRepository($connect);

// Lấy danh sách thẻ cào
$cards = $cardRepo->listAll();

// Tính thống kê
$successCount = 0;
$failedCount = 0;
$totalRevenue = 0;

foreach ($cards as $card) {
    if ($card['status'] == 1) {
        $successCount++;
        $totalRevenue += $card['amount'];
    } elseif ($card['status'] == 2) {
        $failedCount++;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Thẻ Cào - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>
    <!-- Dashboard content như trên -->
</body>
</html>
```

### **Repository: CardRepository->listAll()**

```php
public function listAll(): array
{
    $stmt = $this->mysqli->prepare("SELECT * FROM Cards ORDER BY time DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    $cards = [];
    while ($row = $result->fetch_assoc()) {
        $cards[] = $row;
    }
    $stmt->close();
    return $cards;
}
```

## 🎯 **12. TÓM TẮT KỸ THUẬT:**

### **✅ Công nghệ sử dụng:**

- **Backend:** PHP OOP với Repository Pattern
- **Database:** MySQL với prepared statements
- **Frontend:** Tailwind CSS với responsive design
- **Icons:** Lucide icons
- **Statistics:** Real-time thống kê

### **✅ Kiến trúc:**

- **Repository Pattern** - Tách biệt logic database
- **OOP Design** - Code có cấu trúc, dễ maintain
- **Data Processing** - Xử lý thống kê real-time
- **Status Management** - Quản lý trạng thái thẻ

### **✅ Tính năng:**

- **Card Listing** - Hiển thị danh sách thẻ cào
- **Statistics** - Thống kê thành công/thất bại
- **Revenue Tracking** - Theo dõi doanh thu
- **Status Display** - Hiển thị trạng thái bằng màu sắc

## 🎉 **KẾT LUẬN:**

**Chức năng quản lý thẻ cào đã được thiết kế hoàn chỉnh với thống kê chi tiết và giao diện theo dõi chuyên nghiệp!**
