<?php
namespace App\Models\admin;

use CodeIgniter\Model;
use Config\Database;

class Auth_model extends Model
{


    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // Validate client credentials
    public function validateClient($clientId, $redirectUri)
    {
        $builder = $this->db->table('oauth_clients');
        $builder->where('client_id', $clientId);
        $builder->where('redirect_uri', $redirectUri);

        $query = $builder->get();
        return $query->getRowArray() !== null;
    }
    public function validateClientCredentials($clientId, $clientSecret)
    {
        $builder = $this->db->table('oauth_clients');
        $builder->where('client_id', $clientId);
        $builder->where('client_secret', $clientSecret);
        $query = $builder->get();
    
        return $query->getRowArray() !== null;
    }
    // Validate authorization code
    public function validateAuthorizationCode($code)
    {
        $builder = $this->db->table('oauth_authorization_codes');
        $builder->where('authorization_code', $code);
        $query = $builder->get();

        if ($row = $query->getRowArray()) {
            // Check if code is expired
            if ($row['expires'] > date('Y-m-d H:i:s')) {
                return $row;
            }
        }
        return false;
    }

    // Save access token
    public function saveAccessToken($tokenData)
    {
        $builder = $this->db->table('oauth_access_tokens');
        $builder->insert($tokenData);
    }

    // Save refresh token
    public function saveRefreshToken($tokenData)
    {
        $builder = $this->db->table('oauth_refresh_tokens');
        $builder->insert($tokenData);
    }
    public function saveAuthCode($codeData)
    {
        $builder = $this->db->table('oauth_authorization_codes');
        $builder->insert($codeData);
    }
    // Validate access token
    public function validateAccessToken($accessToken)
    {
        $builder = $this->db->table('oauth_access_tokens');
        $builder->where('access_token', $accessToken);
        $query = $builder->get();

        if ($row = $query->getRowArray()) {
            // Check if token is expired
            if ($row['expires'] > date('Y-m-d H:i:s')) {
                return $row['user_id'];
            }
        }
        return false;
    }

    // Validate refresh token
    public function validateRefreshToken($refreshToken)
    {
        $builder = $this->db->table('oauth_refresh_tokens');
        $builder->where('refresh_token', $refreshToken);
        $query = $builder->get();
        
        if ($row = $query->getRowArray()) {
            // Check if token is expired
            if ($row['expires'] > date('Y-m-d H:i:s')) {
                return $row;
            }
        }
        return false;
    }
    public function authenticateUser($username, $password)
    {
        $builder = $this->db->table('users');
        $builder->where('username', $username);
        $result = $builder->get()->getRowArray();

        if ($result ) {
            return $result; // Return the user's data
        }
        return false; // Authentication failed
    }
    public function getValidAuthorizationCode($userId, $clientId)
    {
        $builder = $this->db->table('oauth_authorization_codes');
        $builder->where('user_id', $userId);
        $builder->where('client_id', $clientId);
        $builder->where('expires >', date('Y-m-d H:i:s')); // Check that the code is not expired
        $query = $builder->get();

        if ($row = $query->getRowArray()) {
            return $row['authorization_code']; // Return the authorization code
        }
        return false; // No valid code found
    }
    public function invalidateUserTokens($userId)
{
    // Invalidate or delete the access and refresh tokens for the user
    $builder = $this->db->table('oauth_access_tokens');
    $builder->where('user_id', $userId);
    $builder->delete();

    $builder = $this->db->table('oauth_refresh_tokens');
    $builder->where('user_id', $userId);
    $builder->delete();

}

}
