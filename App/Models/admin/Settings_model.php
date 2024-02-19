<?php
namespace App\Models\admin;

use CodeIgniter\Model;
use Config\Database;

class Settings_model extends Model
{

    protected $db;

	public function __construct()
	{
		$this->db = Database::connect();
	}

    public function getValueStores()
    {
        $query = $this->db->table('value_store')->get();
        return $query->getResultArray();
    }
    public function updateShippingValues($germanyValue, $europeValue)
    {
        $builder = $this->db->table('shipping_settings');
    
        // Check if the record exists
        $query = $builder->get();
        $existingRecord = $query->getRowArray();
    
        if ($existingRecord) {
            // Update the existing record
            $builder->update([
                'free_shipping_germany' => $germanyValue,
                'free_shipping_europe' => $europeValue
            ]);
        } else {
            // Insert a new record
            $builder->insert([
                'free_shipping_germany' => $germanyValue,
                'free_shipping_europe' => $europeValue
            ]);
        }
    
        return true;
    }
    
    
    public function getShippingSettings()
    {
        $builder = $this->db->table('shipping_settings');
        $query = $builder->get();
        $result = $query->getRowArray();
    
        if (empty($result)) {
            return null;
        }
    
        return $result;
    }
    

}
