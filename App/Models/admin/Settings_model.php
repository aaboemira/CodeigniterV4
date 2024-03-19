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
    public function getGermanShippingOptions()
    {
        return $this->getShippingOptionsByDestination(1);
    }
    
    public function getEuropeanShippingOptions()
    {
        return $this->getShippingOptionsByDestination(2);
    }
    
    private function getShippingOptionsByDestination($destination)
    {
        $builder = $this->db->table('products');
        $builder->select('products.id, products_translations.title,free_shipping_enabled'); // Specify 'products.id' to avoid ambiguity
        $builder->join('products_translations', 'products_translations.for_id = products.id', 'inner');
        $builder->where('products.shipment_destination', $destination);
        $builder->where('products_translations.abbr', MY_LANGUAGE_ABBR);

        $query = $builder->get();
        return $query->getResultArray();
    }
    
    
    
    public function updateShippingOptionsByDestination($selectedOptions, $destination)
{
    // Reset free_shipping_enabled for the current destination
    $this->db->table('products')
             ->where('shipment_destination', $destination)
             ->update(['free_shipping_enabled' => 0]);

    // Set free_shipping_enabled to 1 for the selected options within the destination
    if (!empty($selectedOptions)) {
        $this->db->table('products')
                 ->whereIn('id', $selectedOptions)
                 ->update(['free_shipping_enabled' => 1]);
    }
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
