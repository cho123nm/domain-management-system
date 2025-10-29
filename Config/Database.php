        <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        include_once(__DIR__.'/DatabaseConnection.php');
        $connect = DatabaseConnection::getInstance();

        // Initialize Error Handler
        include_once(__DIR__.'/ErrorHandler.php');
        $errorHandler = ErrorHandler::getInstance();

        include_once(__DIR__.'/../Repositories/SettingsRepository.php');
        include_once(__DIR__.'/../Repositories/DomainRepository.php');
        include_once(__DIR__.'/../Repositories/UserRepository.php');
        include_once(__DIR__.'/../Repositories/HistoryRepository.php');

        // Chỉ khởi tạo biến nếu chưa tồn tại
        if (!isset($CaiDatChung)) {
            try {
                $settingsRepo = new SettingsRepository($connect);
                $CaiDatChung = $settingsRepo->getOne() ?: [];
            } catch (Exception $e) {
                // Fallback nếu có lỗi database
                $CaiDatChung = [];
            }
            
            // Đảm bảo $CaiDatChung có các key cần thiết với giá trị mặc định
            $CaiDatChung = array_merge([
                'tieude' => 'CloudStoreVN',
                'mota' => 'Cung cấp tên miền giá rẻ',
                'keywords' => 'tên miền, domain, giá rẻ',
                'theme' => 'light',
                'apikey' => '',
                'imagebanner' => '',
                'sodienthoai' => '',
                'banner' => '',
                'logo' => ''
            ], $CaiDatChung);
        }
        
        $apikey = $CaiDatChung['apikey'] ?? '';
        $callback = 'https://'.$_SERVER['SERVER_NAME'].'/callback.php';
        $apikeymomo = '';
        $apikeymbbank = '';

        $domainRepo = new DomainRepository($connect);
        $domainname = $domainRepo->getOneSample();
       
        
        // Chỉ khởi tạo biến user nếu chưa tồn tại
        if (!isset($username) || !isset($sodu) || !isset($email)) {
            try {
                if(isset($_SESSION['users'])){
                    $userRepo = new UserRepository($connect);
                    $users = $userRepo->findByUsername($_SESSION['users']) ?: [];
                    $username = $users['taikhoan'] ?? 'Không Xác Định';
                    $sodu = (int)($users['tien'] ?? 0);
                    $email = $users['email'] ?? '2431540219@vaa.edu.vn';
                } else {
                    $username = 'Không Xác Định';
                    $sodu = 0;
                    $email = '2431540219@vaa.edu.vn';
                }
            } catch (Exception $e) {
                // Fallback nếu có lỗi database
                $username = 'Không Xác Định';
                $sodu = 0;
                $email = '2431540219@vaa.edu.vn';
            }
        }
        
        if (!function_exists('curl_get')) {
            function curl_get($url)
            {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $data = curl_exec($ch);
                
                curl_close($ch);
                return $data;
            }
        }
        
        if (!function_exists('random_string')) {
            function random_string($length) {
                $key = '';
                $keys = array_merge(range(0, 9), range('a', 'z'));
             
                for ($i = 0; $i < $length; $i++) {
                    $key .= $keys[array_rand($keys)];
                }
             
                return $key;
            }
        }
            $token = random_string(50);
        
                
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $time2 = date('d/m/Y');
        $time3 = date('m/Y');
      
        $explode = explode("/", $time2);
        $explode2 = explode(" - ", $time2);
        $timecong = $explode[1] + '1';
        $time2 = $explode[0].'/'.$timecong.'/'.$explode[2];
        
        $time = date('d/m/Y - H:i:s');
        $today = date('d/m/Y');
        
       $historyRepo = new HistoryRepository($connect);
       $checkhsd = $historyRepo->getByTimedns($today);
if ($checkhsd && $today == ($checkhsd['timedns'] ?? '')) {
    $historyRepo->resetTimednsById((int)$checkhsd['id']);
}

        ?>