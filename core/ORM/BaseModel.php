<?php

// 定义一个抽象基类 BaseModel，用于其他模型类继承
abstract class BaseModel {
    // 定义一个静态保护属性 $table，用于存储数据库表名
    protected static $table;
    // 定义一个静态保护属性 $primaryKey，默认值为 'id'，用于存储表的主键字段名
    protected static $primaryKey = 'id';

    // 定义一个静态方法 find，用于根据主键 ID 查找记录
    public static function find($id) {
        // 创建一个 Database 对象用于数据库操作
        $db = new Database();
        // 执行 SQL 查询，获取指定主键 ID 的记录
        // 使用 static::$table 和 static::$primaryKey 动态获取表名和主键字段名
        return $db->fetch("SELECT * FROM ".static::$table." WHERE ".static::$primaryKey." = ?", [$id]);
    }

    // 定义一个静态方法 create，用于插入新记录
    public static function create($data) {
        // 获取数据数组的键名，即表中的列名，并用逗号分隔
        $columns = implode(', ', array_keys($data));
        // 根据数据数组的长度，生成相应数量的占位符 '?'
        // 并用逗号分隔
        $values = implode(', ', array_fill(0, count($data), '?'));
        // 创建一个 Database 对象用于数据库操作
        $db = new Database();
        // 执行 SQL 插入操作，将数据插入到指定的表中
        // 使用 static::$table 动态获取表名
        $db->query("INSERT INTO ".static::$table." ($columns) VALUES ($values)", array_values($data));
        // 返回插入记录的主键 ID
        return $db->lastInsertId();
    }

    // 定义一个公共方法 update，用于更新记录
    // 具体实现由子类完成
    public function update($data) {
        // 更新逻辑实现
    }
}