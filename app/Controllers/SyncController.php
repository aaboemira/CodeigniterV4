<?php
namespace App\Controllers;

use App\Models\Public_model;
use CodeIgniter\RESTful\ResourceController;
use App\Models\admin\Auth_model;

class SyncController extends ResourceController
{
    protected $authModel;
    protected $publicModel;

    public function __construct()
    {
        helper('api_helper');
        $this->authModel = new Auth_model();
        $this->publicModel = new Public_model();
    }

    
    public function fulfillment()
    {
        $jsonData = $this->request->getJSON(true);

        // Validate the access token first
        $accessToken = $this->getAccessTokenFromHeader();
        $userId = $this->validateToken($accessToken);
        if (!$userId) {
            return $this->failUnauthorized("Invalid or missing access token");
        }

        // Extract requestId and intent from the request
        $requestId = $jsonData['requestId'] ?? null;
        $intent = $jsonData['intent'] ?? null;

        // Check if requestId is present
        if (is_null($requestId)) {
            return $this->fail('Missing requestId', 400);
        }
        // Route the request to the appropriate method based on the intent
        switch ($intent) {
            case 'CHECK_SYNC':
                return $this->syncCheck($requestId, $userId);
            case 'SYNC':
                return $this->sync($requestId, $userId);
            case 'UPLOAD_SYNC':
                return $this->uploadSync($requestId, $userId, $jsonData);
            default:
                return $this->fail('Unknown intent', 400);
        }
    }

    public function sync($requestId,$intent)
    {
        $jsonData = $this->request->getJSON(true);
        $requestId = $jsonData['requestId'] ?? null;
    
        // Check if requestId is present
        if (is_null($requestId)) {
            return $this->fail('Missing requestId', 400);
        }
    
        $accessToken = $this->getAccessTokenFromHeader();
        $userId = $this->validateToken($accessToken);
    
        if (!$userId) {
            return $this->failUnauthorized("Invalid or missing access token");
        }
    
        // Retrieve the list of devices for the user
        $devices = $this->publicModel->getSmartHomeDevicesByUID($userId);
    
        // Format the devices array
        $formattedDevices = array_map(function ($device) {
            return [
                    'device_name' => $device['device_name'],
                    'serial_number' => $device['serial_number'],
                    'UID' => decryptData($device['UID'] , '@@12@@'),
                    'password' => decryptData($device['password'] , '@@12@@'),
                
                // Add other device details as needed
            ];
        }, $devices);
    
        // Construct the response
        $response = [
            'requestId' => $requestId,
            'payload' => [
                'devices' => $formattedDevices,
            ],
        ];
    
        return $this->response->setJSON($response);
    }
    public function syncCheck($requestId,$intent)
    {
        $jsonData = $this->request->getJSON(true);
    
        // Extract requestId and intent from the request
        $requestId = $jsonData['requestId'] ?? null;
        $intent = $jsonData['intent'] ?? null;
    
        // Check if requestId is present
        if (is_null($requestId)) {
            return $this->fail('Missing requestId', 400);
        }
    
        // Validate the access token first
        $accessToken = $this->getAccessTokenFromHeader();
        $userId = $this->validateToken($accessToken);
        if (!$userId) {
            return $this->failUnauthorized("Invalid or missing access token");
        }
    
        // Proceed with SYNC intent
        $lastUpdate = $this->publicModel->getLatestDeviceUpdateForUser($userId);
        if (is_null($lastUpdate)) {
            // No devices found for this user, return an error message
            return $this->fail("No devices found for this user.");
        }
    
        // Construct the response with a nested payload
        $response = [
            'requestId' => $requestId,
            'payload' => [
                'last_update' => $lastUpdate,
            ],
        ];
    
        return $this->response->setJSON($response);
    }
    // public function uploadSync($requestId,$intent,$jsonData)
    // {
    //     $jsonData = $this->request->getJSON(true);
    //     $requestId = $jsonData['requestId'] ?? null;
        
    //     // Check if requestId is present
    //     if (is_null($requestId)) {
    //         return $this->fail('Missing requestId', 400);
    //     }
        
    //     $accessToken = $this->getAccessTokenFromHeader();
    //     $userId = $this->validateToken($accessToken);
        
    //     if (!$userId) {
    //         return $this->failUnauthorized("Invalid or missing access token");
    //     }
        
    //     // Extract the list of devices from the request payload
    //     $devicesList = $jsonData['payload']['devices'] ?? null;
        
    //     // Check if devices list is present
    //     if (is_null($devicesList)) {
    //         return $this->fail('Missing devices list', 400);
    //     }

        
    //     foreach ($devicesList as $deviceData) {
    //         // Here you should validate and sanitize the device data before processing
    //         $serial = $deviceData['serial_number'];
    //         $deviceName = $deviceData['device_name'];
    //         $UID = $deviceData['UID'];
            
    //         // You may need to decrypt or process the UID and password if they are encrypted in the request
    //         $passwordEncrypted = encryptData($deviceData['password'], '@@12@@');
    //         $UIDEncrypted = encryptData($UID , '@@12@@');

    //         // Check if the device already exists, update if it does, or create a new one
    //         $existingDevice = $this->publicModel->getSmartDeviceBySerialAndUserId($userId,$serial);
    //         if ($existingDevice) {
    //             // Update device
    //             $updateData = [
    //                 'device_name' => $deviceName,
    //                 'UID' => $UIDEncrypted ,
    //                 'password' => $passwordEncrypted,
    //                 'device_id'=>$existingDevice['device_id'],
    //                 'serial_number'=>$serial
    //             ];
    //             $this->publicModel->updateSmartDevice($updateData);
    //         } else {
    //             // Add new device
    //             $newDeviceData = [
    //                 'user_id' => $userId,
    //                 'device_name' => $deviceName,
    //                 'serial_number' => $serial,
    //                 'UID' => $UIDEncrypted,
    //                 'password' => $passwordEncrypted,
    //             ];
    //             $this->publicModel->saveSmartDevice($newDeviceData);
    //         }
    //     }
        
        
        
    //     // If all devices have been processed successfully, construct a success response
    //     $response = [
    //         'requestId' => $requestId,
    //         'status' => 'success',
    //         'message' => 'Devices have been synced successfully',
    //     ];
        
    //     return $this->response->setJSON($response);
    // }
    public function uploadSync($requestId, $intent, $jsonData)
    {
        $jsonData = $this->request->getJSON(true);
        $requestId = $jsonData['requestId'] ?? null;
        
        if (is_null($requestId)) {
            return $this->fail('Missing requestId', 400);
        }
        
        $accessToken = $this->getAccessTokenFromHeader();
        $userId = $this->validateToken($accessToken);
        
        if (!$userId) {
            return $this->failUnauthorized("Invalid or missing access token");
        }
        
        $devicesList = $jsonData['payload']['devices'] ?? null;
        
        if (is_null($devicesList)) {
            return $this->fail('Missing devices list', 400);
        }
    
        // Retrieve current devices for cleanup later
        $currentDevices = $this->publicModel->getSmartHomeDevicesByUID($userId);
        $currentSerialNumbers = array_column($currentDevices, 'serial_number');
        $uploadedSerialNumbers = [];
        
        foreach ($devicesList as $deviceData) {
            $serial = $deviceData['serial_number'];
            $deviceName = $deviceData['device_name'];
            $UID = encryptData($deviceData['UID'], '@@12@@');
            $password = encryptData($deviceData['password'], '@@12@@');
            $uploadedSerialNumbers[] = $serial;
    
            // Check if device exists
            $existingDevice = $this->publicModel->getSmartDeviceBySerialAndUserId($userId, $serial);
            if ($existingDevice) {
                // Update device
                $updateData = [
                    'device_name' => $deviceName,
                    'UID' => $UID ,
                    'password' => $password,
                    'device_id'=>$existingDevice['device_id'],
                    'serial_number'=>$serial
                ];
                $this->publicModel->updateSmartDevice($updateData);
            } else {
                // Add new device
                $newDeviceData = [
                    'user_id' => $userId,
                    'device_name' => $deviceName,
                    'serial_number' => $serial,
                    'UID' => $UID,
                    'password' => $password,
                ];
                $this->publicModel->saveSmartDevice($newDeviceData);
            }
        }
        
        // Delete devices not included in the upload
        $devicesToDelete = array_diff($currentSerialNumbers, $uploadedSerialNumbers);
        foreach ($devicesToDelete as $serial) {
            $this->publicModel->deleteSmartDeviceBySerialAndUserId($userId, $serial);
        }
        
        $response = [
            'requestId' => $requestId,
            'status' => 'success',
            'message' => 'Device sync complete. Devices not included in the upload have been removed.'
        ];
        
        return $this->response->setJSON($response);
    }
    
    private function validateToken($accessToken) {
        if (!$accessToken) {
            return false;
        }
    
        // Use the Auth_model from the member variable
        $userId = $this->authModel->validateAccessToken($accessToken);
        if ($userId) {
            return $userId; // Token is valid, return the user ID associated with this token
        }
        return false; // Token is invalid or expired
    }
    private function getAccessTokenFromHeader() {
        $authHeader = $this->request->getHeaderLine('Authorization');
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return $matches[1];
        }
        return false;
    }
}