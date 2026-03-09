<?php

namespace App\Controllers;

class Settings extends BaseController
{

    public function __construct()
    {
        // Cek apakah session isLoggedIn tidak ada atau tidak bernilai true, maka redirect ke login
        if (!session()->get('isLoggedIn') || session()->get('isLoggedIn') !== true) {
            header('Location: ' . base_url('user/login'));
            exit(); // ✅ WAJIB pakai exit() agar script berhenti
        }
    }
    public function index(): string
    {
        $data['menu'] = 'settings_profile';
        return
            view('template/header', $data) .
            view('app/settings', $data) .
            view('template/footer');
    }
}
