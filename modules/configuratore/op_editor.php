<?php

switch ($path[3]) {
    case 'nuovo':
        require_once 'op_ajax_editor_crea.php';
        break;
    case 'ajax-visualizza-step':
        require_once 'op_ajax_visualizza_step.php';
        break;
    case 'ajax-cambia-opzione':
        require_once 'op_ajax_cambia_opzione.php';
        break;
    case 'ajax-aggiorna-note':
        require_once 'op_ajax_editor_aggiorna_note.php';
        break;
    case 'ajax-ottieni-ultimo-step':
        require_once 'op_ajax_ottieni_ultimo_step.php';
        break;
    case 'ajax-ottieni-totale':
        require_once 'op_ajax_ottieni_totale.php';
        break;
    case 'ajax-visualizza-riepilogo':
        require_once 'op_ajax_visualizza_riepilogo.php';
        break;
    case 'ajax-ottieni-stato':
        require_once 'op_ajax_ottieni_stato.php';
        break;
    case 'finalizza':
        require_once 'op_finalizza.php';
        break;
    default:
        require_once 'op_editor_default.php';
        break;
}