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
        $data['menu']       = 'ai';
        $data['nama_user']  = session()->get('user_name');
        $data['id_recents'] = null;
        $data['slug']       = null;
        $data['history']    = [];
        $data['history_recents_user'] = $this->recentModel->getRecentbyUser(session()->get('user_id'));
        $data['user_data_json']       = $this->getUserDataJson();

        return view('template/header', $data)
            . view('app/chatai', $data)
            . view('template/footer');
    }

    public function loadChat($slug)
    {
        $recent = $this->recentModel->where('slug', $slug)->first();
        if (!$recent) {
            // AJAX request → return JSON error
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Chat tidak ditemukan.']);
            }
            return redirect()->to('/ai');
        }

        $history = $this->detailModel
            ->where('id_recents', $recent['id_recents'])
            ->orderBy('create_at', 'ASC')
            ->findAll();

        // ── AJAX request dari sidebar (loadChatBySlug) → return JSON ──
        if ($this->request->isAJAX()) {
            $messages = array_map(fn($row) => [
                'role'         => (int)$row['role'],
                'conversation' => $row['conversation'], // JSON string atau plain text
            ], $history);

            return $this->response->setJSON([
                'success'    => true,
                'slug'       => $slug,
                'id_recents' => $recent['id_recents'],
                'messages'   => $messages,
            ]);
        }

        // ── Request biasa (akses URL langsung) → render HTML ──
        $data['menu']       = 'ai';
        $data['nama_user']  = session()->get('user_name');
        $data['id_recents'] = $recent['id_recents'];
        $data['slug']       = $slug;
        $data['history']    = $history;
        $data['history_recents_user'] = $this->recentModel->getRecentbyUser(session()->get('user_id'));
        $data['user_data_json']       = $this->getUserDataJson();

        return view('template/header', $data)
            . view('app/chatai', $data)
            . view('template/footer');
    }

    // ----------------------------------------------------------------
    // AJAX: Upload gambar ke uploads/img_conversation/
    // POST /ai/upload-image
    // ----------------------------------------------------------------
    public function uploadImage()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false]);
        }

        $file = $this->request->getFile('image');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'File tidak valid.']);
        }

        $uploadPath = FCPATH . 'uploads/img_conversation/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);

        $newName = $file->getRandomName();
        if (!$file->move($uploadPath, $newName)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal menyimpan file.']);
        }

        return $this->response->setJSON([
            'success' => true,
            'url'     => base_url('uploads/img_conversation/' . $newName),
        ]);
    }

    // ----------------------------------------------------------------
    // AJAX: Proxy gambar internal (wardrobe) → return sebagai blob
    // Dipakai JS untuk fetch gambar wardrobe sebelum kirim ke gpt-image-1
    // GET /ai/proxy-image?url=...
    // ----------------------------------------------------------------
    public function proxyImage()
    {
        $url = $this->request->getGet('url') ?? '';
        if (empty($url)) {
            return $this->response->setStatusCode(400)->setBody('URL required');
        }

        // Hanya izinkan URL dari server sendiri (uploads/wardrobe/)
        $baseUrl = base_url();
        if (!str_starts_with($url, $baseUrl)) {
            return $this->response->setStatusCode(403)->setBody('Forbidden');
        }

        // Konversi URL ke path file
        $relativePath = str_replace($baseUrl, '', $url);
        $filePath     = FCPATH . ltrim($relativePath, '/');

        if (!file_exists($filePath)) {
            return $this->response->setStatusCode(404)->setBody('File not found');
        }

        $mimeType = mime_content_type($filePath) ?: 'image/jpeg';
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Cache-Control', 'private, max-age=3600')
            ->setBody(file_get_contents($filePath));
    }

    // ----------------------------------------------------------------
    // AJAX: Download gambar dari OpenAI, blur area atas, simpan ke server
    // POST /ai/save-generated-image
    // Solusi CORS: PHP yang fetch dari OpenAI, bukan browser langsung
    // ----------------------------------------------------------------
    public function saveGeneratedImage()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false]);
        }

        // Hanya user login yang boleh generate & simpan gambar
        if (!session()->get('user_id')) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false, 'message' => 'Login required.']);
        }

        // Input bisa berupa URL (DALL-E 3) atau JSON body dengan b64_image (gpt-image-1)
        $openaiUrl  = trim($this->request->getPost('openai_url') ?? '');
        $jsonBody   = json_decode($this->request->getBody(), true);
        $hasB64     = !empty($jsonBody['b64_image']);

        // Validasi: harus ada salah satu
        if (empty($openaiUrl) && !$hasB64) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak ada data gambar.']);
        }

        // Validasi URL jika pakai URL mode
        if (!empty($openaiUrl)) {
            if (!filter_var($openaiUrl, FILTER_VALIDATE_URL)) {
                return $this->response->setJSON(['success' => false, 'message' => 'URL tidak valid.']);
            }
            $host = parse_url($openaiUrl, PHP_URL_HOST);
            if (!str_ends_with($host ?? '', 'openai.com') && !str_ends_with($host ?? '', 'windows.net')) {
                return $this->response->setJSON(['success' => false, 'message' => 'Domain tidak diizinkan.']);
            }
        }

        // Deteksi input: base64 langsung (tryon/gpt-image-1) atau URL (outfit/DALL-E 3)
        $mode      = $this->request->getPost('mode') ?? 'outfit';
        $imageData = null;

        // Cek apakah request JSON (tryon mode kirim b64_image via JSON body)
        $jsonBody = json_decode($this->request->getBody(), true);
        if (!empty($jsonBody['b64_image'])) {
            // gpt-image-1 return base64 — decode langsung
            $imageData = base64_decode($jsonBody['b64_image']);
            $mode      = $jsonBody['mode'] ?? 'tryon';
            if (!$imageData) {
                return $this->response->setJSON(['success' => false, 'message' => 'Base64 decode gagal.']);
            }
        } else {
            // DALL-E 3 → download dari URL OpenAI via cURL
            $client = \Config\Services::curlrequest(['verify' => false]);
            try {
                $response  = $client->get($openaiUrl);
                $imageData = $response->getBody();
            } catch (\Exception $e) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal download gambar: ' . $e->getMessage()]);
            }
        }

        // Blur area wajah — hanya untuk mode "outfit", skip untuk "tryon"
        if ($mode !== 'tryon') {
            $imageData = $this->blurFaceZone($imageData);
            if (!$imageData) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal memproses gambar.']);
            }
        }

        // Simpan ke uploads/img_conversation/
        $uploadPath = FCPATH . 'uploads/img_conversation/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);

        $filename = 'ai_outfit_' . time() . '_' . bin2hex(random_bytes(4)) . '.jpg';
        $fullPath = $uploadPath . $filename;

        if (file_put_contents($fullPath, $imageData) === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal menyimpan file.']);
        }

        return $this->response->setJSON([
            'success' => true,
            'url'     => base_url('uploads/img_conversation/' . $filename),
        ]);
    }

    // Blur area atas gambar (zona wajah) menggunakan GD Library
    private function blurFaceZone(string $imageData): string|false
    {
        if (!function_exists('imagecreatefromstring')) return $imageData; // GD tidak tersedia, skip blur

        $src = @imagecreatefromstring($imageData);
        if (!$src) return $imageData;

        $w = imagesx($src);
        $h = imagesy($src);

        // Area yang di-blur: 30% atas, 70% lebar tengah
        $zoneH = (int)($h * 0.30);
        $zoneW = (int)($w * 0.70);
        $zoneX = (int)(($w - $zoneW) / 2);

        // Crop zona wajah
        $zone = imagecrop($src, ['x' => $zoneX, 'y' => 0, 'width' => $zoneW, 'height' => $zoneH]);
        if (!$zone) {
            imagedestroy($src);
            return $imageData;
        }

        // Scale down → scale up (efek blur pixelasi)
        $scaleFactor = 12;
        $smallW = max(1, (int)($zoneW / $scaleFactor));
        $smallH = max(1, (int)($zoneH / $scaleFactor));

        $small = imagecreatetruecolor($smallW, $smallH);
        imagecopyresampled($small, $zone, 0, 0, 0, 0, $smallW, $smallH, $zoneW, $zoneH);

        $blurred = imagecreatetruecolor($zoneW, $zoneH);
        imagecopyresampled($blurred, $small, 0, 0, 0, 0, $zoneW, $zoneH, $smallW, $smallH);

        // Overlay semi-transparan abu-abu
        $overlay = imagecolorallocatealpha($blurred, 200, 200, 200, 50);
        imagefilledrectangle($blurred, 0, 0, $zoneW, $zoneH, $overlay);

        // Tempel kembali ke gambar asli
        imagecopy($src, $blurred, $zoneX, 0, 0, 0, $zoneW, $zoneH);

        // Output ke string JPEG
        ob_start();
        imagejpeg($src, null, 90);
        $result = ob_get_clean();

        imagedestroy($src);
        imagedestroy($zone);
        imagedestroy($small);
        imagedestroy($blurred);

        return $result ?: $imageData;
    }

    // ----------------------------------------------------------------
    // AJAX: Simpan pesan ke DB
    // conversation disimpan sebagai JSON string di kolom conversation (nvarchar MAX)
    //
    // Format JSON yang disimpan:
    // {
    //   "text": "pesan teks",
    //   "images": [
    //     { "url": "https://...", "type": "user" },   ← gambar upload user
    //     { "url": "https://...", "type": "ai"   }    ← gambar DALL-E
    //   ]
    // }
    //
    // POST /ai/send
    // ----------------------------------------------------------------
    public function sendMessage()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false]);
        }

        $idUser          = session()->get('user_id') ?? null;
        $userConvRaw     = $this->request->getPost('user_conversation') ?? '';
        $aiConvRaw       = $this->request->getPost('ai_conversation')   ?? '';
        $aiTitle         = trim($this->request->getPost('ai_title')     ?? '');
        $idRecents       = $this->request->getPost('id_recents')        ?? null;
        $slug            = $this->request->getPost('slug')              ?? null;

        // Validasi JSON
        $userConv = json_decode($userConvRaw, true);
        $aiConv   = json_decode($aiConvRaw,   true);

        if (!$userConv && !$aiConv) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data percakapan tidak valid.']);
        }

        // Chat baru → buat sesi
        if (empty($idRecents)) {
            $slug  = $this->recentModel->generateSlug();
            $judul = !empty($aiTitle)
                ? mb_substr($aiTitle, 0, 100)
                : mb_substr($userConv['text'] ?? '', 0, 50);

            $idRecents = $this->recentModel->insert([
                'id_user'       => $idUser,
                'judul_recents' => $judul,
                'slug'          => $slug,
                'created_at'    => date('Y-m-d H:i:s'),
            ]);

            if (!$idRecents) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal membuat sesi.']);
            }
        }

        // Simpan pesan user (JSON langsung ke kolom conversation)
        if ($userConv) {
            $this->detailModel->insert([
                'id_recents'   => $idRecents,
                'conversation' => json_encode($userConv, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'role'         => 1,
                'create_at'    => date('Y-m-d H:i:s'),
            ]);
        }

        // Simpan reply AI (JSON langsung ke kolom conversation)
        if ($aiConv) {
            $this->detailModel->insert([
                'id_recents'   => $idRecents,
                'conversation' => json_encode($aiConv, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'role'         => 0,
                'create_at'    => date('Y-m-d H:i:s'),
            ]);
        }

        return $this->response->setJSON([
            'success'    => true,
            'id_recents' => $idRecents,
            'slug'       => $slug,
            'redirect'   => base_url('ai/' . $slug),
        ]);
    }

    // ----------------------------------------------------------------
    // AJAX: Load history percakapan
    // GET /ai/history/{id_recents}
    // ----------------------------------------------------------------
    public function getHistory($idRecents)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false]);
        }

        $recent = $this->recentModel->find($idRecents);
        if (!$recent) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sesi tidak ditemukan.']);
        }

        $rows = $this->detailModel
            ->where('id_recents', $idRecents)
            ->orderBy('create_at', 'ASC')
            ->findAll();

        // Kembalikan conversation apa adanya — JS yang parse JSON-nya
        $messages = array_map(fn($row) => [
            'role'         => (int)$row['role'],
            'conversation' => $row['conversation'], // JSON string atau plain text (data lama)
        ], $rows);

        return $this->response->setJSON([
            'success'  => true,
            'slug'     => $recent['slug'],
            'messages' => $messages,
        ]);
    }

    // ----------------------------------------------------------------
    // Data user untuk JS
    // ----------------------------------------------------------------
    private function getUserDataJson(): ?string
    {
        $idUser = session()->get('user_id');
        if (!$idUser) return null;

        $db = \Config\Database::connect();

        $user = $db->table('users')
            ->select('id, nama, email, is_premium')
            ->where('id', $idUser)
            ->get()->getRowArray();

        if (!$user) return null;

        $wardrobe = $db->table('wardrobe w')
            ->select('w.id_wardrobe, w.nama_item, w.file_name, t.type_wardrobe')
            ->join('type_wardrobe t', 't.id = w.id_jenis_wardrobe', 'left')
            ->where('w.id_user', $idUser)
            ->get()->getResultArray();

        // Tambah image_url penuh ke setiap item wardrobe
        $wardrobe = array_map(function ($item) {
            $item['image_url'] = !empty($item['file_name'])
                ? base_url('uploads/wardrobe/' . $item['file_name'])
                : null;
            return $item;
        }, $wardrobe);

        $stylePrefs = $db->table('style_preferences sp')
            ->select('s.style_name')
            ->join('styles s', 's.id = sp.id_styles', 'left')
            ->where('sp.id_user', $idUser)
            ->get()->getResultArray();

        $bodyShape = $db->table('body_shape_user bu')
            ->select('d.jenis_b_shape')
            ->join('dt_body_shape d', 'd.id = bu.id_body_shape', 'left')
            ->where('bu.id_user', $idUser)
            ->get()->getRowArray();

        return json_encode([
            'nama'              => $user['nama'],
            'email'             => $user['email'],
            'is_premium'        => (bool)$user['is_premium'],
            'gender'            => $user['gender']  ?? null, // tambahkan kolom gender di tabel users jika ada
            'wardrobe'          => $wardrobe,
            'style_preferences' => $stylePrefs,
            'body_shape'        => $bodyShape['jenis_b_shape'] ?? null,
        ], JSON_UNESCAPED_UNICODE);
    }
}
