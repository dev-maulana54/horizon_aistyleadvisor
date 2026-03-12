<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama', 'email', 'password', 'role', 'is_active', 'is_premium'];
    protected $useTimestamps    = true;

    // Query Builder untuk mencari user berdasarkan email
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    // Query Builder untuk registrasi user baru
    public function registerUser($data)
    {
        return $this->insert($data);
    }
}
