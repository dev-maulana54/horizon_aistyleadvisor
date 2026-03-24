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
    public function get_style_preferences_user($user_id)
    {
        $builder = $this->db->table('style_preferences');
        $builder->select('id_styles');
        $builder->where('id_user', $user_id);

        $result = $builder->get()->getResultArray();

        return $result ? array_column($result, 'id_styles') : null;
    }
}
