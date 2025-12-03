<?php
/*
 * @Author: 7123
 * @Date: 2025-10-19 18:49:07
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:26:39
 */

namespace App\Controllers;

use Luminode\Core\Controller;
use Luminode\Core\Response;

class TestController extends Controller
{
    public function index()
    {
        return $this->view('test');
    }

    public function showCsrfForm(): Response
    {
        return $this->view('form', [], false); // Render without the main layout for this demo
    }

    public function handleCsrfForm(): Response
    {
        $data = $this->request['input']['some_data'] ?? 'No data received';
        return $this->json([
            'success' => true,
            'message' => 'CSRF token is valid! Form processed successfully.',
            'data_received' => $data
        ]);
    }
}
