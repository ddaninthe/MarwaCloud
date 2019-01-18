<?php
    require_once(__DIR__ . '/utils.php');

    $user = $_POST['user'];
    if (!empty($user)) {
        $data = execCurl('https://console.jumpcloud.com/api/v2/users/' . $user . '/memberof');         

        if (strpos($data, 'error') !== false) {
            http_response_code(500);
        } else {
            http_response_code(200);
        }

        echo $data;
    } else {
        http_response_code(400);
    }
?>