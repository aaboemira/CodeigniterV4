<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use App\Models\admin\Auth_model; // Add the use statement for Auth_model
use App\Models\Public_model;

class HomeGraphController extends ResourceController
{

    protected $oauthModel;
    protected $logger;
    protected $aes_key;
    protected $Public_model;
    public function __construct()
    {
        $this->oauthModel=new Auth_model();
        $this->logger = service('logger');  

        helper(['oauth']);
        helper(['api_helper']);
        $this->Public_model = new Public_model();


    }
    public function getJwtToken()
    {
        // Load the service account JSON file
        $serviceAccountFile = APPPATH . '../nodetest.json';
        $serviceAccount = json_decode(file_get_contents($serviceAccountFile), true);
    
        // Construct the JWT header
        $jwtHeader = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
    
        // Construct the JWT claim set
        $now = time();
        $jwtClaimSet = base64_encode(json_encode([
            'iss' => $serviceAccount['client_email'],
            'scope' => 'https://www.googleapis.com/auth/homegraph',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now
        ]));
    
        // Sign the JWT
        $privateKey = openssl_pkey_get_private($serviceAccount['private_key']);
        openssl_sign("$jwtHeader.$jwtClaimSet", $signature, $privateKey, 'sha256WithRSAEncryption');
        $jwtSignature = base64_encode($signature);
    
        // Construct the JWT
        $jwt = "$jwtHeader.$jwtClaimSet.$jwtSignature";
    
        // Prepare the token request
        $tokenRequest = [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ];
    
        // Make the token request using the callAPI helper function
        $response = callAPI('POST', 'https://oauth2.googleapis.com/token', $tokenRequest);
        $response = json_decode($response, true);
    
        // Return only the access token
        return $response['access_token'] ?? null;
    }
    
    public function sendReportState()
    {
        $this->logger->alert("Entered");
        $this->logger->alert($this->request->getBody());

        // Get the input data
        $inputData = $this->request->getJSON(true);
        $message = $inputData['message'] ?? '';
        // $this->logger->alert($message);
    
        // Extract client_id and client_secret from the headers
        $clientId = $this->request->getHeaderLine('ClientId') ?? '';
        $clientSecret = $this->request->getHeaderLine('ClientSecret') ?? '';
    
        if (!$this->oauthModel->validateClientCredentials($clientId, $clientSecret)) {
            return $this->failUnauthorized("Invalid client credentials");
        }
    
        // Base64 decode the message
        $decodedMessage = base64_decode($message);
        if ($decodedMessage === false) {
            return $this->fail('Invalid base64 encoding.', 400);
        }
        // $this->logger->alert("Decoded Message: {$decodedMessage}");
    
        if (strlen($decodedMessage) !== 114) {
            $this->logger->alert("Invalid Message length");
            if (strlen($decodedMessage) == 0) {
                $this->logger->alert(":Empty Message");
            }
            return $this->fail('The message length is not 114 bytes.', 400);
        }
    
        // Decrypt and parse the message
        $devicesData = $this->decryptAndParseDevices($decodedMessage);
        if ($devicesData === null) {
            return $this->fail('Invalid action encountered.', 400);
        } elseif ($devicesData === false) {
            $this->logger->alert("No devices found.");
            return $this->fail('No devices found .', 400);
        }
        $this->logger->alert('Devices Data: ' . print_r($devicesData, true));
    
        $jwtToken = $this->getJwtToken($clientId, $clientSecret);
        if ($jwtToken == null) {
            return $this->fail('Failed to obtain JWT token', 401);
        }
        // $this->logger->info($jwtToken);
        // Iterate over each device and send the report state
        foreach ($devicesData as $deviceData) {
            // Construct the report state payload using the decrypted data for each device
            $reportStatePayload = [
                'requestId' => uniqid(),
                'agentUserId' => $deviceData['user_id'],
                'payload' => [
                    'devices' => [
                        'states' => [
                            $deviceData['id'] => [
                                'online' => true,
                                'openPercent' => $deviceData['data']['percent'],
                            ]
                        ]
                    ]
                ]
            ];
            $this->logger->alert("Request to Google:");
            $this->logger->alert(json_encode($reportStatePayload));
            $this->logger->alert("-----------End Request to Google------------------------------------");
    
            // Send the report state to Google's HomeGraph API
            $response = $this->sendReportStateToGoogle($jwtToken, $reportStatePayload);
            $this->logger->alert("Google Response:");
            $this->logger->alert($response);
            $this->logger->alert("--------------End Google Response------------------------------------");

            // Check the response for errors
            if (isset($response['error'])) {
                return $this->fail($response['error'], $response['status']);
            }
        }
    
        // Return a successful response if all device reports are sent
        return $this->respond(['message' => 'Success'], 200);
    }
    
    
    
    private function sendReportStateToGoogle($jwtToken, $reportStatePayload)
    {
        // Google HomeGraph API endpoint for Report State
        $url = 'https://homegraph.googleapis.com/v1/devices:reportStateAndNotification';
        
        // Set the headers
        $headers = [
            'Authorization: Bearer ' . $jwtToken,
            'Content-Type: application/json'
        ];
        
        // Send the report state to Google's HomeGraph API using the callAPI helper function
        $response = callAPI('POST', $url, json_encode($reportStatePayload), $headers);
        
        // Decode the response to handle it as an array
        $decodedResponse = json_decode($response, true);
    
        if (isset($decodedResponse['httpCode']) && $decodedResponse['httpCode'] != 200) {
            // Since responseBody is a string, decode it to an associative array
            $errorDetails = json_decode($decodedResponse['responseBody'], true);
            
            // If decoding fails, use the entire responseBody as the error message
            if ($errorDetails === null) {
                $errorMessage = $decodedResponse['responseBody'];
            } else {
                // Otherwise, extract the error message from the decoded details
                $errorMessage = $errorDetails['error']['message'] ?? 'An unknown error occurred';
            }
            
            // Format the error response according to the structure you require
            $errorResponse = [
                'status' => $decodedResponse['httpCode'],
                'error' => $errorMessage
            ];
            
            return $errorResponse;
        }
    
        // If the response is successful, return the decoded response directly
        return $decodedResponse;
    }

    public function decryptAndParseDevices($byteString)
    {
        // Unpack the byte string into its components
        $messageParts = unpack('a16serial/vid/a16aes_iv/a48encrypted_payload/a32hash', $byteString);
        $this->logger->alert($messageParts);

        // Get the devices associated with the serial number
        $devices = $this->Public_model->getSmartDevicesBySerial($messageParts['serial']);
        if (!$devices) {
            return false;
        }

        $parsedDevices = []; // Array to hold all the successfully decrypted devices

        foreach ($devices as $device) {
            $device['UID']=decryptData($device['UID'],'@@12@@');
            // Use the first 16 characters of the UID as the AES key
            $aes_key = substr($device['UID'], 0, 16);
            $decryptedPayload = $this->decryptPayload($messageParts['encrypted_payload'], $messageParts['aes_iv'], $aes_key);
            
            // Check if decryption was successful
            if ($decryptedPayload !== false) {
                // $this->logger->alert("Decrypted Payload Length: " . strlen($decryptedPayload));

                // Unpack the decrypted payload
                $payloadParts = unpack('vaction/a25data/Vtimestamp/Cpermission', $decryptedPayload);
                // $this->logger->info("Payload Parts: {$decryptedPayload}");
                if ($payloadParts['action'] != 101) {
                    // Log the invalid action and return null to indicate the error
                    $this->logger->alert("Invalid action encountered: {$payloadParts['action']} for device with UID: {$device['UID']}");
                    return null;
                }
                $data = unpack('Cgate_position', $payloadParts['data']);
                $data['percent'] = unpack('C2', $payloadParts['data'])[2];

                // Add the device and its decrypted data to the array
                $parsedDevices[] = [
                    'serial' => $messageParts['serial'],
                    'id' => $device['device_id'], // Assuming 'id' is in the $device array
                    'action' => $payloadParts['action'],
                    'data' => $data,
                    'user_id'=>$device['user_id']
                ];
            } else {
                // Log the decryption failure for this device
                $this->logger->error("Failed to decrypt payload for device with UID: {$device['UID']}");
            }
        }

        // Check if there are any successfully parsed devices
        if (empty($parsedDevices)) {
            // Indicate that all decryption attempts failed
            return false;
        }

        return $parsedDevices;
    }
    public function checkReportState()
    {
        // Extract client_id and client_secret from the headers
        $clientId = $this->request->getHeaderLine('ClientId');
        $clientSecret = $this->request->getHeaderLine('Clientsecret');
    
        // Validate the client credentials
        if (!$this->oauthModel->validateClientCredentials($clientId, $clientSecret)) {
            return $this->failUnauthorized("Invalid client credentials");
        }
    
        // Get the serial number from the request body
        $inputData = $this->request->getJSON(true);
        $serialNumber = $inputData['Serialnumber'] ?? '';
    
        if (empty($serialNumber)) {
            return $this->fail("Serial number is required.");
        }
    

        // Check if the device is Google linked
        // Use the modified model function to check if the device is Google linked
        $isGoogleLinked = $this->Public_model->getSmartDeviceBySerial($serialNumber);

        // The report state reflects the google_linked status directly
        $reportState = $isGoogleLinked ? 1 : 0;
        
        // Return the report_state in the response
        return $this->respond(['report_state' => $reportState]);
    }
    

    private function decryptPayload($encryptedPayload, $iv,$aes_key)
    {

        $decrypted = openssl_decrypt($encryptedPayload, 'aes-128-cbc', $aes_key, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }

    
}