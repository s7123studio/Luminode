<?php

// 定义一个名为 Database 的类
class Database {
    // 私有属性 $pdo 用于存储 PDO 对象
    private $pdo;
    
    // 构造函数，用于初始化数据库连接
    public function __construct() {
        // 引入数据库配置文件
        require '../config/database.php';
        // 创建一个新的 PDO 对象，用于与数据库进行交互
        try {
            $this->pdo = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']}",
                $config['username'],
                $config['password']
            );
        } catch (PDOException $e) {
            die("数据库连接失败: " . $e->getMessage());
        }
    }
    
    // 执行 SQL 查询的公共方法
    public function query($sql, $params = []) {
        // 准备 SQL 语句
        $stmt = $this->pdo->prepare($sql);
        // 执行 SQL 语句，并传入参数
        $stmt->execute($params);
        // 返回 PDOStatement 对象
        return $stmt;
    }
    
    // 获取单条查询结果的公共方法
    public function fetch($sql, $params = []) {
        // 调用 query 方法执行 SQL 语句，并获取结果集
        return $this->query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }
    
    // 获取多条查询结果的公共方法
    public function fetchAll($sql, $params = []) {
        // 调用 query 方法执行 SQL 语句，并获取所有结果集
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
}