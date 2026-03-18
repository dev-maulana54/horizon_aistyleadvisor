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

    public function insertBatchData() {}
    public function getWardrobeByUserID()
    {
        return $this->findAll();
    }
}
