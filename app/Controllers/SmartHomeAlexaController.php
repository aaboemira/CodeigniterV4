<?php
namespace App\Controllers;

use App\Models\Public_model;
use CodeIgniter\RESTful\ResourceController;
use App\Models\admin\Auth_model; // Add the use statement for Auth_model
use App\Models\SmartDevice_model;

class SmartHomeAlexaController extends ResourceController
{
	protected $logger;
    protected $authModel; // Add a protected member variable for Auth_model
    protected $publicModel;
    protected $smartDevice;
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
	    $this->logger = service('logger');  
        helper('api_helper');

        // Instantiate Auth_model and store it in the member variable
        $this->authModel = new Auth_model();
        $this->publicModel = new Public_model();
        $this->smartDevice = new SmartDevice_model();

        $this->url = getenv('SMART_DEVICES_API');
    }


    public function fulfillment()
    {
        $jsonData = $this->parseInputData();
        if (!$jsonData) {
            return $this->fail('Invalid input data', 400);
        }
        $this->logger->alert("Request is");
        $this->logger->alert($jsonData );
        $this->logger->alert("-------------------");

        $accessToken=$this->extractTokenFromJson($jsonData);
        $this->logger->alert("Token extracted is ");

        $this->logger->alert($accessToken);
        $this->logger->alert("-------------------");

        $userId = $this->validateToken($accessToken);
        if (!$userId) {
            $this->logger->alert("Invalid token");

            return $this->failUnauthorized("Invalid or missing access token");
        }

        $intent = $jsonData['directive']['header']['name'] ?? null;
        $directiveMessageId = $jsonData['directive']['header']['messageId'];

        switch ($intent) {
            case 'Discover':
                return $this->discover($userId,$directiveMessageId);
            case 'ReportState':
                return $this->reportState($jsonData);
            case 'Lock':
                return $this->lock($jsonData);
            case 'Unlock':
                return $this->unlock($jsonData);
            default:
                return $this->fail('Unknown intent', 400);
        }
    }
    private function discover($userId,$messageId)
    {       
        $this->logger->alert("Report Recieved for user :{$userId} ");

        $userDevices = $this->smartDevice->getSmartHomeDevicesByUID($userId);
        $guestDevices = $this->smartDevice->getGuestDevicesByUserIdAndControl($userId, true);
    
        // Combine the user's own devices and guest devices
        $allDevices = array_merge($userDevices, $guestDevices);
        $formattedDevices = array_map([$this, 'formatDeviceForDiscovery'], $allDevices);
        $this->logger->alert("Formatted Devices:");
        $this->logger->alert($formattedDevices);
        $this->logger->alert("-----------------------");

        $discoveryResponse = [
            'event' => [
                'header' => [
                    'namespace' => 'Alexa.Discovery',
                    'name' => 'Discover.Response',
                    'payloadVersion' => '3',
                    'messageId' => $messageId 
                ],
                'payload' => [
                    'endpoints' => $formattedDevices
                ],
            ],
        ];
        
        $this->logger->emergency("Discovery response: " . json_encode($discoveryResponse));

        return $this->response->setJSON($discoveryResponse);
    }
    
    private function reportState($jsonData)
    {
        $deviceId = $jsonData['directive']['endpoint']['endpointId'];
        $deviceStatus = $this->getDeviceStatus($deviceId);
        $directiveMessageId = $jsonData['directive']['header']['messageId'];
        $accessToken = $this->extractTokenFromJson($jsonData);
    
        $response = $this->formatReportStateResponse($deviceStatus, $directiveMessageId, $deviceId, $accessToken);
        $this->logger->alert("Report Response To Alexa Skill:");
        $this->logger->alert(json_encode($response, JSON_PRETTY_PRINT));
        $this->logger->alert("--------------------------");
        return $this->response->setJSON($response);
    }
    

    private function lock($jsonData)
    {
        $deviceId = $jsonData['directive']['endpoint']['endpointId'];
        $this->logger->alert("Lock Recieved For device({$deviceId}) ");

        $lockResult = $this->controlDevice($deviceId, 'lock');
        $this->logger->alert("Lock Result: ");
        $this->logger->alert($lockResult);
        $this->logger->alert("--------------------------");

        if ($lockResult['status'] == 0) {
            $response = [
                'context' => [
                    'properties' => [
                        [
                            'namespace' => 'Alexa.LockController',
                            'name' => 'lockState',
                            'value' => 'LOCKED',
                            'timeOfSample' => date('c'),
                            'uncertaintyInMilliseconds' => 500
                        ]
                    ]
                ],
                'event' => [
                    'header' => [
                        'namespace' => 'Alexa',
                        'name' => 'Response',
                        'payloadVersion' => '3',
                        'messageId' => $jsonData['directive']['header']['messageId'],
                        'correlationToken' => $jsonData['directive']['header']['correlationToken']
                    ],
                    'endpoint' => [
                        'endpointId' => $deviceId
                    ],
                    'payload' => []
                ]
            ];
        } else {
            $errorCode = $this->errorCodeMapping[$lockResult['status']] ?? 'INTERNAL_ERROR';
            $errorMessage = 'There was a problem locking the device.';
            $response = [
                'event' => [
                    'header' => [
                        'namespace' => 'Alexa',
                        'name' => 'ErrorResponse',
                        'payloadVersion' => '3',
                        'messageId' => $jsonData['directive']['header']['messageId']
                    ],
                    'endpoint' => [
                        'endpointId' => $deviceId
                    ],
                    'payload' => [
                        'type' => $errorCode,
                        'message' => $errorMessage
                    ]
                ]
            ];
        }
    
        return $this->response->setJSON($response);
    }
    
    private function unlock($jsonData)
    {   
        $deviceId = $jsonData['directive']['endpoint']['endpointId'];
        $this->logger->alert("Unlock Recieved For device({$deviceId}) ");

        $unlockResult = $this->controlDevice($deviceId, 'UNLOCK');
        $this->logger->alert("Unlock Result: ");
        $this->logger->alert($unlockResult );
        $this->logger->alert("--------------------------");

        if ($unlockResult['status'] == 0) {
            $response = [
                'context' => [
                    'properties' => [
                        [
                            'namespace' => 'Alexa.LockController',
                            'name' => 'lockState',
                            'value' => 'UNLOCKED',
                            'timeOfSample' => date('c'),
                            'uncertaintyInMilliseconds' => 500
                        ]
                    ]
                ],
                'event' => [
                    'header' => [
                        'namespace' => 'Alexa',
                        'name' => 'Response',
                        'payloadVersion' => '3',
                        'messageId' => $jsonData['directive']['header']['messageId'],
                        'correlationToken' => $jsonData['directive']['header']['correlationToken']
                    ],
                    'endpoint' => [
                        'endpointId' => $deviceId
                    ],
                    'payload' => []
                ]
            ];
            $this->logger->alert("Unlock Response To Alexa:");
            $this->logger->alert($response);

            $this->logger->alert("-------------");

        } else {
            $errorCode = $this->errorCodeMapping[$unlockResult['status']] ?? 'INTERNAL_ERROR';
            $errorMessage = 'There was a problem unlocking the device.';
            $response = [
                'event' => [
                    'header' => [
                        'namespace' => 'Alexa',
                        'name' => 'ErrorResponse',
                        'payloadVersion' => '3',
                        'messageId' => $jsonData['directive']['header']['messageId']
                    ],
                    'endpoint' => [
                        'endpointId' => $deviceId
                    ],
                    'payload' => [
                        'type' => $errorCode,
                        'message' => $errorMessage
                    ]
                ]
            ];
        }
    
        return $this->response->setJSON($response);
    }
    

    private function formatDeviceForDiscovery($device)
    {
    // Each device will be represented as an endpoint in the discovery response
        return [
            'endpointId' => $device['device_id'], // Unique identifier for the endpoint
            'manufacturerName' => 'Node Devices', // Manufacturer of the device
            'description' => 'Smart Garage by Node Devices', // Description of the device
            'friendlyName' => $device['device_name'], // Name of the device to be displayed in the Alexa app
            'displayCategories' => ['SMARTLOCK'], // Categories that best describe the device
            'cookie' => new \stdClass(), // Use an empty object for no cookie needed
            'capabilities' => [
                [
                    'type' => 'AlexaInterface',
                    'interface' => 'Alexa.LockController',
                    'version' => '3',
                    'properties' => [
                        'supported' => [
                            ['name' => 'lockState'], // LockController supports "lockState"
                        ],
                        'proactivelyReported' => true,
                        'retrievable' => true,
                    ],
                ],
                [
                    'type' => 'AlexaInterface',
                    'interface' => 'Alexa.EndpointHealth',
                    'version' => '3',
                    'properties' => [
                        'supported' => [
                            ['name' => 'connectivity'], // EndpointHealth supports "connectivity"
                        ],
                        'proactivelyReported' => true,
                        'retrievable' => true,
                    ],
                ],
                [
                    'type' => 'AlexaInterface',
                    'interface' => 'Alexa',
                    'version' => '3',
                ],
            ],
        ];
    }
    private function formatReportStateResponse($deviceStatus, $directiveMessageId, $deviceId, $accessToken)
    {
        if ($deviceStatus['status'] == 0) {
            return [
                'context' => [
                    'properties' => [
                        [
                            'namespace' => 'Alexa.LockController',
                            'name' => 'lockState',
                            'value' => $deviceStatus['data']['lockState'],
                            'timeOfSample' => date('c'),
                            'uncertaintyInMilliseconds' => 500
                        ]
                    ]
                ],
                'event' => [
                    'header' => [
                        'namespace' => 'Alexa',
                        'name' => 'StateReport',
                        'payloadVersion' => '3',
                        'messageId' => $directiveMessageId,
                        'correlationToken' => $jsonData['directive']['header']['correlationToken'] ?? null
                    ],
                    'endpoint' => [
                        'scope' => [
                            'type' => 'BearerToken',
                            'token' => $accessToken
                        ],
                        'endpointId' => $deviceId
                    ],
                    'payload' => []
                ]
            ];
        } else {
            $errorCode = $this->errorCodeMapping[$deviceStatus['status']] ?? 'INTERNAL_ERROR';
            $errorMessage = 'There was a problem retrieving the device status.';
            return [
                'event' => [
                    'header' => [
                        'namespace' => 'Alexa',
                        'name' => 'ErrorResponse',
                        'payloadVersion' => '3',
                        'messageId' => $directiveMessageId
                    ],
                    'endpoint' => [
                        'scope' => [
                            'type' => 'BearerToken',
                            'token' => $accessToken
                        ],
                        'endpointId' => $deviceId
                    ],
                    'payload' => [
                        'type' => $errorCode,
                        'message' => $errorMessage
                    ]
                ]
            ];
        }
    }
    
    private function getDeviceStatus($deviceId) {
        $device = $this->smartDevice->getSmartDeviceById($deviceId);
        $devicePassword = $device['password'];
        // Call the 'get status' API
        $data = [
            'serial' => $device['serial_number'],
            'uid' => $device['UID'],
            'password' => $devicePassword,
            'api' => 'get_status'
        ];
        $response = callAPI('POST', $this->url, $data);
        $responseData = json_decode($response, true);
    
        // Determine the lockState based on the percentage value
        if ($responseData['data']['percent'] == 0) {
            $responseData['data']['lockState'] = 'LOCKED';
        } elseif ($responseData['data']['percent'] == 100) {
            $responseData['data']['lockState'] = 'UNLOCKED';
        } else {
            $responseData['data']['lockState'] = 'JAMMED';
        }
    
        return $responseData;
    }
    
    private function controlDevice($deviceId,$action){

        // Fetch device data by ID (assuming you have a method like getSmartDeviceById)
        $device = $this->smartDevice->getSmartDeviceById($deviceId);

        $devicePassword = $device['password'];

        // Default password is the device password
        $password = $devicePassword;
    

        $dataValue = '00';

        // Map the action to the corresponding data value
        switch ($action) {
            case 'UNLOCK':
                $dataValue = '64';
            break;
            // Add more cases if needed
            default:
            break;
        }
        // Prepare data for external API request
        $apiData = [
            'serial' => $device['serial_number'],
            'uid' => $device['UID'],
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
    private function extractTokenFromJson($jsonData) {
        if (isset($jsonData['directive']['payload']['scope']['token'])) {
            return $jsonData['directive']['payload']['scope']['token'];
        } elseif (isset($jsonData['directive']['endpoint']['scope']['token'])) {
            return $jsonData['directive']['endpoint']['scope']['token'];
        }
        return false;
    }
}
