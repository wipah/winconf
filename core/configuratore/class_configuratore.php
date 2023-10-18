<?php

class configuratore
{

    public int $documento_ID;
    public float $larghezza;
    public float $lunghezza;


    function getDimensioni ( int $documento_ID) : void {
        global $db;


        $query = 'SELECT * 
                  FROM documenti WHERE ID = ' . $documento_ID;
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);

        $this->lunghezza = $row['lunghezza'];
        $this->larghezza = $row['larghezza'];

    }

    /**
     * @param int $documento_ID
     * @param int $sottostep_ID
     * @param     $opzione_ID
     * @return void
     */
    function checkDipendenzaOpzione (int $documento_ID, $opzione_valore_ID) : array {
        global $db;

        $this->getDimensioni($documento_ID);

        $query = 'SELECT * 
                  FROM configuratore_opzioni_check_dipendenze 
                  WHERE opzione_valore_ID = ' . $opzione_valore_ID . ';';

        $result = $db->query($query);

        if (!$db->affected_rows)
            return ['status' => -2];

        $step = [];
        $sottostep = [];

        while ($row  = mysqli_fetch_assoc($result)) {
            $step[] = $row['step_ID'];
            $sottostep[] = $row['sottostep_ID'];

            echo 'Cambio opzione. Sottostep_ID : ' . $row['sottostep_ID'];

            if ( (int) $row['esito'] === 0) {
                $esclusa = 1;
                $visibile = 0;
            } else {
                $esclusa = 0;
                $visibile = 1;
            }

            if ($tipo === 0 ) {
                $query = 'UPDATE documenti_corpo 
                                SET  esclusa  = ' . $esclusa . '
                                   , visibile = ' . $visibile . '
                      WHERE ID = ' . $row['sottostep_ID'] . '
                      LIMIT 1';

                $db->query($query);
            } else {

                $query = 'DELETE FROM documenti_corpo_opzioni 
                              WHERE documento_ID = ' . $documento_ID . ' AND opzione_ID = '. $row['opzione_ID'];

                $db->query($query);

                $query = 'INSERT INTO documenti_corpo_opzioni 
                                        ( 
                                          documento_ID
                                        , opzione_ID
                                        , stato
                                        )
                               VALUES   ( 
                                          ' . $documento_ID . '
                                        ,  ' . $row['opzione_ID']. '
                                        , ' . $visibile . '
                                        );';


                $db->query($query);

            }

        }

        return ['status' => 1, 'step' => $step, 'sottoStep' => $sottostep];
    }

    /**
     * @param int  $documento_ID ID del docmento
     * @param int  $tipo 0 = sottostep, 1 = opzione
     * @param int  $sottostep_ID ID dell'opzione
     * @param null $opzione_ID
     * @return int
     */
    function checkDipendenzaDimensione(int $documento_ID, int $tipo, int $sottostep_ID, $opzione_ID = null): int
    {
        global $db;

        $this->getDimensioni($documento_ID);

        switch ($tipo) {
            case 0: // Controllo dimensioni per sottostep
                $query = 'SELECT * 
                          FROM configuratore_opzioni_check_dimensioni 
                          WHERE sottostep_ID = ' . $sottostep_ID . ' 
                            AND (opzione_ID = 0 || opzione_ID IS NULL)';
                break;
            case 1: // Controllo dimensioni per opzione
                $query = 'SELECT * 
                          FROM configuratore_opzioni_check_dimensioni 
                          WHERE sottostep_ID = ' . $sottostep_ID . ' 
                            AND opzione_ID = ' . $opzione_ID . ';';
                break;
        }

        $result = $db->query($query);

        /*
         * La tabella configuratore_opzioni_check_dimensioni non contiene alcun riferimento
         * per il sottostep oppure per l'opzione passata. Si presumo che lo stato di visibilità
         * del sottostep oppure dell'opzione rimanga inalterato.
         *
         */

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

            $valore     = (float)   $row['valore'];
            $esito      = (int)     $row['esito'];
            $confronto  = (int)     $row['confronto'];

            if (
                ($confronto === 0 && $dimensione < $valore)  ||
                ($confronto === 1 && $dimensione <= $valore) ||
                ($confronto === 2 && $dimensione == $valore) ||
                ($confronto === 3 && $dimensione >= $valore) ||
                ($confronto === 4 && $dimensione > $valore)  ||
                ($confronto === 5 && $dimensione != $valore)
              ) {

                if ($esito === 1) {
                    $risultato = 1;
                } else {
                    return -1;
                }
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
                        case 0 && (int) $rowOpzioni['visibile'] === 0:
                            break 2;


                    }
                }

                $partSelect .= '<option ' . ((int)$rowOpzioni['ID'] === (int)$rowOpzioneScelta['opzione_ID'] ? ' selected ' : '') . ' 
                                        value="' . $rowOpzioni['ID'] . '">' . $rowOpzioni['opzione_nome'] . ' <!-- [CDM:' . $checkDimensioni . '] -->
                                </option>';
            }
            $partSelect .= '</select>';
        }

        $part = '<div class="layoutEditorSottostep">
                    <div class="row">
                        <div class="col-md-4"> 
                            <div class="layoutEditorSottostepNome">' . $row['sottostep_nome'] . '</div>
                            <hr />
                            <div class="layoutEditorSottostepDescrizione">' . $row['sottostep_descrizione'] . '</div>
                        </div>
                        <div class="col-md-7">' . $partSelect . '</div>
                        <div class="col-md-1"><div id="layoutEditorSottostepStatus-' . $linea_ID . '"></div></div>
                    </div>
                </div>';

        return $part;

    }

    function totaleDocumento (int $documento_ID) : float {
        global $db;


        $totale = 1;

        $query = '
        SELECT  CORPO.ID corpo_ID
              , OPZIONI.opzione_formula_valore
              , FORMULE.formula_sigla
        FROM documenti_corpo CORPO 
        LEFT JOIN configuratore_opzioni OPZIONI
            ON OPZIONI.ID = CORPO.opzione_ID
        LEFT JOIN configuratore_formule FORMULE
            ON FORMULE.ID = OPZIONI.opzioni_formula_ID	
        
        WHERE   CORPO.visibile = 1 
            AND CORPO.valorizzata = 1
            AND CORPO.documento_ID = ' . $documento_ID . ';';

        $result = $db->query($query);

        if (!$db->affected_rows) {
            $this->aggiornaTotaleDocumento($documento_ID, 0);
            return 1;
        }

        while ($row = mysqli_fetch_assoc( $result )) {

            $sigla = strtolower($row['formula_sigla']);
            $valore = (float) $row['opzione_formula_valore'];

            switch (  $sigla) {
                case 'coeff-k':
                    $totale = $totale * $valore;
                    break;
                case 'somma-v':
                    $totale += $valore;
                    break;
                case 'somma-kdim':
                    $totale += $valore * ($this->larghezza * $this->lunghezza);
                    break;
                default:
                    echo 'Opzione ' . $sigla . ' non trovata. <br/>';
                    break;
            }
        }

        $this->aggiornaTotaleDocumento($documento_ID, $totale);

        return $totale;
    }

    function aggiornaTotaleDocumento( int $documento_ID, float $totale ) : void {
        global $db;

        $query = 'UPDATE documenti 
                  SET totale = ' . $totale . ' 
                  WHERE ID = ' . $documento_ID;

        $db->query($query);
    }
}