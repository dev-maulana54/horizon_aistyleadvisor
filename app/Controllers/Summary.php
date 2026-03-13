<?php

namespace App\Controllers;

class Summary extends BaseController
{
    public function index(): string
    {
        $data['menu'] = 'home';
        $data['nama_user'] = session()->get('user_name');
        return
            view('template/header', $data) .
            view('app/summary', $data) .
            view('template/footer');
    }
}
