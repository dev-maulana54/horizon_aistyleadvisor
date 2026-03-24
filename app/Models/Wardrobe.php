<?php

namespace App\Models;

use CodeIgniter\Model;

class Wardrobe extends Model
{
    protected $table            = 'wardrobe';
    protected $primaryKey       = 'id_wardrobe';
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_user', 'id_jenis_wardrobe', 'nama_item', 'file_name', 'update_at'];
    protected $useTimestamps    = false;

    // Query Builder untuk registrasi user baru


    public function getWardrobeByUserID($user_id)
    {
        return $this->where('id_user', $user_id)->findAll();
    }
    public function getWardrobeByType($id_type_wardrobe)
    {
        //todo : tambahkan validasi agar tidak sembarang orang akses ke wardrobe orang lain
        if (!session()->get('user_id')) {
            return [];
        }

        // todo : tambahkan validasi jika datanya ada, ywdh kirim. tapi jika tidak ada berikan text "Data wardrobe tidak ada"
        $wardrobe = $this->where(['id_jenis_wardrobe' => $id_type_wardrobe, 'id_user' => session()->get('user_id')])->findAll();
        if (empty($wardrobe)) {
            return ['message' => 'Data wardrobe tidak ada'];
        }

        return $this->where(['id_jenis_wardrobe' => $id_type_wardrobe, 'id_user' => session()->get('user_id')])->findAll();
    }
}
