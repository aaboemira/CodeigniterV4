<?php
function callAPI($method, $url, $data, $headers = null){
    $curl = curl_init();

    switch ($method){
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data) {
                // Check if data is an array or a JSON string
                $postData = is_array($data) ? http_build_query($data) : $data;
                curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
            }
            break;
        default:
            if ($data) {
                $url = sprintf("%s?%s", $url, http_build_query($data));
            }
    }

    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    // Set headers if provided
    if (!empty($headers) && is_array($headers)) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }

    // EXECUTE:
    $result = curl_exec($curl);
    if (!$result) {
        die("Connection Failure");
    }
    curl_close($curl);
    return $result;
}



function encryptData($data, $encryptionKey) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-CBC'));
    $encrypted = openssl_encrypt($data, 'AES-128-CBC', $encryptionKey, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decryptData($data, $encryptionKey) {
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, 'AES-128-CBC', $encryptionKey, 0, $iv);
}