<?php
    require_once(__DIR__ . '/utils.php');

    $data = execCurl('https://console.jumpcloud.com/api/systems');  

    if (isset($data->message)) {
        http_response_code(500);
    } else {
        http_response_code(200);
    }
    echo json_encode($data);
?>