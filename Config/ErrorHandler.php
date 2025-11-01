<?php

/**
 * ErrorHandler - Xử lý lỗi và exception toàn diện
 * 
 * @author DAM THANH VU
 * @version 1.0
 */
class ErrorHandler {
    private static $instance = null;
    private $logFile;
    private $debugMode;

    private function __construct() {
        $this->logFile = __DIR__ . '/../logs/error.log';
        $this->debugMode = true; // Có thể config từ database
        $this->createLogDirectory();
        $this->setErrorHandlers();
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function createLogDirectory(): void {
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }

    private function setErrorHandlers(): void {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    public function handleError(int $severity, string $message, string $file, int $line): bool {
        if (!(error_reporting() & $severity)) {
            return false;
        }

        $error = [
            'type' => 'Error',
            'severity' => $severity,
            'message' => $message,
            'file' => $file,
            'line' => $line,
            'time' => date('Y-m-d H:i:s'),
            'url' => $_SERVER['REQUEST_URI'] ?? '',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? ''
        ];

        $this->logError($error);
        $this->displayError($error);
        
        return true;
    }

    public function handleException(Throwable $exception): void {
        $error = [
            'type' => 'Exception',
            'class' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'time' => date('Y-m-d H:i:s'),
            'url' => $_SERVER['REQUEST_URI'] ?? '',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? ''
        ];

        $this->logError($error);
        $this->displayError($error);
    }

    public function handleShutdown(): void {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
            $this->handleError($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    private function logError(array $error): void {
        $logEntry = sprintf(
            "[%s] %s: %s in %s:%d\nURL: %s\nIP: %s\n%s\n\n",
            $error['time'],
            $error['type'],
            $error['message'],
            $error['file'],
            $error['line'],
            $error['url'],
            $error['ip'],
            isset($error['trace']) ? "Stack trace:\n" . $error['trace'] : ''
        );

        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }

    private function displayError(array $error): void {
        if (!$this->debugMode) {
            // Trong production, chỉ hiển thị lỗi chung
            echo '<div class="alert alert-danger">Đã xảy ra lỗi. Vui lòng thử lại sau.</div>';
            return;
        }

        // Trong development, hiển thị chi tiết lỗi
        echo '<div class="alert alert-danger">';
        echo '<h4>Lỗi: ' . htmlspecialchars($error['message']) . '</h4>';
        echo '<p><strong>File:</strong> ' . htmlspecialchars($error['file']) . '</p>';
        echo '<p><strong>Line:</strong> ' . $error['line'] . '</p>';
        echo '<p><strong>Time:</strong> ' . $error['time'] . '</p>';
        
        if (isset($error['trace'])) {
            echo '<h5>Stack Trace:</h5>';
            echo '<pre>' . htmlspecialchars($error['trace']) . '</pre>';
        }
        echo '</div>';
    }

    public function logInfo(string $message, array $context = []): void {
        $this->log('INFO', $message, $context);
    }

    public function logWarning(string $message, array $context = []): void {
        $this->log('WARNING', $message, $context);
    }

    public function logErrorMessage(string $message, array $context = []): void {
        $this->log('ERROR', $message, $context);
    }

    private function log(string $level, string $message, array $context = []): void {
        $logEntry = sprintf(
            "[%s] %s: %s %s\n",
            date('Y-m-d H:i:s'),
            $level,
            $message,
            !empty($context) ? json_encode($context) : ''
        );

        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
}

?>
