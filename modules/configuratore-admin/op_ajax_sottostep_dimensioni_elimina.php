<?php
if (!$core)
    die("Accesso diretto");

$this->noTemplateParse = true;

if (!$user->logged) {
    echo 'Devi essere loggato';
    return;
}

if (!$dimensione_ID = (int) $_POST['dimensione_ID']) {
    echo 'Manca l\'ID della dimensione';
    return;
}

$query = 'DELETE FROM configuratore_opzioni_check_dimensioni WHERE ID = ' . $dimensione_ID . ' LIMIT 1';

if (!$db->query($query)) {
    echo '--KO--' . $query;
} else {
    echo '--OK--';
}