<?php
if (!$core)
    die("Accesso diretto rilevato");

switch ($path[2]) {
    case 'login':
        require_once 'op_login.php';
        break;
    case 'logout':
        require_once 'op_logout.php';
        break;
    default:
        require_once 'op_default.php';
        break;
}