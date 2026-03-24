<?php

namespace App\Controllers;

use App\Models\DtBodyShape;
use App\Models\BodyShapeUserModel;
use App\Models\StylesModel;
use App\Models\StylesPreferenceModel;
use App\Models\Wardrobe;
use CodeIgniter\Controller;

class CrudController extends Controller
{
    public function updatePersonalData()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid request',
            ]);
        }

        $body_shape_input = $this->request->getPost('body_shape');
        $stylesInput      = $this->request->getPost('styles');
        $id_user          = session()->get('user_id');

        $rules = [
            'body_shape' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Body shape wajib dipilih',
                ],
            ],
            'styles' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Minimal pilih satu style preference',
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

        if (! is_array($stylesInput)) {
            $stylesInput = [$stylesInput];
        }

        $dt_body_shapes          = new DtBodyShape();
        $body_shape_user_model   = new BodyShapeUserModel();
        $styles_model            = new StylesModel();
        $styles_preference_model = new StylesPreferenceModel();

        // Ambil id body shape berdasarkan input user
        $ambil_id_body_shape = $dt_body_shapes->where('jenis_b_shape', $body_shape_input)->first();

        if (! $ambil_id_body_shape) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Body shape tidak ditemukan',
            ]);
        }

        $id_body_shape   = $ambil_id_body_shape['id'];
        $query_body_shape = $body_shape_user_model->checkUserHasBodyShape($id_user);

        // Update / insert body shape user
        if ($query_body_shape) {
            $body_shape_user_model->update($query_body_shape['id_dt'], [
                'id_body_shape' => $id_body_shape,
            ]);
        } else {
            $body_shape_user_model->insert([
                'id_user'       => $id_user,
                'id_body_shape' => $id_body_shape,
            ]);
        }

        /*
         * Sinkronisasi style preference user
         */

        // 1. Ambil style master berdasarkan nama style yang dipilih user
        // sesuaikan field: style_name
        $selectedStyles = $styles_model
            ->whereIn('style_name', $stylesInput)
            ->findAll();

        // Ambil id style hasil input baru
        $newStyleIds = array_column($selectedStyles, 'id');

        // Validasi kalau ada input style yang tidak ditemukan di master
        if (empty($newStyleIds)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Style yang dipilih tidak valid',
            ]);
        }

        // 2. Ambil style preference user yang sekarang
        $existingPreferences = $styles_preference_model
            ->where('id_user', $id_user)
            ->findAll();

        $existingStyleIds = array_column($existingPreferences, 'id_styles');

        // Optional: samakan tipe data biar perbandingan aman
        $newStyleIds      = array_map('intval', $newStyleIds);
        $existingStyleIds = array_map('intval', $existingStyleIds);

        sort($newStyleIds);
        sort($existingStyleIds);

        // 3. Kalau sama persis, tidak perlu update
        if ($newStyleIds !== $existingStyleIds) {
            // Cari id yang harus dihapus
            $toDelete = array_diff($existingStyleIds, $newStyleIds);

            if (! empty($toDelete)) {
                $styles_preference_model
                    ->where('id_user', $id_user)
                    ->whereIn('id_styles', $toDelete)
                    ->delete();
            }

            // Cari id yang harus diinsert
            $toInsert = array_diff($newStyleIds, $existingStyleIds);

            if (! empty($toInsert)) {
                $insertData = [];

                foreach ($toInsert as $id_style) {
                    $insertData[] = [
                        'id_user'   => $id_user,
                        'id_styles' => $id_style,
                    ];
                }

                $styles_preference_model->insertBatch($insertData);
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Personal data updated successfully!',
        ]);
    }
    public function saveWardrobe()
    {
        $db_wardrobe = new Wardrobe();

        try {
            $idUser = session()->get('user_id');

            if (!$idUser) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'User belum login'
                ]);
            }

            $namaItem        = trim($this->request->getPost('nama_item'));
            $idJenisWardrobe = $this->request->getPost('id_jenis_wardrobe');

            if (empty($namaItem)) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Nama item wajib diisi'
                ]);
            }

            if (empty($idJenisWardrobe)) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Jenis wardrobe wajib dipilih'
                ]);
            }

            // Ambil file
            $uploadedFiles = $this->request->getFileMultiple('wardrobe_images');

            if (empty($uploadedFiles)) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'File gambar tidak ditemukan'
                ]);
            }

            // Filter hanya file valid
            $validFiles = [];
            foreach ($uploadedFiles as $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $validFiles[] = $file;
                }
            }

            if (empty($validFiles)) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Tidak ada file valid yang diupload'
                ]);
            }

            $allowedMime = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];

            // Cek jumlah data existing berdasarkan user + jenis wardrobe
            $totalExisting = $db_wardrobe
                ->where('id_user', $idUser)
                ->where('id_jenis_wardrobe', $idJenisWardrobe)
                ->countAllResults();

            $newUploadCount  = count($validFiles);
            $totalAfterUpload = $totalExisting + $newUploadCount;

            if ($totalExisting >= 5) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Maksimal 5 item sudah tercapai untuk kategori ini'
                ]);
            }

            if ($totalAfterUpload > 5) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => "Tidak bisa upload {$newUploadCount} item, sisa slot hanya " . (5 - $totalExisting)
                ]);
            }

            // Siapkan direktori upload
            $uploadPath = FCPATH  . 'uploads/wardrobe/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Loop file, validasi mime, pindahkan, siapkan batch data
            $batchData = [];

            foreach ($validFiles as $file) {
                $mime = $file->getMimeType();

                if (!in_array($mime, $allowedMime)) {
                    return $this->response->setJSON([
                        'status'  => false,
                        'message' => 'Format file harus jpg, jpeg, png, atau webp'
                    ]);
                }

                $originalName     = pathinfo($file->getClientName(), PATHINFO_FILENAME);
                $safeOriginalName = preg_replace('/[^A-Za-z0-9_-]/', '_', strtolower($originalName));
                $extension        = strtolower($file->getExtension());
                $newName          = 'fixmatchai_' . $safeOriginalName . '_' . time() . '_' . uniqid() . '.' . $extension;

                $file->move($uploadPath, $newName);

                // Setiap file = 1 row di database
                $batchData[] = [
                    'id_user'           => $idUser,
                    'id_jenis_wardrobe' => $idJenisWardrobe,
                    'nama_item'         => $namaItem,
                    'file_name'         => $newName,
                    'update_at'         => date('Y-m-d H:i:s'),
                ];
            }

            if (empty($batchData)) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Tidak ada file yang berhasil diproses'
                ]);
            }

            // Insert batch — 1 file = 1 row, N file = N rows sekaligus
            $db_wardrobe->insertBatch($batchData);

            return $this->response->setJSON([
                'status'  => true,
                'message' => count($batchData) . ' item wardrobe berhasil disimpan',
                'data'    => $batchData
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Gagal simpan data',
                'error'   => $e->getMessage()
            ]);
        }
    }
    public function getWardrobeUser($id_type_wardrobe)
    {
        $wardrobe = new Wardrobe();
        $data = $wardrobe->getWardrobeByType($id_type_wardrobe);

        return $this->response->setJSON($data);
    }
}
