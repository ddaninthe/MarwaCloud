<?php
    require_once(__DIR__ . '/config.php');

    function getUserDn($login) {
        return "uid=" . $login . ",ou=Users,o=" . Config::LDAP_DN . ",dc=jumpcloud,dc=com";
    }

    function execCurl($url, $method = 'GET', $params = array()) {
        $curl = curl_init();

        $headers = array('Accept: application/json', 
                'Content-Type: application/json',
                'x-api-key: ' . Config::API_KEY);

        switch ($method) {
            case 'POST': 
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
            break;
        }
            
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $output = curl_exec($curl);

        if ($errno = curl_errno($curl)) {
            echo "error";
            $error_message = curl_strerror($errno);
            $output = "{\"message\": \"cURL error ({$errno}): {$error_message}\"}";
        }

        curl_close($curl);

        $output = json_encode($output, true);
        return $output;
    }
?>