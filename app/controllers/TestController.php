<?php

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
