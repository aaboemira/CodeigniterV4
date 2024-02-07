<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;

use App\Models\admin\Auth_model;
use App\Models\Public_model;

class OAuthController extends BaseController
{
    protected $Public_model;
    protected $oauthModel;

    protected $logger;

    protected $sendmail;
    use ApiResponseTrait; // Use the trait

    public function __construct()
    {
        $this->oauthModel=new Auth_model();
        $this->logger = service('logger');  
        helper(['oauth']);

    }
    public function showAuthorize()
    {
        $head['title'] = 'Authorization';
        $head['description'] = 'Authorization page';
        $head['keywords'] = str_replace(" ", ",", $head['title']);

        $session = session();
        $queryParams = $this->getQueryParams($this->request);
        if (!$this->oauthModel->validateClient($queryParams['client_id'], $queryParams['redirect_uri'])) {
            return $this->fail('Invalid client_id or redirect_uri', 400);
        }
        $isLoggedIn = $session->has('logged_user');
        $username = $isLoggedIn ? $session->get('user_name') : '';
        $data = array_merge($queryParams, ['isLoggedIn' => $isLoggedIn, 'username' => $username]);

        // return $this->render('oauth/authorize', $head, $data);
        return view('/templates/redlabel/oauth/authorize',$data);
    }

    public function authorize()
    {
        $session = session();
        $request = $this->request;
        $queryParams = [
            'client_id' => $request->getPost('client_id'),
            'redirect_uri' => $request->getPost('redirect_uri'),
            'response_type' => $request->getPost('response_type'),
            'state' => $request->getPost('state'),
            'scope' => $request->getPost('scope') // Include if you have this in your form
        ];
        if ($session->has('logged_user') ) {
            return $this->processConsent($queryParams);
        }
        else {

            if($this->attemptLogin($request)){

                return redirect()->to(base_url('oauth/authorize') . '?' . http_build_query($queryParams));
            }else{
                session()->setFlashdata('error', lang_safe('wrong_user'));
                return redirect()->to(base_url('oauth/authorize') . '?' . http_build_query($queryParams))->withInput();
            }
        }
    }

    private function attemptLogin($request)
    {
        if ($request->getMethod() === 'post') {
            $email = $request->getPost('email');
            $password = $request->getPost('password');

            $usersController = new \App\Controllers\Users();
            return $usersController->performLogin($email, $password);
        }
        return false;
    }

    private function processConsent($queryParams)
    {
        $request = $this->request;
        $userId=session()->get('logged_user');
        $consent = $request->getPost('consent');
        if ($consent === 'yes') {
        // Check for existing, non-expired authorization code
        $existingCode = $this->oauthModel->getValidAuthorizationCode($userId, $queryParams['client_id']);
        if ($existingCode) {
            // Redirect with existing authorization code
            return redirect()->to($queryParams['redirect_uri'] . '?code=' . $existingCode . '&state=' . $queryParams['state']);
        } else {
            // Generate new authorization code
            $authorizationCode = generateAuthorizationCode();
            $this->saveAuthorizationCode($authorizationCode, $queryParams, $userId);
            return redirect()->to($queryParams['redirect_uri'] . '?code=' . $authorizationCode . '&state=' . $queryParams['state']);
        }
        } elseif ($consent === 'no') {
            $errorDescription = "The+user+denied+the+request";
            return redirect()->to($queryParams['redirect_uri'] . '?error=access_denied&error_description=' . $errorDescription . '&state=' . $queryParams['state']);
        }
        return redirect()->to(base_url('oauth/authorize') . '?' . http_build_query($queryParams));
    }

    private function getQueryParams($request)
    {
        return [
            'client_id' => $request->getGet('client_id'),
            'redirect_uri' => $request->getGet('redirect_uri'),
            'response_type' => $request->getGet('response_type'),
            'state' => $request->getGet('state'),
            'scope' => $request->getGet('scope')
        ];
    }


    public function token()
    {
        try {
            $request = $this->request->getJSON(true);

            if (!$this->oauthModel->validateClientCredentials($request['client_id'], $request['client_secret'])) {
                return $this->failUnauthorized("Invalid client credentials");
            }

            if ($request['grant_type'] == 'authorization_code') {
                $authCodeData = $this->oauthModel->validateAuthorizationCode($request['code']);
                if (!$authCodeData) {
                    return $this->fail("Invalid authorization code", 400);
                }

                $accessToken = generateAccessToken();
                $refreshToken = generateRefreshToken();

                $this->oauthModel->saveAccessToken([
                    'access_token' => $accessToken,
                    'client_id' => $request['client_id'],
                    'user_id' => $authCodeData['user_id'],
                    'expires' => date('Y-m-d H:i:s', time() + 3600) // 1 hour for example
                ]);

                $this->oauthModel->saveRefreshToken([
                    'refresh_token' => $refreshToken,
                    'client_id' => $request['client_id'],
                    'user_id' => $authCodeData['user_id'],
                    'expires' => date('Y-m-d H:i:s', time() + 1209600) // 2 weeks for example
                ]);

                return $this->respondSuccess([
                    'token_type' => 'Bearer',
                    'access_token' => $accessToken,
                    'refresh_token' => $refreshToken,
                    'expires_in' => 3600 // 1 hour
                ]);
            } elseif ($request['grant_type'] == 'refresh_token') {
                $refreshData = $this->oauthModel->validateRefreshToken($request['refresh_token']);
                if (!$refreshData) {
                    return $this->fail("Invalid refresh token", 400);
                }

                $newAccessToken = generateAccessToken();

                $this->oauthModel->saveAccessToken([
                    'access_token' => $newAccessToken,
                    'client_id' => $refreshData['client_id'],
                    'user_id' => $refreshData['user_id'],
                    'expires' => date('Y-m-d H:i:s', time() + 3600) // 1 hour for example
                ]);

                return $this-> respondSuccess([
                    'token_type' => 'Bearer',
                    'access_token' => $newAccessToken,
                    'expires_in' => 3600 // 1 hour
                ]);
            } else {
                return $this->fail("Unsupported grant type", 400);
            }
        } catch (\Exception $e) {
            return $this->failServerError("An error occurred".$e);
        }
    }

    // public function userInfo()
    // {
    //     try {
    //         $accessToken = $this->getBearerTokenFromHeader();

    //         $userId = $this->oauthModel->validateAccessToken($accessToken);
    //         if (!$userId) {
    //             return $this->failUnauthorized("Invalid access token");
    //         }

    //         $user = $this->publicModel->getUserById($userId); // Assuming this method exists in Public_model
    //         if (!$user) {
    //             return $this->failNotFound("User not found");
    //         }

    //         return $this->respond([
    //             'sub' => $user['id'],
    //             'email' => $user['email'],
    //             'given_name' => $user['first_name'],
    //             'family_name' => $user['last_name'],
    //             'name' => $user['full_name'],
    //             'picture' => $user['profile_picture'] // Optional
    //         ]);
    //     } catch (\Exception $e) {
    //         return $this->failServerError("An error occurred");
    //     }
    // }
    public function changeAccount()
    {
        $session = session();
        $session->remove('logged_user'); // Clears the session
        $session->remove('user_name'); // Clears the session
        $session->remove('email'); // Clears the session

        // Return a JSON response
        return $this->response->setJSON(['success' => true]);
    }
    private function saveAuthorizationCode($authorizationCode, $queryParams, $userId)
    {
        $data = [
            'authorization_code' => $authorizationCode,
            'client_id' => $queryParams['client_id'],
            'user_id' => $userId,
            'expires' => date('Y-m-d H:i:s', time() + 600), // 10 minutes expiry
            'redirect_uri'=>$queryParams['redirect_uri']
        ];
    
        $this->oauthModel->saveAuthCode($data);
    }


}