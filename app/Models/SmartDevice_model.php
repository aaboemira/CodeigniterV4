<?php
namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class SmartDevice_model extends Model
{
    protected $db;
    protected $logger;
    protected $encryptionKey;  
    private  $deviceEnryption_fields =['UID','serial_number','uid', 'state', 'connected','password','pin_code'];
    
    private  $guestEnryption_fields =['email', 'guest_password', 'guest_pin_code'];

    public function __construct()
    {
        $this->db = Database::connect();

        $this->logger = service('logger');        ;
        helper('api_helper');
        $this->encryptionKey = '@@12@@';
    }

    public function getSmartDeviceByID($id)
    {

        $builder = $this->db->table('smart_devices');
        $builder->select('*');
        $builder->where('device_id', $id);
        $query = $builder->get();
        $result=$query->getRowArray();

        $result=$this->decryptFields($result,$this->deviceEnryption_fields);
        return $result;
    }
    public function getSmartDeviceNameByID($deviceID)
    {
        $builder = $this->db->table('smart_devices');
        $builder->select('device_name');
        $builder->where('device_id', $deviceID);
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result ? $result['device_name'] : null;
    }
    
    public function getSmartDeviceBySerial($serial)
    {
        $builder = $this->db->table('smart_devices');
        $builder->select('*');
        $builder->where('serial_number', $serial);
    
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result ? $this->decryptFields($result, $this->deviceEnryption_fields) : false;
    }
    
    public function getSmartHomeDevicesByUID($uid, $limit = null, $page = 0)
    {
        $builder = $this->db->table('smart_devices');
        $builder->select('*');
        $builder->where('user_id', $uid);
    
        if (isset($limit) && $page > 0) {
            $offset = ($page - 1) * $limit;
            $builder->limit($limit, $offset);
        }
    
        $query = $builder->get();
        $results = $query->getResultArray();
        foreach ($results as &$result) {
            $result = $this->decryptFields($result, $this->deviceEnryption_fields);
        }
        return $results;
    }
    
    public function getSmartDevicesBySerial($serial)
    {
        $builder = $this->db->table('smart_devices');
        $builder->select('*');
        $builder->where('serial_number', $serial);
        $builder->where('google_linked', '1');
    
        $query = $builder->get();
        $results = $query->getResultArray();
        if($results){
            foreach ($results as &$result) {
                $result = $this->decryptFields($result, $this->deviceEnryption_fields);
            }
        }else{
            return false;
        }

        return $results ? $results : false;
    }
    
    public function getSmartDeviceBySerialAndUserId($uid, $serial)
    {
        $builder = $this->db->table('smart_devices');
        $builder->select('*');
        $builder->where('serial_number', $serial);
        $builder->where('user_id', $uid);
    
        $query = $builder->get();
        $result = $query->getRowArray();
        $result = $this->decryptFields($result, $this->deviceEnryption_fields);
        return $result;
    }
    

    public function saveSmartDevice($deviceData)
    {
        $builder = $this->db->table('smart_devices');
    
        $encryptionFields = array_diff($this->deviceEnryption_fields, ['serial_number']);
        $deviceData = $this->encryptData($deviceData, $encryptionFields);
        // Encrypt 'serial_number' separately with a fixed IV
        $deviceData['serial_number'] = encryptDataWithFixedIV($deviceData['serial_number'], $this->encryptionKey);
    
        return $builder->insert($deviceData);
    }
    
    public function updateSmartDeviceStatus($deviceData)
    {
        // Assuming 'device_id' is the primary key or unique identifier for the devices
        $deviceId = $deviceData['device_id'];

        // Prepare the data for updating
        $updateData = [
            'connected' => $deviceData['connected'],
            'state' => $deviceData['state']
        ];
        $updateData=$this->encryptData($updateData,['connected','state']);
        $builder = $this->db->table('smart_devices');
        $builder->where('device_id', $deviceId);
        return $builder->update($updateData);
    }
    public function updateSmartDevice($deviceData)
    {
        $builder = $this->db->table('smart_devices');
        $builder->where('device_id', $deviceData['device_id']);
        $encryptionFields = array_diff($this->deviceEnryption_fields, ['serial_number']);
        $deviceData = $this->encryptData($deviceData, $encryptionFields);
        // Encrypt 'serial_number' separately with a fixed IV
        $deviceData['serial_number'] = encryptDataWithFixedIV($deviceData['serial_number'], $this->encryptionKey);
    
        return $builder->update($deviceData);
    }

    public function deleteSmartDevice($deviceId)
    {
        $builder = $this->db->table('smart_devices');
        $builder->where('device_id', $deviceId);
        return $builder->delete();
    }
    public function deleteSmartDeviceBySerialAndUserId($userId, $serial)
    {
        $serial = encryptDataWithFixedIV($serial, $this->encryptionKey);

        return $this->db->table('smart_devices') // Replace 'devices_table' with your actual table name
                        ->where('user_id', $userId)
                        ->where('serial_number', $serial)
                        ->delete();
    }
    public function isSerialNumberUnique($userID, $serial, $deviceID = null)
    {
        $serial = encryptDataWithFixedIV($serial, $this->encryptionKey);

        $builder = $this->db->table('smart_devices');
        $builder->where('user_id', $userID);
        $builder->where('serial_number', $serial);

        // Exclude the current device if deviceID is provided
        if ($deviceID !== null) {
            $builder->where('device_id !=', $deviceID);
        }

        return $builder->countAllResults() === 0;
    }
    public function isDeviceNameUnique($userID, $name, $deviceID = null)
    {
        $builder = $this->db->table('smart_devices');
        $builder->where('user_id', $userID);
        $builder->where('device_name', $name);

        // Exclude the current device if deviceID is provided
        if ($deviceID !== null) {
            $builder->where('device_id !=', $deviceID);
        }

        // Return true if no records found, false otherwise
        return $builder->countAllResults() === 0;
    }
    public function countSmartHomeDevicesByUID($uid)
    {
        $builder = $this->db->table('smart_devices');
        $builder->where('user_id', $uid);
        return $builder->countAllResults();
    }
    public function countSmartHomeDevicesByUserAndSerial($userID, $serial)
    {
        $serial = encryptDataWithFixedIV($serial, $this->encryptionKey);

        $builder = $this->db->table('smart_devices');
        $builder->where('user_id', $userID);
        $builder->where('serial_number', $serial);
        return $builder->countAllResults();
    }
    public function countSmartHomeDevicesByUserAndName($userID, $name)
    {
        $builder = $this->db->table('smart_devices');
        $builder->where('user_id', $userID);
        $builder->where('device_name', $name);
        return $builder->countAllResults();
    }
    public function getLatestUpdateForUser($userId)
    {
        $subQuery1 = $this->db->table('smart_devices')
                              ->selectMax('last_updated')
                              ->where('user_id', $userId);
    
        $subQuery2 = $this->db->table('smart_devices_guests')
                              ->join('smart_devices', 'smart_devices.device_id = smart_devices_guests.device_id')
                              ->selectMax('smart_devices_guests.last_updated')
                              ->where('smart_devices.user_id', $userId);
    
        $query = $this->db->table('(' . $subQuery1->getCompiledSelect() . ' UNION ' . $subQuery2->getCompiledSelect() . ') as combined_updates')
                          ->selectMax('last_updated');
    
        $result = $query->get()->getRow();
        return $result ? $result->last_updated : null;
    }
    //Guest Devices

    public function getGuestByDevice($deviceID)
    {
        $builder = $this->db->table('smart_devices_guests');
        $builder->select('*');
        $builder->where('device_id', $deviceID);
        $query = $builder->get();
        $result = $query->getRowArray();
        return $this->decryptFields($result, $this->guestEnryption_fields);
    }

    public function getGuestsByDevice($deviceID)
    {
        $builder = $this->db->table('smart_devices_guests');
        $builder->select('email, can_control, guest_password');
        $builder->where('device_id', $deviceID);
        $query = $builder->get();
        $results = $query->getResultArray();
        return array_map(function ($result) {
            return $this->decryptFields($result, $this->guestEnryption_fields);
        }, $results);
    }
    
    public function getGuestDevicesByUserId($userId)
    {
        $builder = $this->db->table('smart_devices_guests');
        $builder->join('smart_devices', 'smart_devices.device_id = smart_devices_guests.device_id');
        $builder->where('smart_devices_guests.guest_id', $userId);
        $query = $builder->get();
        $results = $query->getResultArray();
        return array_map(function ($result) {
            return $this->decryptFields($result, $this->guestEnryption_fields);
        }, $results);
    }
    
    public function getGuestDevicesByUserIdAndControl($userId)
    {
        $canControlValue = 1;
    
        $builder = $this->db->table('smart_devices_guests');
        $builder->select('smart_devices.*');
        $builder->join('smart_devices', 'smart_devices.device_id = smart_devices_guests.device_id');
        $builder->where('smart_devices_guests.guest_id', $userId);
        $builder->where('smart_devices_guests.can_control', $canControlValue);
    
        $query = $builder->get();
        $results = $query->getResultArray();
        return array_map(function ($result) {
            return $this->decryptFields($result, $this->guestEnryption_fields);
        }, $results);
    }
    public function getGuestDeviceById($guestId)
    {
        $builder = $this->db->table('smart_devices_guests');
        $builder->select('*');
        $builder->where('id', $guestId);
        $query = $builder->get();
        $result = $query->getRowArray();
        return $this->decryptFields($result, $this->guestEnryption_fields);
    }

    public function getGuestsForDevice($deviceId)
    {
        $builder = $this->db->table('smart_devices_guests');
        $builder->select('*');
        $builder->where('device_id', $deviceId);
        $query = $builder->get();
        $results = $query->getResultArray();
        return array_map(function ($result) {
            return $this->decryptFields($result, $this->guestEnryption_fields);
        }, $results);
    }
    
    public function getGuestByDeviceAndEmail($deviceID, $email)
    {
        $builder = $this->db->table('smart_devices_guests');
        $builder->select('*');
        $builder->where('device_id', $deviceID);
        $builder->where('email', $email);
        $query = $builder->get();
        $result = $query->getRowArray();
        return $this->decryptFields($result, $this->guestEnryption_fields);
    }
    
    public function getGuestDevicePinDetails($userId, $deviceId)
    {
        $builder = $this->db->table('smart_devices_guests');
        $builder->select('guest_pin_enabled, guest_pin_code, guest_password');
        $builder->where('guest_id', $userId);
        $builder->where('device_id', $deviceId);
    
        $query = $builder->get();
        $result = $query->getRowArray();
        return $this->decryptFields($result, $this->guestEnryption_fields);
    }
    
    public function getGuestPasswordById($guestId)
    {
        $builder = $this->db->table('smart_devices_guests');
        $builder->select('guest_password');
        $builder->where('id', $guestId);
        $query = $builder->get();
        $result = $query->getRowArray();
        return $this->decryptFields($result, ['guest_password'])['guest_password'];
    }
    
    public function isGuestAddedToDevice($email, $deviceId, $excludeGuestId = null)
    {
        $builder = $this->db->table('smart_devices_guests');
        $builder->where('email', $email);
        $builder->where('device_id', $deviceId);
    
        // Exclude the current guest if $excludeGuestId is provided
        if ($excludeGuestId !== null) {
            $builder->where('id !=', $excludeGuestId);
        }
    
        $query = $builder->get();
        // If the query returns more than 0 rows, the guest is already added
        return $query->getNumRows() > 0;
    }

    public function addGuestToSmartDevice($guestData)
    {
        $builder = $this->db->table('smart_devices_guests');
        $guestData=$this->encryptData($guestData,$this->guestEnryption_fields);
        return $builder->insert($guestData);
    }
    public function updateGuest($guestData)
    {

        $builder = $this->db->table('smart_devices_guests');
        $builder->where('id', $guestData['id']);
        $guestData=$this->encryptData($guestData,$this->guestEnryption_fields);

        return $builder->update($guestData);
    }
    public function updateGuestSync($guestId,$data)
    {
        $builder = $this->db->table('smart_devices_guests');
        $builder->where('id', $guestId);
        $data=$this->encryptData($data,$this->guestEnryption_fields);

        return $builder->update($data);
    }
    public function updateGuestSpeech($deviceId, $userId, $pinEnabled, $speechPin)
    {
        $builder = $this->db->table('smart_devices_guests');
        $builder->where('device_id', $deviceId);
        $builder->where('guest_id', $userId);
        $data = [
            'guest_pin_enabled' => $pinEnabled,
            'guest_pin_code' => $speechPin
        ];
        $data=$this->encryptData($data,['guest_pin_code']);

        return $builder->update($data);
    }

    public function updateOwnerSpeech($deviceId, $pinEnabled, $speechPin)
    {
        $builder = $this->db->table('smart_devices');
        $builder->where('device_id', $deviceId);
        $data = [
            'pin_enabled' => $pinEnabled,
            'pin_code' => $speechPin
        ];
        $data=$this->encryptData($data,['pin_code']);

        return $builder->update($data);
    }
    public function deleteGuest($guestId)
    {
        $builder = $this->db->table('smart_devices_guests');
        $builder->where('id', $guestId);
        return $builder->delete();
    }
    public function deleteGuestDevice($deviceId,$userId)
    {
        $builder = $this->db->table('smart_devices_guests');
        $builder->where('device_id', $deviceId);
        return $builder->delete();
    }
    public function deleteGuestByDeviceAndEmail($deviceId,$email)
    {
        $builder = $this->db->table('smart_devices_guests');
        $builder->where('device_id', $deviceId);
        $builder->where('email', $email);

        return $builder->delete();
    }
    public function deleteGuestByDeviceAndUserId($deviceId,$userID)
    {
        $builder = $this->db->table('smart_devices_guests');
        $builder->where('device_id', $deviceId);
        $builder->where('guest_id', $userID);

        return $builder->delete();
    }
    public function isUserGuestOfDevice($userID, $deviceID)
    {
        $builder = $this->db->table('smart_devices_guests');
        $builder->select('*');
        $builder->where('device_id', $deviceID);
        $builder->where('guest_id', $userID);
    
        $query = $builder->get();
        $result = $query->getRowArray();
    
        return $result !== null; // Returns true if a record is found, otherwise false
    }

    public function connectGoogleHome($userId)
    {
        $builder = $this->db->table('smart_devices');
        $builder->set('google_linked', 1); // Assuming you are using a boolean type
        $builder->where('device_id', $userId);
        return $builder->update(); // This will return true if the update was successful
    }
    public function disconnectGoogleHome($userId)
    {
        $builder = $this->db->table('smart_devices');
        $builder->set('google_linked', 'false'); // Assuming you are using a boolean type
        $builder->where('user_id', $userId);
        return $builder->update(); // This will return true if the update was successful
    }

    public function checkGoogleLinkWithSerial($serial)
    {
        $serial=encryptDataWithFixedIV($serial,$this->encryptionKey);
        $builder = $this->db->table('smart_devices');
        $builder->select('google_linked'); // Select only the google_linked column since that's what we need.
        $builder->where('serial_number', $serial);
        $builder->where('google_linked', 1); // Add a condition to check for google_linked = 1
    
        $query = $builder->get();
        $result = $query->getRowArray();
    
        // Instead of returning the device data, return true if a device is found, otherwise false.
        return $result ? true : false;
    }
    private function encryptData($data, $keysToEncrypt = null) {
        $encryptedData = [];
        foreach ($data as $key => $value) {
            
            if ($keysToEncrypt === null || in_array($key, $keysToEncrypt)) {
                $encryptedData[$key] = encryptData($value, $this->encryptionKey);
            } else {
                $encryptedData[$key] = $value;
            }
        }
        return $encryptedData;
    }
    
    

    private function decryptFields($data, $fieldsToDecrypt) {
        foreach ($fieldsToDecrypt as $field) {
            if (isset($data[$field])) {
                if (!empty($data[$field]) && is_string($data[$field])) {
                    $data[$field] = decryptData($data[$field], $this->encryptionKey);
                }
                // $this->logger->alert('Decrypted:'.$data[$field] );
            }
            
        }
        return $data;
    }


}