<?php

if (!$core)
    die("Accesso diretto rilevato");

switch ($path[2]) {
    case 'categorie':
        require_once 'op_categorie.php';
        break;
    case 'step':
        require_once 'op_step.php';
        break;
    case 'editor':
        require_once 'op_categorie_editor.php';
        break;
    default:
        require_once 'op_default.php';
        break;
}