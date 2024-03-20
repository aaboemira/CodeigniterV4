<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use App\Models\admin\Auth_model; // Add the use statement for Auth_model
use App\Models\Public_model;

class FirebaseController extends ResourceController
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
    public function fcm_test() {
        return view('fcm_test');
    }
    


    
}