# 📋 **TRÌNH BÀY THIẾT KẾ VÀ XÂY DỰNG CÁC CHỨC NĂNG THÊM, SỬA, XÓA SẢN PHẨM**

---

## ➕ **CÂU 5: CHỨC NĂNG THÊM SẢN PHẨM**

### **📝 1. MÔ TẢ CHỨC NĂNG:**

Cho phép admin thêm mới các loại domain/sản phẩm vào hệ thống với thông tin đầy đủ bao gồm tên miền, giá bán và hình ảnh.

### **👤 2. TÁC NHÂN THỰC HIỆN:**

- **Admin/Quản trị viên** - Người có quyền thêm sản phẩm mới

### **🔍 3. DẠNG TRUY VẤN:**

- **INSERT** - Truy vấn thêm dữ liệu mới vào database

### **🗄️ 4. TRUY VẤN VÀO TABLE:**

- **Table:** `ListDomain`

### **📊 5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

- `duoi` (varchar) - Tên miền/đuôi miền
- `price` (varchar) - Giá bán sản phẩm
- `image` (varchar) - Đường dẫn hình ảnh sản phẩm

### **🔄 6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. Admin truy cập trang thêm sản phẩm
2. Hệ thống hiển thị form nhập thông tin
3. Admin nhập đầy đủ thông tin sản phẩm (tên miền, giá, chọn hình ảnh)
4. Hệ thống validate dữ liệu đầu vào

### **🔄 7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Thành công:** Hiển thị thông báo thành công, chuyển về danh sách sản phẩm
2. **Thất bại:** Hiển thị thông báo lỗi, yêu cầu nhập lại thông tin

### **📊 8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (Admin)
    ↓ POST Request
    ↓ URL: /Adminstators/them-san-pham.php
    ↓ Data: {duoi: ".com", price: "100000", image: "/images/logo.svg"}
Web Server (Apache)
    ↓ HTTP Basic Authentication
    ↓ Xác thực thành công
File PHP xử lý
    ↓ them-san-pham.php
    ↓ include_once DomainRepository.php
    ↓ $domainRepo = new DomainRepository($connect)
PHP Processing
    ↓ DomainRepository->create()
    ↓ INSERT INTO ListDomain (duoi, price, image) VALUES (?, ?, ?)
Database (MySQL)
    ↓ Table: ListDomain
    ↓ Trả về: true/false
Response
    ↓ Success: toastr.success("Đăng Thành Công!")
    ↓ Error: toastr.error("Không Thể Đăng Bán!")
```

### **🖼️ 9. GIAO DIỆN CHỨC NĂNG:**

**📋 FORM THÊM SẢN PHẨM:**

```
┌─────────────────────────────────────────────────────────────┐
│                    Thêm Sản Phẩm                           │
├─────────────────────────────────────────────────────────────┤
│ Đuôi Miền: [.com                    ]                      │
│                                                             │
│ Hình Ảnh: [Chọn hình ảnh ▼] [Preview]                      │
│                                                             │
│ Giá Tiền: [100000                ]                         │
│                                                             │
│ [☐] Remember me                                             │
│                                                             │
│                    [Đăng Ngay]                             │
└─────────────────────────────────────────────────────────────┘
```

### **💻 10. CODE XỬ LÝ:**

**File: Adminstators/them-san-pham.php**

```php
<?php
include('Connect/Header.php');

// Lấy danh sách hình ảnh từ folder images
$imagesPath = __DIR__ . '/../images/';
$availableImages = [];
if (is_dir($imagesPath)) {
    $files = scandir($imagesPath);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && preg_match('/\.(jpg|jpeg|png|gif|svg)$/i', $file)) {
            $availableImages[] = $file;
        }
    }
}

if(isset($_POST['dangngay'])){
    $duoi = $_POST['duoi'];
    $image = $_POST['image'];
    $price = $_POST['price'];

    if($price == "" || $image == "" || $duoi == ""){
        echo '<script>swal("Thông Báo", "Vui Lòng Nhập Đầy Đủ Thông Tin!", "error");</script>';
    } else {
        include_once(__DIR__.'/../Repositories/DomainRepository.php');
        $domainRepo = new DomainRepository($connect);
        $ok = $domainRepo->create((int)$price, $duoi, $image);
        if($ok){
            echo '<script>swal("Thông Báo", "Đăng Thành Công!", "success");</script>';
            echo '<meta http-equiv="refresh" content="1;url=">';
        } else {
            echo '<script>swal("Thông Báo", "Không Thể Đăng Bán!", "error");</script>';
        }
    }
}
?>
```

**Repository: DomainRepository->create()**

```php
public function create(int $price, string $duoi, string $image): bool
{
    $stmt = $this->mysqli->prepare("INSERT INTO ListDomain (`price`,`duoi`,`image`) VALUES (?,?,?)");
    $stmt->bind_param('iss', $price, $duoi, $image);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}
```

---

## ✏️ **CÂU 6: CHỨC NĂNG SỬA SẢN PHẨM**

### **📝 1. MÔ TẢ CHỨC NĂNG:**

Cho phép admin chỉnh sửa thông tin của các sản phẩm đã có trong hệ thống bao gồm tên miền, giá bán và hình ảnh.

### **👤 2. TÁC NHÂN THỰC HIỆN:**

- **Admin/Quản trị viên** - Người có quyền sửa sản phẩm

### **🔍 3. DẠNG TRUY VẤN:**

- **UPDATE** - Truy vấn cập nhật dữ liệu đã có

### **🗄️ 4. TRUY VẤN VÀO TABLE:**

- **Table:** `ListDomain`

### **📊 5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

- `id` (int) - ID sản phẩm cần sửa
- `duoi` (varchar) - Tên miền/đuôi miền
- `price` (varchar) - Giá bán sản phẩm
- `image` (varchar) - Đường dẫn hình ảnh sản phẩm

### **🔄 6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. Admin chọn sản phẩm cần sửa từ danh sách
2. Hệ thống hiển thị form với thông tin hiện tại đã được điền sẵn
3. Admin chỉnh sửa thông tin cần thiết
4. Hệ thống validate dữ liệu đầu vào

### **🔄 7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Thành công:** Hiển thị thông báo thành công, cập nhật danh sách sản phẩm
2. **Thất bại:** Hiển thị thông báo lỗi, giữ nguyên form với dữ liệu đã nhập

### **📊 8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (Admin)
    ↓ POST Request
    ↓ URL: /Adminstators/Edit.php?id=1
    ↓ Data: {id: "1", duoi: ".com", price: "120000", image: "/images/new_logo.svg"}
Web Server (Apache)
    ↓ HTTP Basic Authentication
    ↓ Xác thực thành công
File PHP xử lý
    ↓ Edit.php
    ↓ include_once DomainRepository.php
    ↓ $domainRepo = new DomainRepository($connect)
    ↓ $cloudstorevn12 = $domainRepo->findById($domainId)
PHP Processing
    ↓ DomainRepository->updateById()
    ↓ UPDATE ListDomain SET duoi=?, price=?, image=? WHERE id=?
Database (MySQL)
    ↓ Table: ListDomain
    ↓ Trả về: true/false
Response
    ↓ Success: toastr.success("Cập Nhật Thành Công!")
    ↓ Error: toastr.error("Thất Bại")
```

### **🖼️ 9. GIAO DIỆN CHỨC NĂNG:**

**📋 FORM SỬA SẢN PHẨM:**

```
┌─────────────────────────────────────────────────────────────┐
│                  Chỉnh Sửa Sản Phẩm                        │
├─────────────────────────────────────────────────────────────┤
│ Đuôi Miền: [.com                    ]                      │
│                                                             │
│ Hình Ảnh: [Chọn hình ảnh ▼] [Preview]                      │
│                                                             │
│ Giá Tiền: [120000                ]                         │
│                                                             │
│ [☐] Remember me                                             │
│                                                             │
│                    [Đăng Ngay]                             │
└─────────────────────────────────────────────────────────────┘
```

### **💻 10. CODE XỬ LÝ:**

**File: Adminstators/Edit.php**

```php
<?php
include('Connect/Header.php');
include_once(__DIR__.'/../Repositories/DomainRepository.php');
$domainRepo = new DomainRepository($connect);
$domainId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$cloudstorevn12 = $domainRepo->findById($domainId);

if(!$cloudstorevn12 || $domainId != (int)$cloudstorevn12['id']){
    echo '<script>location.href="./danh-sach-san-pham.php";</script>';
}

if(isset($_POST['dangngay'])){
    $tieude = $_POST['nameproduct'];
    $image = $_POST['image'];
    $price = $_POST['price'];

    if($tieude == "" || $price == "" || $image == ""){
        echo '<script>swal("Thông Báo", "Vui Lòng Nhập Đầy Đủ Thông Tin!", "error");</script>';
    } else {
        $ok = $domainRepo->updateById($domainId, $tieude, $image, (int)$price);
        if($ok){
            echo '<script>swal("Thông Báo", "Cập Nhật Thành Công!", "success");</script>';
            echo '<meta http-equiv="refresh" content="1;url=">';
        } else {
            echo '<script>swal("Thông Báo", "Thất Bại", "error");</script>';
        }
    }
}
?>
```

**Repository: DomainRepository->updateById()**

```php
public function updateById(int $id, string $duoi, string $image, int $price): bool
{
    $stmt = $this->mysqli->prepare("UPDATE ListDomain SET duoi=?, image=?, price=? WHERE id=?");
    $stmt->bind_param('ssii', $duoi, $image, $price, $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}
```

---

## 🗑️ **CÂU 7: CHỨC NĂNG XÓA SẢN PHẨM**

### **📝 1. MÔ TẢ CHỨC NĂNG:**

Cho phép admin xóa các sản phẩm không còn cần thiết khỏi hệ thống với xác nhận trước khi thực hiện.

### **👤 2. TÁC NHÂN THỰC HIỆN:**

- **Admin/Quản trị viên** - Người có quyền xóa sản phẩm

### **🔍 3. DẠNG TRUY VẤN:**

- **DELETE** - Truy vấn xóa dữ liệu khỏi database

### **🗄️ 4. TRUY VẤN VÀO TABLE:**

- **Table:** `ListDomain`

### **📊 5. CỘT THÔNG TIN TRONG TABLE CẦN DÙNG:**

- `id` (int) - ID sản phẩm cần xóa

### **🔄 6. LUỒNG SỰ KIỆN TRƯỚC KHI THỰC HIỆN:**

1. Admin chọn sản phẩm cần xóa từ danh sách
2. Hệ thống hiển thị modal xác nhận xóa với thông báo cảnh báo
3. Admin xác nhận việc xóa sản phẩm
4. Hệ thống kiểm tra quyền và dữ liệu

### **🔄 7. LUỒNG SỰ KIỆN SAU KHI THỰC HIỆN:**

1. **Thành công:** Hiển thị thông báo thành công, cập nhật danh sách sản phẩm
2. **Thất bại:** Hiển thị thông báo lỗi, giữ nguyên danh sách

### **📊 8. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client (Admin)
    ↓ POST Request
    ↓ URL: /Adminstators/danh-sach-san-pham.php
    ↓ Data: {id: "1"}
Web Server (Apache)
    ↓ HTTP Basic Authentication
    ↓ Xác thực thành công
File PHP xử lý
    ↓ danh-sach-san-pham.php
    ↓ include_once DomainRepository.php
    ↓ $domainRepo = new DomainRepository($connect)
PHP Processing
    ↓ DomainRepository->deleteById()
    ↓ DELETE FROM ListDomain WHERE id=?
Database (MySQL)
    ↓ Table: ListDomain
    ↓ Trả về: true/false
Response
    ↓ Success: toastr.success("Xóa Thành Công!")
    ↓ Error: toastr.error("Xóa Thất Bại!")
```

### **🖼️ 9. GIAO DIỆN CHỨC NĂNG:**

**📋 MODAL XÁC NHẬN XÓA:**

```
┌─────────────────────────────────────────────────────────────┐
│                                                             │
│                    ⚠️  X CẢNH BÁO  ⚠️                     │
│                                                             │
│              Bạn Có Chắc Muốn Xóa Nó?                      │
│                                                             │
│        Sai Khi Thực Hiện Xóa Sẽ Không Thể Khôi Phục Nó!    │
│                                                             │
│                                                             │
│                    [Đóng]  [Xóa]                           │
└─────────────────────────────────────────────────────────────┘
```

### **💻 10. CODE XỬ LÝ:**

**File: Adminstators/danh-sach-san-pham.php**

```php
<?php
// Modal xác nhận xóa
foreach ($resultRows as $cloudstorevn){
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

<?php
// Xử lý xóa
if(isset($_POST['xoa'])){
    $id = $_POST['id'];
    include_once(__DIR__.'/../Repositories/DomainRepository.php');
    $domainRepo = new DomainRepository($connect);
    $domainRepo->deleteById((int)$id);
    echo '<script>swal("Thông Báo", "Xóa Thành Công!", "success");</script>';
    echo '<meta http-equiv="refresh" content="1;url=">';
}
?>
```

**Repository: DomainRepository->deleteById()**

```php
public function deleteById(int $id): bool
{
    $stmt = $this->mysqli->prepare("DELETE FROM ListDomain WHERE id = ?");
    $stmt->bind_param('i', $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}
```

---

## 🎯 **TÓM TẮT CÁC CHỨC NĂNG:**

### **✅ Đặc điểm chung:**

- **Repository Pattern** - Tách biệt logic database
- **OOP Architecture** - Code có cấu trúc, dễ maintain
- **Security** - Prepared statements chống SQL injection
- **User Experience** - Toastr notifications, modal confirmations
- **HTTP Basic Authentication** - Bảo mật cấp server

### **🗄️ Database Operations:**

- **INSERT** - Thêm sản phẩm mới
- **UPDATE** - Cập nhật thông tin sản phẩm
- **DELETE** - Xóa sản phẩm khỏi hệ thống

### **🎨 UI/UX Features:**

- **Form validation** - Kiểm tra dữ liệu đầu vào
- **Image preview** - Xem trước hình ảnh
- **Modal confirmation** - Xác nhận trước khi xóa
- **Success/Error messages** - Thông báo kết quả
- **Auto refresh** - Tự động cập nhật trang

### **🔧 Technical Stack:**

- **Backend:** PHP OOP + Repository Pattern
- **Database:** MySQL với prepared statements
- **Frontend:** Tailwind CSS + Lucide Icons
- **JavaScript:** SweetAlert + Toastr notifications

**Tất cả các chức năng CRUD đã được thiết kế hoàn chỉnh với giao diện đẹp và logic xử lý chuyên nghiệp!** 🎉
