<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Api extends Controller
{

    public function reg()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'   => 'error',
                'message'  => 'Invalid request',
                'csrfHash' => csrf_hash(),
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
                'csrfHash' => csrf_hash(),
            ]);
        }

        $userModel = new UserModel();

        $userModel->insert([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ]);

        return $this->response->setJSON([
            'status'   => 'success',
            'message'  => 'Registrasi berhasil',
            'csrfHash' => csrf_hash(),
        ]);
    }

    public function loginForm()
    {
        return view('auth/login');
    }

    public function processLogin()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'   => 'error',
                'message'  => 'Invalid request',
                'csrfHash' => csrf_hash(),
            ]);
        }

        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setJSON([
                'status'   => 'validation_error',
                'errors'   => $this->validator->getErrors(),
                'csrfHash' => csrf_hash(),
            ]);
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $this->request->getPost('email'))->first();

        if (! $user || ! password_verify($this->request->getPost('password'), $user['password'])) {
            return $this->response->setJSON([
                'status'   => 'error',
                'message'  => 'Email atau password salah',
                'csrfHash' => csrf_hash(),
            ]);
        }

        session()->set([
            'user_id'   => $user['id'],
            'user_name' => $user['name'],
            'logged_in' => true,
        ]);

        return $this->response->setJSON([
            'status'   => 'success',
            'message'  => 'Login berhasil',
            'csrfHash' => csrf_hash(),
        ]);
    }
}
