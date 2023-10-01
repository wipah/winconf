<?php
if (!$core)
    die("Direct access");

$this->noTemplateParse = true;

if (!$user->logged) {
    echo 'Devi aver effettuato il login';
    return;
}

if (!$dipendenza_ID = (int) $_POST['dipendenza_ID']);

echo 'Dipendenza non passata';

$query = 'DELETE 
          FROM configuratore_opzioni_check_dipendenze
          WHERE ID = ' . $dipendenza_ID . ' LIMIT 1';

if (!$db->query($query)) {
    echo '--KO-- Query error. ' . $query;
} else {
    echo '--OK--';
}