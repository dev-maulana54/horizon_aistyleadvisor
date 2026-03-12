<?php

namespace App\Controllers;

class Settings extends BaseController
{

    // public function __construct()
    // {
    //     // Cek apakah session isLoggedIn tidak ada atau tidak bernilai true, maka redirect ke login

    // }
    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('isLoggedIn') !== true) {
            return redirect()->to(base_url('user/login'));
        }
        $data['menu'] = 'settings_profile';
        return
            view('template/header', $data) .
            view('app/settings', $data) .
            view('template/footer');
    }
}
