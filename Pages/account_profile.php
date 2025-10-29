<?php 
/**
 * Trang Profile Người Dùng
 * 
 * Chức năng chính:
 * - Hiển thị thông tin tài khoản
 * - Thống kê số dư và đơn hàng
 * - Danh sách tên miền gần đây
 * - Các thao tác nhanh
 * - Chỉnh sửa thông tin cá nhân
 * 
 * @author DAM THANH VU
 * @version 1.0
 */

include('../Config/Header.php'); 
include_once(__DIR__.'/../Repositories/UserRepository.php');
include_once(__DIR__.'/../Repositories/HistoryRepository.php');

if(!isset($_SESSION['users']) || empty($_SESSION['users'])){     
    echo '<script>location.href="/auth/login";</script>';
    exit;
}

$userRepo = new UserRepository($connect);
$historyRepo = new HistoryRepository($connect);
$currentUsername = $_SESSION['users'];
$user = $userRepo->findByUsername($currentUsername) ?: [];
$userId = (int)($user['id'] ?? 0);

// Xử lý cập nhật thông tin
if(isset($_POST['update_profile'])) {
    $newEmail = trim($_POST['email'] ?? '');
    $newUsername = trim($_POST['username'] ?? '');
    
    $errors = [];
    
    // Validate email
    if(empty($newEmail)) {
        $errors[] = 'Email không được để trống';
    } elseif(!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email không hợp lệ';
    } else {
        // Check if email already exists for another user
        $existingUser = $userRepo->findByEmail($newEmail);
        if($existingUser && $existingUser['id'] != $userId) {
            $errors[] = 'Email này đã được sử dụng bởi tài khoản khác';
        }
    }
    
    // Validate username
    if(empty($newUsername)) {
        $errors[] = 'Tên đăng nhập không được để trống';
    } elseif(strlen($newUsername) < 3) {
        $errors[] = 'Tên đăng nhập phải có ít nhất 3 ký tự';
    } elseif(strlen($newUsername) > 20) {
        $errors[] = 'Tên đăng nhập không được quá 20 ký tự';
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', $newUsername)) {
        $errors[] = 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới';
    } else {
        // Check if username already exists for another user
        $existingUser = $userRepo->findByUsername($newUsername);
        if($existingUser && $existingUser['id'] != $userId) {
            $errors[] = 'Tên đăng nhập này đã được sử dụng bởi tài khoản khác';
        }
    }
    
    if(empty($errors)) {
        // Update user information
        if($userRepo->updateProfile($userId, $newEmail, $newUsername)) {
            // Update session with new username
            $_SESSION['users'] = $newUsername;
            $currentUsername = $newUsername;
            
            echo '<script>swal("Thành công", "Cập nhật thông tin thành công!", "success");</script>';
            // Refresh user data
            $user = $userRepo->findByUsername($currentUsername) ?: [];
        } else {
            echo '<script>swal("Lỗi", "Có lỗi xảy ra khi cập nhật thông tin!", "error");</script>';
        }
    } else {
        echo '<script>swal("Lỗi", "' . implode('<br>', $errors) . '", "error");</script>';
    }
}

// Lấy thống kê từ database
$waiting_orders = $historyRepo->countByUserAndStatus($userId, 0);
$completed_orders = $historyRepo->countByUserAndStatus($userId, 1);
$recent_orders = $historyRepo->listRecentByUser($userId, 5);
?>

<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
    <div class="app-container container-xxl d-flex flex-row flex-column-fluid">
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <div class="d-flex flex-column flex-column-fluid">
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    
                    <!-- Header Card -->
                    <div class="card mb-5 mb-xl-10">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-800">Thông Tin Tài Khoản</span>
                                <span class="text-gray-400 mt-1 fw-semibold fs-6">Chào mừng bạn, <?= htmlspecialchars($user['taikhoan'] ?? ''); ?>!</span>
                            </h3>
                        </div>
                        <div class="card-body pt-5">
                            <div class="row g-5 g-xl-10">
                                <div class="col-xl-4">
                                    <div class="card card-flush h-md-100">
                                        <div class="card-header pt-5">
                                            <div class="card-title d-flex flex-column">
                                                <div class="d-flex align-items-center">
                                                    <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2"><?= number_format((int)($user['tien'] ?? 0)); ?>đ</span>
                                                </div>
                                                <span class="text-gray-400 pt-1 fw-semibold fs-6">Số dư tài khoản</span>
                                            </div>
                                        </div>
                                        <div class="card-body pt-2">
                                            <div class="d-flex flex-stack">
                                                <span class="badge badge-light-success fs-7 fw-bold">Hoạt động</span>
                                            </div>
                                        </div>
                                    </div>
    </div>

                                <div class="col-xl-8">
                                    <div class="card card-flush h-md-100">
                                        <div class="card-header pt-5">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label fw-bold text-gray-800">Thông tin cá nhân</span>
                                            </h3>
                                            <div class="card-toolbar">
                                                <button type="button" class="btn btn-sm btn-light-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                                    Chỉnh sửa
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body pt-5">
                                            <div class="row g-5">
                                                <div class="col-sm-6">
                                                    <div class="d-flex flex-column">
                                                        <span class="text-gray-400 fw-semibold fs-7">Tên đăng nhập</span>
                                                        <span class="fw-bold fs-6 text-gray-800"><?= htmlspecialchars($user['taikhoan'] ?? ''); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="d-flex flex-column">
                                                        <span class="text-gray-400 fw-semibold fs-7">Email</span>
                                                        <span class="fw-bold fs-6 text-gray-800"><?= htmlspecialchars($user['email'] ?? ''); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="d-flex flex-column">
                                                        <span class="text-gray-400 fw-semibold fs-7">Ngày tạo</span>
                                                        <span class="fw-bold fs-6 text-gray-800"><?= htmlspecialchars($user['time'] ?? ''); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="d-flex flex-column">
                                                        <span class="text-gray-400 fw-semibold fs-7">Trạng thái</span>
                                                        <span class="badge badge-light-success fs-7 fw-bold">Hoạt động</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
            </div>
        </div>

                    <!-- Statistics Cards -->
                    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                        <div class="col-xl-3">
                            <div class="card card-flush h-md-100">
                                <div class="card-header pt-5">
                                    <div class="card-title d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2"><?= $waiting_orders; ?></span>
                                        </div>
                                        <span class="text-gray-400 pt-1 fw-semibold fs-6">Đơn hàng chờ</span>
                                    </div>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="d-flex flex-stack">
                                        <span class="badge badge-light-warning fs-7 fw-bold">Đang xử lý</span>
                                    </div>
                                </div>
            </div>
        </div>

                        <div class="col-xl-3">
                            <div class="card card-flush h-md-100">
                                <div class="card-header pt-5">
                                    <div class="card-title d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2"><?= $completed_orders; ?></span>
                                        </div>
                                        <span class="text-gray-400 pt-1 fw-semibold fs-6">Đơn hoàn thành</span>
                                    </div>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="d-flex flex-stack">
                                        <span class="badge badge-light-success fs-7 fw-bold">Thành công</span>
                                    </div>
                                </div>
            </div>
        </div>

                        <div class="col-xl-3">
                            <div class="card card-flush h-md-100">
                                <div class="card-header pt-5">
                                    <div class="card-title d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2"><?= $waiting_orders + $completed_orders; ?></span>
                                        </div>
                                        <span class="text-gray-400 pt-1 fw-semibold fs-6">Tổng tên miền</span>
                                    </div>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="d-flex flex-stack">
                                        <span class="badge badge-light-primary fs-7 fw-bold">Tất cả</span>
                                    </div>
            </div>
        </div>
    </div>

                        <div class="col-xl-3">
                            <div class="card card-flush h-md-100">
                                <div class="card-header pt-5">
                                    <div class="card-title d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2"><?= number_format((int)($user['tien'] ?? 0)); ?>đ</span>
                                        </div>
                                        <span class="text-gray-400 pt-1 fw-semibold fs-6">Số dư hiện tại</span>
                                    </div>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="d-flex flex-stack">
                                        <span class="badge badge-light-info fs-7 fw-bold">Có sẵn</span>
            </div>
            </div>
            </div>
            </div>
        </div>

        <!-- Recent Orders -->
                    <div class="card mb-5 mb-xl-10">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-800">Tên miền gần đây</span>
                                <span class="text-gray-400 mt-1 fw-semibold fs-6">Danh sách tên miền bạn đã mua</span>
            </h3>
                            <div class="card-toolbar">
                                <a href="/Manager" class="btn btn-sm btn-light-primary">Xem tất cả</a>
                            </div>
                        </div>
                        <div class="card-body pt-5">
                            <?php if(!empty($recent_orders)): ?>
                                <div class="table-responsive">
                                    <table class="table table-row-dashed align-middle gs-0 gy-4 my-0">
                                        <thead>
                                            <tr class="fs-7 fw-bold text-gray-400 border-bottom-0">
                                                <th class="p-0 pb-3 min-w-175px text-start">Tên miền</th>
                                                <th class="p-0 pb-3 min-w-100px text-start">MGD</th>
                                                <th class="p-0 pb-3 w-125px text-start pe-7">Trạng thái</th>
                                                <th class="p-0 pb-3 min-w-175px text-start">Thời gian</th>
                                                <th class="p-0 pb-3 min-w-175px text-start">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($recent_orders as $order): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="d-flex justify-content-start flex-column">
                                                                <span class="text-gray-800 fw-bold text-hover-primary fs-6"><?= htmlspecialchars($order['domain'] ?? ''); ?></span>
                        </div>
                    </div>
                                                    </td>
                                                    <td>
                                                        <span class="text-gray-800 fw-bold text-hover-primary fs-6">#<?= htmlspecialchars($order['mgd'] ?? ''); ?></span>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                        $statusClass = '';
                                                        $statusText = '';
                                                        switch($order['status']) {
                                                            case '0': 
                                                                $statusClass = 'badge-light-warning';
                                                                $statusText = 'Chờ xử lý';
                                                                break;
                                                            case '1': 
                                                                $statusClass = 'badge-light-success';
                                                                $statusText = 'Hoàn thành';
                                                                break;
                                                            case '3': 
                                                                $statusClass = 'badge-light-info';
                                                                $statusText = 'Đang cập nhật';
                                                                break;
                                                            case '4': 
                                                                $statusClass = 'badge-light-danger';
                                                                $statusText = 'Từ chối';
                                                                break;
                                                            default: 
                                                                $statusClass = 'badge-light-warning';
                                                                $statusText = 'Chờ xử lý';
                                                        }
                                                        ?>
                                                        <span class="badge <?= $statusClass; ?> fs-7 fw-bold"><?= $statusText; ?></span>
                                                    </td>
                                                    <td>
                                                        <span class="text-gray-800 fw-bold text-hover-primary fs-6"><?= htmlspecialchars($order['time'] ?? ''); ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if($order['status'] == '1'): ?>
                                                            <a href="/ManagesWhois/<?= htmlspecialchars($order['mgd'] ?? ''); ?>" class="btn btn-sm btn-light-primary">
                                                                <i class="fas fa-cog"></i> Quản lý
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-gray-400 fs-7">Chờ xử lý</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                    </div>
            <?php else: ?>
                                <div class="text-center py-10">
                                    <div class="text-gray-400 fs-6 mb-5">Chưa có tên miền nào</div>
                                    <a href="/" class="btn btn-primary">Mua tên miền ngay</a>
                                </div>
            <?php endif; ?>
        </div>
    </div>

                    <!-- Quick Actions -->
                    <div class="card mb-5 mb-xl-10">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-800">Thao tác nhanh</span>
                                <span class="text-gray-400 mt-1 fw-semibold fs-6">Các chức năng chính của tài khoản</span>
        </h3>
                        </div>
                        <div class="card-body pt-5">
                            <div class="row g-5">
                                <div class="col-xl-4">
                                    <div class="card card-flush h-md-100">
                                        <div class="card-body d-flex flex-column justify-content-between">
                                            <div class="mb-5">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="symbol symbol-50px me-3">
                                                        <div class="symbol-label bg-light-primary">
                                                            <i class="fas fa-globe text-primary fs-2x"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-gray-800 fw-bold">Quản lý tên miền</h4>
                                                        <div class="text-gray-400 fs-7">Xem và quản lý tên miền của bạn</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="/Manager" class="btn btn-primary w-100">Truy cập</a>
                                            </div>
        </div>
    </div>
</div>

                                <div class="col-xl-4">
                                    <div class="card card-flush h-md-100">
                                        <div class="card-body d-flex flex-column justify-content-between">
                                            <div class="mb-5">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="symbol symbol-50px me-3">
                                                        <div class="symbol-label bg-light-success">
                                                            <i class="fas fa-plus text-success fs-2x"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-gray-800 fw-bold">Nạp tiền</h4>
                                                        <div class="text-gray-400 fs-7">Nạp tiền vào tài khoản</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="/Recharge" class="btn btn-success w-100">Nạp ngay</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-4">
                                    <div class="card card-flush h-md-100">
                                        <div class="card-body d-flex flex-column justify-content-between">
                                            <div class="mb-5">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="symbol symbol-50px me-3">
                                                        <div class="symbol-label bg-light-info">
                                                            <i class="fas fa-shopping-cart text-info fs-2x"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-gray-800 fw-bold">Mua tên miền</h4>
                                                        <div class="text-gray-400 fs-7">Đặt mua tên miền mới</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="/" class="btn btn-info w-100">Mua ngay</a>
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
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Chỉnh sửa thông tin cá nhân</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="mb-5">
                        <label for="username" class="form-label">Tên đăng nhập</label>
                        <input type="text" class="form-control" id="username" name="username" 
                               value="<?= htmlspecialchars($user['taikhoan'] ?? ''); ?>" 
                               placeholder="Nhập tên đăng nhập" required>
                        <div class="form-text">Tên đăng nhập sẽ được cập nhật trong database</div>
                    </div>
                    
                    <div class="mb-5">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= htmlspecialchars($user['email'] ?? ''); ?>" 
                               placeholder="Nhập email" required>
                        <div class="form-text">Email sẽ được sử dụng để liên lạc và khôi phục tài khoản</div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Lưu ý:</strong> Thay đổi tên đăng nhập sẽ ảnh hưởng đến việc đăng nhập. Hãy ghi nhớ tên đăng nhập mới!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" name="update_profile" class="btn btn-primary">
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('../Config/Footer.php'); ?>