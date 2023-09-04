<?php

namespace App\Models\admin;

use CodeIgniter\Model;
use Config\Database;
use App\Models\admin\Languages_model;

class Pages_model extends Model
{

    protected $db;
    protected $Languages_model;

	public function __construct()
	{
		$this->db = Database::connect();
        $this->Languages_model = new Languages_model();
	}

    public function getPages($active = null, $advanced = false)
    {
        $builder = $this->db->table('active_pages');
        if ($active != null) {
            $builder->where('enabled', $active);
        }
        if ($advanced == false) {
            $builder->select('name');
        } else {
            $builder->select('*');
        }
        $result = $builder->get();
        if ($result != false) {
            $array = array();
            if ($advanced == false) {
                foreach ($result->getResultArray() as $arr)
                    $array[] = $arr['name'];
            } else {
                $array = $result->getResultArray();
            }
            return $array;
        }
    }

    public function setPage($name)
    {
        $name = strtolower($name);
        $name = str_replace(' ', '-', $name);
        $this->db->transStart();
        $builder = $this->db->table('active_pages');
        if (!$builder->insert(array('name' => $name, 'enabled' => 1))) {
            ///log_message('error', print_r($builder->error(), true));
        }
        $thisId = $this->db->insertID();
        $languages = $this->Languages_model->getLanguages();
        foreach ($languages as $language) {
            $builder = $this->db->table('textual_pages_tanslations');
            if (!$builder->insert(array(
                        'for_id' => $thisId,
                        'abbr' => $language->abbr
                    ))) {
                ///log_message('error', print_r($builder->error(), true));
            }
        }
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            //show_error(lang_safe('database_error'));
        } else {
            $this->db->transCommit();
        }
    }

    public function deletePage($id)
    {
        $this->db->transStart();
        $builder = $this->db->table('active_pages');
        $builder->where('id', $id);
        if (!$builder->delete()) {
            ///log_message('error', print_r($this->db->error(), true));
        }

        $builder = $this->db->table('products');
        $builder->where('for_id', $id);
        if (!$builder->delete()) {
            ///log_message('error', print_r($this->db->error(), true));
        }

        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
        } else {
            $this->db->transCommit();
        }
    }

}
