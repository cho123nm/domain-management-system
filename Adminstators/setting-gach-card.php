<?php
include('Connect/Header.php');
include_once(__DIR__.'/../Repositories/SettingsRepository.php');
$settingsRepo = new SettingsRepository($connect);
$cloudstorevn12 = $settingsRepo->getOne();
if(isset($_POST['update'])){
    $apikey = $_POST['apikey'];
    $webgach = $_POST['webgach'];
    $callback = $_POST['callback'];
    if($apikey == "" || $webgach == "" || $callback == ""){
        echo '<script>swal("Thông Báo", "Vui Lòng Nhập Đầy Đủ Thông Tin!", "error");</script>';
    } else {
        $ok = $settingsRepo->updateCardGateway($apikey, $callback, $webgach);
        if($ok){
            echo '<script>swal("Thông Báo", "Cập Nhật Thành Công!", "success");</script>'; 
            echo '<meta http-equiv="refresh" content="1;url=">';
        } else {
            echo '<script>swal("Thông Báo", "Thất Bại", "error");</script>';
        }    
    }
}
?>

<div class="intro-y box mt-5">
    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
        <h2 class="font-medium text-base mr-auto"> Cài Đặt Gạch Thẻ </h2>
    </div>
    <div id="horizontal-form" class="p-5">
        <div class="preview">
            <form action="" method="post"> 
                <div class="form-inline">
                    <label for="horizontal-form-1" class="form-label sm:w-20"> TÊN WEB GẠCH </label>
                    <input id="horizontal-form-1" type="text" name="webgach" class="form-control" value="<?=$cloudstorevn12['webgach'];?>" placeholder="Tên Web Gạch">
                </div>
                <div class="form-inline mt-5">
                    <label for="horizontal-form-1" class="form-label sm:w-20"> API KEY </label>
                    <input id="horizontal-form-1" type="text" name="apikey" class="form-control" value="<?=$cloudstorevn12['apikey'];?>" placeholder="API KEY Tại CardVip.Vn">
                </div>
                <div class="form-inline mt-5">
                    <label for="horizontal-form-1" class="form-label sm:w-20"> URL CALLBACK </label>
                    <input id="horizontal-form-1" type="text" name="callback" class="form-control" value="<?=$cloudstorevn12['callback'];?>" placeholder="URL TRẢ TRẠNG THÁI THẺ">
                </div>
                <div class="form-check sm:ml-20 sm:pl-5 mt-5">
                    <input id="horizontal-form-3" class="form-check-input" type="checkbox" value="">
                    <label class="form-check-label" for="horizontal-form-3">Remember me</label>
                </div>
                <div class="sm:ml-20 sm:pl-5 mt-5">
                    <button type="submit" name="update" class="btn btn-primary"> Cập Nhật </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include('Connect/Footer.php'); 
?>
