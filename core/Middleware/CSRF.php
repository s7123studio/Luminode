<?php

// 定义一个名为CSRF的类，用于处理跨站请求伪造（CSRF）保护
class CSRF {
    // 生成并返回一个CSRF令牌
    public static function generateToken() {
        // 检查会话中是否已经有csrf_token，如果没有则生成一个新的
        if (empty($_SESSION['csrf_token'])) {
            // 使用random_bytes生成32字节的安全随机数，然后转换为十六进制字符串
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        // 返回会话中的csrf_token
        return $_SESSION['csrf_token'];
    }

    // 验证CSRF令牌
    public static function validate() {
        // 检查请求方法是否为POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 从POST请求中获取csrf_token，如果不存在则默认为空字符串
            $token = $_POST['csrf_token'] ?? '';
            // 使用hash_equals安全比较会话中的csrf_token和请求中的csrf_token
            if (!hash_equals($_SESSION['csrf_token'], $token)) {
                // 如果令牌不匹配，抛出一个异常
                throw new Exception("CSRF token validation failed");
            }
        }
    }
}