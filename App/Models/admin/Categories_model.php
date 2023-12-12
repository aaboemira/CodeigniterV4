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

    public function getShopCategories($limit = null, $page = null)
    {
        $offset_sql = '';
        if ($limit !== null && $page !== null) {
            $offset = ($page - 1) * $limit; // Calculate the offset
            $offset_sql = ' LIMIT ' . $offset . ',' . $limit;
        }
    
        // Execute the updated SQL query
        $query = $this->db->query(
            'SELECT 
                sc.id AS category_id, 
                sc.sub_for AS parent_category_id, 
                sc.position AS category_position,
                sct.name AS translation_name, 
                sct.abbr AS language_abbr,
                parent_sct.name AS parent_translation_name
            FROM 
                shop_categories AS sc
            LEFT JOIN 
                shop_categories_translations AS sct 
                ON sc.id = sct.for_id
            LEFT JOIN 
                shop_categories AS parent_sc
                ON sc.sub_for = parent_sc.id
            LEFT JOIN 
                shop_categories_translations AS parent_sct
                ON parent_sc.id = parent_sct.for_id AND sct.abbr = parent_sct.abbr
            ORDER BY 
                sc.sub_for ASC, 
                sc.position ASC, 
                sct.id ASC,
                sct.abbr ASC' . $offset_sql
        );
    
        $arr = array();
        foreach ($query->getResult() as $shop_category) {
            $for_id = $shop_category->category_id;
    
            if (!isset($arr[$for_id])) {
                $arr[$for_id] = [
                    'info' => [],
                    'sub' => [],
                    'position' => $shop_category->category_position
                ];
            }
    
            $arr[$for_id]['info'][] = [
                'abbr' => $shop_category->language_abbr,
                'name' => $shop_category->translation_name
            ];
    
            // Adding parent category name instead of ID
            if ($shop_category->parent_category_id) {
                $arr[$for_id]['sub'][] = $shop_category->parent_translation_name;
            }
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
