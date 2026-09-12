<?php


declare(strict_types=1);

require_once __DIR__ . '/config.php';

if (!defined('DB_HOST')) {
    define('DB_HOST', '127.0.0.1');
    define('DB_PORT', 3306);
    define('DB_NAME', 'hostel_management');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_CHARSET', 'utf8mb4');
}


function getDBConnection(): PDO {
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', DB_HOST, DB_PORT, DB_NAME, DB_CHARSET);
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET,
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        if ($e->getCode() === 1049 || str_contains($e->getMessage(), 'Unknown database')) {
            try {
                $serverDsn = sprintf('mysql:host=%s;port=%d;charset=%s', DB_HOST, DB_PORT, DB_CHARSET);
                $serverPdo = new PDO($serverDsn, DB_USER, DB_PASS, $options);
                $serverPdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

                $schemaFile = __DIR__ . '/../database/hostel_management.sql';
                $seedFile = __DIR__ . '/../database/seed.sql';

                if (file_exists($schemaFile)) {
                    $serverPdo->exec(file_get_contents($schemaFile));
                }
                if (file_exists($seedFile)) {
                    $serverPdo->exec(file_get_contents($seedFile));
                }

                $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
                return $pdo;
            } catch (PDOException $initEx) {
                die('Database initialization failed: ' . htmlspecialchars($initEx->getMessage()));
            }
        }
        die('Database connection error: ' . htmlspecialchars($e->getMessage()));
    }

    return $pdo;
}

$pdo = getDBConnection();
