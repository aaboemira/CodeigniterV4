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

    public function sync($requestId, $userId)
    {
        // Retrieve the list of devices owned by the user
        $ownedDevices = $this->publicModel->getSmartHomeDevicesByUID($userId);
    
        // Format the owned devices array
        $formattedOwnedDevices = array_map(function ($device) {
            return [
                'device_name' => $device['device_name'],
                'serial_number' => $device['serial_number'],
                'UID' => $device['UID'],
                'password' => $device['password'],
                'is_guest' => 0,
                'can_control'=> 1,
                'pin_enabled' => $device['pin_enabled'], // Add this line
                'pin_code' => $device['pin_code'] // Add this line
            ];
        }, $ownedDevices);
    
        // Retrieve the list of devices where the user is a guest
        $guestDevices = $this->publicModel->getGuestDevicesByUserId($userId);
    
        // Format the guest devices array
        // Format the guest devices array
        $formattedGuestDevices = array_map(function ($device) {
            return [
                'device_name' => $device['device_name'],
                'serial_number' => $device['serial_number'],
                'UID' => $device['UID'],
                'password' => $device['guest_password'],
                'is_guest' => 1,
                'can_control' => $device['can_control'],
                'pin_enabled' => $device['guest_pin_enabled'], // Add this line
                'pin_code' => $device['guest_pin_code'] // Add this line
            ];
        }, $guestDevices);

    
        // Combine owned and guest devices
        $allDevices = array_merge($formattedOwnedDevices, $formattedGuestDevices);
    
        // Construct the response
        $response = [
            'requestId' => $requestId,
            'payload' => [
                'devices' => $allDevices,
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
        $lastUpdate = $this->publicModel->getLatestUpdateForUser($userId);

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
    
        $devicesList = $jsonData['payload']['devices'] ?? [];
        $guestDevicesList = $jsonData['payload']['guest_devices'] ?? [];
    
        try {
            // Process owned devices
            $this->processDevices($devicesList, $userId);
    
            // Process guest devices
            $this->processGuestDevices($guestDevicesList, $userId);
    
            $response = [
                'requestId' => $requestId,
                'status' => 'success',
                'message' => 'Device and guest device sync complete.'
            ];
        } catch (\Exception $e) {
            // Catch the exception and return the error response
            return $this->fail($e->getMessage(), $e->getCode());
        }
    
        return $this->response->setJSON($response);
    }
    



    private function processDevices($devicesList, $userId)
    {
        foreach ($devicesList as $deviceData) {
            $requiredFields = ['serial_number', 'device_name', 'UID', 'password', 'pin_enabled', 'pin_code'];
            foreach ($requiredFields as $field) {
                if (!isset($deviceData[$field])) {
                    throw new \Exception("Missing field '{$field}' in device data", 400);
                }
            }
            $serial = $deviceData['serial_number'];
            $deviceName = $deviceData['device_name'];
            $UID = $deviceData['UID'];
            $password = $deviceData['password'];
            $pinEnabled = $deviceData['pin_enabled'] ?? 0; // Add this line
            $pinCode = $deviceData['pin_code'] ?? ''; // Add this line
    
            // Check if the device already exists for the user
            $existingDevice = $this->publicModel->getSmartDeviceBySerialAndUserId($userId, $serial);
            if ($existingDevice) {
                // Update the existing device
                $updateData = [
                    'device_name' => $deviceName,
                    'UID' => $UID,
                    'password' => $password,
                    'serial_number' => $serial,
                    'device_id' => $existingDevice['device_id'],
                    'pin_enabled' => $pinEnabled, // Add this line
                    'pin_code' => $pinCode // Add this line
                ];
                $this->publicModel->updateSmartDevice($updateData);
            } else {
                // Add a new device for the user
                $newDeviceData = [
                    'user_id' => $userId,
                    'device_name' => $deviceName,
                    'serial_number' => $serial,
                    'UID' => $UID,
                    'password' => $password,
                    'pin_enabled' => $pinEnabled, // Add this line
                    'pin_code' => $pinCode // Add this line
                ];
                $this->publicModel->saveSmartDevice($newDeviceData);
            }
        }
    
        // Cleanup devices not included in the upload
        $this->cleanupDevices($userId, $devicesList);
    }
    
    
    private function processGuestDevices($guestDevicesList, $userId)
    {
        foreach ($guestDevicesList as $guestDeviceData) {
            $requiredFields = ['serial_number', 'guest_password', 'can_control', 'pin_enabled', 'pin_code'];
            foreach ($requiredFields as $field) {
                if (!isset($guestDeviceData[$field])) {
                    throw new \Exception("Missing field '{$field}' in guest device data", 400);
                }
            }
            $serial = $guestDeviceData['serial_number'];
            $password = $guestDeviceData['guest_password'];
            $canControl = $guestDeviceData['can_control'] ?? 1;
            $userEmail = $this->publicModel->getUserEmail($userId);
            $pinEnabled = $guestDeviceData['pin_enabled'] ?? 0; // Add this line
            $pinCode = $guestDeviceData['pin_code'] ?? ''; // Add this line
    
            $device = $this->publicModel->getSmartDeviceBySerial($serial);
            if ($device) {
                $existingGuest = $this->publicModel->getGuestByDeviceAndEmail($device['device_id'], $userEmail);
                if ($existingGuest) {
                    // Update guest
                    $guestUpdateData = [
                        'can_control' => $canControl,
                        'guest_password' => $password,
                        'guest_pin_enabled' => $pinEnabled, // Add this line
                        'guest_pin_code' => $pinCode // Add this line
                    ];
                    $this->publicModel->updateGuestSync($existingGuest['id'], $guestUpdateData);
                } else {
                    // Add new guest
                    $newGuestData = [
                        'device_id' => $device['device_id'],
                        'email' => $userEmail,
                        'can_control' => $canControl,
                        'guest_password' => $password,
                        'guest_id' => $userId,
                        'guest_pin_enabled' => $pinEnabled, // Add this line
                        'guest_pin_code' => $pinCode // Add this line
                    ];
                    $this->publicModel->addGuestToSmartDevice($newGuestData);
                }
            } else {
                throw new \Exception("Guest device with serial number '{$serial}' not found", 400);
            }
        }
        // Cleanup guest devices not included in the upload
        $this->cleanupGuestDevices($userId, $guestDevicesList);
    }
    

    private function cleanupDevices($userId, $devicesList)
    {
        $currentDevices = $this->publicModel->getSmartHomeDevicesByUID($userId);
        $currentSerialNumbers = array_column($currentDevices, 'serial_number');
        $uploadedSerialNumbers = array_column($devicesList, 'serial_number');

        $devicesToDelete = array_diff($currentSerialNumbers, $uploadedSerialNumbers);
        foreach ($devicesToDelete as $serial) {
            $this->publicModel->deleteSmartDeviceBySerialAndUserId($userId, $serial);
        }
    }

    private function cleanupGuestDevices($userId, $guestDevicesList)
    {
        $allGuestDeviceSerials = array_column($guestDevicesList, 'serial_number');
        $currentGuestDevices = $this->publicModel->getGuestDevicesByUserId($userId);
        $currentGuestDeviceSerials = array_column($currentGuestDevices, 'serial_number');
    
        $guestDevicesToDelete = array_diff($currentGuestDeviceSerials, $allGuestDeviceSerials);
        foreach ($guestDevicesToDelete as $serial) {
            $device = $this->publicModel->getSmartDeviceBySerial($serial);
            if ($device) {
                $this->publicModel->deleteGuestByDeviceAndUserId($device['device_id'], $userId);
            }
        }
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