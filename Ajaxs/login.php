<?php



include_once('../Config/Database.php');
include_once('../Repositories/UserRepository.php');

$taikhoan = $_POST['taikhoan'];
$matkhau = $_POST['matkhau'];
if($taikhoan == "" || $matkhau == ""){
     echo '<script>toastr.error("Vui Lòng Nhập Đầy Đủ Thông Tin!", "Thông Báo");</script>';
} else {
       $userRepo = new UserRepository($connect);
       if ($userRepo->verifyCredentials($taikhoan, $matkhau)){
           echo '<script>toastr.success("Đăng Nhập Thành Công!", "Thông Báo");</script>';
           $_SESSION['users'] = $taikhoan;
           echo '<meta http-equiv="refresh" content="1;url=/">';
       } else {
           echo '<script>toastr.error("Thông Tin Đăng Nhập Không Hợp Lệ!", "Thông Báo");</script>';
       }
}
?>
