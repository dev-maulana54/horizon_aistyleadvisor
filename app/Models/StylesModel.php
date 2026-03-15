<?php

namespace App\Models;

use CodeIgniter\Model;

class StylesModel extends Model
{
    protected $table            = 'styles';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['style_name', 'image_style'];
    protected $useTimestamps    = false;

    // Query Builder untuk mencari Seluruh Styles
    public function getAllStyles()
    {
        return $this->findAll();
    }
}
