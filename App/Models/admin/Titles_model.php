<?php
namespace App\Models\admin;

use CodeIgniter\Model;
use Config\Database;

class Titles_model extends Model
{

    protected $db;

	public function __construct()
	{
		$this->db = Database::connect();
	}

    public function setSeoPageTranslations($post)
    {
        $i = 0;
        foreach ($post['pages'] as $page) {
            foreach ($post['translations'] as $abbr) {
                $builder = $this->db->table('seo_pages_translations');
                $builder->where('abbr', $abbr);
                $builder->where('page_type', $page);
                $num_rows = $builder->countAllResults();
                if ($num_rows == 0) {
                    $builder = $this->db->table('seo_pages_translations');
                    if (!$builder->insert( array(
                                'page_type' => $page,
                                'abbr' => $abbr,
                                'title' => $post['title'][$i],
                                'description' => $post['description'][$i]
                            ))) {
                        ///log_message('error', print_r($builder->error(), true));
                        show_error(lang_safe('database_error'));
                    }
                } else {
                    $builder = $this->db->table('seo_pages_translations');
                    $builder->where('abbr', $abbr);
                    $builder->where('page_type', $page);
                    if (!$builder->update(array(
                                'title' => $post['title'][$i],
                                'description' => $post['description'][$i]
                            ))) {
                        ///log_message('error', print_r($builder->error(), true));
                        show_error(lang_safe('database_error'));
                    }
                }
                $i++;
            }
        }
    }

    public function getSeoTranslations()
    {
        $builder = $this->db->table('seo_pages_translations');
        $result = $builder->get();
        $arr = array();
        foreach ($result->getResultArray() as $row) {
            $arr[$row['page_type']][$row['abbr']]['title'] = $row['title'];
            $arr[$row['page_type']][$row['abbr']]['description'] = $row['description'];
        }
        return $arr;
    }

    public function getSeoPages()
    {
        $builder = $this->db->table('seo_pages');
        $result = $builder->get();
        return $result->getResultArray();
    }

}
