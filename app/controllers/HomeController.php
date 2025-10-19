<?php

namespace App\Controllers;

use Luminode\Core\Controller;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        // 使用 ORM 来获取所有文章
        $posts = Post::all();

        // 将文章数据传递给视图
        return $this->view('home', ['posts' => $posts]);
    }
}