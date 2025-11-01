<?php

/**
 * DatabaseConnection - Singleton class quản lý kết nối database
 * 
 * Chức năng chính:
 * - Đảm bảo chỉ có 1 kết nối database duy nhất
 * - Tự động cấu hình charset UTF8
 * - Quản lý lifecycle của kết nối
 * 
 * @author DAM THANH VU
 * @version 1.0
 */
class DatabaseConnection {
    private static ?mysqli $instance = null;

    public static function getInstance(): mysqli
    {
        if (self::$instance === null) {
            $servername = getenv('DB_HOST') ?: 'localhost';
            $database   = getenv('DB_NAME') ?: 'tenmien';
            $username   = getenv('DB_USER') ?: 'root';
            $password   = getenv('DB_PASS') ?: '';

            $mysqli = mysqli_connect($servername, $username, $password, $database);
            if (!$mysqli) {
                die('Status!: Error Connect Database!');
            }
            mysqli_query($mysqli, "SET NAMES 'UTF8'");
            self::$instance = $mysqli;
        }
        return self::$instance;
    }
}

?>

