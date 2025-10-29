<?php

/**
 * RepositoryFactory - Factory class tạo các Repository instances
 * 
 * Chức năng chính:
 * - Tạo các Repository objects với cùng 1 mysqli connection
 * - Đảm bảo consistency trong việc sử dụng database
 * - Centralized factory pattern cho repositories
 * 
 * @author DAM THANH VU
 * @version 1.0
 */
class RepositoryFactory {
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function users(): UserRepository
    {
        return new UserRepository($this->mysqli);
    }

    public function history(): HistoryRepository
    {
        return new HistoryRepository($this->mysqli);
    }

    public function cards(): CardRepository
    {
        return new CardRepository($this->mysqli);
    }

    public function domains(): DomainRepository
    {
        return new DomainRepository($this->mysqli);
    }

    public function settings(): SettingsRepository
    {
        return new SettingsRepository($this->mysqli);
    }
}

?>


