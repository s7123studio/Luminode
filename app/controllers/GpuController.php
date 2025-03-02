<?php

// 定义GpuController类，用于处理主页相关的请求
class GpuController {
    // index方法用于显示主页
    public function index() {

        include '../app/views/gpu/index.php';
    }
}