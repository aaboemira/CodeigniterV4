<?php
namespace App\Controllers;

use App\Models\Public_model;
use CodeIgniter\RESTful\ResourceController;
use App\Models\admin\Auth_model; // Add the use statement for Auth_model

class SmartHomeController extends ResourceController
{
    protected $authModel; // Add a protected member variable for Auth_model
    protected $publicModel;
    private  $url = 'http://localhost:8012/dashboard/enddevice/Enddevice.php';
    private $errorCodeMapping = [
        -1 => 'serviceOutage', // Assuming a custom TTS code; adjust as necessary
        -2 => 'deviceOffline',
        -3 => 'deviceOffline',
        -4 => 'authFailure',
        -5 => 'authFailure', // Assuming UID issues can be considered an authentication failure
    ];
    public function __construct()
    {
        helper('api_helper');

        // Instantiate Auth_model and store it in the member variable
        $this->authModel = new Auth_model();
        $this->publicModel = new Public_model();

    }
    public function fulfillment()
    {
        $jsonData = $this->parseInputData();
        if (!$jsonData) {
            return $this->fail('Invalid input data', 400);
        }

        $accessToken = $this->getAccessTokenFromHeader();
        $userId = $this->validateToken($accessToken);
        if (!$userId) {
            return $this->failUnauthorized("Invalid or missing access token");
        }

        $intent = $jsonData['inputs'][0]['intent'] ?? null;
        switch ($intent) {
            case 'action.devices.SYNC':
                return $this->sync($jsonData);
            case 'action.devices.QUERY':
                return $this->query($jsonData);
            case 'action.devices.EXECUTE':
                return $this->execute($jsonData);
            default:
                return $this->fail('Unknown intent', 400);
        }
    }
    private function sync($jsonData)
    {
    
        
        // Assume $jsonData contains the SYNC intent structure
        $accessToken = $this->getAccessTokenFromHeader();
        $userId = $this->validateToken($accessToken);
        if (!$userId) {
            return $this->failUnauthorized("Invalid or missing access token");
        }
        $userDevices = $this->publicModel->getSmartHomeDevicesByUID($userId);
        $devices = [];
        foreach ($userDevices as $device) {
            // Map your device data to the expected SYNC response format
            $devices[] = [
                'id' => $device['device_id'], // Use the correct field for your device's unique identifier
                'type' => 'action.devices.types.GARAGE', // Replace with actual type if necessary
                'traits' => [
                    'action.devices.traits.OpenClose',
                ],
                'name'=>[
                    'name'=>$device['device_name']
                ],
                'willReportState' => false, // Whether the device reports state changes to Google
            ];
        }
        // Example response structure
        $response = [
            'requestId' => $jsonData['requestId'], // Echo back the requestId from the request
            'payload' => [
                'agentUserId' => $userId, // An immutable user ID unique to the user's account on your platform
                'devices' => $devices
            ],
        ];
        
    
        return $this->response->setJSON($response);
    }
    

    private function query($jsonData) {
        // Process each device in the request
        $deviceStates = [];
        foreach ($jsonData['inputs'][0]['payload']['devices'] as $device) {
            $deviceId = $device['id'];
    
            // Use the getDeviceStatus function to get the latest status
            $deviceStatus = $this->getDeviceStatus($deviceId);
    
            if (!isset($deviceStatus['status']) || $deviceStatus['status'] !== 0) {
                // Handle error in device status response
                $errorCode=$this->errorCodeMapping[$deviceStatus['status']];
                $deviceStates[$deviceId] = [
                    'status' => 'ERROR',
                    'errorCode' => $errorCode,
                ];
            } else {
                $deviceStates[$deviceId] = [
                    'status' => 'SUCCESS',
                    'online' => TRUE, // Assuming 'online' means the gate is open
                    'openPercent' => $deviceStatus['data']['percent'] ?? 0,
                ];
            }
        }
    
        $response = [
            'requestId' => $jsonData['requestId'],
            'payload' => [
                'devices' => $deviceStates,
            ],
        ];
    
        return $this->response->setJSON($response);
    }
    
    private function execute($jsonData)
    {
        $commands = [];
        foreach ($jsonData['inputs'][0]['payload']['commands'] as $commandGroup) {
            $devices = $commandGroup['devices'];
            $executions = $commandGroup['execution'];
    
            foreach ($devices as $device) {
                $deviceId = $device['id'];
                foreach ($executions as $execution) {
                    $command = $execution['command'];
                    $params = $execution['params'];
                    
                    // Default to a "close" action; adjust as needed
                    $action = 'close';
                    if (isset($params['openPercent']) && $params['openPercent'] > 0) {
                        $action = 'open';
                    }
    
                    // Attempt to control the device
                    $controlResult = $this->controlDevice($deviceId, $action);
    
                    // Assuming controlDevice() now returns an array with 'status' and optionally 'message'
                    if ($controlResult['status'] === 0) {
                        // On success, fetch the updated status to reflect in the command response
                        $deviceStatus = $this->getDeviceStatus($deviceId);
                        $online = true;
                        $openPercent = $deviceStatus['data']['percent'] ?? 0;
    
                        $commands[] = [
                            'ids' => [$deviceId],
                            'status' => 'SUCCESS',
                            'states' => [
                                'openPercent' => $openPercent,
                                'online' => $online,
                            ],
                        ];
                    } else {
                        // On error, map the error code to a Google TTS error code
                        $errorCode = $this->errorCodeMapping[$controlResult['status']];
                        $commands[] = [
                            'ids' => [$deviceId],
                            'status' => 'ERROR',
                            'errorCode' => $errorCode,
                        ];
                    }
                }
            }
        }
    
        $response = [
            'requestId' => $jsonData['requestId'],
            'payload' => [
                'commands' => $commands,
            ],
        ];
    
        return $this->response->setJSON($response);
    }
    


    
    
    private function getAccessTokenFromHeader() {
        $authHeader = $this->request->getHeaderLine('Authorization');
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return $matches[1];
        }
        return false;
    }
    private function getDeviceStatus($deviceId){
        $device = $this->publicModel->getSmartDeviceById($deviceId);
        $devicePassword = decryptData($device['password'], '@@12@@');
         // Call the 'get status' API
         $data = [
            'serial' => $device['serial_number'],
            'uid' => decryptData($device['UID'], '@@12@@'),
            'password' => $devicePassword ,
            'api' => 'get_status'
        ];
        $response = callAPI('POST', $this->url, $data);
        $responseData = json_decode($response, true);
        return $responseData;
    }
    private function controlDevice($deviceId,$action){


        // Fetch device data by ID (assuming you have a method like getSmartDeviceById)
        $device = $this->publicModel->getSmartDeviceById($deviceId);

        $devicePassword = decryptData($device['password'], '@@12@@');

        // Default password is the device password
        $password = $devicePassword;
    

        $dataValue = '00';

        // Map the action to the corresponding data value
        switch ($action) {
            case 'open':
                $dataValue = '64';
                break;
            case 'stop':
                $dataValue = 'FF';
                break;
            // Add more cases if needed
            default:
                // For 'close' action or any other action not specified, keep the default value
                break;
        }
        // Prepare data for external API request
        $apiData = [
            'serial' => $device['serial_number'],
            'uid' => decryptData($device['UID'], '@@12@@'),
            'password' => $password,
            'api' => 'control', // Change this to the actual API endpoint for device control
            'action' => '100', // Assuming action 100 is used for control_device
            'data' => $dataValue,
        ];
        // Make the API request
        $response = callAPI('POST', $this->url, $apiData);
        // Handle the response as needed
        $responseData = json_decode($response, true);


        // Send the response back to the client (optional)
        return $responseData;
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
    private function parseInputData()
    {
        $contentType = $this->request->getHeaderLine('Content-Type');
        if (strpos($contentType, 'application/json') !== false) {
            return $this->request->getJSON(true);
        } elseif (strpos($contentType, 'multipart/form-data') !== false) {
            $data = $this->request->getPost('data'); // Assuming JSON data is provided in a field named 'data'
            if ($data) {
                return json_decode($data, true);
            }
        }
        return false; // Return false if the content type is unsupported or data is missing
    }
}
