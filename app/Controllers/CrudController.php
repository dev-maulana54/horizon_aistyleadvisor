<?php

namespace App\Controllers;

use App\Models\DtBodyShape;
use App\Models\BodyShapeUserModel;
use App\Models\StylesModel;
use App\Models\StylesPreferenceModel;
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
}
