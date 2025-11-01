<?php
include('Connect/Header.php'); 
?>

<div class="col-span-12 mt-6">
    <div class="intro-y block sm:flex items-center h-10">
        <h2 class="text-lg font-medium truncate mr-5"> Quản Lý Gạch Cards </h2>
        <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
        </div>
    </div>
    <div class="intro-y box">
        <div class="p-5" id="head-options-table">
            <div class="preview">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead class="table-dark">
                            <tr>
                                <th class="whitespace-nowrap">#</th>
                                <th class="whitespace-nowrap"> UID </th>
                                <th class="whitespace-nowrap"> Mã Thẻ </th>
                                <th class="whitespace-nowrap"> Serial </th>
                                <th class="whitespace-nowrap"> Mệnh Giá </th>
                                <th class="whitespace-nowrap"> Loại Thẻ </th>
                                <th class="whitespace-nowrap"> Status </th>
                                <th class="whitespace-nowrap"> Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include_once(__DIR__.'/../Repositories/CardRepository.php');
                            $cardRepo = new CardRepository($connect);
                            $resultRows = $cardRepo->listAll();
                            $id = '0';
                            foreach($resultRows as $cloudstorevn){
                                $id++;
                            ?>
                            <tr>
                                <td>#<?=$id;?></td>
                                <td> <?=$cloudstorevn['uid'];?> </td>
                                <td> <?=$cloudstorevn['pin'];?> </td>
                                <td> <?=$cloudstorevn['serial'];?> </td>
                                <td> <?=$cloudstorevn['type'];?> </td>
                                <td> <?=number_format($cloudstorevn['amount']);?>đ </td>
                                <td> 
                                    <?php 
                                    if($cloudstorevn['status'] == '0'){
                                        echo '<button class="btn btn-primary"> Đang Duyệt </button>';
                                    } 
                                    if($cloudstorevn['status'] == '1'){
                                        echo '<button class="btn btn-success"> Thẻ Đúng </button>';
                                    } 
                                    if($cloudstorevn['status'] == '2'){
                                        echo '<button class="btn btn-danger"> Thẻ Sai </button>';
                                    } 
                                    ?> 
                                </td>
                                <td class="whitespace-nowrap"> <?=$cloudstorevn['time'];?> </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('Connect/Footer.php'); 
?>
