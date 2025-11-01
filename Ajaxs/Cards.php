<?php
include_once('../Config/Database.php');
include_once('../Repositories/CardRepository.php');
include_once('../Repositories/UserRepository.php');
include_once('../Repositories/SettingsRepository.php');

$pin     = trim($_POST['pin']    ?? '');
$serial  = trim($_POST['serial'] ?? '');
$amount  = trim($_POST['amount'] ?? '');
$type    = trim($_POST['type']   ?? '');
$requestid = rand(500000000,900000000);

// 👉 đảm bảo các biến cần thiết có giá trị (đồng bộ với cách lưu trong Cards)
$time    = isset($time) ? (string)$time : date('d/m/Y - H:i:s');
$time2   = isset($time2) ? (string)$time2 : date('d/m/Y');

// Lấy cấu hình từ DB nếu biến chưa có
$settingsRepo = new SettingsRepository($connect);
$settings = $settingsRepo->getOne() ?: [];
$apikey   = !empty($apikey) ? $apikey : ($settings['apikey'] ?? '');
$callback = !empty($callback) ? $callback : ($settings['callback'] ?? ('https://'.$_SERVER['SERVER_NAME'].'/callback.php'));

// Xác định user hiện tại an toàn
$user_id = 0;
if (isset($users['id'])) {
    $user_id = (int)$users['id'];
} elseif (isset($_SESSION['users'])) {
    $userRepo = new UserRepository($connect);
    $u = $userRepo->findByUsername($_SESSION['users']);
    $user_id = (int)($u['id'] ?? 0);
}

// Validate cơ bản
$allowedTypes = ['VIETTEL','VINAPHONE','MOBIFONE','GATE','ZING','VNMOBI','VIETNAMMOBILE'];
if ($pin === "" || $serial === "" || $amount === "" || $type === "") {
    echo '<script>toastr.error("Vui Lòng Nhập Đầy Đủ Thông Tin!", "Thông Báo");</script>';
} elseif (!ctype_digit($amount)) {
    echo '<script>toastr.error("Mệnh giá không hợp lệ", "Thông Báo");</script>';
} elseif (!in_array(strtoupper($type), $allowedTypes, true)) {
    echo '<script>toastr.error("Loại thẻ không hỗ trợ", "Thông Báo");</script>';
} elseif ($user_id <= 0) {
    echo '<script>toastr.error("Vui lòng đăng nhập lại để nạp thẻ", "Thông Báo");</script>';
} else {
    $cardRepo = new CardRepository($connect);
    if ($cardRepo->existsByPinSerial($pin, $serial)) {
        echo '<script>toastr.error("Thẻ Đã Tồn Tại Trong Hệ Thống!");</script>';
    } else {
        $dataPost = array(
            'APIKey'        => $apikey,
            'NetworkCode'   => $type,
            'PricesExchange'=> $amount,
            'NumberCard'    => $pin,
            'SeriCard'      => $serial,
            'IsFast'        => true,
            'RequestId'     => $requestid,
            'UrlCallback'   => $callback
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://partner.cardvip.vn/api/createExchange",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($dataPost),
            CURLOPT_HTTPHEADER => array("Content-Type: application/json"),
        ));

        $response = curl_exec($curl);
        $curlErr  = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($response === false) {
            $msg = $curlErr !== '' ? $curlErr : 'Không thể kết nối cổng nạp thẻ';
            echo '<script>toastr.error("'.$msg.'", "Thông Báo");</script>';
            return;
        }

        $obj = json_decode($response, true);
        if (!is_array($obj)) {
            echo '<script>toastr.error("API trả về dữ liệu không hợp lệ (HTTP '.$httpCode.')", "Thông Báo");</script>';
            return;
        }

        $status  = $obj['status']  ?? null;
        $message = $obj['message'] ?? '';

        if ($status === 200) {
            echo '<script>toastr.success("Nạp thẻ thành công, vui lòng chờ 30s - 1 phút để duyệt", "Thông Báo");</script>';
            $cardRepo->insertCard((int)$user_id, $pin, $serial, strtoupper($type), (string)$amount, (string)$requestid, (string)$time, (string)$time2);
        } elseif ($status === 400) {
            echo '<script>toastr.error("Thẻ đã tồn tại hoặc không hợp lệ: '.htmlspecialchars($message).'", "Thông Báo");</script>';
        } elseif ($status === 401) {
            echo '<script>toastr.error("Sai định dạng thẻ: '.htmlspecialchars($message).'", "Thông Báo");</script>';
        } elseif ($status === 403) {
            echo '<script>toastr.error("APIKey không hợp lệ hoặc bị hạn chế", "Thông Báo");</script>';
        } else {
            $safeMsg = $message !== '' ? htmlspecialchars($message) : 'Có lỗi khi gửi thẻ (HTTP '.$httpCode.')';
            echo '<script>toastr.error("'.$safeMsg.'", "Thông Báo");</script>';
        }
    }
}
?>
