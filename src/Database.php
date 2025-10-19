<?php

namespace Luminode\Core;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Class Database
 * A simple PDO wrapper. Its lifecycle is managed by the DI container.
 */
class Database {
    public $pdo;
    private $inTransaction = false;
    private $queryLog = [];

    public function __construct() {
        // The APP_ROOT constant is expected to be defined in the bootstrap process.
        $configPath = APP_ROOT . '/config/database.php';
        if (!file_exists($configPath)) {
            throw new RuntimeException("Database configuration file not found.");
        }
        require $configPath;

        try {
            $this->pdo = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']}",
                $config['username'],
                $config['password'],
                $config['options'] ?? []
            );
        } catch (PDOException $e) {
            throw new RuntimeException("Database connection failed: " . $e->getMessage());
        }
    }

    public function beginTransaction() {
        $this->inTransaction = true;
        return $this->pdo->beginTransaction();
    }

    public function commit() {
        $this->inTransaction = false;
        return $this->pdo->commit();
    }

    public function rollBack() {
        $this->inTransaction = false;
        return $this->pdo->rollBack();
    }

    public function inTransaction() {
        return $this->inTransaction;
    }

    public function query($sql, $params = []) {
        $start = microtime(true);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $this->queryLog[] = [
            'sql' => $sql,
            'params' => $params,
            'time' => microtime(true) - $start
        ];

        return $stmt;
    }

    public function fetch($sql, $params = []) {
        return $this->query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    public function getQueryLog() {
        return $this->queryLog;
    }
}