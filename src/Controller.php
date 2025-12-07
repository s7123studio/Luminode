<?php
/*
 * @Author: 7123
 * @Date: 2025-03-09 22:43:28
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:20:36
 */

namespace Luminode\Core;

use Luminode\Core\Validation\Validator;
use Luminode\Core\Response;

/**
 * @property Database $db
 * @property Template $template
 * @property Validator $validator
 * @property Auth $auth
 */
abstract class Controller {

    protected array $request;
    private array $services = [];

    public function __construct()
    {
        $this->request = $this->parseRequest();
    }

    public function __get($name)
    {
        if (isset($this->services[$name])) {
            return $this->services[$name];
        }

        switch ($name) {
            case 'db':
                return $this->services['db'] = app(Database::class);
            case 'template':
                return $this->services['template'] = app(Template::class);
            case 'validator':
                return $this->services['validator'] = app(Validator::class);
            case 'auth':
                return $this->services['auth'] = app(Auth::class);
        }

        return null;
    }

    protected function parseRequest(): array
    {
        return [
            'method' => $_SERVER['REQUEST_METHOD'],
            'path' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
            'query' => $_GET,
            'input' => json_decode(file_get_contents('php://input'), true) ?? $_POST
        ];
    }

    protected function view(string $viewPath, array $data = []): Response
    {
        // 使用全局 helper 或懒加载的 template 服务
        $content = $this->template->render($viewPath, $data);
        return new Response($content);
    }

    protected function json(array $data, int $statusCode = 200): Response
    {
        return new Response(json_encode($data), $statusCode, ['Content-Type' => 'application/json']);
    }

    protected function redirect(string $url, int $statusCode = 302): Response
    {
        return new Response('', $statusCode, ['Location' => $url]);
    }

    protected function validate(array $data, array $rules): bool
    {
        return $this->validator->validate($data, $rules);
    }

    protected function validationErrors(): array
    {
        return $this->validator->errors();
    }
}
