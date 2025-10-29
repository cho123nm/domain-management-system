<?php

/**
 * DomainRepository - Quản lý tất cả thao tác với bảng ListDomain
 * 
 * Chức năng chính:
 * - Quản lý danh sách tên miền có sẵn
 * - Cập nhật giá bán và hình ảnh
 * - Thêm/xóa loại tên miền
 * - Tìm kiếm thông tin tên miền
 * 
 * @author DAM THANH VU
 * @version 1.0
 */
class DomainRepository {
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function listAll(): array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM ListDomain");
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function create(int $price, string $duoi, string $image): bool
    {
        $stmt = $this->mysqli->prepare("INSERT INTO ListDomain (`price`,`duoi`,`image`) VALUES (?,?,?)");
        $stmt->bind_param('iss', $price, $duoi, $image);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function deleteById(int $id): bool
    {
        $stmt = $this->mysqli->prepare("DELETE FROM ListDomain WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // đa phần dùng cho admin để thao tác thêm , sưã xóa sp 
    public function findById(int $id): ?array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM ListDomain WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function updateById(int $id, string $duoi, string $image, int $price): bool
    {
        $stmt = $this->mysqli->prepare("UPDATE ListDomain SET duoi = ?, image = ?, price = ? WHERE id = ?");
        $stmt->bind_param('ssii', $duoi, $image, $price, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    //kiểm tra nhanh có dữ liệu  trong bảng hay ko (nằm trong connectiondtb)
    public function getOneSample(): ?array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM ListDomain LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    // dùng cho user khi nhập tên miền kèm thêm đuôi sẽ dựa vào đó mà lấy ra dữ liệu cũng như giá của domain
    public function findByDuoi(string $duoi): ?array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM ListDomain WHERE duoi = ? LIMIT 1");
        $stmt->bind_param('s', $duoi);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }
}

?>

