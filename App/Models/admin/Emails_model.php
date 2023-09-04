<?php
namespace App\Models\admin;

use CodeIgniter\Model;

class Emails_model extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function emailsCount()
    {
        $builder = $this->db->table('subscribed');
        return $builder->countAllResults();
    }

    public function getSuscribedEmails($limit, $page)
    {
        $builder = $this->db->table('subscribed');
        $builder->orderBy('id', 'desc');
        $query = $builder->select('*')->get($limit, $page);
        return $query;
    }

    public function deleteEmail($id)
    {
        if (!$builder->where('id', $id)->delete('subscribed')) {
            ///log_message('error', print_r($builder->error(), true));
            show_error(lang_safe('database_error'));
        }
    }

}
