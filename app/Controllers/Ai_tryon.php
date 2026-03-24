<?php

namespace App\Controllers;

class Ai_tryon extends BaseController
{
    public function index(): string
    {
        $data['menu'] = 'tryon';
        $data['nama_user'] = session()->get('user_name');
        return
            view('template/header', $data) .
            view('app/ai_tryon', $data) .
            view('template/footer');
    }
}
