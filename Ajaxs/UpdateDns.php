<?php



include_once('../Config/Database.php');
include_once('../Repositories/HistoryRepository.php');
include_once('../Repositories/UserRepository.php');

if(!isset($_SESSION['users'])){
    echo '<script>toastr.error("Vui lòng đăng nhập!", "Thông Báo");</script>';
    exit;
}

$ns1 = $_POST['ns1'];
$ns2 = $_POST['ns2'];
$mgd = $_POST['mgd'];
$today = date('d/m/Y');
$ex = explode("/", $today);
$chuky = $ex[0] + '15'.'/'.$ex[1].'/'.$ex[2];

$historyRepo = new HistoryRepository($connect);
$userRepo = new UserRepository($connect);
$currentUser = $userRepo->findByUsername($_SESSION['users']);

if(!$currentUser) {
    echo '<script>toastr.error("Không tìm thấy thông tin người dùng!", "Thông Báo");</script>';
    exit;
}

$checkmgd = $historyRepo->getByUserIdAndMgd((int)$currentUser['id'], $mgd);

if(!$checkmgd) {
    echo '<script>toastr.error("Bạn không có quyền quản lý tên miền này!", "Thông Báo");</script>';
    exit;
}
if($ns1 == "" || $ns2 == ""){
   echo '<script>toastr.error("Vui Lòng Nhập Đầy Đủ Thông Tin!", "Thông Báo");</script>';
} else {
   if(($checkmgd['timedns'] ?? '') == '0'){
      $historyRepo->updateDns($mgd, $ns1, $ns2, $chuky);
      echo '<script>toastr.success("Thay Đổi DNS Thành Công, Vui Lòng Chờ 12h - 24h Để DNS Mới Hoạt Động", "Thông Báo");</script>';
    } else {
        echo '<script>toastr.error("Bạn Không Thể Cập Nhật Thông Tin Ngay Bây Giờ Vui Lòng Đợi Chu Kỳ 15 Qua!", "Thông Báo");</script>';
    }
}
?>