<?php

namespace App\Models;

use CodeIgniter\Model;

class BodyShapeUserModel extends Model
{
    protected $table            = 'body_shape_user';
    protected $primaryKey       = 'id_dt';
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_body_shape', 'id_user'];
    protected $useTimestamps    = false;

    // Query Builder untuk registrasi user baru
    public function getAll()
    {
        return $this->findAll();
    }
    public function getId_body_shape_user($user_id)
    {
        $builder = $this->db->table('body_shape_user');
        $builder->select('id_body_shape');
        $builder->where('id_user', $user_id);

        $result = $builder->get()->getRowArray();

        return $result ? $result['id_body_shape'] : null;
    }

    public function checkUserHasBodyShape($user_id)
    {
        $builder = $this->db->table('body_shape_user');
        $builder->where('id_user', $user_id);
        return $builder->get()->getRowArray(); // return array atau null
    }
}
