<?php

namespace Luminode\Core;

use Luminode\Core\Validation\Validator;

abstract class Controller {
    protected Database $db;
    protected Template $template;
    protected Validator $validator;
    protected array $request;

    public function __construct(Database $db, Template $template, Validator $validator)
    {
        $this->db = $db;
        $this->template = $template;
        $this->validator = $validator;
        $this->request = $this->parseRequest();
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

    protected function view(string $viewPath, array $data = [], bool $useTemplate = true)
    {
        if ($useTemplate) {
            echo $this->template->render($viewPath, $data);
        } else {
            extract($data);
            require APP_ROOT . "/resources/views/{$viewPath}.php";
        }
    }

    protected function json(array $data, int $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $url, int $statusCode = 302)
    {
        header("Location: {$url}", true, $statusCode);
        exit;
    }

    protected function validate(array $data, array $rules): bool
    {
        return $this->validator->validate($data, $rules);
    }

    protected function validationErrors(): array
    {
        return $this->validator->errors();
    }

    protected function setLayout(string $layout)
    {
        $this->template->extend($layout);
    }
}
