<?php
namespace App\Models\admin;

use CodeIgniter\Model;
use Config\Database;

class History_model extends Model
{

    protected $db;

	public function __construct()
	{
		$this->db = Database::connect();
	}

    public function historyCount()
    {
        $builder = $this->db->table('history');
        return $builder->countAllResults();
    }

    public function getHistory($limit, $page)
    {
        $builder = $this->db->table('history');
        $builder->orderBy('id', 'desc');
        $query = $builder->select('*')->get($limit, $page);
        return $query;
    }

    public function setHistory($activity, $user)
    {
        $builder = $this->db->table('history');
        if (!$builder->insert(array(
                    'activity' => $activity,
                    'username' => $user,
                    'time' => time())
                )) {
            ///log_message('error', print_r($builder->error(), true));
            show_error(lang_safe('database_error'));
        }
    }

}
