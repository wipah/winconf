<?php

if (!$core)
    die ("Accesso diretto");

$this->noTemplateParse = true;

if (!$user->logged){
    echo 'Devi essere loggato';
    return;
}

/*
if (!$sottostep_ID = (int) $_POST['sottostep_ID']) {
    echo 'Manca il sottostep.';
    return;
}
*/

$categoria_ID        = (int) $_POST['categoria_ID'];
$step_ID             = (int) $_POST['step_ID'];
$sottostep_ID        = (int) $_POST['sottostep_ID'];
$opzione_ID          = (int) $_POST['opzione_ID'];
$opzione_valore_ID   = (int) $_POST['opzione_valore_ID'];
$confronto           = (int) $_POST['confronto'];
$esito               = (int) $_POST['esito'];

$ID = (int) $_POST['ID'];

if ($ID === 0) {
    $query = 'INSERT INTO configuratore_opzioni_check_dipendenze (categoria_ID, step_ID, sottostep_ID, opzione_ID, opzione_valore_ID, confronto, esito, valore)
              VALUES 
                  (
                       '  . $categoria_ID . '
                     ,  ' . $step_ID . '
                     ,  ' . $sottostep_ID . '
                     , ' . ( (int) $_POST['opzione_ID']  ) . '
                     , ' . $opzione_valore_ID . '
                     , ' . ( (int) $_POST['confronto']  ) . '
                     , ' . ( (int) $_POST['esito']  ) . '
                     , ' . ( (float) $_POST['valore']  ) . '
                  )';
} else {
    $query = 'UPDATE configuratore_opzioni_check_dipendenze SET
                   opzione_ID = ' . ( (int) $_POST['opzione_ID']  ) . '
                 ,  opzione_valore_ID = ' . $opzione_valore_ID . '
                 , confronto = ' . ( (int) $_POST['confronto']  ) . ' 
                 ,  esito = ' . ( (int) $_POST['esito']  ) . '
                 WHERE ID = ' . $ID .'
                 LIMIT 1;';
}

if (!$db->query($query)) {
    echo '--KO--' . $query;
}

echo '--OK--';