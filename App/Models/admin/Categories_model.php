<?php
namespace App\Models\admin;


use CodeIgniter\Model;
use Config\Database;

class Categories_model extends Model
{

    protected $db;

	public function __construct()
	{
		$this->db = Database::connect();
	}

    public function categoriesCount()
    {
        $builder = $this->db->table('shop_categories');
        return $builder->countAllResults();
    }

    public function getShopCategories($limit = null, $start = null)
    {
        $limit_sql = '';
        if ($limit !== null && $start !== null) {
            $limit_sql = ' LIMIT ' . $start . ',' . $limit;
        }

        $query = $this->db->query('SELECT translations_first.*, (SELECT name FROM shop_categories_translations WHERE for_id = sub_for AND abbr = translations_first.abbr) as sub_is, shop_categories.position FROM shop_categories_translations as translations_first INNER JOIN shop_categories ON shop_categories.id = translations_first.for_id ORDER BY position ASC ' . $limit_sql);
        $arr = array();
        foreach ($query->getResult() as $shop_categorie) {
            $arr[$shop_categorie->for_id]['info'][] = array(
                'abbr' => $shop_categorie->abbr,
                'name' => $shop_categorie->name
            );
            $arr[$shop_categorie->for_id]['sub'][] = $shop_categorie->sub_is;
            $arr[$shop_categorie->for_id]['position'] = $shop_categorie->position;
        }
        return $arr;
    }

    public function deleteShopCategorie($id)
    {
        $this->db->transStart();
        $builder = $this->db->table('shop_categories_translations');
        $builder->where('for_id', $id);
        if (!$builder->delete()) {
            ///log_message('error', print_r($this->db->error(), true));
        }
        $builder = $this->db->table('shop_categories');
        $builder->where('id', $id);
        $builder->orWhere('sub_for', $id);
        if (!$builder->delete()) {
            ///log_message('error', print_r($this->db->error(), true));
        }

        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
        } else {
            $this->db->transCommit();
        }
    }

    public function setShopCategorie($post)
    {
        $this->db->transStart();
        $builder = $this->db->table('shop_categories');
        if (!$builder->insert(array('sub_for' => $post['sub_for']))) {
            ///log_message('error', print_r($builder->error(), true));
        }
        $id = $this->db->insertID();

        $i = 0;
        foreach ($post['translations'] as $abbr) {
            $arr = array();
            $arr['abbr'] = $abbr;
            $arr['name'] = $post['categorie_name'][$i];
            $arr['for_id'] = $id;
            $builder = $this->db->table('shop_categories_translations');
            if (!$builder->insert($arr)) {
                ///log_message('error', print_r($builder->error(), true));
            }
            $i++;
        }
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            //show_error(lang_safe('database_error'));
        } else {
            $this->db->transCommit();
        }
    }

    public function editShopCategorieSub($post)
    {
        $result = true;
        if ($post['editSubId'] != $post['newSubIs']) {
            $builder = $this->db->table('shop_categories');
            $builder->where('id', $post['editSubId']);
            if (!$builder->update(array(
                        'sub_for' => $post['newSubIs']
                    ))) {
                ///log_message('error', print_r($builder->error(), true));
                show_error(lang_safe('database_error'));
            }
        } else {
            $result = false;
        }
        return $result;
    }

    public function editShopCategorie($post)
    {
        $builder = $this->db->table('shop_categories_translations');
        $builder->where('abbr', $post['abbr']);
        $builder->where('for_id', $post['for_id']);
        if (!$builder->update(array(
                    'name' => $post['name']
                ))) {
            ///log_message('error', print_r($builder->error(), true));
            show_error(lang_safe('database_error'));
        }
    }

    public function editShopCategoriePosition($post)
    {
        $builder = $this->db->table('shop_categories');
        $builder->where('id', $post['editid']);
        if (!$builder->update(array(
                    'position' => $post['new_pos']
                ))) {
            ///log_message('error', print_r($builder->error(), true));
            show_error(lang_safe('database_error'));
        }
    }

}
