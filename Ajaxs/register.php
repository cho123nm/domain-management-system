<?php
include_once('../Config/Database.php');
include_once('../Repositories/UserRepository.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$taikhoan = $_POST['taikhoan'];
$password = $_POST['password'];
$password2 = $_POST['password2'];
$email = $_POST['email'];

if($taikhoan == "" || $password == "" || $password2 == "" || $email == ""){
    echo '<script>toastr.error("Vui Lòng Nhập Đầy Đủ Thông Tin!", "Thông Báo");</script>';
} else {
    // Validate username (3-20, letters, numbers, underscore)
    if(!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $taikhoan)){
        echo '<script>toastr.error("Tên đăng nhập chỉ gồm chữ, số, gạch dưới (3-20 ký tự)", "Thông Báo");</script>';
        return;
    }
    // Validate email
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo '<script>toastr.error("Email không hợp lệ", "Thông Báo");</script>';
        return;
    }
    // Validate password strength (min 8, includes letter and number)
    if(strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)){
        echo '<script>toastr.error("Mật khẩu tối thiểu 8 ký tự, gồm chữ và số", "Thông Báo");</script>';
        return;
    }
    
    if($password == $password2){
        $userRepo = new UserRepository($connect);
        $existsUser = $userRepo->findByUsername($taikhoan);
        $existsEmail = $userRepo->findByEmail($email);
        if($existsUser || $existsEmail){
            echo '<script>toastr.error("Tên Đăng Nhập Hoặc Email Này Đã Tồn Tại Trên Hệ Thống!", "Thông Báo");</script>';
        } else {
            if($taikhoan == $password){
                echo '<script>toastr.error("Tên Đăng Nhập Và Mật Khẩu Phải Khác Nhau!", "Thông Báo");</script>';
            } else {
                $matkhauMd5 = md5($password);
                $save = $userRepo->createUser($taikhoan, $matkhauMd5, $email, $time);
                if($save){
                    echo '<script>toastr.success("Đăng Ký Thành Công!", "Thông Báo");</script>';
                    $_SESSION['users'] = $taikhoan;
                    echo '<meta http-equiv="refresh" content="1;url=/">';
                } else {
                    echo '<script>toastr.error("Có Lỗi Xảy Ra Trong Quá Trình Xử Lí!", "Thông Báo");</script>';
                }
            }
        }
    } else {
        echo '<script>toastr.error("Vui Lòng Nhập Đúng Mật Khẩu 2", "Thông Báo");</script>';
    }
}
?>
