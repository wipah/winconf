<?php
$this->noTemplateParse = true;
if (!$user->validateLogin())
    return;

$documento_ID = (int)$_POST['documento_ID'];
$linea_ID = (int)$_POST['linea_ID'];
$opzione_ID = $core->in($_POST['opzione_ID']);
$step_ID = (int)$_POST['step_ID'];
$sottostep_ID = (int)$_POST['sottostep_ID'];
$special = (int)$_POST['special'];
$debug = '';

if ($special > 0) {
    if ($special === 2) {
        $query = 'UPDATE documenti_corpo 
                  SET valore_testo = \'' . $opzione_ID . '\' 
                  WHERE ID = ' . $linea_ID . ' 
                  LIMIT 1';

        $db->query($query);
    } else {
        switch ($special) {
            case 99:
                $dimensione = 'larghezza';
                break;
            case 98:
                $dimensione = 'lunghezza';
                break;
            case 97:
                $dimensione = 'spessore';
                break;
        }

        $query = 'UPDATE documenti SET ' . $dimensione . ' =  ' .  ((int) $opzione_ID) . ' WHERE ID = ' . $documento_ID . ' LIMIT 1';
        $db->query($query);

        $query = 'UPDATE documenti_corpo SET valore = ' . ((int) $opzione_ID) . '  WHERE ID = ' . $linea_ID . ' LIMIT 1';
        $db->query($query);
    }

} else {
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
          SET opzione_ID = ' . ( (int) $opzione_ID) . '
            , valorizzata = 1 
          WHERE ID = ' . $linea_ID . ' 
          LIMIT 1';

    $db->query($query);

// Controlla le dipendenze
    $configuratore->checkDipendenzaOpzione($documento_ID, $opzione_ID);
}

// Cerca se, tra le opzioni successive, esistono dei campi opzione che sono scritti in testata (Larghezza, lunghezza, spessore)
$query = 'SELECT SOTTOSTEP.tipo_scelta 
          FROM documenti_corpo CORPO
          LEFT JOIN configuratore_sottostep SOTTOSTEP 
            ON SOTTOSTEP.ID = CORPO.sottostep_ID 
          WHERE SOTTOSTEP.tipo_scelta IN (99,98,97)
            AND documento_ID = ' . $documento_ID . ' AND CORPO.ID > ' . $linea_ID;

$result = $db->query($query);

if ($db->affected_rows) {
    while ($rowDelete = mysqli_fetch_assoc($result)) {
        $queryUpdate = 'UPDATE documenti SET ';
        switch ( (int) $rowDelete['tipo_scelta']) {
            case 99:
                $queryUpdate .= ' larghezza ';
                break;
            case 98:
                $queryUpdate .= ' lunghezza ';
                break;
            case 97:
                $queryUpdate .= ' spessore ';
                break;

        }

        $queryUpdate .= ' = 0 WHERE ID = ' . $documento_ID . ' LIMIT 1';

        $db->query($queryUpdate);
    }
}
// Elimina la valorizzazione e l'eventuale scelta delle opzioni successive
$query = 'UPDATE documenti_corpo 
          SET  valorizzata = 0 
             , visibile = 0 
             , valore = 0
             , valore_testo = 0
             , opzione_ID = NULL
          WHERE documento_ID = ' . $documento_ID . ' 
          AND ID > ' . $linea_ID;


if (!$db->query($query)) {
    echo json_encode(['status' => -2, 'message' => 'Errore nella query di update visibilità' . $query]);
    return;
}

/*
 * Questa query cerca e abilita la linea successiva. Valuta, a tal proposito, la presenza di opzioni valide nel sottostep.
 * Ad esempio, se lo step successivo non possiede opzioni valide restituisce lo stato -1.
 */
$query = 'SELECT documenti_corpo.ID, 
       documenti_corpo.step_ID,SOTTOSTEP.tipo_scelta,
       (
        SELECT COUNT(configuratore_opzioni.ID) totale_opzioni
        FROM configuratore_opzioni
        WHERE configuratore_opzioni.sottostep_ID = documenti_corpo.sottostep_ID
       ) totale_opzioni
       FROM documenti_corpo 
       LEFT JOIN configuratore_sottostep SOTTOSTEP
       ON SOTTOSTEP.ID = documenti_corpo.sottostep_ID
       WHERE documento_ID = ' . $documento_ID . '
        AND documenti_corpo.visibile = 0
        AND esclusa = 0
        AND ( (origine_visibile = 0 && inclusa = 1) || origine_visibile = 1 )
       HAVING (totale_opzioni > 0) OR (tipo_scelta IN (99, 98, 97, 2)) /* agganciare la tipo_scelta permette di selezionare una tra le dimensioni (altezza, larghezza, spessore) oppure una casella di testo*/
       LIMIT 1';

$result = $db->query($query);
$debug .= $query;

if (!$db->affected_rows) {
    echo json_encode(['status' => -1
        , 'message' => 'Non sono state trovate linee da abilitare'
        , 'step_ID' => 0
        , 'debug' => str_replace('\r\n', PHP_EOL, $debug)]);
    return;
}

$rowNext = mysqli_fetch_assoc($result);
$rowStepID = $rowNext['step_ID'];

$query = 'UPDATE documenti_corpo 
          SET visibile = 1 
          WHERE ID = ' . $rowNext['ID'];

if (!$db->query($query)) {
    echo json_encode(['status' => -2, 'message' => 'Errore nella query.' . $query]);
    return;
}

echo json_encode(['status' => 1, 'message' => 'Operazione completata', 'step_ID' => $rowStepID, 'debug' => $debug]);