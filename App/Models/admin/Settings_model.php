<?php
namespace App\Models\admin;

use CodeIgniter\Model;
use Config\Database;

class Settings_model extends Model
{

    protected $db;

	public function __construct()
	{
		$this->db = Database::connect();
	}

    public function getValueStores()
    {
        $query = $this->db->table('value_store')->get();
        return $query->getResultArray();
    }

}
