<?php

namespace App\Models;

use CodeIgniter\Model;

class DtBodyShape extends Model
{
    protected $table            = 'dt_body_shape';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['jenis_b_shape', 'image_b_shape'];
    protected $useTimestamps    = false;

    // Query Builder untuk mencari user berdasarkan email
    public function GetAllbodyShape()
    {
        return $this->findAll();
    }
    // Join dt_body_shape dengan body_shape_user
    // Ambil semua body shape + flag is_checked berdasarkan user_id
    // Ambil body_shape_id yang dimiliki user
    public function GetBodyShapeByUser($user_id)
    {
        $builder = $this->db->table('body_shape_user');
        $builder->select('id_body_shape');
        $builder->where('id_user', $user_id);

        $result = $builder->get()->getResultArray();

        // Return array of id saja, misal: [1, 3, 5]
        return array_column($result, 'body_shape_id');
    }
}
