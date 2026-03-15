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
    public function checkUserHasBodyShape($id_user)
    {
        return $this->where('id_user', $id_user)->first();
    }
}
