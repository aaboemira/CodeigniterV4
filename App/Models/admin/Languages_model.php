<?php

namespace App\Models\admin;

use CodeIgniter\Model;
use Config\Database;

class Languages_model extends Model
{

    protected $db;

	public function __construct()
	{
		$this->db = Database::connect();
	}

    public function deleteLanguage($id)
    {
        $builder = $this->db->table('languages');
        $builder->select('abbr');
        $builder->where('id', $id);
        $res = $builder->get();
        $row = $res->getRowArray();
        $this->db->transStart();
        $this->db->query('DELETE FROM languages WHERE id = ' . $id);
        $this->db->query('DELETE FROM products_translations WHERE abbr = "' . $row['abbr'] . '"');
        $this->db->query('DELETE FROM shop_categories_translations WHERE abbr = "' . $row['abbr'] . '"');
        $this->db->query('DELETE FROM textual_pages_tanslations WHERE abbr = "' . $row['abbr'] . '"');
        $this->db->query('DELETE FROM blog_translations WHERE abbr = "' . $row['abbr'] . '"');
        $this->db->query('DELETE FROM cookie_law_translations WHERE abbr = "' . $row['abbr'] . '"');
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            return false;
        }
        return true;
    }

    public function countLangs($name = null, $abbr = null)
    {
        $builder = $this->db->table('languages');
        if ($name != null) {
            $builder->where('name', $name);
        }
        if ($abbr != null) {
            $builder->orWhere('abbr', $abbr);
        }
        return $builder->countAllResults();
    }

    public function getLanguages()
    {
        $query = $this->db->query('SELECT * FROM languages');
        return $query->getResult();
    }

    public function setLanguage($post)
    {
        $post['name'] = strtolower($post['name']);
        $post['abbr'] = strtolower($post['abbr']);
        $builder = $this->db->table('languages');
        if (!$builder->insert($post)) {
            ///log_message('error', print_r($builder->error(), true));
            show_error(lang_safe('database_error'));
        }
    }

}
