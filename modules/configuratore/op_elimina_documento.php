<?php

if(!$user->validateLogin())
    return;

if (!isset($_GET['ID'])) {
    echo 'Manca il numero del documento';
    return;
}

$documento_ID = (int) $_GET['ID'];

if (isset($_GET['conferma'])) {

    $query = 'DELETE FROM documenti WHERE ID = ' . $documento_ID .  ';';

    if (!$db->query($query)) {
        echo 'Query error. ' . $query;

    } else {
        echo '&bull; Testata eliminata. <br/>';
    }

    $query = 'DELETE FROM documenti_corpo WHERE documento_ID = ' . $documento_ID . ';';

    if (!$db->query($query)) {
        echo 'Query error. ' . $query;

    } else {
        echo '&bull; Corpo eliminato. <br/>';
    }

    return;
}

echo '<div>
Attenzione: confermare l\'eliminazione? Questa operazione non potrà essere annullata!
<a href="' . $conf['URI'] . 'configuratore/elimina-documento/?ID=' . $documento_ID . '&conferma">Confermo</a>
</div>';