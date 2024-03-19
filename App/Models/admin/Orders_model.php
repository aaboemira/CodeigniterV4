<?php
namespace App\Models\admin;

use CodeIgniter\Model;
use Config\Database;

class Orders_model extends Model
{

    protected $db;

	public function __construct()
	{
		$this->db = Database::connect();
	}

    public function ordersCount($onlyNew = false)
    {
        $builder = $this->db->table('orders');
        if ($onlyNew == true) {
            $builder->where('viewed', 0);
        }
        return $builder->countAllResults();
    }

    public function orders($limit, $page, $order_by)
    {
        $builder = $this->db->table('orders');
        if ($order_by != null) {
            $builder->orderBy($order_by, 'DESC');
        } else {
            $builder->orderBy('id', 'DESC');
        }
        $builder->select('orders.*, orders_clients.first_name,'
                . ' orders_clients.last_name, orders_clients.email, orders_clients.phone, orders_clients.company, '
                . 'orders_clients.street, orders_clients.housenr, orders_clients.country, orders_clients.city, orders_clients.post_code,'
                . ' orders_clients.notes, discount_codes.type as discount_type, discount_codes.amount as discount_amount');
        $builder->join('orders_clients', 'orders_clients.for_id = orders.id', 'inner');
        $builder->join('discount_codes', 'discount_codes.code = orders.discount_code', 'left');
        $result = $builder->get($limit, $page);
        return $result->getResultArray();
    }
public function getOrderStatuses()
{
    $query = $this->db->query("SHOW COLUMNS FROM orders WHERE Field = 'order_status'");
    $type = $query->getRow()->Type;
    preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
    $enum = explode("','", $matches[1]);
    return $enum;
}
public function getOrderStatus($id)
{
    $builder = $this->db->table('orders');

    $builder->select('orders.order_status');
    $builder->where('id', $id);

    $result = $builder->get();
    return $result->getResultArray();
}
public function changeOrderStatus($id, $to_status)
{
    $builder = $this->db->table('orders');
    $builder->where('order_id', $id);
    $builder->select('order_status');
    $result1 = $builder->get();
    $res = $result1->getRowArray();

    $result = true;
    if ($res['order_status'] != $to_status) {
        $builder = $this->db->table('orders');
        $builder->where('order_id', $id);
        $result = $builder->update(array('order_status' => $to_status, 'viewed' => '1'));

        if ($result == true) {
            $this->manageQuantitiesAndProcurement($id, $to_status, $res['order_status']);
        }
    }
    return $result;
}


    private function manageQuantitiesAndProcurement($id, $to_status, $current)
    {

        if (($to_status == 0 || $to_status == 2) && $current == 1) {
            $operator = '+';
            $operator_pro = '-';
        }
        if ($to_status == 1) {
            $operator = '-';
            $operator_pro = '+';
        }
        $builder = $this->db->table('orders');
        $builder->select('products');
        $builder->where('order_id', $id);
        $result = $builder->get();
        $arr = $result->getRowArray();
        $products = unserialize($arr['products']);
        foreach ($products as $product) {
                if (isset($operator)) {
                    if (!$this->db->query('UPDATE products SET quantity=quantity' . $operator . $product['product_quantity'] . ' WHERE id = ' . $product['product_info']['id'])) {
                        ///log_message('error', print_r($builder->error(), true));
                        show_error(lang_safe('database_error'));
                    }
                }
                if (isset($operator_pro)) {
                    if (!$this->db->query('UPDATE products SET procurement=procurement' . $operator_pro . $product['product_quantity'] . ' WHERE id = ' . $product['product_info']['id'])) {
                        ///log_message('error', print_r($builder->error(), true));
                        show_error(lang_safe('database_error'));
                    }
                } 
        }
    }

    // public function setBankAccountSettings($post)
    // {
    //     $query = $this->db->query('SELECT id FROM bank_accounts');
    //     if ($query->countAllResults() == 0) {
    //         $id = 1;
    //     } else {
    //         $result = $query->getRowArray();
    //         $id = $result['id'];
    //     }
    //     $post['id'] = $id;
    //     if (!$builder->replace('bank_accounts', $post)) {
    //         ///log_message('error', print_r($builder->error(), true));
    //         show_error(lang_safe('database_error'));
    //     }
    // }

    public function getBankAccountSettings()
    {
        $result = $this->db->query("SELECT * FROM bank_accounts LIMIT 1");
        return $result->getRowArray();
    }

}