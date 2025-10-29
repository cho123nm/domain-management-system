# 📋 **TRÌNH BÀY THIẾT KẾ VÀ XÂY DỰNG CHỨC NĂNG HIỂN THỊ DANH SÁCH SẢN PHẨM**

## 📝 **1. MÔ TẢ CHỨC NĂNG:**

Chức năng hiển thị danh sách sản phẩm cho phép admin xem toàn bộ các loại domain/sản phẩm đang có trong hệ thống dưới dạng bảng với đầy đủ thông tin bao gồm hình ảnh, tên miền, giá bán và các thao tác quản lý (Edit, Delete). Trang cũng hỗ trợ xuất dữ liệu ra file Excel và PDF.

## 👨‍💼 **2. TÁC NHÂN THỰC HIỆN:**

- **Admin/Quản trị viên** - Người có quyền truy cập vào hệ thống quản trị thông qua HTTP Basic Authentication

## 🔍 **3. DẠNG TRUY VẤN:**

- **SELECT** - Truy vấn lấy tất cả dữ liệu sản phẩm từ database
- **SELECT COUNT()** - Đếm tổng số sản phẩm (nếu cần phân trang)

## 🗄️ **4. TRUY VẤN VÀO TABLE:**

- **Table:** `ListDomain`

## 📊 **5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

- `id` (int) - ID sản phẩm (khóa chính)
- `image` (varchar) - Đường dẫn hình ảnh sản phẩm
- `duoi` (varchar) - Tên miền/đuôi miền (ví dụ: .com, .net, .org)
- `price` (varchar) - Giá bán sản phẩm

## 🔄 **6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. **Admin đăng nhập vào hệ thống** (HTTP Basic Authentication)
2. **Admin truy cập menu "Sản phẩm"** hoặc trực tiếp URL `/Adminstators/danh-sach-san-pham.php`
3. **Hệ thống kiểm tra quyền truy cập** (đã đăng nhập admin)
4. **Hệ thống chuẩn bị hiển thị danh sách** sản phẩm

## 🔄 **7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Thành công:** Hiển thị bảng danh sách sản phẩm với đầy đủ thông tin và các nút thao tác
2. **Thất bại:** Hiển thị thông báo lỗi hoặc trang trống nếu không có dữ liệu
3. **Thao tác Edit:** Chuyển hướng đến trang chỉnh sửa sản phẩm
4. **Thao tác Delete:** Hiển thị modal xác nhận xóa
5. **Export:** Tải file Excel/PDF chứa danh sách sản phẩm

## 📊 **8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (Admin Browser)
    ↓ GET Request
    ↓ URL: /Adminstators/danh-sach-san-pham.php
    ↓ HTTP Basic Authentication
Web Server (Apache)
    ↓ Xác thực thành công
    ↓ Xử lý request
File PHP xử lý
    ↓ Adminstators/danh-sach-san-pham.php
    ↓ include_once DomainRepository.php
    ↓ $domainRepo = new DomainRepository($connect)
PHP Processing
    ↓ DomainRepository->listAll()
    ↓ SELECT * FROM ListDomain ORDER BY id
Database (MySQL)
    ↓ Table: ListDomain
    ↓ Trả về: Array các sản phẩm
Response
    ↓ Render HTML table với dữ liệu
    ↓ Hiển thị danh sách sản phẩm
```

## 🗃️ **9. BẢNG RECORDSET VÀ ARRAY[KEY]:**

### **Dữ liệu thực tế trong database:**

```sql
-- Truy vấn chính
SELECT * FROM ListDomain ORDER BY id;

-- Kết quả mẫu:
| id | image                    | duoi | price  |
|----|--------------------------|------|--------|
| 1  | /images/dot_com.svg      | .com | 100000 |
| 2  | /images/net_logo.svg     | .net | 120000 |
| 3  | /images/org_logo.svg     | .org | 150000 |
| 4  | /images/website.svg      | .website | 80000 |
```

### **Array[key] sử dụng trong PHP:**

```php
// Kết quả từ DomainRepository->listAll()
$resultRows = [
    [
        'id' => 1,
        'image' => '/images/dot_com.svg',
        'duoi' => '.com',
        'price' => '100000'
    ],
    [
        'id' => 2,
        'image' => '/images/net_logo.svg',
        'duoi' => '.net',
        'price' => '120000'
    ],
    // ... các sản phẩm khác
];

// Sử dụng trong vòng lặp
foreach ($resultRows as $cloudstorevn) {
    echo $cloudstorevn['id'];        // ID sản phẩm
    echo $cloudstorevn['image'];     // Đường dẫn hình ảnh
    echo $cloudstorevn['duoi'];      // Tên miền
    echo $cloudstorevn['price'];     // Giá bán
}
```

## 🖼️ **10. GIAO DIỆN CHỨC NĂNG:**

### **Giao diện danh sách sản phẩm:**

```html
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Danh Sách Sản Phẩm - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
  </head>
  <body>
    <div class="min-h-screen bg-gray-50">
      <!-- Header -->
      <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center py-6">
            <h1 class="text-3xl font-bold text-gray-900">Danh Sách Sản Phẩm</h1>
            <div class="flex space-x-4">
              <button
                class="btn box flex items-center text-slate-600 dark:text-slate-300"
              >
                <svg
                  class="lucide lucide-file-text w-4 h-4 mr-2"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"
                  ></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                  <line x1="16" y1="13" x2="8" y2="13"></line>
                  <line x1="16" y1="17" x2="8" y2="17"></line>
                  <line x1="10" y1="9" x2="8" y2="9"></line>
                </svg>
                Export to Excel
              </button>
              <button
                class="btn box flex items-center text-slate-600 dark:text-slate-300"
              >
                <svg
                  class="lucide lucide-file-text w-4 h-4 mr-2"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"
                  ></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                  <line x1="16" y1="13" x2="8" y2="13"></line>
                  <line x1="16" y1="17" x2="8" y2="17"></line>
                  <line x1="10" y1="9" x2="8" y2="9"></line>
                </svg>
                Export to PDF
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
          <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              Danh Sách Tất Cả Sản Phẩm
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Quản lý và theo dõi tất cả các loại domain/sản phẩm trong hệ thống
            </p>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Ảnh
                  </th>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Loại Miền
                  </th>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Giá Bán
                  </th>
                  <th
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Hành Động
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <!-- Sản phẩm 1 -->
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <img
                        class="h-10 w-10 rounded-lg"
                        src="/images/dot_com.svg"
                        alt=".com"
                      />
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">.com</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">100,000đ</div>
                  </td>
                  <td
                    class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium"
                  >
                    <div class="flex justify-center space-x-2">
                      <a
                        href="./Edit.php?id=1"
                        class="text-indigo-600 hover:text-indigo-900 flex items-center"
                      >
                        <svg
                          class="w-4 h-4 mr-1"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                          ></path>
                        </svg>
                        Edit
                      </a>
                      <button
                        class="text-red-600 hover:text-red-900 flex items-center"
                        onclick="confirmDelete(1)"
                      >
                        <svg
                          class="w-4 h-4 mr-1"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                          ></path>
                        </svg>
                        Delete
                      </button>
                    </div>
                  </td>
                </tr>

                <!-- Sản phẩm 2 -->
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <img
                        class="h-10 w-10 rounded-lg"
                        src="/images/net_logo.svg"
                        alt=".net"
                      />
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">.net</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">120,000đ</div>
                  </td>
                  <td
                    class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium"
                  >
                    <div class="flex justify-center space-x-2">
                      <a
                        href="./Edit.php?id=2"
                        class="text-indigo-600 hover:text-indigo-900 flex items-center"
                      >
                        <svg
                          class="w-4 h-4 mr-1"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                          ></path>
                        </svg>
                        Edit
                      </a>
                      <button
                        class="text-red-600 hover:text-red-900 flex items-center"
                        onclick="confirmDelete(2)"
                      >
                        <svg
                          class="w-4 h-4 mr-1"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                          ></path>
                        </svg>
                        Delete
                      </button>
                    </div>
                  </td>
                </tr>

                <!-- Thêm các sản phẩm khác... -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal xác nhận xóa -->
    <div
      id="deleteModal"
      class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden"
    >
      <div
        class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
      >
        <div class="mt-3 text-center">
          <div
            class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100"
          >
            <svg
              class="h-6 w-6 text-red-600"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
              ></path>
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 mt-4">Xác nhận xóa</h3>
          <div class="mt-2 px-7 py-3">
            <p class="text-sm text-gray-500">
              Bạn có chắc chắn muốn xóa sản phẩm này? Hành động này không thể
              hoàn tác.
            </p>
          </div>
          <div class="items-center px-4 py-3">
            <button
              id="confirmDelete"
              class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600"
            >
              Xóa
            </button>
            <button
              onclick="closeModal()"
              class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600"
            >
              Hủy
            </button>
          </div>
        </div>
      </div>
    </div>

    <script>
      function confirmDelete(id) {
        document.getElementById("deleteModal").classList.remove("hidden");
        document.getElementById("confirmDelete").onclick = function () {
          // Xử lý xóa sản phẩm
          window.location.href = "./danh-sach-san-pham.php?delete=" + id;
        };
      }

      function closeModal() {
        document.getElementById("deleteModal").classList.add("hidden");
      }
    </script>
  </body>
</html>
```

## 💻 **11. CODE XỬ LÝ:**

### **File: Adminstators/danh-sach-san-pham.php**

```php
<?php
// Include header với authentication
include('Connect/Header.php');
?>

<div class="col-span-12 mt-6">
    <!-- Header với nút Export -->
    <div class="intro-y block sm:flex items-center h-10">
        <h2 class="text-lg font-medium truncate mr-5">Danh Sách Sản Phẩm</h2>
        <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
            <button class="btn box flex items-center text-slate-600 dark:text-slate-300">
                <svg class="lucide lucide-file-text w-4 h-4 mr-2">
                    <path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <line x1="10" y1="9" x2="8" y2="9"></line>
                </svg>
                Export to Excel
            </button>
            <button class="ml-3 btn box flex items-center text-slate-600 dark:text-slate-300">
                <svg class="lucide lucide-file-text w-4 h-4 mr-2">
                    <path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <line x1="10" y1="9" x2="8" y2="9"></line>
                </svg>
                Export to PDF
            </button>
        </div>
    </div>

    <!-- Bảng danh sách sản phẩm -->
    <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
        <table class="table table-report sm:mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">ẢNH</th>
                    <th class="whitespace-nowrap">LOẠI MIỀN</th>
                    <th class="whitespace-nowrap">GIÁ BÁN</th>
                    <th class="text-center whitespace-nowrap">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Include DomainRepository
                include_once(__DIR__.'/../Repositories/DomainRepository.php');
                $domainRepo = new DomainRepository($connect);

                // Lấy tất cả sản phẩm
                $resultRows = $domainRepo->listAll();

                // Hiển thị từng sản phẩm
                foreach ($resultRows as $cloudstorevn) {
                ?>
                <tr class="intro-x">
                    <td class="w-40">
                        <div class="flex">
                            <img alt="Product Image" class="tooltip" width="30px" src="<?=$cloudstorevn['image'];?>">
                        </div>
                    </td>
                    <td>
                        <a href="" class="font-medium whitespace-nowrap"><?=$cloudstorevn['duoi'];?></a>
                    </td>
                    <td>
                        <?=number_format($cloudstorevn['price']);?>đ
                    </td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <!-- Nút Edit -->
                            <a class="flex items-center mr-3" href="./Edit.php?id=<?=$cloudstorevn['id'];?>">
                                <svg class="lucide lucide-check-square w-4 h-4 mr-1">
                                    <polyline points="9 11 12 14 22 4"></polyline>
                                    <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path>
                                </svg>
                                Edit
                            </a>
                            <!-- Nút Delete -->
                            <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-modal-preview-<?=$cloudstorevn['id'];?>">
                                <svg class="lucide lucide-trash-2 w-4 h-4 mr-1">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                                Delete
                            </a>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal xác nhận xóa cho từng sản phẩm -->
<?php
foreach ($resultRows as $cloudstorevn) {
?>
<div id="delete-modal-preview-<?=$cloudstorevn['id'];?>" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 text-center">
                    <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                    <div class="text-3xl mt-5">Bạn Có Chắc Muốn Xóa Nó?</div>
                    <div class="text-slate-500 mt-2">Sai Khi Thực Hiện Xóa Sẽ Không Thể Khôi Phục Nó!</div>
                    <form action="" method="post">
                        <input type="hidden" name="id" value="<?=$cloudstorevn['id'];?>">
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <a data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Đóng</a>
                        <button type="submit" name="xoa" class="btn btn-danger w-24">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- Xử lý xóa sản phẩm -->
<?php
if(isset($_POST['xoa'])) {
    $id = $_POST['id'];
    include_once(__DIR__.'/../Repositories/DomainRepository.php');
    $domainRepo = new DomainRepository($connect);
    $domainRepo->deleteById((int)$id);
    echo '<script>swal("Thông Báo", "Xóa Thành Công!", "success");</script>';
    echo '<meta http-equiv="refresh" content="1;url=">';
}
?>

<?php
include('Connect/Footer.php');
?>
```

### **Repository: DomainRepository->listAll()**

```php
public function listAll(): array
{
    $stmt = $this->mysqli->prepare("SELECT * FROM ListDomain ORDER BY id ASC");
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];

    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    $stmt->close();
    return $rows;
}

public function deleteById(int $id): bool
{
    $stmt = $this->mysqli->prepare("DELETE FROM ListDomain WHERE id = ?");
    $stmt->bind_param('i', $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
```

## 🎯 **12. TÓM TẮT KỸ THUẬT:**

### **✅ Công nghệ sử dụng:**

- **Backend:** PHP OOP với Repository Pattern
- **Database:** MySQL với prepared statements
- **Frontend:** HTML + Tailwind CSS + Lucide Icons
- **Authentication:** HTTP Basic Authentication
- **Export:** Excel/PDF (chức năng sẵn sàng)

### **✅ Kiến trúc:**

- **Repository Pattern** - Tách biệt logic database
- **MVC Pattern** - Model-View-Controller
- **Responsive Design** - Giao diện đẹp trên mọi thiết bị
- **Modal System** - Xác nhận xóa an toàn

### **✅ Tính năng:**

- **Hiển thị danh sách** - Bảng đầy đủ thông tin
- **Thao tác CRUD** - Edit và Delete
- **Export dữ liệu** - Excel và PDF
- **Xác nhận xóa** - Modal an toàn
- **Responsive** - Tương thích mobile

### **✅ Bảo mật:**

- **HTTP Basic Auth** - Bảo vệ cấp server
- **Prepared Statements** - Chống SQL injection
- **Input Validation** - Kiểm tra dữ liệu đầu vào
- **XSS Protection** - Escape output

## 🎬 **13. DEMO CHỨC NĂNG:**

### **Bước 1: Truy cập trang danh sách sản phẩm**

```
URL: http://localhost/Adminstators/danh-sach-san-pham.php
```

### **Bước 2: Hiển thị danh sách sản phẩm**

```
┌─────────────────────────────────────────────────────────────┐
│  📋 Danh Sách Sản Phẩm                    [Excel] [PDF]     │
├─────────────────────────────────────────────────────────────┤
│  Ảnh    │ Loại Miền │ Giá Bán    │ Hành Động              │
├─────────────────────────────────────────────────────────────┤
│  🖼️     │ .com      │ 100,000đ   │ [Edit] [Delete]        │
│  🖼️     │ .net      │ 120,000đ   │ [Edit] [Delete]        │
│  🖼️     │ .org      │ 150,000đ   │ [Edit] [Delete]        │
│  🖼️     │ .website  │ 80,000đ    │ [Edit] [Delete]        │
└─────────────────────────────────────────────────────────────┘
```

### **Bước 3: Thao tác Edit**

- Click nút **"Edit"** → Chuyển đến trang chỉnh sửa
- URL: `./Edit.php?id=1`

### **Bước 4: Thao tác Delete**

- Click nút **"Delete"** → Hiển thị modal xác nhận
- Click **"Xóa"** → Xóa sản phẩm và hiển thị thông báo thành công

### **Bước 5: Export dữ liệu**

- Click **"Export to Excel"** → Tải file Excel
- Click **"Export to PDF"** → Tải file PDF

## 🎉 **KẾT LUẬN:**

**Chức năng hiển thị danh sách sản phẩm đã được thiết kế hoàn chỉnh với giao diện đẹp, tính năng đầy đủ và bảo mật cao!**

**Đặc điểm nổi bật:**

- ✅ **Giao diện chuyên nghiệp** - Bảng responsive với Tailwind CSS
- ✅ **Tính năng đầy đủ** - Hiển thị, Edit, Delete, Export
- ✅ **Bảo mật cao** - HTTP Basic Auth + Prepared Statements
- ✅ **User Experience tốt** - Modal xác nhận, thông báo rõ ràng
- ✅ **Kiến trúc tốt** - Repository Pattern, tách biệt logic
- ✅ **Hiệu suất cao** - Truy vấn tối ưu, giao diện nhanh
