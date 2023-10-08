<?php

class configuratore
{

    public int $documento_ID;
    public float $larghezza;
    public float $lunghezza;

    /**
     * @param int $tipo 0 = sottostep, 1 = opzione
     * @param int $ID
     * @return int
     */
    function checkDipendenzaDimensione(int $documento_ID, int $tipo, int $sottostep_ID, $opzione_ID = null): int
    {
        global $db;


        $query = 'SELECT * FROM documenti WHERE ID = ' . $documento_ID;
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);

        $this->lunghezza = $row['lunghezza'];
        $this->larghezza = $row['larghezza'];


        switch ($tipo) {
            case 0: // Controllo dimensioni per sottostep
                $query = 'SELECT * FROM configuratore_opzioni_check_dimensioni 
                      WHERE sottostep_ID = ' . $sottostep_ID . ' AND (opzione_ID = 0 || opzione_ID IS NULL)';
                break;
            case 1: // Controllo dimensioni per opzione
                $query = 'SELECT * FROM configuratore_opzioni_check_dimensioni 
                      WHERE sottostep_ID = ' . $sottostep_ID . ' AND opzione_ID = ' . $opzione_ID . ';';
                break;

        }

        $result = $db->query($query);

        // Non esistono controlli, quindi il sottostep oppure l'opzione rimane nello stato attuale
        if (!$db->affected_rows)
            return 0;

        $risultato = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            switch ((int)$row['dimensione']) {
                case 0:
                    $dimensione = $this->larghezza;
                    break;
                case 1:
                    $dimensione = $this->lunghezza;
                    break;
                default:
                    // Gestisci altri casi di dimensione se necessario
                    break;
            }

            $valore = (float)$row['valore'];
            $esito = (int)$row['esito'];
            $confronto = (int)$row['confronto'];

            if (
                ($confronto === 0 && $dimensione < $valore) ||
                ($confronto === 1 && $dimensione <= $valore) ||
                ($confronto === 2 && $dimensione == $valore) ||
                ($confronto === 3 && $dimensione >= $valore) ||
                ($confronto === 4 && $dimensione > $valore) ||
                ($confronto === 5 && $dimensione != $valore)
            ) {
                if ($esito !== 0) {
                    $risultato = 1;
                }
                // Se esito è 0, il risultato rimane 0 e il loop continuerà con la prossima iterazione
                break;
            }
        }

        return $risultato;


        /* while ($row = mysqli_fetch_assoc($result)) {
            switch ( (int) $row['dimensione']) {
                case 0:
                    $dimensione = $this->larghezza;
                    break;
                case 1:
                    $dimensione = $this->lunghezza;
                    break;
            }

            $valore = (float) $row['valore'];
            $esito  = (int) $row['esito'];

            switch ( (int) $row['confronto']) {
                case 0:
                    if ($dimensione < $valore) {
                        if ($esito === 0 ) {
                            return 0;
                        } else {
                            $risultato = 1;
                        }
                    }
                    break;
                case 1:
                    if ($dimensione <= $valore) {
                        if ($esito === 0 ) {
                            return 0;
                        } else {
                            $risultato = 1;
                        }
                    }
                    break;
                case 2:
                    if ($dimensione == $valore) {
                        if ($esito === 0 ) {
                            return 0;
                        } else {
                            $risultato = 1;
                        }
                    }
                    break;
                case 3:
                    if ($dimensione >= $valore) {
                        if ($esito === 0) {
                            return 0;
                        } else {
                            $risultato = 1;
                        }
                    }
                    break;
                case 4:
                    if ($dimensione > $valore) {
                        if ($esito === 0 ) {
                            return 0;
                        } else {
                            $risultato = 1;
                        }
                    }
                    break;
                case 5:
                    if ($dimensione != $valore) {
                        if ($esito === 0 ) {
                            return 0;
                        } else {
                            $risultato = 1;
                        }
                    }
                    break;
            }

        } */
    }


    function aggiornaHash()
    {
        global $db;
        global $user;
        $hash = substr(md5(microtime(true)), 0, 5);

        $query = 'UPDATE companies SET configuratore_hash = \'' . $hash . '\'
        WHERE ID = ' . $user->company_ID . '
        LIMIT 1;';

        $db->query($query);

    }

    function stepDaOrdine(int $ordine_ID): array|false
    {
        global $db;

        $query = 'SELECT STEP.ID,
                         STEP.step_nome
                  FROM documenti_corpo CORPO
                  LEFT JOIN configuratore_step STEP
                    ON STEP.ID = CORPO.step_ID
                  WHERE CORPO.documento_ID = ' . $ordine_ID;


        $result = $db->query($query);

        if (!$db->affected_rows) {
            return false;
        } else {
            $return = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $return[(int)$row['ID']] = $row['step_nome'];
            }

            return $return;
        }
    }

    function layoutCreaSottoStep(int $documento_ID, int $step_ID, int $sottostep_ID, $linea_ID): string
    {
        global $db;
        global $configuratore;



        $query = 'SELECT * 
                 FROM configuratore_sottostep 
                 WHERE ID = ' . $sottostep_ID . ' LIMIT 1';

        $result = $db->query($query);

        if (!$db->affected_rows)
            return 'Sottostep di ID ' . $sottostep_ID . ' non trovato';


        $row = mysqli_fetch_assoc($result);

        $query = 'SELECT opzione_ID 
                  FROM documenti_corpo WHERE ID = ' . $linea_ID;

        $risultatoOpzioneScelta = $db->query($query);
        $rowOpzioneScelta = mysqli_fetch_assoc($risultatoOpzioneScelta);

        $partSelect = '';

        if ((int)$row['tipo_scelta'] === 0) {
            $query = 'SELECT * 
                      FROM configuratore_opzioni 
                      WHERE sottostep_ID = ' . $sottostep_ID;

            $risultatoOpzioni = $db->query($query);


            $partSelect = '<select class="form-control"  onchange="cambiaSingolaOpzione(\'' . $linea_ID . '\', $(this).val(), ' . $step_ID . ');" id="">';
            while ($rowOpzioni = mysqli_fetch_assoc($risultatoOpzioni)) {


                /*
                 * Controlla se l'opzione ha un check sulle dimensioni.
                 */
                if ( (int) $rowOpzioni['check_dimensioni'] === 1) {
                    $checkDimensioni = $configuratore->checkDipendenzaDimensione($documento_ID,1, $sottostep_ID,$rowOpzioni['ID']);

                    switch ($checkDimensioni) {
                        case -1:
                        case 0 && (int)$rowOpzioni['visibile'] === 0:
                            break 2;
                            continue;

                    }
                }

                $partSelect .= '<option ' . ((int)$rowOpzioni['ID'] === (int)$rowOpzioneScelta['opzione_ID'] ? ' selected ' : '') . ' 
                                        value="' . $rowOpzioni['ID'] . '">' . $rowOpzioni['opzione_nome'] . '
                                </option>';
            }
            $partSelect .= '</select>';
        }

        $part = '<div class="layoutEditorSottostep">
                    <div class="row">
                        <div class="col-md-3 layoutEditorSottostepNome">' . $row['sottostep_nome'] . '</div>
                        <div class="col-md-8">' . $partSelect . '</div>
                        <div class="col-md-1"><div id="layoutEditorSottostepStatus-' . $linea_ID . '"></div></div>
                    </div>
                </div>';

        return $part;

    }
}