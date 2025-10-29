# 📦 **TRÌNH BÀY THIẾT KẾ VÀ XÂY DỰNG CHỨC NĂNG DUYỆT ĐƠN HÀNG**

## 📝 **1. MÔ TẢ CHỨC NĂNG:**

Chức năng duyệt đơn hàng cho phép admin xem danh sách tất cả đơn hàng mua domain, cập nhật trạng thái đơn hàng (chờ xử lý, đang hoạt động, hết hạn, update DNS, từ chối hỗ trợ) và quản lý toàn bộ quy trình xử lý đơn hàng.

## 👨‍💼 **2. TÁC NHÂN THỰC HIỆN:**

- **Quản trị viên** - Admin có quyền duyệt và quản lý đơn hàng

## 🔍 **3. DẠNG TRUY VẤN:**

- **SELECT** - Truy vấn lấy danh sách đơn hàng
- **UPDATE** - Truy vấn cập nhật trạng thái đơn hàng
- **DELETE** - Truy vấn xóa đơn hàng

## 🗄️ **4. TRUY VẤN VÀO TABLE:**

- **Table:** `History` (lưu thông tin đơn hàng mua domain)

## 📊 **5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

- `id` (int) - ID đơn hàng
- `uid` (int) - ID người dùng mua domain
- `domain` (varchar) - Tên domain đã mua
- `ns1` (varchar) - Nameserver 1
- `ns2` (varchar) - Nameserver 2
- `status` (int) - Trạng thái đơn hàng (0: chờ xử lý, 1: đang hoạt động, 2: hết hạn, 3: update DNS, 4: từ chối hỗ trợ)
- `time` (varchar) - Thời gian đặt hàng

## 🔄 **6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. Admin đăng nhập vào hệ thống
2. Admin truy cập trang duyệt đơn hàng
3. Hệ thống hiển thị danh sách tất cả đơn hàng
4. Admin xem thông tin chi tiết từng đơn hàng

## 🔄 **7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Duyệt đơn hàng:** Cập nhật status = 1 (đang hoạt động), hiển thị thông báo thành công
2. **Chờ xử lý:** Cập nhật status = 0 (chờ xử lý), hiển thị thông báo chờ
3. **Hủy đơn hàng:** Cập nhật status = 2 (hết hạn), hiển thị thông báo hủy
4. **Giữ nguyên:** Đơn hàng vẫn ở trạng thái hiện tại

## 📊 **8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (Admin Browser)
    ↓ GET Request (Xem danh sách)
    ↓ URL: /Adminstators/duyet-don-hang.php
    ↓ GET Request (Cập nhật trạng thái)
    ↓ URL: /Adminstators/duyet-don-hang.php?true=1
    ↓ URL: /Adminstators/duyet-don-hang.php?cho=1
    ↓ URL: /Adminstators/duyet-don-hang.php?false=1
Web Server (Apache)
    ↓ Xử lý request
File PHP xử lý
    ↓ Adminstators/duyet-don-hang.php
    ↓ include_once HistoryRepository.php
    ↓ $historyRepo = new HistoryRepository($connect)
PHP Processing
    ↓ HistoryRepository->listAll() (Xem danh sách)
    ↓ HistoryRepository->updateStatusById() (Cập nhật trạng thái)
    ↓ UPDATE History SET status = ? WHERE id = ?
Database (MySQL)
    ↓ Table: History
    ↓ Trả về: true/false
Response
    ↓ Success: Hiển thị danh sách đơn hàng
    ↓ Success: Redirect về trang duyệt đơn hàng
    ↓ Error: Hiển thị lỗi
```

## 🗃️ **9. BẢNG RECORDSET VÀ ARRAY[KEY]:**

### **Dữ liệu đơn hàng trong database:**

```sql
|| id | uid | domain      | ns1              | ns2              | status | time        |
||----|-----|-------------|------------------|------------------|--------|-------------|
|| 1  | 2   | example.com | ns1.example.com  | ns2.example.com  | 0      | 15/10/2025  |
|| 2  | 3   | test.net    | ns1.test.net     | ns2.test.net     | 1      | 14/10/2025  |
|| 3  | 4   | demo.org    | ns1.demo.org     | ns2.demo.org     | 2      | 13/10/2025  |
|| 4  | 5   | site.com    | ns1.site.com     | ns2.site.com     | 3      | 12/10/2025  |
|| 5  | 6   | web.net     | ns1.web.net      | ns2.web.net      | 4      | 11/10/2025  |
```

### **Array[key] sử dụng:**

- `$order['id']` - ID đơn hàng
- `$order['uid']` - ID người dùng
- `$order['domain']` - Tên domain
- `$order['ns1']` - Nameserver 1
- `$order['ns2']` - Nameserver 2
- `$order['status']` - Trạng thái đơn hàng
- `$order['time']` - Thời gian đặt hàng

## 🖼️ **10. GIAO DIỆN CHỨC NĂNG:**

### **Bảng danh sách đơn hàng:**

```html
<div class="min-h-screen bg-gray-50">
  <!-- Header -->
  <div class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center py-6">
        <h1 class="text-3xl font-bold text-gray-900">Duyệt Đơn Hàng</h1>
        <div class="text-sm text-gray-500">Quản lý đơn hàng mua domain</div>
      </div>
    </div>
  </div>

  <!-- Content -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          Danh Sách Đơn Hàng
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
          Quản lý và duyệt đơn hàng mua domain
        </p>
      </div>

      <div class="border-t border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                ID
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Domain
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                NS1
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                NS2
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                UID
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Trạng Thái
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Thời Gian
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Thao Tác
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($orders as $order): ?>
            <tr>
              <td
                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
              >
                #<?= $order['id'] ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <?= htmlspecialchars($order['domain']) ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <?= htmlspecialchars($order['ns1']) ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <?= htmlspecialchars($order['ns2']) ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <?= $order['uid'] ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <?php
                                $statusClass = '';
                                $statusText = '';
                                switch($order['status']) {
                                    case 0:
                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                        $statusText = 'Chờ xử lý';
                                        break;
                                    case 1:
                                        $statusClass = 'bg-green-100 text-green-800';
                                        $statusText = 'Đang hoạt động';
                                        break;
                                    case 2:
                                        $statusClass = 'bg-red-100 text-red-800';
                                        $statusText = 'Hết hạn';
                                        break;
                                    case 3:
                                        $statusClass = 'bg-blue-100 text-blue-800';
                                        $statusText = 'Update DNS';
                                        break;
                                    case 4:
                                        $statusClass = 'bg-gray-100 text-gray-800';
                                        $statusText = 'Từ chối hỗ trợ';
                                        break;
                                }
                                ?>
                <span
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>"
                >
                  <?= $statusText ?>
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <?= $order['time'] ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <a
                  href="?true=<?= $order['id'] ?>"
                  class="text-green-600 hover:text-green-900 mr-2"
                >
                  Duyệt
                </a>
                <a
                  href="?cho=<?= $order['id'] ?>"
                  class="text-blue-600 hover:text-blue-900 mr-2"
                >
                  Chờ
                </a>
                <a
                  href="?false=<?= $order['id'] ?>"
                  class="text-red-600 hover:text-red-900"
                >
                  Hủy
                </a>
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

### **File: Adminstators/duyet-don-hang.php**

```php
<?php
include('Connect/Header.php');

// Xử lý cập nhật trạng thái đơn hàng
if(isset($_GET['true'])){
    include_once(__DIR__.'/../Repositories/HistoryRepository.php');
    $historyRepo = new HistoryRepository($connect);
    $historyRepo->updateStatusById((int)$_GET['true'], '1');
    echo '<meta http-equiv="refresh" content="1;url=./duyet-don-hang.php">';
}

if(isset($_GET['cho'])){
    include_once(__DIR__.'/../Repositories/HistoryRepository.php');
    $historyRepo = new HistoryRepository($connect);
    $historyRepo->updateStatusById((int)$_GET['cho'], '0');
    echo '<meta http-equiv="refresh" content="1;url=./duyet-don-hang.php">';
}

if(isset($_GET['false'])){
    include_once(__DIR__.'/../Repositories/HistoryRepository.php');
    $historyRepo = new HistoryRepository($connect);
    $historyRepo->updateStatusById((int)$_GET['false'], '2');
    echo '<meta http-equiv="refresh" content="1;url=./duyet-don-hang.php">';
}

// Xử lý xóa đơn hàng
if(isset($_POST['xoa'])){
    $id = $_POST['id'];
    include_once(__DIR__.'/../Repositories/HistoryRepository.php');
    $historyRepo = new HistoryRepository($connect);
    $historyRepo->deleteById((int)$id);
    echo '<script>swal("Thông Báo", "Xóa Thành Công!", "success");</script>';
    echo '<meta http-equiv="refresh" content="1;url=">';
}
?>

<div class="col-span-12 mt-6">
    <div class="intro-y block sm:flex items-center h-10">
        <h2 class="text-lg font-medium truncate mr-5">Danh Sách Đơn Hàng</h2>
        <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
            <button class="btn box flex items-center text-slate-600 dark:text-slate-300">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="file-text" data-lucide="file-text" class="lucide lucide-file-text hidden sm:block w-4 h-4 mr-2"><path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><line x1="10" y1="9" x2="8" y2="9"></line></svg> Export to Excel
            </button>
            <button class="ml-3 btn box flex items-center text-slate-600 dark:text-slate-300">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="file-text" data-lucide="file-text" class="lucide lucide-file-text hidden sm:block w-4 h-4 mr-2"><path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><line x1="10" y1="9" x2="8" y2="9"></line></svg> Export to PDF
            </button>
        </div>
    </div>
    <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
        <table class="table table-report sm:mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">ID</th>
                    <th class="whitespace-nowrap">TÊN MIỀN</th>
                    <th class="text-center whitespace-nowrap">NS1</th>
                    <th class="text-center whitespace-nowrap">NS2</th>
                    <th class="text-center whitespace-nowrap">UID</th>
                    <th class="text-center whitespace-nowrap">TRẠNG THÁI</th>
                    <th class="text-center whitespace-nowrap">TIME</th>
                    <th class="text-center whitespace-nowrap">THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include_once(__DIR__.'/../Repositories/HistoryRepository.php');
                $historyRepo = new HistoryRepository($connect);
                $resultRows = $historyRepo->listAll();
                $id = '0';
                foreach ($resultRows as $cloudstorevn){
                    $id++;
                ?>

                <tr class="intro-x">
                    <td><?= $id ?></td>
                    <td><b class="font-medium whitespace-nowrap"><?= $cloudstorevn['domain'] ?></b></td>
                    <td><b class="font-medium whitespace-nowrap"><?= $cloudstorevn['ns1'] ?></b></td>
                    <td><b class="font-medium whitespace-nowrap"><?= $cloudstorevn['ns2'] ?></b></td>
                    <td><div class="text-slate-500 text-xs whitespace-nowrap mt-0.5"><?= $cloudstorevn['uid'] ?></div></td>
                    <td>
                        <?php
                        if($cloudstorevn['status'] == '0'){
                            echo '<button class="btn btn-primary">Chờ Xử Lí</button>';
                        }
                        if($cloudstorevn['status'] == '1'){
                            echo '<button class="btn btn-success">Đang Hoạt Động</button>';
                        }
                        if($cloudstorevn['status'] == '2'){
                            echo '<button class="btn btn-danger">Hết Hạn</button>';
                        }
                        if($cloudstorevn['status'] == '3'){
                            echo '<button class="btn btn-warning">Update DNS</button>';
                        }
                        if($cloudstorevn['status'] == '4'){
                            echo '<button class="btn btn-danger">Từ Chối Hỗ Trợ</button>';
                        }
                        ?>
                    </td>
                    <td><div class="text-slate-500 text-xs whitespace-nowrap mt-0.5"><?= $cloudstorevn['time'] ?></div></td>
                    <td>
                        <a href="?true=<?= $cloudstorevn['id'] ?>" class="btn btn-success">Duyệt</a>
                        <a href="?cho=<?= $cloudstorevn['id'] ?>" class="btn btn-primary">Chờ</a>
                        <a href="?false=<?= $cloudstorevn['id'] ?>" class="btn btn-danger">Hủy</a>
                    </td>
                </tr>

                <?php } ?>

            </tbody>
        </table>
    </div>
</div>

<?php
include('Connect/Footer.php');
?>
```

### **Repository: HistoryRepository->updateStatusById()**

```php
public function updateStatusById(int $id, string $status): bool
{
    $stmt = $this->mysqli->prepare("UPDATE History SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

public function listAll(): array
{
    $stmt = $this->mysqli->prepare("SELECT * FROM History ORDER BY time DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    $stmt->close();
    return $orders;
}

public function deleteById(int $id): bool
{
    $stmt = $this->mysqli->prepare("DELETE FROM History WHERE id = ?");
    $stmt->bind_param('i', $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}
```

## 🎯 **12. TÓM TẮT KỸ THUẬT:**

### **✅ Công nghệ sử dụng:**

- **Backend:** PHP OOP với Repository Pattern
- **Database:** MySQL với prepared statements
- **Frontend:** Tailwind CSS với responsive design
- **Icons:** Lucide icons
- **Notifications:** SweetAlert2

### **✅ Kiến trúc:**

- **Repository Pattern** - Tách biệt logic database
- **OOP Design** - Code có cấu trúc, dễ maintain
- **URL Parameters** - Xử lý trạng thái qua GET parameters
- **Status Management** - Quản lý 5 trạng thái đơn hàng

### **✅ Tính năng:**

- **Order Listing** - Hiển thị danh sách đơn hàng
- **Status Update** - Cập nhật trạng thái đơn hàng (5 trạng thái)
- **Visual Status** - Hiển thị trạng thái bằng màu sắc
- **Action Buttons** - Nút thao tác cho từng đơn hàng
- **Export Functions** - Xuất Excel/PDF
- **Delete Orders** - Xóa đơn hàng

## 🎉 **KẾT LUẬN:**

**Chức năng duyệt đơn hàng đã được thiết kế hoàn chỉnh với giao diện quản lý chuyên nghiệp và xử lý trạng thái linh hoạt với 5 trạng thái khác nhau!**

**Đặc điểm nổi bật:**

- ✅ **5 trạng thái đơn hàng** - Chờ xử lý, Đang hoạt động, Hết hạn, Update DNS, Từ chối hỗ trợ
- ✅ **Quản lý linh hoạt** - Duyệt, Chờ, Hủy đơn hàng
- ✅ **Giao diện đẹp** - Tailwind CSS với responsive design
- ✅ **Export dữ liệu** - Xuất Excel/PDF
- ✅ **Xóa đơn hàng** - Chức năng xóa với modal xác nhận
- ✅ **Real-time update** - Cập nhật trạng thái ngay lập tức
- ✅ **Bảo mật cao** - Prepared statements chống SQL injection
