<?php
include('Connect/Header.php');

// Lấy danh sách hình ảnh từ folder images
$imagesPath = __DIR__ . '/../images/';
$availableImages = [];
if (is_dir($imagesPath)) {
    $files = scandir($imagesPath);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && preg_match('/\.(jpg|jpeg|png|gif|svg)$/i', $file)) {
            $availableImages[] = $file;
        }
    }
}

if(isset($_POST['dangngay'])){
$duoi = $_POST['duoi'];
$image = $_POST['image'];
$price = $_POST['price'];
if($price == "" || $image == "" || $duoi == ""){
 echo '<script>swal("Thông Báo", "Vui Lòng Nhập Đầy Đủ Thông Tin!", "error");</script>';    
} else {
                           include_once(__DIR__.'/../Repositories/DomainRepository.php');
                           $domainRepo = new DomainRepository($connect);
                           $ok = $domainRepo->create((int)$price, $duoi, $image);
                           if($ok){
                           echo '<script>swal("Thông Báo", "Đăng Thành Công!", "success");</script>'; 
                           echo '<meta http-equiv="refresh" content="1;url=">';
                           } else {
                           echo '<script>swal("Thông Báo", "Không Thể Đăng Bán!", "error");</script>';
                           }
}
}
?>


    
            <div class="intro-y box mt-5">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto"> Thêm Sản Phẩm </h2>
               
                </div>
                <div id="horizontal-form" class="p-5">
                    <div class="preview">
                        <form action="" method="post"> 
                        <div class="form-inline">
                            <label for="horizontal-form-1" class="form-label sm:w-20"> Đuôi Miền </label>
                            <input id="horizontal-form-1" type="text" name="duoi" class="form-control" placeholder="Đuôi Miền">
                        </div>
                        
                        
                          <div class="form-inline mt-5">
                            <label for="horizontal-form-1" class="form-label sm:w-20"> Hình Ảnh </label>
                            <div class="flex items-center space-x-3">
                                <select id="image-select" class="form-control" name="image" onchange="updateImagePreview()">
                                    <option value="">-- Chọn hình ảnh --</option>
                                    <?php foreach ($availableImages as $img): ?>
                                    <option value="/images/<?= htmlspecialchars($img) ?>"><?= htmlspecialchars($img) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div id="image-preview" class="ml-3" style="display: none;">
                                    <img id="preview-img" src="" alt="Preview" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                </div>
                            </div>
                        </div>
                        
                        
                         <div class="form-inline mt-5">
                            <label for="horizontal-form-1" class="form-label sm:w-20"> Giá Tiền </label>
                            <input id="horizontal-form-1" type="number" class="form-control" name="price" placeholder="Giá Bán">
                        </div>
                        
                        
                        <div class="form-check sm:ml-20 sm:pl-5 mt-5">
                            <input id="horizontal-form-3" class="form-check-input" type="checkbox" value="">
                            <label class="form-check-label" for="horizontal-form-3">Remember me</label>
                        </div>
                        <div class="sm:ml-20 sm:pl-5 mt-5">
                            <button type="submit" name="dangngay" class="btn btn-primary"> Đăng Ngay </button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
                                
                        
<?php
include('Connect/Footer.php'); ?>

<script>
function updateImagePreview() {
    const select = document.getElementById('image-select');
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    if (select.value) {
        previewImg.src = select.value;
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
}
</script>

