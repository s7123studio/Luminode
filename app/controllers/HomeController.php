<?php
/*
 * @Author: 7123
 * @Date: 2025-10-18 19:00:41
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:26:45
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
    protected Auth $auth;

    public function __construct(Database $db, Template $template, Validator $validator, Auth $auth)
    {
        parent::__construct($db, $template, $validator);
        $this->auth = $auth;
    }

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
            'user' => $this->auth->user()
        ], false);
    }
}