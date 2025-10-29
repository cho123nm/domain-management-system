<?php
include('Connect/Header.php'); 
         
                 
                    if(isset($_GET['true'])){
                    include_once(__DIR__.'/../Repositories/HistoryRepository.php');
                    $historyRepo = new HistoryRepository($connect);
                    $historyRepo->updateStatusById((int)$_GET['true'], '1');
                     echo '<meta http-equiv="refresh" content="1;url=./duyet-don-hang.php">';
                    } if(isset($_GET['cho'])){
                    include_once(__DIR__.'/../Repositories/HistoryRepository.php');
                    $historyRepo = new HistoryRepository($connect);
                    $historyRepo->updateStatusById((int)$_GET['cho'], '0');
                     echo '<meta http-equiv="refresh" content="1;url=./duyet-don-hang.php">';
                    } if(isset($_GET['false'])){
                    include_once(__DIR__.'/../Repositories/HistoryRepository.php');
                    $historyRepo = new HistoryRepository($connect);
                    $historyRepo->updateStatusById((int)$_GET['false'], '2');
                     echo '<meta http-equiv="refresh" content="1;url=./duyet-don-hang.php">';
                    } 
              
                    
?>



<div class="col-span-12 mt-6">
                    <div class="intro-y block sm:flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5"> Danh Sách Đơn Hàng </h2>
                        <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
                            <button class="btn box flex items-center text-slate-600 dark:text-slate-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="file-text" data-lucide="file-text" class="lucide lucide-file-text hidden sm:block w-4 h-4 mr-2"><path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><line x1="10" y1="9" x2="8" y2="9"></line></svg> Export to Excel
                            </button>
                            <button class="ml-3 btn box flex items-center text-slate-600 dark:text-slate-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="file-text" data-lucide="file-text" class="lucide lucide-file-text hidden sm:block w-4 h-4 mr-2"><path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><line x1="10" y1="9" x2="8" y2="9"></line></svg> Export to PDF
                            </button>
                        </div>
                    </div>
                    <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
                        <table class="table table-report sm:mt-2">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap"> ID </th>
                                    <th class="whitespace-nowrap"> TÊN MIỀN </th>
                                    <th class="text-center whitespace-nowrap"> NS1 </th>
                                    <th class="text-center whitespace-nowrap"> NS2 </th>
                                    <th class="text-center whitespace-nowrap"> UID </th>
                                    <th class="text-center whitespace-nowrap"> TRẠNG THÁI </th>
                                    <th class="text-center whitespace-nowrap"> TIME </th>
                                    <th class="text-center whitespace-nowrap"> THAO TÁC </th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php
                        include_once(__DIR__.'/../Repositories/HistoryRepository.php');
                        $historyRepo = new HistoryRepository($connect);
                        	$resultRows = $historyRepo->listAll();
	                   	$id = '0';
	                   	foreach ($resultRows as $cloudstorevn){
	                  	    $id++;
                        ?>
                        
                        <tr class="intro-x">
                                        <td>
                                         <?=$id;?>
                                        </td>
                                        
                                        <td>
                                            <b class="font-medium whitespace-nowrap"><?=$cloudstorevn['domain'];?></b></td>
                              
                                        
                                        <td>
                                            <b class="font-medium whitespace-nowrap"><?=$cloudstorevn['ns1'];?></b></td>
                                            
                                      
                                          <td>
                                            <b class="font-medium whitespace-nowrap"><?=$cloudstorevn['ns2'];?></b></td>
                                            
                                          <td>
                                            <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5"><?=$cloudstorevn['uid'];?></div>
                                        </td>
                                        
                                        
                                        <td> <?php if($cloudstorevn['status'] == '0'){
                                            echo '<button class="btn btn-primary"> Chờ Xử Lí </button>';
                                            } if($cloudstorevn['status'] == '1'){
                                            echo '<button class="btn btn-success"> Đang Hoạt Động </button>';
                                            } if($cloudstorevn['status'] == '2'){
                                            echo '<button class="btn btn-danger"> Hết Hạn </button>';
                                            }  if($cloudstorevn['status'] == '3'){
                                            echo '<button class="btn btn-warning"> Update DNS </button>';
                                            }  if($cloudstorevn['status'] == '4'){
                                            echo '<button class="btn btn-danger"> Từ Chối Hỗ Trợ </button>';
                                            } ?> </td>
                                        
                                        <td>    
                                            <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5"><?=$cloudstorevn['time'];?></div>
                                        </td>
                                        
                                        <td> <a href="?true=<?=$cloudstorevn['id'];?>" class="btn btn-success"> Duyệt </a> <a href="?cho=<?=$cloudstorevn['id'];?>" class="btn btn-primary"> Chờ </a> <a href="?false=<?=$cloudstorevn['id'];?>" class="btn btn-danger"> Hủy </a> </td>
                                 
                                        
                                    
                                    <?php } ?>
                                    
                              </tbody>
                        </table>
                    </div>
                    
           
                </div>  </div>  </div>  </div>
                

    <?php
                        foreach ($resultRows as $cloudstorevn){
                        ?>
                        
            
                        <div id="delete-modal-preview-<?=$cloudstorevn['id'];?>" class="modal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body p-0">
                                        <div class="p-5 text-center">
                                            <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                            <div class="text-3xl mt-5">Bạn Có Chắc Muốn Xóa Nó?</div>
                                            <div class="text-slate-500 mt-2"> Sau Khi Thực Hiện Xóa Sẽ Không Thể Khôi Phục Nó! </div>
                                            <form action="" method="post">
                                            <input type="hidden" name="id" value="<?=$cloudstorevn['id'];?>">
                                        </div>
                                        <div class="px-5 pb-8 text-center">
                                            <a data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1"> Đóng </a>
                                            <button type="submit" name="xoa" class="btn btn-danger w-24"> Xóa</button>     
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                 <?php } ?>
                      
                           
                        
                        <?php
                        if(isset($_POST['xoa'])){
                        $id = $_POST['id'];
                        include_once(__DIR__.'/../Repositories/HistoryRepository.php');
                        $historyRepo = new HistoryRepository($connect);
                        $historyRepo->deleteById((int)$id);
                        echo '<script>swal("Thông Báo", "Xóa Thành Công!", "success");</script>';
                        echo '<meta http-equiv="refresh" content="1;url=">';
                        }
                        ?>
                        
                        
                        
<?php
include('Connect/Footer.php'); ?>

