<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{

    public function login()
    {
        // Cek apakah session isLoggedIn tidak ada atau tidak bernilai true, maka redirect ke login
        if (session()->get('isLoggedIn') && session()->get('isLoggedIn') === true) {
            return redirect()->to(base_url('summary'));
        }
        $data['menu'] = 'login';
        return view('app/login', $data);
    }

    public function reg()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'   => 'error',
                'message'  => 'Invalid request',

            ]);
        }

        $rules = [
            'name' => [
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => 'Nama wajib diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                ],
            ],
            'email' => [
                'rules'  => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required'    => 'Email wajib diisi',
                    'valid_email' => 'Format email tidak valid',
                    'is_unique'   => 'Email sudah terdaftar',
                ],
            ],
            'password' => [
                'rules'  => 'required|min_length[8]',
                'errors' => [
                    'required'   => 'Password wajib diisi',
                    'min_length' => 'Password minimal 8 karakter',
                ],
            ],
            'password_confirm' => [
                'rules'  => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password wajib diisi',
                    'matches'  => 'Konfirmasi password tidak sama',
                ],
            ],
            'terms' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Kamu harus menyetujui syarat & ketentuan',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return $this->response->setJSON([
                'status'   => 'validation_error',
                'errors'   => $this->validator->getErrors(),

            ]);
        }

        $userModel = new UserModel();

        $saveData = [
            'nama'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => '2',
            'is_active' => 1,
            'is_premium' => 1
        ];

        // Memanggil fungsi di model
        if ($userModel->registerUser($saveData)) {
            return $this->response->setJSON([
                'status'   => 'success',
                'message'  => 'Registrasi berhasil',
            ]);
        }
    }


    public function processLogin()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid request',
            ]);
        }

        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'validation_error',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $this->request->getPost('email'))->first();

        if (! $user || ! password_verify($this->request->getPost('password'), $user['password'])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Email atau password salah',
            ]);
        }

        // Cek status aktif user
        if ((int) $user['is_active'] !== 1) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Akun Anda tidak aktif. Hubungi administrator untuk informasi lebih lanjut.',
            ]);
        }

        session()->set([
            'user_id'    => $user['id'],
            'user_name'  => $user['nama'],
            'role'  => $user['role'],
            'is_active'  => $user['is_active'],
            'is_premium'  => $user['is_premium'],

            'isLoggedIn' => true,
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Login berhasil',
        ]);
    }
}
