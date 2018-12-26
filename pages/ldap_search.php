<?php
require_once(__DIR__ . '/include/ldap_utils.php');


$login = $_POST['login'];
$password = $_POST['password'];

if (empty($login) || empty($password)) {
    // Bad Request
    http_response_code(400);
    exit(0);
}

$ldap_connect = ldap_connect("ldap.jumpcloud.com", 389) or die("Impossible de se co aux serveur LDAP");

if ($ldap_connect) {
    // Needed LDAP Option
    ldap_set_option($ldap_connect, LDAP_OPT_PROTOCOL_VERSION, 3);

    $ldap_user_dn = getUserDn($login);

    // Binding
    $ldap_bind = ldap_bind($ldap_connect, $ldap_user_dn, $password);

    if ($ldap_bind) {
        http_response_code(200);
        setcookie("logged", true, time() + 60 * 60, '/'); // One hour


    } else {
        http_response_code(401);
    }
}

ldap_close($ldap_connect);

?>