<?php

namespace App\Models;

use CodeIgniter\Model;

class StylesPreferenceModel extends Model
{
    protected $table            = 'style_preferences';
    protected $primaryKey       = 'id_reference';
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_user', 'id_styles'];
    protected $useTimestamps    = false;

    // Query Builder untuk mencari Seluruh Styles
    public function getAllStyles()
    {
        return $this->findAll();
    }
}
