<?php

namespace App\Controllers;

use App\Models\RecentModel;
use App\Models\DetailRecentModel;

class ChatAI extends BaseController
{
    protected $recentModel;
    protected $detailModel;

    public function __construct()
    {
        $this->recentModel = new RecentModel();
        $this->detailModel = new DetailRecentModel();
    }
    public function index(): string
    {
        $data['menu'] = 'ai';
        $data['nama_user'] = session()->get('user_name');
        $data['history_recents_user'] = $this->recentModel->getRecentbyUser(session()->get('user_id'));
        $data['id_recents'] = null;
        $data['history'] = [];
        return
            view('template/header', $data) .
            view('app/chatai', $data) .
            view('template/footer');
    }
    // Halaman chat existing: /ai/{slug}
    public function loadChat($slug)
    {
        $recent = $this->recentModel->where('slug', $slug)->first();

        if (!$recent) {
            return redirect()->to('/ai');
        }

        // Validasi kepemilikan
        if ($recent['id_user'] != session()->get('user_id')) {
            return redirect()->to('/ai');
        }

        $history = $this->detailModel
            ->where('id_recents', $recent['id_recents'])
            ->orderBy('create_at', 'ASC')
            ->findAll();

        $data['menu']       = 'ai';
        $data['nama_user']  = session()->get('user_name');
        $data['id_recents'] = $recent['id_recents'];
        $data['slug']       = $slug;
        $data['history']    = $history;
        $data['history_recents_user'] = $this->recentModel->getRecentbyUser(session()->get('user_id'));
        // $data['data_chat'] = $this->detailModel->getDetail_byUser(session()->get('user_id'));
        return view('template/header', $data) .
            view('app/chatai', $data) .
            view('template/footer');
    }

    // Endpoint AJAX: kirim pesan
    public function sendMessage()
    {
        $idUser    = session()->get('user_id');
        $message   = $this->request->getPost('message');
        $idRecents = $this->request->getPost('id_recents'); // null jika chat baru
        $slug      = $this->request->getPost('slug');

        // === JIKA CHAT BARU ===
        if (empty($idRecents)) {
            $slug      = $this->recentModel->generateSlug();
            $judul = $this->generateTitle($message); // <-- pakai ChatGPT

            $idRecents = $this->recentModel->insert([
                'id_user'       => $idUser,
                'judul_recents' => $judul,
                'slug'          => $slug,
                'created_at'     => date('Y-m-d H:i:s'),
            ]);
        }

        // Simpan pesan user ke detail_recents
        $this->detailModel->insert([
            'id_recents'   => $idRecents,
            'conversation' => $message,
            'role'         => 1, // 1 untuk user
            'create_at'    => date('Y-m-d H:i:s'),
        ]);

        // === PANGGIL API AI ===
        // $aiResponse = $this->callAI($message, $idRecents);

        // Simpan response AI ke detail_recents
        $this->detailModel->insert([
            'id_recents'   => $idRecents,
            // 'conversation' => $aiResponse,
            'role'         => 0, // 0 untuk AI
            'create_at'    => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'success'    => true,
            // 'reply'      => $aiResponse,
            'id_recents' => $idRecents,
            'slug'       => $slug,
            'redirect'   => base_url('ai/' . $slug), // URL baru untuk redirect
        ]);
    }

    // Fungsi panggil API AI (sesuaikan dengan API yang kamu pakai)
    private function callAI($message, $idRecents)
    {
        $history = $this->detailModel
            ->where('id_recents', $idRecents)
            ->orderBy('create_at', 'ASC')
            ->findAll();

        $messages = [];
        foreach ($history as $h) {
            $messages[] = ['role' => $h['role'], 'content' => $h['conversation']];
        }

        $client = \Config\Services::curlrequest([
            'verify' => false
        ]);

        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'), // <-- ambil dari .env
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'model'    => 'gpt-4',
                'messages' => $messages,
            ],
        ]);

        $body = json_decode($response->getBody(), true);
        return $body['choices'][0]['message']['content'] ?? 'Maaf, terjadi kesalahan.';
    }
    private function generateTitle($message)
    {
        try {
            $client = \Config\Services::curlrequest([
                'verify' => false
            ]);

            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model'      => 'gpt-4',
                    'max_tokens' => 20,
                    'messages'   => [
                        [
                            'role'    => 'system',
                            'content' => 'Buat judul singkat maksimal 50 kata dalam Bahasa Indonesia dari pesan berikut. Balas hanya judulnya saja, tanpa tanda kutip, tanpa penjelasan.'
                        ],
                        [
                            'role'    => 'user',
                            'content' => $message
                        ]
                    ],
                ],
            ]);

            $body  = json_decode($response->getBody(), true);
            $judul = trim($body['choices'][0]['message']['content'] ?? '');

            // Fallback jika kosong
            return !empty($judul) ? $judul : mb_substr($message, 0, 50);
        } catch (\Exception $e) {
            // Fallback jika API error
            return mb_substr($message, 0, 50);
        }
    }
}
