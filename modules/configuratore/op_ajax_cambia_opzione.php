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
$query = 'SELECT ID, step_ID
          FROM documenti_corpo 
          WHERE documento_ID = ' . $documento_ID . '
          AND visibile = 0
          AND ID > ' . $linea_ID . '
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