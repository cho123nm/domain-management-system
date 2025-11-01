<?php
include('Connect/Header.php');
if(isset($_POST['congtien'])){
    $id = $_POST['idc'];
    $price = $_POST['price'];
    if($id == "" || $price == "" ){
        echo '<script>swal("Thông Báo", "Vui Lòng Nhập Đầy Đủ Thông Tin!", "error");</script>';    
    } else {
        include_once(__DIR__.'/../Repositories/UserRepository.php');
        $userRepo = new UserRepository($connect);
        $checkus = $userRepo->findById((int)$id);
        if($checkus === null){
            echo '<script>swal("Thông Báo", "Không Tìm Thấy Người Dùng Với ID '.$id.'!", "error");</script>';    
        } else {
            $thanhright = $userRepo->incrementBalance((int)$id, (int)$price);
            if($thanhright){
                echo '<script>swal("Thông Báo", "Giao Dịch Cộng '.number_format($price).'đ Thành Công Cho Người Dùng '.$checkus['taikhoan'].' ", "success");</script>';
                echo '<meta http-equiv="refresh" content="1;url=">';
            } else {
                echo '<script>swal("Thông Báo", "Không Thể Thực Hiện Giao Dịch!", "error");</script>';
            }
        }
    }
}
?>

<div class="intro-y box mt-5">
    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
        <h2 class="font-medium text-base mr-auto"> Cộng Tiền Thành Viên </h2>
    </div>
    <div id="horizontal-form" class="p-5">
        <div class="preview">
            <form action="" method="post"> 
                <div class="form-inline">
                    <label for="horizontal-form-1" class="form-label sm:w-20"> ID Thành Viên </label>
                    <input id="horizontal-form-1" type="text" name="idc" class="form-control" placeholder="Mã Số Thành Viên">
                </div>
                
                <div class="form-inline mt-5">
                    <label for="horizontal-form-1" class="form-label sm:w-20"> Số Tiền </label>
                    <input id="horizontal-form-1" type="text" class="form-control" placeholder="Tiền Cần Cộng" name="price">
                </div>
                
                <div class="form-check sm:ml-20 sm:pl-5 mt-5">
                    <input id="horizontal-form-3" class="form-check-input" type="checkbox" value="">
                    <label class="form-check-label" for="horizontal-form-3">Remember me</label>
                </div>
                <div class="sm:ml-20 sm:pl-5 mt-5">
                    <button type="submit" name="congtien" class="btn btn-primary"> Cộng Ngay </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include('Connect/Footer.php'); 
?>
