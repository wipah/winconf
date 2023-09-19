<?php

$this->noTemplateParse = true;
if (!$user->validateLogin())
    return;

$opzione_ID     = (int) $_POST['opzione_ID'];
$linea_ID       = (int) $_POST['linea_ID'];
$documento_ID   = (int) $_POST['documento_ID'];

$query = 'UPDATE documenti_corpo 
          SET opzione_ID = ' . $opzione_ID . ' 
          WHERE ID = ' . $linea_ID . ' 
          LIMIT 1';

$db->query($query);

// Cerca e abilita la successiva linea
$query = 'SELECT ID 
          FROM documenti_corpo 
          WHERE documento_ID = ' . $documento_ID . '
          AND visible = 0
          AND ID > ' . $ID . '
          LIMIT 1';

$result = $db->query($query);
if (!$db->affected_rows) {
    echo '--KO-- Non risultano linee. ' . $query;
    return;
}

$rowNext = mysqli_fetch_assoc($result);

$query = 'UPDATE documenti_corpo SET visibile = 1 WHERE ID = ' . $rowNext['ID'];

$db->query($query);

echo '--OK--';