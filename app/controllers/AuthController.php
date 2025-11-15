<?php

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
            // In a real app, you'd redirect back with errors.
            // For this demo, we'll just show a simple error.
            return $this->json(['success' => false, 'errors' => $this->validationErrors()]);
        }

        // In a real app, you should also check if username/email is unique.
        $user = new User();
        $user->fill([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);
        $user->save();

        // Log the user in
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

        // In a real app, you'd redirect back with an error message.
        return $this->json(['success' => false, 'message' => 'Invalid credentials']);
    }

    public function logout(): Response
    {
        $this->auth->logout();
        return $this->redirect('/login');
    }
}
