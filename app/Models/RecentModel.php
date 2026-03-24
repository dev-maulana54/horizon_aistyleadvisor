<?php

namespace App\Models;

use CodeIgniter\Model;

class RecentModel extends Model
{
    protected $table = 'recents';
    protected $primaryKey = 'id_recents';
    protected $allowedFields = ['id_user', 'judul_recents', 'slug', 'created_at'];

    public function generateSlug(): string
    {
        do {
            $slug = bin2hex(random_bytes(8)); // 16 karakter hex acak
        } while ($this->where('slug', $slug)->first()); // pastikan unik

        return $slug;
    }

    public function getRecentbyUser($id_user)
    {
        return $this->where('id_user', $id_user)->orderBy('created_at', 'DESC')->findAll();
    }
}
