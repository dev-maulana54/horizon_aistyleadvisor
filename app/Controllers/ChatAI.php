<?php

namespace App\Controllers;

class ChatAI extends BaseController
{
    public function index(): string
    {
        $data['menu'] = 'ai';
        return
            view('template/header', $data) .
            view('app/chatai', $data) .
            view('template/footer');
    }
}
