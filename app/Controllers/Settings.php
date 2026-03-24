<?php

namespace App\Controllers;

use App\Models\DtBodyShape;
use App\Models\BodyShapeUserModel;
use App\Models\StylesModel;
use App\Models\StylesPreferenceModel;
use App\Models\TypeWardrobe;
use App\Models\Wardrobe;

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
        $body_shape_user_model = new BodyShapeUserModel();
        $styles_model = new StylesModel();
        $styles_user_model = new StylesPreferenceModel();
        $type_wardrobe = new TypeWardrobe();
        $wardrobe = new Wardrobe();
        $data['menu'] = 'settings_profile';
        $data['title'] = 'Settings Profile';
        $data['nama_user'] = session()->get('user_name');
        $data['is_premium'] = session()->get('is_premium');
        $data['body_shape'] = $body_shape_model->GetAllbodyShape();
        $data['wardrobe'] = $wardrobe->getWardrobeByUserID(session()->get('user_id'));
        $data['styles'] = $styles_model->getAllStyles();
        $data['type_wardrobe'] = $type_wardrobe->getAll();


        // data setting user 
        $data['id_body_shape_user'] = $body_shape_user_model->getId_body_shape_user(session()->get('user_id'));
        $data['id_styles_user'] = $styles_user_model->get_style_preferences_user(session()->get('user_id'));
        return
            view('template/header', $data) .
            view('app/settings', $data) .
            view('template/footer', $data);
    }
}
