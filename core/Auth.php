<?php

// 定义一个名为 Auth 的类，用于处理用户认证相关的功能
class Auth {
    // 静态方法 attempt 用于尝试用户登录
    public static function attempt($username, $password) {
        // 创建一个 Database 对象，用于数据库操作
        $db = new Database();
        // 从数据库中查询用户信息，条件是用户名等于传入的 $username
        $user = $db->fetch("SELECT * FROM users WHERE username = ?", [$username]);
        
        // 检查用户是否存在且密码是否正确
        if ($user && password_verify($password, $user['password'])) {
            // 如果验证成功，将用户信息存储到会话中
            $_SESSION['user'] = $user;
            // 返回 true 表示登录成功
            return true;
        }
        // 如果验证失败，返回 false
        return false;
    }

    // 静态方法 check 用于检查用户是否已登录
    public static function check() {
        // 检查会话中是否存在用户信息
        return isset($_SESSION['user']);
    }
    
    // 静态方法 logout 用于用户登出
    public static function logout() {
        // 销毁当前会话
        session_destroy();
    }
}