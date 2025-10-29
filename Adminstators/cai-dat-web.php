<?php
include('Connect/Header.php');
include_once(__DIR__.'/../Repositories/SettingsRepository.php');
$settingsRepo = new SettingsRepository($connect);
$CaiDatChung = $settingsRepo->getOne();
if(!$CaiDatChung){
    $CaiDatChung = [
        'theme' => '0',
        'tieude' => '',
        'keywords' => '',
        'mota' => '',
        'imagebanner' => '',
        'sodienthoai' => '',
        'banner' => '',
        'logo' => ''
    ];
}
if(isset($_POST['update'])){
    $theme = $_POST['theme'];
    $title = $_POST['title'];
    $keywords = $_POST['keywords'];
    $description = $_POST['description'];
    $imagebanner = $_POST['imagebanner'];
    $phone = $_POST['phone'];
    $banner = $_POST['banner'];
    $logo = $_POST['logo'];

    $ok = $settingsRepo->updateWebsiteSettings($title, $theme, $keywords, $description, $imagebanner, $phone, $banner, $logo);
    if($ok){
    echo '<script>swal("Thông Báo", "Cập Nhật Thành Công!", "success");</script>'; 
    echo '<meta http-equiv="refresh" content="1;url=">';
    } else {
    echo '<script>swal("Thông Báo", "Thất Bại", "error");</script>';
}    
 
}
?>


      <div class="intro-y box mt-5">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto"> Cài Đặt Trang Web </h2>
               
                </div>
                <div id="horizontal-form" class="p-5">
                    <div class="preview">
                        <form action="" method="post"> 
                        <div class="form-inline">
                            <label for="horizontal-form-1" class="form-label sm:w-20"> Giao Diện Admin </label>
                            <select class="form-control" name="theme">
                            <option value="<?=$CaiDatChung['theme'];?>"> <?php if($CaiDatChung['theme'] == '0'){
                                print('Xanh Dương');
                            } if($CaiDatChung['theme'] == '1'){
                                print('Xanh Lá Đậm');
                            } if($CaiDatChung['theme'] == '2'){
                                print('Xanh Dương Sáng');
                            } if($CaiDatChung['theme'] == '3'){
                                print('Xanh Xám');
                            } if($CaiDatChung['theme'] == '4'){
                                print('Tím');
                            } ?> </option> 
                            <option value="0"> Xanh Dương </option>    
                            <option value="1"> Xanh Lá Đậm </option>
                            <option value="2"> Xanh Dương Sáng </option>
                            <option value="3"> Xanh Xám (Khuyên Dùng) </option>
                            <option value="4"> Tím </option>
                            </select>
                        </div>
                        <div class="form-inline mt-5">
                            <label for="horizontal-form-2" class="form-label sm:w-20"> title </label>
                        <textarea id="horizontal-form-2" type="text" class="form-control" name="title" placeholder="Tiêu Đề Trang Web" rows="4" cols="50"><?=$CaiDatChung['tieude'];?></textarea>
                        </div>
                        
                          <div class="form-inline mt-5">
                            <label for="horizontal-form-1" class="form-label sm:w-20"> keywords </label>
                             <textarea id="horizontal-form-2" type="text" class="form-control" name="keywords" placeholder="keywords" rows="4" cols="50"><?=$CaiDatChung['keywords'];?></textarea>
                        </div>
                        
                        
                         <div class="form-inline mt-5">
                            <label for="horizontal-form-1" class="form-label sm:w-20"> description </label>
                          <textarea id="horizontal-form-2" type="text" class="form-control" name="description" placeholder="Mô Tả Trang Web" rows="4" cols="50"><?=$CaiDatChung['mota'];?></textarea>
                        </div>
                        
                        
                           <div class="form-inline mt-5">
                            <label for="horizontal-form-2" class="form-label sm:w-20"> Ảnh Mô Tả Trang Web </label>
                        <textarea id="horizontal-form-2" type="text" class="form-control" name="imagebanner" placeholder="Ảnh Mô Tả" rows="4" cols="50"><?=$CaiDatChung['imagebanner'];?></textarea>
                        </div>
                        
                        
                           <div class="form-inline mt-5">
                            <label for="horizontal-form-2" class="form-label sm:w-20"> Số ĐIện Thoại Zalo </label>
                        <input id="horizontal-form-2" type="text" class="form-control" name="phone" placeholder="Số Điện Thoại Zalo" value="<?=$CaiDatChung['sodienthoai'];?>">
                        </div>
                        
                          
                         <div class="form-inline mt-5">
                        <label for="horizontal-form-2" class="form-label sm:w-20"> ID Video banner </label>
                        <input id="horizontal-form-2" type="text" class="form-control" name="banner"  placeholder="banner Ở Home" value="<?=$CaiDatChung['banner'];?>">
                        </div>
                        
                        
                           
                         <div class="form-inline mt-5">
                        <label for="horizontal-form-2" class="form-label sm:w-20"> lOGO </label>
                        <input id="horizontal-form-2" type="text" class="form-control" name="logo" placeholder="Ảnh logo" value="<?=$CaiDatChung['logo'];?>">
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
        </div>
    </div>
                                
                                
                        
<?php
include('Connect/Footer.php'); ?>

