<?php

// 定义HomeController类，用于处理主页相关的请求
class HomeController {
    // index方法用于显示主页
    public function index() {
        // 创建Database对象，用于数据库操作
        $db = new Database();
        // 从数据库中获取所有帖子，并按创建时间降序排列
        $posts = $db->fetchAll("SELECT * FROM posts ORDER BY created_at DESC");
        // 包含主页视图文件，将$posts变量传递给视图
        include '../app/views/home.php';
    }
    
    // showPost方法用于显示单个帖子的详细内容
    public function showPost($id) {
        // 创建Database对象，用于数据库操作
        $db = new Database();
        // 从数据库中获取指定ID的帖子
        $post = $db->fetch("SELECT * FROM posts WHERE id = ?", [$id]);
        // 包含帖子视图文件，将$post变量传递给视图
        include '../app/views/post.php';
    }
}