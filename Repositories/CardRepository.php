<?php

/**
 * CardRepository - Quản lý tất cả thao tác với bảng Cards
 * 
 * Chức năng chính:
 * - Quản lý lịch sử nạp thẻ
 * - Xử lý giao dịch thẻ cào
 * - Thống kê doanh thu từ thẻ
 * - Cập nhật trạng thái thẻ
 * 
 * @author DAM THANH VU
 * @version 1.0
 */
class CardRepository {
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function existsByPinSerial(string $pin, string $serial): bool
    {
        $stmt = $this->mysqli->prepare("SELECT id FROM Cards WHERE pin = ? AND serial = ? LIMIT 1");
        $stmt->bind_param('ss', $pin, $serial);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ? true : false;
    }

    public function insertCard(int $userId, string $pin, string $serial, string $type, string $amount, string $requestId, string $time, string $time2): bool
    {
        $stmt = $this->mysqli->prepare("INSERT INTO Cards (`uid`,`pin`,`serial`,`type`,`amount`,`status`,`requestid`,`time`,`time2`) VALUES (?,?,?,?,?,'0',?,?,?)");
        $stmt->bind_param('issssssss', $userId, $pin, $serial, $type, $amount, $requestId, $time, $time2);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function listByUserId(int $userId): array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM Cards WHERE uid = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function sumAmountByStatusAndTime2(int $status, string $time2): int
    {
        $stmt = $this->mysqli->prepare("SELECT SUM(amount) as total FROM Cards WHERE status = ? AND time2 = ?");
        $stmt->bind_param('is', $status, $time2);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int)($row['total'] ?? 0);
    }

    public function sumAmountByStatusAndTime3(int $status, string $time3): int
    {
        $stmt = $this->mysqli->prepare("SELECT SUM(amount) as total FROM Cards WHERE status = ? AND time3 = ?");
        $stmt->bind_param('is', $status, $time3);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int)($row['total'] ?? 0);
    }

    public function sumAmountByStatus(int $status): int
    {
        $stmt = $this->mysqli->prepare("SELECT SUM(amount) as total FROM Cards WHERE status = ?");
        $stmt->bind_param('i', $status);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int)($row['total'] ?? 0);
    }

    public function listAll(): array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM Cards");
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function getUidByRequestId(string $requestId): ?int
    {
        $stmt = $this->mysqli->prepare("SELECT uid FROM Cards WHERE requestid = ? LIMIT 1");
        $stmt->bind_param('s', $requestId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row && isset($row['uid']) ? (int)$row['uid'] : null;
    }

    public function updateStatusByRequestId(string $requestId, string $status): bool
    {
        $stmt = $this->mysqli->prepare("UPDATE Cards SET status = ? WHERE requestid = ?");
        $stmt->bind_param('is', $status, $requestId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}

?>

