<?php
    require_once(__DIR__ . '/config.php');

    function getUserDn($login) {
        return "uid=" . $login . ",ou=Users," . Config::LDAP_DN;
    }
?>