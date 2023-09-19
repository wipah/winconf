<?php
if (!$core)
    die("Accesso diretto rilevato");

switch ($path[2]) {

    case 'editor':
        require_once 'op_editor.php';
        break;
    case 'elimina-documento':
        require_once 'op_elimina_documento.php';
        break;
    default:
        require_once 'op_configuratore_default.php';
        break;
}