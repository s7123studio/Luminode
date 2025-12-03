<?php
/*
 * @Author: 7123
 * @Date: 2025-11-15 19:47:02
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:26:52
 */

namespace App\Controllers;

use App\Models\User;
use Luminode\Core\Auth;
use Luminode\Core\Controller;
use Luminode\Core\Database;
use Luminode\Core\Response;
use Luminode\Core\Template;
use Luminode\Core\Validation\Validator;

class AuthController extends Controller
{
    protected Auth $auth;

    public function __construct(Database $db, Template $template, Validator $validator, Auth $auth)
    {
        parent::__construct($db, $template, $validator);
        $this->auth = $auth;
    }

    public function showRegistrationForm(): Response
    {
        return $this->view('register', [], false);
    }

    public function register(): Response
    {
        $data = $this->request['input'];
        $rules = [
            'username' => 'required|alpha_num|min:4|max:20',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ];

        if (!$this->validate($data, $rules)) {
            // 在实际应用中，您通常会带着错误信息重定向回去。
            // 在此演示中，我们仅显示简单的 JSON 错误。
            return $this->json(['success' => false, 'errors' => $this->validationErrors()]);
        }

        // 在实际应用中，您还应该检查用户名/邮箱是否唯一。
        $user = new User();
        $user->fill([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);
        $user->save();

        // 用户注册后自动登录
        $this->auth->login($user->toArray());

        return $this->redirect('/profile');
    }

    public function showLoginForm(): Response
    {
        return $this->view('login', [], false);
    }

    public function login(): Response
    {
        $data = $this->request['input'];
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($data, $rules)) {
            return $this->json(['success' => false, 'errors' => $this->validationErrors()]);
        }

        if ($this->auth->attempt($data['username'], $data['password'])) {
            return $this->redirect('/profile');
        }

        // 在实际应用中，您通常会带着错误信息重定向回去。
        return $this->json(['success' => false, 'message' => '用户名或密码无效']);
    }

    public function logout(): Response
    {
        $this->auth->logout();
        return $this->redirect('/login');
    }
}
