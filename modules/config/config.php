<?php

switch ($path[2]){
    case 'salva':
        require_once 'op_ajax_salva.php';
        break;
    default:
        require_once 'op_default.php';
        break;
}