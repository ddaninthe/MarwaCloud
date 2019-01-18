<?php
require_once(__DIR__ . '/utils.php');

session_start();

$login = $_POST['login'];
$password = $_POST['password'];

if (empty($login) || empty($password)) {
    // Bad Request
    http_response_code(400);
    exit(0);
}

$ldap_connect = ldap_connect("ldap.jumpcloud.com", 389) or die("Impossible de se co aux serveurs LDAP");

if ($ldap_connect) {
    // Needed LDAP Option
    ldap_set_option($ldap_connect, LDAP_OPT_PROTOCOL_VERSION, 3);

    $ldap_user_dn = getUserDn($login);

    
    // Binding
    $ldap_bind = ldap_bind($ldap_connect, $ldap_user_dn, $password);

    if ($ldap_bind) {
        $data = execCurl('https://console.jumpcloud.com/api/systemusers/');

        foreach ($data->results as $result) {
            if (strcmp($result->username, $user)) {
                $id = $result->_id;
                break;
            }
        }

        $_SESSION['user'] = $id;
        http_response_code(200);
    } else {
        http_response_code(500);
        echo ldap_error($ldap_connect);
    }
}

ldap_close($ldap_connect);
?>