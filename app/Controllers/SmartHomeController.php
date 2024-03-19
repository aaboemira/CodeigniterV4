<?php
namespace App\Controllers;

use App\Models\Public_model;
use CodeIgniter\RESTful\ResourceController;
use App\Models\admin\Auth_model; // Add the use statement for Auth_model

class SmartHomeController extends ResourceController
{
    protected $authModel; // Add a protected member variable for Auth_model
    protected $publicModel;
    protected  $url ;
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
        $this->url = getenv('SMART_DEVICES_API');


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
            case 'action.devices.DISCONNECT':
                return $this->disconnect($jsonData);
            default:
                return $this->fail('Unknown intent', 400);
        }
    }
    private function sync($jsonData)
    {
        
        $this->logger->alert("-------------------Enter Sync--------------");

        // Assume $jsonData contains the SYNC intent structure
        $accessToken = $this->getAccessTokenFromHeader();
        $userId = $this->validateToken($accessToken);
        if (!$userId) {
            return $this->failUnauthorized("Invalid or missing access token");
        }
        $userDevices = $this->publicModel->getSmartHomeDevicesByUID($userId);
        $guestDevices = $this->publicModel->getGuestDevicesByUserIdAndControl($userId, true);
    
        // Combine the user's own devices and guest devices
        $allDevices = array_merge($userDevices, $guestDevices);
        $devices = [];
        foreach ($allDevices as $device) {
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
                'willReportState' => true, // Whether the device reports state changes to Google
            ];
            $result=$this->publicModel->connectGoogleHome($device['device_id']);
            $this->logger->alert("Connect Google Home with id {$device['device_id']}, Serial number{$device['serial_number']} result:{$result}");
        }
        // Example response structure
        $response = [
            'requestId' => $jsonData['requestId'], // Echo back the requestId from the request
            'payload' => [
                'agentUserId' => $userId, // An immutable user ID unique to the user's account on your platform
                'devices' => $devices
            ],
        ];
        
            $this->reportStateToApi($userId, 'on');

        return $this->response->setJSON($response);
        $this->logger->alert("-------------------End Sync--------------");

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
                    $pin = isset($execution['challenge']) && isset($execution['challenge']['pin']) ? $execution['challenge']['pin'] : null;

                    // Attempt to control the device
                    $controlResult = $this->controlDevice($deviceId, $params['openPercent'], $pin);
    
                    if ($controlResult['status'] === 'pin_required') {
                        // Handle the case where a pin is required
                        $commands[] = [
                            'ids' => [$deviceId],
                            'status' => 'ERROR',
                            'errorCode' => 'challengeNeeded',
                            'challengeNeeded' => [
                                'type' => 'pinNeeded'
                            ]
                        ];
                    } elseif ($controlResult['status'] === 'incorrect_pin') {
                        // Handle the case where the pin is incorrect
                        $commands[] = [
                            'ids' => [$deviceId],
                            'status' => 'ERROR',
                            'errorCode' => 'challengeNeeded',
                            'challengeNeeded' => [
                                'type' => 'challengeFailedPinNeeded'
                            ]
                        ];
                    } elseif ($controlResult['status'] === 0) {
                        // Handle successful control
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
                        // Handle other errors
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
        $this->logger->alert($response);
        $responseData = json_decode($response, true);
        return $responseData;
    }
    private function controlDevice($deviceId,$action,$pin = null){

        $accessToken = $this->getAccessTokenFromHeader();
        $userID=$this->authModel->getUserIdFromAccessToken($accessToken);
        $guest=$this->publicModel->getGuestDevicePinDetails($userID,$deviceId);
        
        // Fetch device data by ID (assuming you have a method like getSmartDeviceById)
        $device = $this->publicModel->getSmartDeviceById($deviceId);
        if($guest!=null){
            $device['pin_enabled']=$guest['guest_pin_enabled'];
            $device['pin_code']=$guest['guest_pin_code'];
            $device['password']=$guest['guest_password'];
        }
        if ($device['pin_enabled']) {
            if ($pin === null) {
                return ['status' => 'pin_required'];  // Indicate that a pin is required
            } elseif ($pin !== $device['pin_code']) {
                return ['status' => 'incorrect_pin'];  // Indicate that the pin is incorrect
            }
        }
        $devicePassword = decryptData($device['password'], '@@12@@');

        // Default password is the device password
        $password = $devicePassword;
    

        $dataValue = dechex($action);
        $dataValue = str_pad($dataValue, 2, '0', STR_PAD_LEFT);
        $this->logger->alert("openpercent:{$dataValue}");

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
        $this->logger->alert($response);
        $this->logger->alert($apiData);

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
    public function disconnect() {
        // Extract the access token from the header to identify the user
        $accessToken = $this->getAccessTokenFromHeader();
        $userId = $this->validateToken($accessToken);
        // $this->logger->alert("DIsconect Recieved");

        if (!$userId) {
            $this->logger->alert("DIsconect UnAuthorized");

            return $this->failUnauthorized("Invalid or missing access token");
        }
        // $this->logger->alert("DIsconect Authorized");
        // Set google_linked to false for all devices associated with this user
        $this->publicModel->disconnectGoogleHome($userId);
        $this->logger->alert("DIsconect User with id:{$userId}");
        $this->reportStateToApi($userId, 'off');

        // Return an empty object with a success HTTP status code
        return $this->response->setJSON([])->setStatusCode(200);
    }
    
    protected function reportStateToApi($userId, $reportStateValue) {
        $this->logger->alert("-------------------Enter Sending Report State  (Start Reporting)--------------");

        $apiUrl = getenv('SMART_DEVICES_API');
        $apiSecret = getenv('API_SECRET'); // Assuming you have your API secret stored in .env
        
        // Fetch both owned and guest devices with control access
        $ownedDevices = $this->publicModel->getSmartHomeDevicesByUID($userId);
        $guestDevices = $this->publicModel->getGuestDevicesByUserIdAndControl($userId, true);
        
        // Combine both owned and guest devices
        $allDevices = array_merge($ownedDevices, $guestDevices);
        $this->logger->alert($allDevices);
    
        foreach ($allDevices as $device) {
    
            $data = [
                'serial' => $device['serial_number'],
                'api' => 'report_state',
                'report_state' => $reportStateValue,
                'api_secret' => $apiSecret,
            ];
            $this->logger->alert("Request of report state ");

            $this->logger->alert($data);
            $this->logger->alert("End Of Request----------------------- ");

            $response = callAPI('POST', $apiUrl, $data); // callAPI function from api_helper
            $this->logger->alert("Response of report state ");

            $this->logger->alert($response);
            $this->logger->alert("End Of Response----------------------- ");

            $responseData = json_decode($response, true);
            
            if (isset($responseData['status']) && $responseData['status'] != 0) {
                $this->logger->alert("Error for device {$device['serial_number']}: {$responseData['message']}");
            } else {
                if (isset($responseData['data']['code']) && $responseData['data']['code'] != 0) {
                    $this->logger->alert("Error code for device {$device['serial_number']}: {$responseData['data']['code']}");
                }
            }
        }
        $this->logger->alert("-------------------End Sending Report State  (Start Reporting)--------------");

    }
    

    
}
