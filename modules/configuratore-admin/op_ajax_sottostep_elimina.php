<?php

if (!$core)
    die("Accesso diretto");

$this->noTemplateParse = true;

if (!$user->logged) {
    echo 'Devi essere loggato';
    return;
}

if (!$sottostep_ID = (int) $_POST['sottostep_ID']) {
    echo 'Manca l\'ID della dimensione';
    return;
}

$query = 'DELETE FROM configuratore_sottostep WHERE ID = ' . $sottostep_ID . ' LIMIT 1';

if (!$db->query($query)) {
    echo '--KO--' . $query;
} else {
    echo '--OK--';
}


$query = 'DELETE FROM configuratore_sottostep_check WHERE sottostep_ID = ' . $sottostep_ID . ' LIMIT 1';

if (!$db->query($query)) {
    echo '--KO--' . $query;
} else {
    echo '--OK--';
}


$query = 'DELETE FROM configuratore_opzioni WHERE sottostep_ID = ' . $sottostep_ID . ' LIMIT 1';

if (!$db->query($query)) {
    echo '--KO--' . $query;
} else {
    echo '--OK--';
}

$query = 'DELETE FROM configuratore_opzioni_check_dimensioni WHERE sottostep_ID = ' . $sottostep_ID . ' LIMIT 1';

if (!$db->query($query)) {
    echo '--KO--' . $query;
} else {
    echo '--OK--';
}

$query = 'DELETE FROM configuratore_opzioni_check_dipendenze WHERE sottostep_ID = ' . $sottostep_ID . ' LIMIT 1';

if (!$db->query($query)) {
    echo '--KO--' . $query;
} else {
    echo '--OK--';
}