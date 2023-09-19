<?php

switch ($path[2] ?? '') {
    case 'editor':
        require_once 'op_clienti_editor.php';
        return;
    case 'addresses':
        require_once 'op_clienti_destinazioni.php';
        break;
    default:
        require_once 'op_cliente_default.php';
        break;
}