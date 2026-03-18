<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeWardrobe extends Model
{
    protected $table            = 'type_wardrobe';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['type_wardrobe'];
    protected $useTimestamps    = false;

    // Query Builder untuk registrasi user baru
    public function getAll()
    {
        return $this->findAll();
    }
}
