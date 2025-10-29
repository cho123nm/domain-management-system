<?php
include_once('../Config/Database.php');
include_once('../Repositories/DomainRepository.php');

$tenmien = strtolower(trim($_POST['name'] ?? ''));
$domainSuffix = $_POST['domain'] ?? '';
$ok = $tenmien . $domainSuffix; // full domain user nhập, ví dụ mysite.com

// Lấy danh sách đuôi miền hỗ trợ từ DB thay vì hard-code
$domainRepo = new DomainRepository($connect);
$supported = array_map(function($d){ return strtolower($d['duoi']); }, $domainRepo->listAll());

// Validate rỗng
if($tenmien === ''){
    echo '<script>toastr.error("Vui Lòng Nhập Tên Miền", "Thông Báo");</script>';
    exit;
}

// Validate đuôi miền
if(!in_array(strtolower($domainSuffix), $supported, true)){
    echo '<script>toastr.error("Đuôi Miền '.$domainSuffix.' Không Hỗ Trợ! ", "Thông Báo");</script>';
    exit;
}

// Validate định dạng tên miền: chỉ chữ/số/gạch ngang, không bắt đầu/kết thúc bằng gạch ngang, 1-63 mỗi label, tổng <= 253
$labelRegex = '/^(?!-)[a-z0-9-]{1,63}(?<!-)$/';
$labels = explode('.', $tenmien);
foreach($labels as $label){
    if($label === '' || !preg_match($labelRegex, $label)){
        echo '<script>toastr.error("Tên miền không hợp lệ (chỉ chữ, số, gạch ngang; không bắt đầu/kết thúc bằng -)", "Thông Báo");</script>';
        exit;
    }
}
if(strlen($ok) > 253){
    echo '<script>toastr.error("Tên miền quá dài", "Thông Báo");</script>';
    exit;
}

// SỬA LẠI: LOGIC ĐƠN GIẢN VÀ CHÍNH XÁC - CHỈ KIỂM TRA KHI THẬT SỰ CHẮC CHẮN

// BƯỚC 1: KIỂM TRA DNS A RECORDS
$hasARecord = checkdnsrr($ok, 'A');

// BƯỚC 2: KIỂM TRA PING (CHỈ BÁO TRUE KHI CHẮC CHẮN)
$pingResult = checkDomainByPing($ok);

// BƯỚC 3: KIỂM TRA WHOIS (CHỈ BÁO TRUE KHI CHẮC CHẮN)
$whoisResult = checkWhoisVietnam($ok);

// CHỈ BÁO "ĐÃ ĐĂNG KÝ" KHI CÓ BẰNG CHỨNG RÕ RÀNG
$strongEvidence = 0;
if($hasARecord) $strongEvidence++;
if($pingResult === true) $strongEvidence++;
if($whoisResult === true) $strongEvidence++;

// NẾU CÓ ÍT NHẤT 2 BẰNG CHỨNG RÕ RÀNG → BÁO ĐÃ ĐĂNG KÝ
if($strongEvidence >= 2) {
    echo '<script>toastr.error("Tên Miền '.$ok.' Đã Được Đăng Ký!", "Thông Báo");</script>';
    exit;
}

// NẾU CHỈ CÓ 0-1 BẰNG CHỨNG → BÁO CÓ THỂ ĐĂNG KÝ
echo '<script>toastr.success("Bạn Có Thể Mua Miền '.$ok.' Ngay Bây Giờ", "Thông Báo");</script>';
echo '<center><b class="text-danger"> Bạn Có Thể Đăng Ký Tên Miền Này Ngay Bây Giờ <a href="/Checkout?domain='.$ok.'" class="text-success"> Tại Đây </a> </b><br><br></center>';

// LOGIC PING CẢI TIẾN - CHỈ BÁO TRUE KHI CHẮC CHẮN

function checkDomainByPing($domain) {
    // Kiểm tra bằng cách resolve IP
    $ip = gethostbyname($domain);
    
    // Nếu trả về chính domain name thì không có IP (chưa đăng ký)
    if($ip === $domain) {
        return false;
    }
    
    // SỬA LẠI: CHỈ BÁO TRUE KHI IP THUỘC VỀ WEBSITE THẬT SỰ
    
    // Lọc bỏ TẤT CẢ IP có thể gây false positive
    $excludeIPs = [
        '127.0.0.1',           // localhost
        '0.0.0.0',             // invalid
        '8.8.8.8',             // Google DNS
        '1.1.1.1',             // Cloudflare DNS
        '208.67.222.222',      // OpenDNS
        '74.125.224.72',       // Google parking
        '173.194.44.0',        // Google parking range
        '216.58.192.0',        // Google parking range
        '104.21.0.0',          // Cloudflare range
        '172.67.0.0',          // Cloudflare range
        '141.101.0.0',         // Cloudflare range
        '162.158.0.0',         // Cloudflare range
        '198.41.0.0',          // Cloudflare range
        '188.114.0.0',         // Cloudflare range
    ];
    
    // Kiểm tra IP có phải trong danh sách loại trừ không
    foreach($excludeIPs as $excludeIP) {
        if($ip === $excludeIP || strpos($ip, substr($excludeIP, 0, 8)) === 0) {
            return false; // Coi như chưa đăng ký
        }
    }
    
    // Kiểm tra IP private/reserved ranges
    if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
        return false; // IP private/reserved, coi như chưa đăng ký
    }
    
    // SỬA LẠI: CHỈ BÁO TRUE KHI IP THUỘC VỀ WEBSITE LỚN (CHẮC CHẮN ĐÃ ĐĂNG KÝ)
    $majorWebsiteIPs = [
        '142.250.0.0',         // Google
        '157.240.0.0',         // Facebook
        '31.13.0.0',           // Facebook (IP range khác)
        '66.220.0.0',          // Facebook (IP range khác)
        '69.63.0.0',           // Facebook (IP range khác)
        '104.244.0.0',         // Twitter
        '151.101.0.0',         // Reddit
        '13.107.0.0',          // Microsoft
        '52.84.0.0',           // Amazon
        '104.16.0.0',          // Cloudflare
        '172.64.0.0',          // Cloudflare
        '198.41.0.0',          // Cloudflare
    ];
    
    foreach($majorWebsiteIPs as $majorIP) {
        if(strpos($ip, substr($majorIP, 0, 8)) === 0) {
            return true; // CHẮC CHẮN ĐÃ ĐĂNG KÝ
        }
    }
    
    // NẾU IP KHÔNG THUỘC WEBSITE LỚN → COI NHƯ CHƯA ĐĂNG KÝ (TRÁNH FALSE POSITIVE)
    return false;
}

// SỬA LẠI: WHOIS service với logic cải tiến
function checkWhoisVietnam($domain) {
    $url = "https://domain.inet.vn/api/whois?domain=" . urlencode($domain);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Giảm timeout xuống 5s
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    
    $check = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // SỬA LẠI: Nếu API fail hoặc timeout, coi như không xác định được (không báo đã đăng ký)
    if($httpCode != 200 || !$check) {
        return null; // Không xác định được, không báo false positive
    }
    
    $checkLower = strtolower($check);
    
    // SỬA LẠI: Chỉ báo "đã đăng ký" khi có bằng chứng RÕ RÀNG
    $strongRegisteredPhrases = [
        'registry expiry date:',  // Ngày hết hạn rõ ràng
        'expiration date:',       // Ngày hết hạn
        'registration date:',    // Ngày đăng ký
        'created:',              // Ngày tạo
        'registrar:',            // Registrar rõ ràng
        'domain status: ok',     // Status OK
        'domain status: active', // Status Active
    ];
    
    foreach($strongRegisteredPhrases as $phrase) {
        if(strpos($checkLower, $phrase) !== false) {
            return true; // Đã đăng ký - bằng chứng rõ ràng
        }
    }
    
    // SỬA LẠI: Ưu tiên báo "chưa đăng ký" khi có bằng chứng
    $availablePhrases = [
        'no match',
        'not found', 
        'no data found',
        'không tìm thấy',
        'chưa được đăng ký',
        'domain not found',
        'no entries found'
    ];
    
    foreach($availablePhrases as $phrase) {
        if(strpos($checkLower, $phrase) !== false) {
            return false; // Chưa đăng ký - bằng chứng rõ ràng
        }
    }
    
    // SỬA LẠI: Nếu không có bằng chứng rõ ràng, coi như không xác định được (không báo đã đăng ký)
    return null; // Không xác định được, tránh false positive
}
?>