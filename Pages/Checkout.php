<?php
include_once('../Config/Header.php');
if($_GET['domain'] == ""){
    echo '<script>location.href="/";</script>';
}

$explode = explode(".", $_GET['domain']);
$duoimien = '.'.$explode[1];
include_once(__DIR__.'/../Repositories/DomainRepository.php');
$domainRepo = new DomainRepository($connect);
$fetch = $domainRepo->findByDuoi($duoimien);
if($fetch['duoi'] != $duoimien){
    echo '<script>location.href="/";</script>';
}
$tienphaitra = $fetch['price'];
$images = $fetch['image'];
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
                                <h1 class="anchor fw-bold mb-5" id="text-input" data-kt-scroll-offset="50">
                                    <a href="#text-input"></a> Đăng Ký Tên Miền &nbsp; <img src="<?=$images;?>" width="50px">
                                </h1>
                                <div class="py-5">
                                    <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                        <div class="fv-row mb-10 fv-plugins-icon-container">
                                            <label class="required fw-semibold fs-6 mb-2"> Tên Miền </label>
                                            <input type="text" id="domain" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Tên Miền Cần Mua" value="<?=$_GET['domain'];?>" disabled>
                                        </div>
                                        <div class="fv-row mb-10 fv-plugins-icon-container">
                                            <label class="required fw-semibold fs-6 mb-2"> NS1 </label>
                                            <input type="text" id="ns1" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="NS1 Của Cloudflare">
                                        </div>
                                        <div class="fv-row mb-10 fv-plugins-icon-container">
                                            <label class="required fw-semibold fs-6 mb-2"> NS2 </label>
                                            <input type="text" id="ns2" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="NS2 Của Cloudflare">
                                        </div>
                                        <div class="fv-row mb-10 fv-plugins-icon-container">
                                            <label class="required fw-semibold fs-6 mb-2"> Hạn Dùng </label>
                                            <select id="hsd" class="form-select">
                                                <option value="1"> 1 Năm </option>
                                            </select>
                                            <div id="status"></div>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                        <button id="buy" type="submit" class="btn btn-primary">
                                            <span class="indicator-label"> Mua Ngay - <?=number_format($tienphaitra);?>đ</span>
                                        </button>
                                    </div>
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
    $('#buy').on('click', function() {
        $("#buy").text('Đang xử lý...');
        var domain = $('#domain').val();
        var ns1 = $('#ns1').val();
        var ns2 = $('#ns2').val();
        var hsd = $('#hsd').val();
        $.ajax({
            url: '/Ajaxs/BuyDomain.php',
            type: 'POST',
            data: {domain:domain,ns1:ns1,ns2:ns2,hsd:hsd},
            success:function(data){
                $("#buy").attr("disabled", false);
                $("#buy").text('Mua Ngay - <?=number_format($tienphaitra);?>đ');
                $('#status').html(data);
            }
        }); 
    });
});
</script>

<?php
include('../Config/Footer.php');
?>
