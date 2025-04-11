<?php

abstract class Controller {
    protected $db;
    protected $request;
    protected $template;
    
    public function __construct() {
        $this->db = new Database();
        $this->request = $this->parseRequest();
        $this->template = new Template(APP_ROOT . '/app/views');
    }
    
    protected function parseRequest() {
        return [
            'method' => $_SERVER['REQUEST_METHOD'],
            'path' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
            'query' => $_GET,
            'input' => json_decode(file_get_contents('php://input'), true) ?? $_POST
        ];
    }
    
    protected function view($viewPath, $data = [], $useTemplate = true) {
        if ($useTemplate) {
            echo $this->template->render($viewPath, $data);
        } else {
            // 保持原生PHP视图的兼容性
            extract($data);
            require APP_ROOT . "/app/views/{$viewPath}.php";
        }
    }
    
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($url, $statusCode = 302) {
        header("Location: {$url}", true, $statusCode);
        exit;
    }
    
    protected function validate($data, $rules) {
        $validator = new Validator();
        return $validator->validate($data, $rules);
    }
    
    protected function setLayout($layout) {
        $this->template->extend($layout);
    }
}
