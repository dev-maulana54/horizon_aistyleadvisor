<?php

namespace App\Controllers;

use App\Models\DtBodyShape;
use App\Models\StylesModel;
use App\Models\TypeWardrobe;

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

        $body_shape_model = new DtBodyShape();
        $styles_model = new StylesModel();
        $wardrobe = new TypeWardrobe();
        $data['menu'] = 'settings_profile';
        $data['title'] = 'Settings Profile';
        $data['nama_user'] = session()->get('user_name');
        $data['is_premium'] = session()->get('is_premium');
        $data['body_shape'] = $body_shape_model->GetAllbodyShape();
        $data['styles'] = $styles_model->getAllStyles();
        $data['type_wardrobe'] = $wardrobe->getAll();
        return
            view('template/header', $data) .
            view('app/settings', $data) .
            view('template/footer', $data);
    }
}
