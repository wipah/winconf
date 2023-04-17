<?php

switch ($path[3]) {
    case 'editor':
        require_once 'op_step_editor.php';
        break;
    case 'visualizza-categoria':
        require_once 'op_step_visualizza_categoria.php';
        break;
    default:
        require_once 'op_step_default.php';
        break;
}