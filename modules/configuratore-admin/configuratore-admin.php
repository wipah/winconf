<?php

if (!$core)
    die("Accesso diretto rilevato");

switch ($path[2]) {
    case 'ajax-step-mostra-opzioni':
        require_once 'op_ajax_sottostep_opzioni.php';
        break;
    case 'ajax-sottostep-editor':
        require_once 'op_ajax_sottostep_editor.php';
        break;
    case 'ajax-sottostep-editor-post':
        require_once 'op_ajax_sottostep_editor_post.php';
        break;
    case 'ajax-sottostep':
        require_once 'op_ajax_sottostep.php';
        break;
    case 'categorie':
        require_once 'op_categorie.php';
        break;
    case 'step':
        require_once 'op_step.php';
        break;
    case 'sottostep':
        require_once 'op_sottostep.php';
        break;
    case 'editor':
        require_once 'op_categorie_editor.php';
        break;
    default:
        require_once 'op_default.php';
        break;
}