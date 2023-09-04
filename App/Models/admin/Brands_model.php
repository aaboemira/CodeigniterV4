<?php
namespace App\Models\admin;

use CodeIgniter\Model;
use Config\Database;

class Brands_model extends Model
{
	protected $db;

	public function __construct()
	{
		$this->db = Database::connect();
	}

    public function getBrands()
    {
        $result = $this->db->table('brands')->get();
        return $result->getResultArray();
    }

    public function setBrand($name)
    {
        $builder = $this->db->table('brands');
        if (!$builder->insert(array('name' => $name))) {
            ///log_message('error', print_r($builder->error(), true));
            show_error(lang_safe('database_error'));
        }
    }

    public function deleteBrand($id)
    {
        if (!$builder->where('id', $id)->delete('brands')) {
            ///log_message('error', print_r($builder->error(), true));
            show_error(lang_safe('database_error'));
        }
    }

}
