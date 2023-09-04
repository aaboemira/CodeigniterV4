<?php
namespace App\Models\admin;

use CodeIgniter\Model;
use Config\Database;

class Discounts_model extends Model
{

    protected $db;

	public function __construct()
	{
		$this->db = Database::connect();
	}

    public function getDiscountCodeInfo($id)
    {
        $builder = $this->db->table('discount_codes');
        $builder->where('id', $id);
        $result = $builder->get();
        return $result->getRowArray();
    }

    public function changeCodeDiscountStatus($codeId, $toStatus)
    {
        $builder = $this->db->table('discount_codes');
        $builder->where('id', $codeId);
        if (!$builder->update(array(
                    'status' => $toStatus
                ))) {
            ///log_message('error', print_r($builder->error(), true));
            show_error(lang_safe('database_error'));
        }
    }

    public function discountCodesCount()
    {
        $builder = $this->db->table('discount_codes');
        return $builder->countAllResults();
    }

    public function getDiscountCodes($limit, $page)
    {
        $builder = $this->db->table('discount_codes');
        $result = $builder->get($limit, $page);
        return $result->getResultArray();
    }

    public function setDiscountCode($post)
    {
        $builder = $this->db->table('discount_codes');
        if (!$builder->insert(array(
                    'type' => $post['type'],
                    'code' => trim($post['code']),
                    'amount' => $post['amount'],
                    'valid_from_date' => strtotime($post['valid_from_date']),
                    'valid_to_date' => strtotime($post['valid_to_date'])
                ))) {
            ///log_message('error', print_r($builder->error(), true));
            show_error(lang_safe('database_error'));
        }
    }

    public function updateDiscountCode($post)
    {
        $builder = $this->db->table('discount_codes');
        $builder->where('id', $post['update']);
        if (!$builder->update(array(
                    'type' => $post['type'],
                    'code' => trim($post['code']),
                    'amount' => $post['amount'],
                    'valid_from_date' => strtotime($post['valid_from_date']),
                    'valid_to_date' => strtotime($post['valid_to_date'])
                ))) {
            ///log_message('error', print_r($builder->error(), true));
            show_error(lang_safe('database_error'));
        }
    }

    public function discountCodeTakenCheck($post)
    {
        $builder = $this->db->table('discount_codes');
        if ($post['update'] > 0) {
            $builder->where('id !=', $post['update']);
        }
        $builder->where('code', $post['code']);
        $num_rows = $builder->countAllResults();
        if ($num_rows == 0) {
            return true;
        }
        return false;
    }

}
