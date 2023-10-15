<?php
if (!$core)
    die("Accesso diretto");

if (!$user->logged) {
    echo 'Devi essere loggato';
    return;
}

if (!$opzione_ID = (int) $_POST['opzione_ID']) {
    echo 'Manca l\'ID dell\'opzione';
    return;
}

$query = 'DELETE FROM configuratore_opzioni WHERE ID = ' . $opzione_ID . ' LIMIT 1';

if (!$db->query($query)) {
    echo '--KO--' . $query;
} else {
    echo '--OK--';
}

$query = 'DELETE FROM configuratore_opzioni_check_dimensioni WHERE opzione_ID = ' . $opzione_ID . ' LIMIT 1';

if (!$db->query($query)) {
    echo '--KO--' . $query;
} else {
    echo '--OK--';
}

$query = 'DELETE FROM configuratore_opzioni_check_dipendenze WHERE opzione_ID = ' . $opzione_ID . ' LIMIT 1';

if (!$db->query($query)) {
    echo '--KO--' . $query;
} else {
    echo '--OK--';
}