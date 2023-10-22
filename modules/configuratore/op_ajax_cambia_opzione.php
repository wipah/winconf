<?php

$this->noTemplateParse = true;
if (!$user->validateLogin())
    return;

$documento_ID   = (int) $_POST['documento_ID'];
$linea_ID       = (int) $_POST['linea_ID'];
$opzione_ID     = (int) $_POST['opzione_ID'];
$step_ID        = (int) $_POST['step_ID'];
$sottostep_ID   = (int) $_POST['sottostep_ID'];
$debug          = '';

// Ottiene l'ID della vecchia opzione
$opzionePrecedente_ID = $configuratore->ottieneOpzioneSelezionata($sottostep_ID);

/*
 * Resetta le linee di corpo (sottostep) che hanno una dipendenza con la vecchia opzione.
 * Ciascun sottostep sarà ulteriormente processato per alterare la visibilità delle opzioni legate alle opzioni del
 * sottostep stesso.
 */
$configuratore->resettaSottoStepDaOpzione($documento_ID, $opzionePrecedente_ID);

$configuratore->resettaOpzioni($documento_ID, $opzionePrecedente_ID);

$debug .= 'Opzione precedentemente selezionata: ' . $opzionePrecedente_ID . PHP_EOL;

$query = 'UPDATE documenti_corpo 
          SET opzione_ID = ' . $opzione_ID . '
            , valorizzata = 1 
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
        SELECT COUNT(configuratore_opzioni.ID) totale_opzioni
        FROM configuratore_opzioni
        WHERE configuratore_opzioni.sottostep_ID = documenti_corpo.sottostep_ID
       ) totale_opzioni
       FROM documenti_corpo 
       WHERE documento_ID = ' . $documento_ID .'
        AND documenti_corpo.visibile = 0
        AND esclusa = 0
        AND ( (origine_visibile = 0 && inclusa = 1) || origine_visibile = 1 )
       HAVING totale_opzioni > 0
       LIMIT 1';


$result = $db->query($query);

if (!$db->affected_rows) {
    echo json_encode([  'status' => -1
                            , 'message' => 'Non sono state trovate linee'
                            , 'step_ID' => 0
                            , 'debug' => $debug]);
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

echo json_encode(['status' => 1, 'message' => 'Operazione completata', 'step_ID' => $rowStepID, 'debug' => $debug]);