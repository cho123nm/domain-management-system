<?php
require_once('Config/Database.php');
require_once(__DIR__.'/Repositories/CardRepository.php');
require_once(__DIR__.'/Repositories/UserRepository.php');
?>

<?php
# Các đối số mà CARDVIP gửi lại cho khách hàng

# Đối số status là trạng thái của thẻ sau khi được xử lý. 
# Status == 200 -> thẻ đúng
# Status == 201 -> thẻ sai mệnh giá
# Status == 100 -> thẻ sai
# Quý khách lưu ý dựa vào status để trả lại kết quả cho khách
$status = $_GET['status']; 

# Đối số pricesvalue là mệnh giá thẻ cào lúc quý khách gửi thẻ
$pricesvalue = $_GET['pricesvalue'];

# Đối số value_receive là mệnh giá thực của thẻ, quý khách chỉ quan tâm đối số này khi status nhận giá trị 200 hoặc 201
$value_receive = $_GET['value_receive'];

# Đối số card_code là mã thẻ cào quý khách gửi
$card_code = $_GET['card_code'];

# Đối số card_seri là serial cào quý khách gửi
$card_seri = $_GET['card_seri'];

# Đối số value_customer_receive là mệnh giá mà quý khách nhận được sau khi đã trừ chiết khấu, mệnh giá này là mệnh giá được cộng vào tài khoản CARDVIP
$value_customer_receive = $_GET['value_customer_receive'];

# Đối số requestid là mã đơn thẻ cào mà quý khách đã gửi sang
$requestid = $_GET['requestid'];

# Quý khách check thẻ cào có tồn tại trong hệ thống hoặc thẻ cào đó đã xử lý chưa thông qua các điều kiện như card_code, card_seri, requestid, trạng thái xử lý của thẻ
# Sau đó sẽ check trạng thái của biến $status để cập nhật trạng thái của thẻ theo hướng dẫn trên.
$cardRepo = new CardRepository($connect);
$userRepo = new UserRepository($connect);

if($status == "200"){
    $uid = $cardRepo->getUidByRequestId($requestid);
    $cardRepo->updateStatusByRequestId($requestid, 1);
    if ($uid !== null) {
        $userRepo->incrementBalance($uid, (int)$value_customer_receive);
    }
} elseif ($status == "100") {
    $cardRepo->updateStatusByRequestId($requestid, 2);
}
?>