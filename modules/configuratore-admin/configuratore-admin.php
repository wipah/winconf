<?php

if (!$core)
    die("Accesso diretto rilevato");

if (!$user->logged) {
    echo 'Devi aver effettuato il login';
    return;
}

switch ($path[2]) {
    case 'ajax-riordina-categorie':
        require_once 'op_ajax_categorie_riordina.php';
        break;
    case 'ajax-sottostep-elimina':
        require_once 'op_ajax_sottostep_elimina.php';
        break;
    case 'ajax-riordina-step':
        require_once 'op_ajax_step_riordina.php';
        break;
    case 'ajax-riordina-sottostep':
        require_once 'op_ajax_sottostep_riordina.php';
        break;
    case 'ajax-riordina-sottostep-opzioni':
        require_once 'op_ajax_sottostep_opzioni_riordina.php';
        break;

    case 'elimina-categoria':
        require_once 'op_categoria_elimina.php';
        break;
    case 'ajax-opzioni-elimina':
        require_once 'op_ajax_sottostep_opzioni_elimina.php';
        break;
    case 'ajax-dimensione-elimina':
        require_once 'op_ajax_sottostep_dimensioni_elimina.php';
        break;
    case 'ajax-sottostep-dipendenze-editor':
        require_once 'op_ajax_sottostep_dipendenze_editor.php';
        break;
    case 'ajax-sottostep-dipendenze-editor-post':
        require_once 'op_ajax_sottostep_dipendenze_editor_post.php';
        break;
    case 'ajax-step-mostra-opzioni':
        require_once 'op_ajax_sottostep_opzioni.php';
        break;
    case 'ajax-dipendenze':
        require_once 'op_ajax_dipendenze.php';
        break;
    case 'ajax-sottostep-dipendenze':
        require_once 'op_ajax_sottostep_dipendenze.php';
        break;
    case 'ajax-dimensioni':
        require_once 'op_ajax_dimensioni.php';
        break;
    case 'ajax-sottostep-dimensioni':
        require_once 'op_ajax_sottostep_dimensioni.php';
        break;
    case 'ajax-sottostep-dimensioni-editor':
        require_once 'op_ajax_sottostep_dimensioni_editor.php';
        break;
    case 'ajax-sottostep-dimensioni-editor-post':
        require_once 'op_ajax_sottostep_dimensioni_editor_post.php';
        break;
    case 'ajax-sottostep-dipendenze-elimina':
        require_once 'op_ajax_sottostep_dipendenze_elimina.php';
        break;
    case 'ajax-sottostep-opzioni-editor':
        require_once 'op_ajax_sottostep_opzioni_editor.php';
        break;
    case 'ajax-sottostep-opzioni-editor-post':
        require_once 'op_ajax_sottostep_opzioni_editor_post.php';
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