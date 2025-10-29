<?php

/**
 * SettingsRepository - Quản lý tất cả thao tác với bảng CaiDatChung
 * 
 * Chức năng chính:
 * - Quản lý cài đặt website
 * - Cập nhật thông tin liên hệ
 * - Cấu hình API keys
 * - Quản lý banner và logo
 * 
 * @author DAM THANH VU
 * @version 1.0
 */
class SettingsRepository {
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function getOne(): ?array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM CaiDatChung LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function updateWebsiteSettings(string $title, string $theme, string $keywords, string $description, string $imagebanner, string $phone, string $banner, string $logo): bool
    {
        $stmt = $this->mysqli->prepare("UPDATE CaiDatChung SET tieude = ?, theme = ?, keywords = ?, mota = ?, imagebanner = ?, sodienthoai = ?, banner = ?, logo = ? WHERE id = '1'");
        $stmt->bind_param('ssssssss', $title, $theme, $keywords, $description, $imagebanner, $phone, $banner, $logo);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function updateCardGateway(string $apikey, string $callback, string $webgach): bool
    {
        $stmt = $this->mysqli->prepare("UPDATE CaiDatChung SET apikey = ?, callback = ?, webgach = ? WHERE id = '1'");
        $stmt->bind_param('sss', $apikey, $callback, $webgach);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}

?>


