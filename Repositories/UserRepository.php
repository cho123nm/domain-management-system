<?php

/**
 * UserRepository - Repository quản lý tất cả thao tác với bảng Users
 * 
 * CHỨC NĂNG CHÍNH:
 * - Xác thực đăng nhập (verifyCredentials)
 * - Quản lý thông tin người dùng (CRUD operations)
 * - Cập nhật số dư tài khoản (updateBalance)
 * - Đăng ký tài khoản mới (createUser)
 * - Tìm kiếm user theo username/email
 * - Cập nhật profile user
 * - Đếm số lượng user
 * 
 * PATTERN: Repository Pattern - Tách biệt logic database khỏi business logic
 * SECURITY: Sử dụng prepared statements để chống SQL injection
 * 
 * @author DAM THANH VU
 * @version 1.0
 */
class UserRepository {
    private mysqli $mysqli;  // Kết nối database

    /**
     * Constructor - Khởi tạo UserRepository
     * 
     * @param mysqli $mysqli - Kết nối database
     */
    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    /**
     * Tìm kiếm người dùng theo tên đăng nhập
     * 
     * @param string $username Tên đăng nhập
     * @return array|null Thông tin người dùng hoặc null nếu không tìm thấy
     */
    public function findByUsername(string $username): ?array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM Users WHERE taikhoan = ? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    /**
     * Xác thực thông tin đăng nhập
     * 
     * @param string $username Tên đăng nhập
     * @param string $password Mật khẩu (sẽ được mã hóa MD5)
     * @return bool True nếu đăng nhập thành công, false nếu thất bại
     */
    public function verifyCredentials(string $username, string $password): bool
    {
        // Mã hóa mật khẩu bằng MD5 (có thể nâng cấp lên bcrypt sau)
        $passwordMd5 = md5($password);
        $stmt = $this->mysqli->prepare("SELECT id FROM Users WHERE taikhoan = ? AND matkhau = ? LIMIT 1");
        $stmt->bind_param('ss', $username, $passwordMd5);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ? true : false;
    }

    /**
     * Lấy danh sách tất cả người dùng
     * 
     * @return array Danh sách tất cả người dùng
     */
    public function listAll(): array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM Users");
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function countAll(): int
    {
        $stmt = $this->mysqli->prepare("SELECT COUNT(*) as c FROM Users");
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int)($row['c'] ?? 0);
    }

    public function updateBalance(int $userId, int $amount): bool
    {
        $stmt = $this->mysqli->prepare("UPDATE Users SET tien = ? WHERE id = ?");
        $stmt->bind_param('ii', $amount, $userId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /**
     * Tăng số dư tài khoản (cộng thêm)
     * 
     * @param int $userId ID người dùng
     * @param int $delta Số tiền cần cộng thêm
     * @return bool True nếu cập nhật thành công
     */
    public function incrementBalance(int $userId, int $delta): bool
    {
        $stmt = $this->mysqli->prepare("UPDATE Users SET tien = tien + ? WHERE id = ?");
        $stmt->bind_param('ii', $delta, $userId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function createUser(string $username, string $passwordMd5, string $email, string $time): bool
    {
        $stmt = $this->mysqli->prepare("INSERT INTO Users (`taikhoan`,`matkhau`,`email`,`time`) VALUES (?,?,?,?)");
        $stmt->bind_param('ssss', $username, $passwordMd5, $email, $time);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function findById(int $userId): ?array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM Users WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM Users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function updateProfile(int $userId, string $email, string $newUsername): bool
    {
        $stmt = $this->mysqli->prepare("UPDATE Users SET email = ?, taikhoan = ? WHERE id = ?");
        $stmt->bind_param('ssi', $email, $newUsername, $userId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}

?>

