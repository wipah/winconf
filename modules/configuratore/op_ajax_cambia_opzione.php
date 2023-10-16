<?php

$this->noTemplateParse = true;
if (!$user->validateLogin())
    return;

$documento_ID   = (int) $_POST['documento_ID'];
$linea_ID       = (int) $_POST['linea_ID'];
$opzione_ID     = (int) $_POST['opzione_ID'];


$query = 'UPDATE documenti_corpo 
          SET opzione_ID = ' . $opzione_ID . ' 
          WHERE ID = ' . $linea_ID . ' 
          LIMIT 1';

$db->query($query);

// Controlla le dipendenze
$configuratore->checkDipendenzaOpzione($documento_ID, $opzione_ID);

// Elimina la valorizzazione e l'eventuale scelta delle opzioni successive
$query = 'UPDATE documenti_corpo 
          SET  valorizzata = 0 
             , visibile = 0 
             , opzione_ID = NULL
          WHERE documento_ID = ' . $documento_ID . ' 
          AND ID > ' .  $linea_ID;

if (!$db->query($query)){
    echo json_encode(['status' => -2, 'message' => 'Errore nella query di update visibilità' . $query]);
    return;
}

// Cerca e abilita la successiva linea
/*
$query = 'SELECT ID, step_ID
          FROM documenti_corpo
          WHERE documento_ID = ' . $documento_ID . '
          AND visibile = 0
          AND esclusa = 0
          AND ID > ' . $linea_ID . '
          LIMIT 1';
*/

$query = 'SELECT documenti_corpo.ID, 
       documenti_corpo.step_ID,
       (
       SELECT COUNT(configuratore_opzioni.ID) totale
       FROM configuratore_opzioni
       WHERE configuratore_opzioni.sottostep_ID = documenti_corpo.sottostep_ID
       ) totale_opzioni
FROM documenti_corpo 
WHERE documento_ID = ' . $documento_ID .'
AND documenti_corpo.visibile = 0
AND esclusa = 0
AND documenti_corpo.ID > ' . $linea_ID . '
HAVING totale_opzioni > 1
LIMIT 1';
$result = $db->query($query);

if (!$db->affected_rows) {
    echo json_encode(['status' => -1, 'message' => 'Non sono state trovate linee', 'step_ID' => 0]);
    return;
}

$rowNext = mysqli_fetch_assoc($result);
$rowStepID = $rowNext['step_ID'];

$query = 'UPDATE documenti_corpo 
          SET visibile = 1 
          WHERE ID = ' . $rowNext['ID'];

if (!$db->query($query)){
    echo json_encode(['status' => -2, 'message' => 'Errore nella query.' . $query]);
    return;
}


echo json_encode(['status' => 1, 'message' => 'Operazione completata', 'step_ID' => $rowStepID]);