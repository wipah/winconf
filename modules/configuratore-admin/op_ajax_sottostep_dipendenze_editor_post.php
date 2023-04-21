<?php

if (!$core)
    die ("Accesso diretto");

$this->noTemplateParse = true;

if (!$user->logged){
    echo 'Devi essere loggato';
    return;
}

if (!$sottostep_ID = (int) $_POST['sottostep_ID']) {
    echo 'Manca il sottostep.';
    return;
}

$ID = (int) $_POST['ID'];

if ($ID === 0) {
    $query = 'INSERT INTO configuratore_opzioni_check_dipendenze (sottostep_ID, opzione_valore_ID, confronto, esito)
              VALUES 
                  (
                   
                  )';
} else {
    $query = '';
}