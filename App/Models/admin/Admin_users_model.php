<?php
namespace App\Models\admin;

use CodeIgniter\Model;
use Config\Database;

class Admin_users_model extends Model
{

    protected $db;

	public function __construct()
	{
		$this->db = Database::connect();
	}

    public function deleteAdminUser($id)
    {
        $builder = $this->db->table('users');
        $builder->where('id', $id);
        if (!$builder->delete()) {
            ///log_message('error', print_r($builder->error(), true));
            show_error(lang_safe('database_error'));
        }
    }

    public function getAdminUsers($user = null)
    {
        $builder = $this->db->table('users');
        if ($user != null && is_numeric($user)) {
            $builder->where('id', $user);
        } else if ($user != null && is_string($user)) {
            $builder->where('username', $user);
        }
        $query = $builder->get();
        if ($user != null) {
            return $query->getRowArray();
        } else {
            return $query;
        }
    }

    public function setAdminUser($post)
    {
        if ($post['edit'] > 0) {
            if (trim($post['password']) == '') {
                unset($post['password']);
            } else {
                $post['password'] = md5($post['password']);
            }
            $builder = $this->db->table('users');
            $builder->where('id', $post['edit']);
            unset($post['id'], $post['edit']);
            if (!$builder->update($post)) {
                ///log_message('error', print_r($builder->error(), true));
                show_error(lang_safe('database_error'));
            }
        } else {
            unset($post['edit']);
            $post['password'] = md5($post['password']);
            $builder = $this->db->table('users');
            if (!$builder->insert($post)) {
                ///log_message('error', print_r($builder->error(), true));
                show_error(lang_safe('database_error'));
            }
        }
    }

}
