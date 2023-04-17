<?php
if (!$core)
    die("Accesso diretto rilevato");

switch ($path[3]) {
    case 'editor':
        require_once 'op_sottostep_editor.php';
        break;
}
