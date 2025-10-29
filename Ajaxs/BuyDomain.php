<?php
include_once('../Config/Database.php');
include_once('../Repositories/DomainRepository.php');
include_once('../Repositories/HistoryRepository.php');
include_once('../Repositories/UserRepository.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$domain = $_POST['domain'] ?? '';
$ns1    = $_POST['ns1'] ?? '';
$ns2    = $_POST['ns2'] ?? '';
$hsd    = $_POST['hsd'] ?? '';
$mgd    = rand(10000000,999999999);

if ($domain == "" || $ns1 == "" || $ns2 == "" || $hsd == "") {
    echo '<script>toastr.error("Vui Lòng Nhập Đầy Đủ Thông Tin", "Thông Báo");</script>';
    exit;
}

// lấy đuôi miền
$explode   = explode(".", $domain);
$duoimien  = isset($explode[1]) ? '.'.$explode[1] : '';

$domainRepo = new DomainRepository($connect);
$historyRepo = new HistoryRepository($connect);
$userRepo = new UserRepository($connect);

// fetch domain info
$fetch = $domainRepo->findByDuoi($duoimien);

$checkls = $historyRepo->getByDomain($domain);

if (!$fetch) {
    echo '<script>toastr.error("Không tìm thấy thông tin đuôi miền!", "Thông Báo");</script>';
    exit;
}

$tienphaitra = $fetch['price'];
$images      = $fetch['image'];

if ($hsd == '1') {
    if (isset($_SESSION['users'])) {
        $users = $userRepo->findByUsername($_SESSION['users']);
        if (!$users) {
            echo '<script>toastr.error("Không tìm thấy thông tin tài khoản!", "Thông Báo");</script>';
            exit;
        }

        if ($users['tien'] >= $tienphaitra) {
            if (!$checkls || $domain != ($checkls['domain'] ?? '')) {
                $save = $historyRepo->insertPurchase((int)$users['id'], $domain, $ns1, $ns2, $hsd, (string)$mgd, (string)$time);

                if ($save) {
                    $userRepo->incrementBalance((int)$users['id'], -1 * (int)$tienphaitra);
                    echo '<script>toastr.success("Mua Tên Miền Thành Công, Chờ Xử Lí!", "Thông Báo");</script>';
                } else {
                    echo '<script>toastr.error("Không Thể Mua Vào Lúc Này!", "Thông Báo");</script>';
                }
            } else {
                echo '<script>toastr.error("Đơn Hàng Này Đã Thanh Toán, Chờ Xử Lí!", "Thông Báo");</script>';
            }
        } else {
            echo '<script>toastr.error("Số Dư Tài Khoản Không Đủ!", "Thông Báo");</script>';
        }
    } else {
        echo '<script>toastr.error("Vui Lòng Đăng Nhập Để Thực Hiện!", "Thông Báo");</script>';
    }
} else {
    echo '<script>toastr.error("Hạn Sử Dụng Không Hợp Lệ!", "Thông Báo");</script>';
}
?>
