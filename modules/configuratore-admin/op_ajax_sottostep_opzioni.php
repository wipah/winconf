<?php
if (!$core)
    die("Accesso diretto non consentito");


$this->noTemplateParse = true;

if (!$sottostep_ID = (int) $_POST['sottostep_ID'] ) {
    echo 'Manca l\'ID dello step';
}

$query = 'SELECT * 
          FROM configuratore_opzioni
          WHERE sottostep_ID = ' . $sottostep_ID . ' 
          ORDER BY ordine ASC';

if (!$result = $db->query($query)) {
    echo 'Query error.' . $query;
    return;
}

if (!$db->affected_rows) {
    echo 'Nessuna opzione scelta';
    return;
}

