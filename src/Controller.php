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



    protected function view(string $viewPath, array $data = [], bool $useTemplate = true): Response

    {

        if ($useTemplate) {

            $content = $this->template->render($viewPath, $data);

            return new Response($content);

        }



        ob_start();

        extract($data);

        require APP_ROOT . "/resources/views/{$viewPath}.php";

        $content = ob_get_clean();

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



    protected function setLayout(string $layout)

    {

        $this->template->extend($layout);

    }

}
