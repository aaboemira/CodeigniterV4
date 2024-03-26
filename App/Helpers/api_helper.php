<?php
function callAPI($method, $url, $data, $headers = null) {
    $curl = curl_init();
    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data) {
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
    curl_setopt($curl, CURLOPT_FAILONERROR, false); // Change to false to handle HTTP error codes
    if (!empty($headers) && is_array($headers)) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }

    // EXECUTE:
    $result = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get the HTTP status code

    if ($httpCode >= 200 && $httpCode < 300) {
        // Request was successful
        curl_close($curl);
        return $result;
    } else {
        // There was an error, handle the response
        $error = curl_error($curl);
        $responseBody = $result ? $result : json_encode(['error' => 'No response body']);
        curl_close($curl);
        // Return a JSON object with the HTTP code, response body, and curl error
        return json_encode([
            'httpCode' => $httpCode,
            'responseBody' => $responseBody,
            'curlError' => $error
        ]);
    }
}


function callAPIForPatch($url, $data, $headers) {
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
    curl_setopt($curl, CURLOPT_FAILONERROR, false); // Change to false to handle HTTP error codes

    if ($data) {
        $jsonData = json_encode($data);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    }

    if (!empty($headers) && is_array($headers)) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }

    // Execute the request
    $result = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if (!$result && $httpCode != 200) {
        $error = curl_error($curl);
        curl_close($curl);
        return json_encode(['error' => $error, 'httpCode' => $httpCode]);
    } else {
        curl_close($curl);
        return $result;
    }
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
function encryptDataWithFixedIV($data, $encryptionKey) {
    // Retrieve the fixed IV from an environment variable
    $iv = getenv('FIXED_IV');
    
    // Check if the IV is the correct length for AES-128-CBC
    if (strlen($iv) !== openssl_cipher_iv_length('AES-128-CBC')) {
        throw new Exception('Invalid IV length');
    }

    // Encrypt the data using the fixed IV
    $encrypted = openssl_encrypt($data, 'AES-128-CBC', $encryptionKey, 0, $iv);

    // Return the base64-encoded encrypted data and the IV
    return base64_encode($encrypted . '::' . $iv);
}
