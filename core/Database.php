<?php

class Database {
    private $pdo;
    private $inTransaction = false;
    private $queryLog = [];
    
    public function __construct() {
        require '../config/database.php';
        try {
            $this->pdo = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']}",
                $config['username'],
                $config['password'],
                $config['options'] ?? []
            );
        } catch (PDOException $e) {
            throw new RuntimeException("数据库连接失败: " . $e->getMessage());
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