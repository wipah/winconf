<?php

if (!$core)
    die ("Accesso diretto");

$this->noTemplateParse = true;

if (!$user->logged) {
    echo 'Devi essere loggato';
    return;
}

$arrayOrdine = explode(",", $_REQUEST['sortOrder']);

$i = 0;
foreach ($arrayOrdine as $ID) {
    $query = 'UPDATE configuratore_opzioni
              SET ordine = ' . $i . ' 
              WHERE ID = ' . $ID . ' 
              LIMIT 1';

    if (!$db->query($query)) {
        echo 'Query error. ' . $query;
    } else {
        echo '--OK--';
    }

    $i++;
}