<?php
include_once("funkcije.php");

$params = session_get_cookie_params();
setcookie(session_name(), '', 0, $params['path'], $params['domain'], $params['secure'], isset($params['httponly']));
session_unset();
session_destroy();
session_write_close();
header("location: /");
exit();
?>