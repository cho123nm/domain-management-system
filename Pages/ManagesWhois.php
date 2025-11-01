<?php
/**
 * Trang Quản Lý DNS Tên Miền
 * 
 * Chức năng chính:
 * - Kiểm tra quyền quản lý tên miền
 * - Cập nhật nameserver (NS1, NS2)
 * - Quản lý thời gian cập nhật DNS
 * - Validation và bảo mật
 * 
 * @author DAM THANH VU
 * @version 1.0
 */

include_once('../Config/Header.php');
include_once(__DIR__.'/../Repositories/HistoryRepository.php');
include_once(__DIR__.'/../Repositories/UserRepository.php');

if(!isset($_SESSION['users'])){
    echo '<script>location.href="/auth/login";</script>';
    exit;
}

$historyRepo = new HistoryRepository($connect);
$userRepo = new UserRepository($connect);
$currentUser = $userRepo->findByUsername($_SESSION['users']);

if(!$currentUser) {
    echo '<script>alert("Không tìm thấy thông tin người dùng!");</script>';
    echo '<script>location.href="/auth/login";</script>';
    exit;
}

$mgd = $_GET['domain'] ?? '';
$checkmgd = $historyRepo->getByUserIdAndMgd((int)$currentUser['id'], $mgd);

if(!$checkmgd) {
    echo '<script>alert("Bạn Không Có Quyền Quản Lý Miền Này!");</script>';
    echo '<script>location.href="/managers";</script>';
    exit;
}

if($checkmgd['status'] == '4'){
    echo '<script>alert("Tên miền này đã bị từ chối hỗ trợ!");</script>';
    echo '<script>location.href="/managers";</script>';
    exit;
}
?>

<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
    <div class="app-container container-xxl d-flex flex-row flex-column-fluid">
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <div class="d-flex flex-column flex-column-fluid">
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <div class="card mb-5 mb-xl-10">
                    </div>
                    <div class="card card-docs flex-row-fluid mb-2">
                        <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                            <div class="py-10">
                                <h1 class="anchor fw-bold mb-5" id="form-labels" data-kt-scroll-offset="50">
                                    <input type="hidden" id="mgd" value="<?=$checkmgd['mgd'];?>">
                                    <a href="#form-labels"></a> Quản Lý Tên Miền (<?=$checkmgd['domain'];?>) 
                                </h1>
                                <p> Thời Gian Cập Nhật Gần Đây : <code>
                                    <?php 
                                    if($checkmgd['timedns'] == '0'){
                                        echo 'Không Có Lần Cập Nhật Gần Đây';
                                    } else {
                                        echo $checkmgd['timedns'];
                                    }
                                    ?> 
                                </code></p>
                                <div class="py-5">
                                    <div class="mb-10">
                                        <label for="exampleFormControlInput1" class="required form-label">Nameserver 1</label>
                                        <input type="text" class="form-control form-control-solid" id="ns1" placeholder="Nameserver 1" value="<?=$checkmgd['ns1'];?>">
                                    </div>
                                    <div class="mb-10">
                                        <label for="exampleFormControlInput1" class="required form-label">Nameserver 2</label>
                                        <input type="text" class="form-control form-control-solid" id="ns2" placeholder="Nameserver 2" value="<?=$checkmgd['ns2'];?>">
                                    </div>
                                    <div class="py-0">
                                        <center> 
                                            <b class="text-danger"> Chỉ Có Thể Thay Đổi DNS Sau 15 Ngày Khi Thực Hiện Đổi Bạn Phải Chờ Sau 15 Ngày Để Có Thể Thay Đổi Tiếp Tục! </b>
                                        </center>
                                        <br>
                                    </div>
                                    <div id="status"></div>
                                    <button class="btn btn-warning" type="submit" id="UpdateDns"> Thay Đổi </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#UpdateDns').on('click', function() {
        $("#UpdateDns").text('Đang xử lý...');
        var ns1 = $('#ns1').val();
        var ns2 = $('#ns2').val();
        var mgd = $('#mgd').val();
        $.ajax({
            url: '/Ajaxs/UpdateDns.php',
            type: 'POST',
            data: {ns1:ns1,ns2:ns2,mgd:mgd},
            success:function(data){
                $("#UpdateDns").attr("disabled", false);
                $("#UpdateDns").text('Thay Đổi');
                $('#status').html(data);
            }
        }); 
    });
});
</script>

<?php 
include('../Config/Footer.php'); 
?>
