<?php

// 定义X2024YR4类，用于处理主页相关的请求
class X2024YR4 {
    // index方法用于显示主页
    public function index() {
        $db = new Database();
        
        include '../app/views/2024YR4/index.php';
    }
}