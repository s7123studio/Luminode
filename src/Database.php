<?php
/*
 * @Author: 7123
 * @Date: 2025-03-09 22:43:28
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:20:42
 */

namespace Luminode\Core;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Database 类
 * 一个简单的 PDO 包装器，其生命周期由依赖注入容器管理
 */
class Database {
    public $pdo;
    private $inTransaction = false;
    private $queryLog = [];

    public function __construct(Config $configService) {
        $default = $configService->get('database.default', 'mysql');
        $config = $configService->get("database.connections.{$default}");

        if (!$config) {
            throw new RuntimeException("Database configuration for [{$default}] not found.");
        }

        try {
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
            $this->pdo = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
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