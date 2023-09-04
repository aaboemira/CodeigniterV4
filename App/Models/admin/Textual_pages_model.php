<?php
namespace App\Models\admin;

use CodeIgniter\Model;
use Config\Database;

class Textual_pages_model extends Model
{

    protected $db;

	public function __construct()
	{
		$this->db = Database::connect();
	}

    public function getOnePageForEdit($pname)
    {
        $builder = $this->db->table('active_pages');
        $builder->join('textual_pages_tanslations', 'textual_pages_tanslations.for_id = active_pages.id', 'left');
        $builder->join('languages', 'textual_pages_tanslations.abbr = languages.abbr', 'left'); 
        $builder->where('active_pages.enabled', 1);
        $builder->where('active_pages.name', $pname);
        $query = $builder->select('active_pages.id, textual_pages_tanslations.description, textual_pages_tanslations.abbr, textual_pages_tanslations.name, languages.name as lname, languages.flag')->get();
        return $query->getResultArray();
    }

    public function setEditPageTranslations($post)
    {
        $i = 0;
        foreach ($post['translations'] as $abbr) {
            $builder = $this->db->table('textual_pages_tanslations');
            $builder->where('abbr', $abbr);
            $builder->where('for_id', $post['pageId']); 
            if (!$builder->update(array(
                        'name' => $post['name'][$i],
                        'description' => $post['description'][$i]
                    ))) {
                ///log_message('error', print_r($builder->error(), true));
                show_error(lang_safe('database_error'));
            }
            $i++;
        }
    }

    public function changePageStatus($id, $to_status)
    {
        $result = $builder->where('id', $id)->update('active_pages', array('enabled' => $to_status));
        return $result;
    }

}
