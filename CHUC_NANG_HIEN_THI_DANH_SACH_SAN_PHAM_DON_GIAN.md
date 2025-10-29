# 📋 **CHỨC NĂNG HIỂN THỊ DANH SÁCH SẢN PHẨM**

## 📝 **1. MÔ TẢ CHỨC NĂNG:**

Hiển thị danh sách tất cả sản phẩm/domain trong hệ thống dưới dạng bảng với thông tin: hình ảnh, tên miền, giá bán.

## 👨‍💼 **2. TÁC NHÂN THỰC HIỆN:**

- **Admin** - Người có quyền truy cập admin panel

## 🔍 **3. DẠNG TRUY VẤN:**

- **SELECT** - Lấy tất cả dữ liệu sản phẩm

## 🗄️ **4. TRUY VẤN VÀO TABLE:**

- **Table:** `ListDomain`

## 📊 **5. CỘT THÔNG TIN CẦN DÙNG:**

- `id` - ID sản phẩm
- `image` - Đường dẫn hình ảnh
- `duoi` - Tên miền (.com, .net, .org)
- `price` - Giá bán

## 🔄 **6. LUỒNG SỰ KIỆN:**

1. Admin truy cập `/Adminstators/danh-sach-san-pham.php`
2. Hệ thống lấy dữ liệu từ database
3. Hiển thị bảng danh sách sản phẩm

## 📊 **7. SƠ ĐỒ LUỒNG XỬ LÝ:**

```
Client → GET /Adminstators/danh-sach-san-pham.php
↓
PHP File → include DomainRepository.php
↓
DomainRepository → SELECT * FROM ListDomain
↓
Database → Trả về Array sản phẩm
↓
Response → Hiển thị bảng HTML
```

## 🗃️ **8. BẢNG RECORDSET:**

```sql
SELECT * FROM ListDomain ORDER BY id;

| id | image              | duoi     | price  |
|----|--------------------|----------|--------|
| 1  | /images/dot_com.svg| .com     | 100000 |
| 2  | /images/net_logo.svg| .net    | 120000 |
| 3  | /images/org_logo.svg| .org    | 150000 |
```

## 💻 **10. CODE XỬ LÝ:**

### **File: Adminstators/danh-sach-san-pham.php**

```php
<?php
include('Connect/Header.php');
?>

<div class="col-span-12 mt-6">
    <h2 class="text-lg font-medium mb-4">Danh Sách Sản Phẩm</h2>

    <table class="table table-report">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Loại Miền</th>
                <th>Giá Bán</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include_once(__DIR__.'/../Repositories/DomainRepository.php');
            $domainRepo = new DomainRepository($connect);
            $resultRows = $domainRepo->listAll();

            foreach ($resultRows as $product) {
            ?>
            <tr>
                <td>
                    <img src="<?=$product['image'];?>" width="30" height="30" alt="<?=$product['duoi'];?>">
                </td>
                <td><?=$product['duoi'];?></td>
                <td><?=number_format($product['price']);?>đ</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

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
```

## 🎬 **11. DEMO:**

1. **Truy cập:** `http://localhost/Adminstators/danh-sach-san-pham.php`
2. **Kết quả:** Hiển thị bảng danh sách sản phẩm

```
┌─────────────────────────────────┐
│  📋 Danh Sách Sản Phẩm         │
├─────────────────────────────────┤
│  Ảnh  │ Loại Miền │ Giá Bán    │
├─────────────────────────────────┤
│  🖼️   │ .com      │ 100,000đ   │
│  🖼️   │ .net      │ 120,000đ   │
│  🖼️   │ .org      │ 150,000đ   │
└─────────────────────────────────┘
```
