<?php

namespace Luminode\Core;

use Luminode\Core\Database;
use Exception;

// 定义一个名为 Auth 的类，用于处理用户认证
class Auth {
    // 定义一个私有静态变量 $db，用于存储数据库连接实例
    private static $db;

    // 静态方法 setDatabase，用于设置数据库连接实例
    public static function setDatabase(Database $db) {
        self::$db = $db; // 将传入的数据库实例赋值给静态变量 $db
    }

    // 静态方法 attempt，用于尝试用户登录
    public static function attempt($username, $password) {
        // 检查用户名或密码是否为空，如果为空则返回 false
        if (empty($username) || empty($password)) {
            return false;
        }

        // 如果数据库连接实例未设置，则创建一个新的 Database 对象
        if (!self::$db) {
            self::$db = new Database();
        }

        // 使用 try-catch 结构来处理可能的数据库操作异常
        try {
            // 从数据库中获取与用户名匹配的用户记录
            $user = self::$db->fetch("SELECT * FROM users WHERE username = ?", [$username]);

            // 检查用户是否存在且密码是否正确
            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);

                if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    self::$db->execute("UPDATE users SET password = ? WHERE id = ?", [$newHash, $user['id']]);
                }

                $_SESSION['user'] = $user;
                return true;
            }
        } catch (Exception $e) {
            // 记录错误或返回 false
            return false;
        }

        return false;
    }

    public static function check() {
        return isset($_SESSION['user']);
    }

    public static function logout() {
        $_SESSION = array();
        session_destroy();
    }
}