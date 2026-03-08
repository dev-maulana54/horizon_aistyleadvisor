<?php

namespace App\Controllers;

class Settings extends BaseController
{
    public function index(): string
    {
        $data['menu'] = 'settings_profile';
        return
            view('template/header', $data) .
            view('app/chatai', $data) .
            view('template/footer');
    }
}
