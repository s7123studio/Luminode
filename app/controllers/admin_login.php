<?php

require_once '../core/Middleware/CSRF.php';
CSRF::validate(); // 验证CSRF令牌
$username = $_POST['username'];
$password = $_POST['password'];

// 尝试登录
if (Auth::attempt($username, $password)) {
    // 登录成功，重定向到设置页面
    $_SESSION['user'] = $username;
    header('Location: /admin/');
    exit;
} else {
    // 登录失败，重定向回登录页面
    header('Location: /admin');
    exit;
    }