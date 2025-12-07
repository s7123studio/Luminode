<?php
/*
 * @Author: 7123
 * @Date: 2025-10-18 19:00:41
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-08 03:46:42
 */

namespace App\Controllers;

use Luminode\Core\Controller;
use App\Models\Post;
use Luminode\Core\Auth;
use Luminode\Core\Database;
use Luminode\Core\Response;
use Luminode\Core\Template;
use Luminode\Core\Validation\Validator;

class HomeController extends Controller
{
    // 构造函数已不再需要，父类会自动管理依赖

    public function index()
    {

        // 使用 ORM 来获取所有文章
        $posts = Post::all();

        // 将文章数据传递给视图
        return $this->view('home', ['posts' => $posts]);
    }

    public function profile(): Response
    {
        return $this->view('profile', [
            // $this->auth 属性会自动懒加载
            'user' => $this->auth->user()
        ], false);
    }
}