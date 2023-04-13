<?php

if (!$core)
    die("Accesso diretto rilevato");

switch ($path[2]) {
    case 'editor':
        require_once 'op_editor.php';
        break;
    default:
        require_once 'op_default.php';
        break;
}