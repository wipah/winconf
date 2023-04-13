<?php
if (!$core)
    die("Accesso diretto rilevato");

switch ($path[3]) {
    case 'editor':
        require_once 'op_categorie_editor.php';
        break;
    default:
        require_once 'op_caterie_default.php';
        break;
}