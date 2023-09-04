<?php
namespace App\Models\admin;

use CodeIgniter\Model;
use Config\Database;

class Home_admin_model extends Model
{

	protected $db;

	public function __construct()
	{
		$this->db = Database::connect();
	}

    public function loginCheck($values)
    {
        $builder = $this->db->table('users');
        $arr = array(
            'username' => $values['username'],
            'password' => md5($values['password']),
        );
        $builder->where($arr);
        $result = $builder->get();
        $resultArray = $result->getRowArray();
        if ($resultArray) {
            $builder = $this->db->table('users');
            $builder->where('id', $resultArray['id']);
            $builder->update(array('last_login' => time()));
        }
        return $resultArray;
    }

    /*
     * Some statistics methods for home page of
     * administration
     * START
     */

    public function countLowQuantityProducts()
    {
        $builder = $this->db->table('products');
        $builder->where('quantity <=', 5);
        return $builder->countAllResults();
    }

    public function lastSubscribedEmailsCount()
    {
        $builder = $this->db->table('subscribed');
        $yesterday = strtotime('-1 day', time());
        $builder->where('time > ', $yesterday);
        return $builder->countAllResults();
    }

    public function getMostSoldProducts($limit = 10)
    {
        $builder = $this->db->table('products');
        $builder->select('url, procurement');
        $builder->orderBy('procurement', 'desc');
        $builder->where('procurement >', 0);
        $builder->limit($limit);
        $queryResult = $builder->get();
        return $queryResult->getResultArray();
    }

    public function getReferralOrders()
    {
        $builder = $this->db->table('orders');
        $builder->select('count(id) as num, clean_referrer as referrer');
        $builder->groupBy('clean_referrer');
        $queryResult = $builder->get();
        return $queryResult->getResultArray();
    }

    public function getOrdersByPaymentType($limit = 10)
    {
        $builder = $this->db->table('orders');
        $builder->select('count(id) as num, payment_type');
        $builder->groupBy('payment_type');
        $builder->limit($limit);
        $queryResult = $builder->get();
        return $queryResult->getResultArray();
    }

    public function getOrdersByMonth()
    {
        $result = $this->db->query("SELECT YEAR(FROM_UNIXTIME(date)) as year, MONTH(FROM_UNIXTIME(date)) as month, COUNT(id) as num FROM orders GROUP BY YEAR(FROM_UNIXTIME(date)), MONTH(FROM_UNIXTIME(date))");
        $result = $result->getResultArray();
        $orders = array();
        $years = array();
        foreach ($result as $res) {
            if (!isset($orders[$res['year']])) {
                for ($i = 1; $i <= 12; $i++) {
                    $orders[$res['year']][$i] = 0;
                }
            }
            $years[] = $res['year'];
            $orders[$res['year']][$res['month']] = $res['num'];
        }
        return array(
            'years' => array_unique($years),
            'orders' => $orders
        );
    }

    /*
     * Some statistics methods for home page of
     * administration
     * END
     */

    public function setValueStore($key, $value)
    {
        $builder = $this->db->table('value_store');
        $builder->where('thekey', $key);
        $query = $builder->get();
        if ($builder->countAllResults() > 0) {
            $builder = $this->db->table('value_store');
            $builder->where('thekey', $key);
            if (!$builder->update(array('value' => $value))) {
                ///log_message('error', print_r($builder->error(), true));
                show_error(lang_safe('database_error'));
            }
        } else {
            $builder = $this->db->table('value_store');
            if (!$builder->insert(array('value' => $value, 'thekey' => $key))) {
                ///log_message('error', print_r($builder->error(), true));
                show_error(lang_safe('database_error'));
            }
        }
    }

    public function changePass($new_pass, $username)
    {
        $builder = $this->db->table('users');
        $builder->where('username', $username);
        $result = $builder->update(array('password' => md5($new_pass)));
        return $result;
    }

    public function getValueStore($key)
    {
        $value = $this->db->query("SELECT value FROM value_store WHERE thekey = ? LIMIT 1", [$key])->getRowArray();
        if(!$value) {
            return null;
        }
        return $value['value'];
    }

    public function newOrdersCheck()
    {
        $result = $this->db->query("SELECT count(id) as num FROM `orders` WHERE viewed = 0");
        $row = $result->getRowArray();
        return $row['num'];
    }

    public function setCookieLaw($post)
    {
        $query = $this->db->query('SELECT id FROM cookie_law');
        if ($query->countAllResults() == 0) {
            $update = false;
        } else {
            $result = $query->getRowArray();
            $update = $result['id'];
        }

        if ($update === false) {
            $this->db->transStart();
            $builder = $this->db->table('cookie_law');
            if (!$builder->insert(array(
                        'link' => $post['link'],
                        'theme' => $post['theme'],
                        'visibility' => $post['visibility']
                    ))) {
                ///log_message('error', print_r($builder->error(), true));
            }
            $for_id = $this->db->insertID();
            $i = 0;
            foreach ($post['translations'] as $translate) {
                $builder = $this->db->table('cookie_law_translations');
                if (!$builder->insert(array(
                            'message' => htmlspecialchars($post['message'][$i]),
                            'button_text' => htmlspecialchars($post['button_text'][$i]),
                            'learn_more' => htmlspecialchars($post['learn_more'][$i]),
                            'abbr' => $translate,
                            'for_id' => $for_id
                        ))) {
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
        } else {
            $this->db->transStart();
            $builder = $this->db->table('cookie_law');
            $builder->where('id', $update);
            if (!$builder->update(array(
                        'link' => $post['link'],
                        'theme' => $post['theme'],
                        'visibility' => $post['visibility']
                    ))) {
                ///log_message('error', print_r($builder->error(), true));
            }
            $i = 0;
            foreach ($post['translations'] as $translate) {
                $builder = $this->db->table('cookie_law_translations');
                $builder->where('for_id', $update);
                $builder->where('abbr', $translate);
                if (!$builder->update(array(
                            'message' => htmlspecialchars($post['message'][$i]),
                            'button_text' => htmlspecialchars($post['button_text'][$i]),
                            'learn_more' => htmlspecialchars($post['learn_more'][$i])
                        ))) {
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
    }

    public function getCookieLaw()
    {
        $arr = array('cookieInfo' => null, 'cookieTranslate' => null);
        $query = $this->db->query('SELECT * FROM cookie_law');
        if (count($query->getRowArray()) > 0) {
            $arr['cookieInfo'] = $query->getRowArray();
            $query = $this->db->query('SELECT * FROM cookie_law_translations');
            $arrTrans = $query->getResultArray();
            foreach ($arrTrans as $trans) {
                $arr['cookieTranslate'][$trans['abbr']] = array(
                    'message' => $trans['message'],
                    'button_text' => $trans['button_text'],
                    'learn_more' => $trans['learn_more']
                );
            }
        }
        return $arr;
    }

}
