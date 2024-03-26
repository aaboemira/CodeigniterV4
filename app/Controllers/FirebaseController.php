<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use App\Models\admin\Auth_model; // Add the use statement for Auth_model
use App\Models\Public_model;

class FirebaseController extends ResourceController
{

    protected $logger;
    protected $aes_key;
    protected $Public_model;
    protected $authModel;
    public function __construct()
    {
        $this->authModel=new Auth_model();
        $this->logger = service('logger');  

        helper(['oauth']);
        helper(['api_helper']);
        $this->Public_model = new Public_model();

    }

    public function sendMessage($messageData)
    {
        $accessToken = $this->getJwtToken();
        $url = 'https://fcm.googleapis.com/v1/projects/appnotifications-8184c/messages:send';
        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ];
        $this->logger->alert("Send Message");
        $this->logger->alert(json_encode($messageData));
        $this->logger->alert("End send Message");

        $response = callAPI('POST', $url, json_encode($messageData), $headers);
        return $response;
    }
    public function subscribe()
    {
        $deviceToken = $this->request->getHeaderLine('Device-Token');
        $accessToken = $this->getAccessTokenFromHeader();
        $userId = $this->validateToken($accessToken);
        if (!$userId) {
            return $this->failUnauthorized("Invalid or missing access token");
        }
        if ($this->Public_model->isSubscribed($userId)) {
            return $this->respond(['message' => 'Already subscribed'], 200);
        } else {
            $this->Public_model->subscribeUserFCM($userId, $deviceToken);
            return $this->respond(['message' => 'Subscribed successfully'], 200);
        }
    }
    public function unsubscribe()
    {
        $accessToken = $this->getAccessTokenFromHeader();
        $userId = $this->validateToken($accessToken);
        if (!$userId) {
            return $this->failUnauthorized("Invalid or missing access token");
        }
         // Implement this method in your Public_model to update the database
        if (!$this->Public_model->isSubscribed($userId)) {
            return $this->respond(['message' => 'Already Unsubscribed'], 200);
        } else {
            $this->Public_model->unsubscribeUserFCM($userId);
            return $this->respond(['message' => 'UnSubscribed successfully'], 200);
        }
    }
    public function updateToken()
    {
        $userId = $this->request->getPost('user_id'); // Or however you obtain the user ID
        $newDeviceToken = $this->request->getPost('device_token'); // Get the new device token from POST data

        if ($this->Public_model->updateDeviceToken($userId, $newDeviceToken)) {
            return $this->respond(['message' => 'Device token updated successfully'], 200);
        } else {
            return $this->respond(['message' => 'Update failed: user is not subscribed or does not exist'], 404);
        }
    }
    public function getJwtToken()
    {
        // Load the service account JSON file
        $serviceAccountFile = APPPATH . '../firebase.json';
        $serviceAccount = json_decode(file_get_contents($serviceAccountFile), true);
    
        // Construct the JWT header
        $jwtHeader = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
    
        // Construct the JWT claim set
        $now = time();
        $jwtClaimSet = base64_encode(json_encode([
            'iss' => $serviceAccount['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
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
        $this->logger->alert($response);
        // Return only the access token
        return $response['access_token'] ?? null;
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
    public function fcm_test() {
        return view('fcm_test');
    }
}