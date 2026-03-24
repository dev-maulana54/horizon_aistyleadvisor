<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailRecentModel extends Model
{
    protected $table = 'detail_recents';
    protected $primaryKey = 'id_detail_r';
    protected $allowedFields = ['id_recents', 'conversation', 'role', 'create_at'];
    protected $useTimestamps = false;

    public function getDetail_byUser($id_user)
    {
        return $this->where('id_user', $id_user)->orderBy('created_at', 'DESC')->findAll();
    }
}
