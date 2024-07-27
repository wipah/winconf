<?php

class configuratore
{

    public int $documento_ID;
    public float $larghezza;
    public float $lunghezza;


    function ottieneOpzioneSelezionata( int $sottostep_ID ) : int {
        global $db;

        $query = 'SELECT opzione_ID 
                  FROM documenti_corpo 
                  WHERE sottostep_ID = ' . $sottostep_ID;

        $result = $db->query($query);

        if (!$db->affected_rows)
            return 0;

        $row = mysqli_fetch_assoc($result);

        return $row['opzione_ID'] ?? 0;
    }

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
    function checkDipendenzaOpzione (int $documento_ID, $opzione_valore_ID, $cambio = 0) : array {
        global $db;

        $this->getDimensioni($documento_ID);

        $query = 'SELECT * 
                  FROM configuratore_opzioni_check_dipendenze 
                  WHERE opzione_valore_ID = ' . $opzione_valore_ID . ';';

        $debug = 'Query iniziale: ' . $query . PHP_EOL;
        $result = $db->query($query);

        if (!$db->affected_rows) {
            $debug .= 'L\'opzione non ha dipendenze.' . PHP_EOL;
            return ['status' => -2, 'debug' => $debug];
        }

        $step           =   [];
        $sottostep      =   [];

        while ($row  = mysqli_fetch_assoc($result)) {
            $step[] = $row['step_ID'];
            $sottostep[] = $row['sottostep_ID'];

            if ( (int) $row['esito'] === 0) {
                $esclusa = 1;
                $visibile = 0;
            } else {
                $esclusa = 0;
                $visibile = 1;
            }

            if (is_null($row['opzione_ID']) || (int) $row['opzione_ID'] === 0 ) {
                $debug .= 'L\'opzione cambia la visibilità di un sottostep.' .PHP_EOL;

                $query = 'UPDATE documenti_corpo 
                                SET  esclusa  = ' . $esclusa . '
                                   , inclusa  = ' . !$esclusa . '
                                   , visibile = ' . $visibile . '
                      WHERE sottostep_ID = ' . $row['sottostep_ID'] . '
                      LIMIT 1';

                $debug .= 'Query di update del sottostep. ' . $query;

                $db->query($query);
            } else {
                $debug .= 'L\'opzione cambia la visibilità di una opzione.' .PHP_EOL;

                $query = 'DELETE FROM documenti_corpo_opzioni 
                              WHERE documento_ID = ' . $documento_ID . ' AND opzione_ID = '. $row['opzione_ID'];

                $db->query($query);

                $query = 'INSERT INTO documenti_corpo_opzioni 
                                        ( 
                                          documento_ID
                                        , categoria_ID
                                        , step_ID
                                        , sottostep_ID
                                        , opzione_ID
                                        , stato
                                        )
                               VALUES   ( 
                                          ' . $documento_ID . '
                                        ,  ' . $row['categoria_ID'] . '
                                        ,  ' . $row['step_ID'] . '
                                        ,  ' . $row['sottostep_ID'] . '
                                        ,  ' . $row['opzione_ID']. '
                                        , ' . $visibile . '
                                        );';
                $debug .= 'Cambiata la visibilità dell\'opzione con la query. ' . $query;
                $db->query($query);

            }

        }

        return ['status' => 1, 'step' => $step, 'sottoStep' => $sottostep, 'debug' => $debug];
    }

    function resettaOpzioni(int $documento_ID, $opzione_ID) {
        global $db;

        $query = 'SELECT DIPENDENZE.opzione_ID 
                  FROM configuratore_opzioni_check_dipendenze DIPENDENZE
                  WHERE DIPENDENZE.opzione_valore_ID = ' . $opzione_ID;

        $result = $db->query ($query);

        if (!$db->affected_rows)
            return;

        $arrayOpzioni = [];
        while ($row = mysqli_fetch_assoc($result)) {

            if (in_array($row['opzione_ID'], $arrayOpzioni ))
                continue;

            $query = 'UPDATE documenti_corpo_opzioni 
                    SET stato = 1 ^ stato 
                    WHERE opzione_ID = ' . $row['opzione_ID'] . ' 
                    LIMIT 1';

            $arrayOpzioni[] = $row['opzione_ID'];
            $db->query($query);
        }
    }

    function resettaSottoStepDaOpzione(int $documento_ID, int $opzione_ID)
    {
        global $db;
        $query = 'SELECT * 
                  FROM configuratore_opzioni_check_dipendenze 
                  WHERE opzione_valore_ID = ' . $opzione_ID;

        $risultato = $db->query($query);

        if (!$db->affected_rows)
            return;

        while ($row = mysqli_fetch_assoc($risultato)) {

            $query = 'UPDATE documenti_corpo 
                      SET  valorizzata = 0
                         , inclusa     = 0
                         , esclusa     = 0
                         , visibile    = origine_visibile
                      WHERE documento_ID = ' . $documento_ID . '
                        AND sottostep_ID = ' . $row['sottostep_ID'];

            $db->query($query);

            $this->resettaOpzioniDaOpzione($documento_ID, $row['sottostep_ID'], $opzione_ID);
        }

    }

    /**
     * Ripristina lo stato di visibilità delle opzioni a seguito della modifica di una opzione che ha effetto su
     * un sottostep controlla altre opzioni.
     * @param int $documento_ID
     * @param int $sottostep_ID
     * @param int $opzione_ID
     * @return void
     */
    function resettaOpzioniDaOpzione(int $documento_ID, int $sottostep_ID, int $opzione_ID)
    {
        global $db;

        // Viene passato un $sottostep_ID che contiene alcune opzioni.
        // Tramite query vengono ricavati gli ID di queste opzioni
        $query = 'SELECT ID 
                  FROM configuratore_opzioni 
                  WHERE sottostep_ID = ' . $sottostep_ID;

        $result = $db->query($query);

        if (!$db->affected_rows)
            return;

        // Ottenuti gli ID delle opzioni vengono ricercati gli ID nella tabella configuratore_opzioni_check_Dipendenze
        // In questo modo è possibile ottenere le opzioni che dipendono dalle opzioni variate nel sottostep
        $arraySottoStep = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $query = 'SELECT * 
                      FROM configuratore_opzioni_check_dipendenze 
                      WHERE opzione_valore_ID = ' . $row['ID'];

            $resultDipendenze = $db->query($query);


            if (!$db->affected_rows)
               return;


            while ($rowDipendenze = mysqli_fetch_assoc($resultDipendenze)) {

                // Il sottostep è già stato modificato. Procede per evitare "ping-pong" sul valore di visibilità
                if (in_array($rowDipendenze['sottostep_ID'], $arraySottoStep)) {
                    continue;
                }

                $query = 'UPDATE documenti_corpo_opzioni
                      SET  stato = 1 - stato
                      WHERE documento_ID = ' . $documento_ID . '
                        AND opzione_ID   = ' . $rowDipendenze['opzione_ID'] . '
                        AND sottostep_ID = ' .  $rowDipendenze['sottostep_ID'];

                $arraySottoStep[] = $rowDipendenze['sottostep_ID'];
                $db->query($query);
            }
        }

        $db->query($query);

        if (!$db->affected_rows)
            return;
    }


    /**
     * Questa funzione controlla se un sottostep oppure una opzione possano o meno essere visualizzati.
     * Qualora il parametro $opzione_ID non fosse passato si ritiene che il controllo andrà fatto sul sottostep.
     *
     * La funziona restituisce tre tipi di valori:
     *  -1, se il sottostep oppure l'opzione non vanno mostrati
     *   0, se non ci sono condizioni nel database (ad esempio se è stato flaggato per una opzione / sottostep
     *      il controllo delle dimensioni ma non vi sono specificati i controlli.
     *   1, se il sottostep oppure l'opzione vanno mostrati
     *
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

            /*
             * Variabili
             * $valore      = Valore da verificare
             * $confronto   = Tipo di confronto da effettuare
             * $esito       = 0 escludi, 1 = includi
             */

            $valore     = (float)   $row['valore'];
            $confronto  = (int)     $row['confronto'];
            $esito      = (int)     $row['esito'];

            // echo 'Valore ' . $valore . ' confronto ' . $confronto . ' esito ' . $esito . ' dimensione ' . $dimensione;
            /*
             * Controlla se il tipo di confronto è coerente con la dimensione e il valore.
             * ATTENZIONE: In caso di esito 0 il programma restituisce immediatamente -1 poiché il primo match di
             * esclusione, di fatto, esclude la possibilità che l'opzione possa essere inclusa negli step successivi.
             *
             * A fine loop viene restituito l'esito
             */

            if (
                ($confronto === 0 && $dimensione < $valore)  ||
                ($confronto === 1 && $dimensione <= $valore) ||
                ($confronto === 2 && $dimensione == $valore) ||
                ($confronto === 3 && $dimensione >= $valore) ||
                ($confronto === 4 && $dimensione > $valore)  ||
                ($confronto === 5 && $dimensione != $valore)
              ) {

                if ($esito === 1) {
                    // echo 'here';
                    $risultato = 1;
                } else {
                    // echo 'there';
                    return -1;
                }
            }
        }

        return $risultato;

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


    /**
     * Determina se un sottostep è visibile in base alle eventuali opzioni che lo controllano. Il sottostep
     * è determinato come non visibile se almeno una delle opzioni lo rende tale.
     * @param int $documento_ID
     * @param int $step_ID
     * @param int $sottostep_ID
     * @param int $linea_ID
     * @return bool
     */
    function sottoStepVisibile(int $documento_ID, int $step_ID, int $sottostep_ID, int $linea_ID) : int
    {
        global $db;

        $query = 'SELECT * FROM configuratore_sottostep WHERE ID = ' . $sottostep_ID;
        $result = $db->query($query);

        if (!$db->affected_rows) {
            die ("Parsing di un sottostep non esistente. Errore interno");
        }

        $row = mysqli_fetch_assoc($result);
        $origineVisibile = (int) $row['visibile'];

        // Se non ci sono check sulle dipendenze ritorna lo stato di visibilità originario
        if ( (int) $row['check_dipendenze'] === 0)
            return $origineVisibile;

        // Cerca tutte le opzioni che controllano il sottostep
        $query = 'SELECT CHECKS.opzione_ID /* Da ignorare in caso di controllo per il sottoStep */
                       , CHECKS.opzione_valore_ID
                       , CHECKS.confronto
                       , CHECKS.esito 
                       , CORPO.opzione_ID corpo_opzione_ID
                  FROM configuratore_opzioni_check_dipendenze CHECKS
                  LEFT JOIN documenti_corpo CORPO
                    ON CORPO.opzione_ID = CHECKS.opzione_ID
                  WHERE CHECKS.sottostep_ID = ' . $sottostep_ID . ';';

        $result = $db->query($query);

        if (!$db->affected_rows)
            return $origineVisibile;


        while ($row = mysqli_fetch_assoc($result)) {
            $esito              = (int) $row['esito'];
            $confronto          = (int) $row['confronto'];
            $opzione_valore_ID  = (int) $row['opzione_valore_ID'];
            $opzione_ID         = (int) $row['opzione_ID'];

            if ($confronto === 9) {
                if (($opzione_valore_ID === $opzione_ID) && $esito === 0) {
                    return 0;
                } elseif (($opzione_valore_ID === $opzione_ID) && $esito === 1){

                    $origineVisibile = 1;
                }
            } else {
                die ("Confronto non accettato");
            }
        }

        return $origineVisibile;
    }


    function layoutCreaSottoStep(int $documento_ID, int $step_ID, int $sottostep_ID, $linea_ID): string
    {
        global $db;
        global $configuratore;

        $query = 'SELECT * 
                  FROM configuratore_sottostep 
                  WHERE ID = ' . $sottostep_ID . ' 
                  LIMIT 1';

        $result = $db->query($query);

        if (!$db->affected_rows)
            return 'Sottostep di ID ' . $sottostep_ID . ' non trovato';

        $row = mysqli_fetch_assoc($result);

        // Controlla se lo step dipende da una opzione e può essere visualizzato
        $visibile = $this->sottoStepVisibile($documento_ID, $step_ID, $sottostep_ID, $linea_ID);

        // Ottiene l'ID dell'opzione scelta nel corpo
        $query = 'SELECT opzione_ID 
                  FROM documenti_corpo WHERE ID = ' . $linea_ID;

        $risultatoOpzioneScelta = $db->query($query);
        $rowOpzioneScelta       = mysqli_fetch_assoc($risultatoOpzioneScelta);

        $partSelect = '';
        if ( (int) $row['tipo_scelta'] === 0) {

            $query = 'SELECT * 
                      FROM configuratore_opzioni 
                      WHERE sottostep_ID = ' . $sottostep_ID;


            $risultatoOpzioni = $db->query($query);

            $partSelect = '<select aria-progressivo="' . $linea_ID . '" class="form-control"  onchange="cambiaSingolaOpzione(\'' . $linea_ID . '\', $(this).val(), ' . $step_ID . ',' . $sottostep_ID .');" id="sottostep-select-' . $linea_ID . '">
                            <option ' . (is_null($rowOpzioneScelta['opzione_ID']) || (int) $rowOpzioneScelta['opzione_ID'] === 0 ? ' selected ' : ' ') . ' disabled >Seleziona una opzione</option>';

            $countOpzioni = 0;
            while ($rowOpzioni = mysqli_fetch_assoc($risultatoOpzioni)) {
                //Controlla se l'opzione ha un check sulle dimensioni
                if ( (int) $rowOpzioni['check_dimensioni'] === 1) {
                    $checkDimensioni = $configuratore->checkDipendenzaDimensione($documento_ID,1, $sottostep_ID,$rowOpzioni['ID']);

                    if ($checkDimensioni == -1)
                        continue;
                }

                // Controlla se l'opzione ha un check sulle dipendenze
                if ( (int) $rowOpzioni['check_dipendenze'] === 1) {
                    $checkDipendenza = $configuratore->checkOpzioneDipendenzaDaOpzione($documento_ID, $rowOpzioni['ID']);

                    $opzioneVisibile = (int) $rowOpzioni['visibile'];

                    if ($opzioneVisibile === 0 && ($checkDipendenza === -1 || $checkDipendenza === 0)) {
                         echo 'STEP 1. Check dipendenza: ' . $checkDipendenza . ', opzioneVisibile: ' . $opzioneVisibile ;
                    } elseif ($opzioneVisibile === 1 && $checkDipendenza === 0) {
                         echo 'STEP 2. Check dipendenza: ' . $checkDipendenza . ', opzioneVisibile: ' . $opzioneVisibile ;
                    } else {
                        $countOpzioni++;
                        $partSelect .= '<option ' . ((int)$rowOpzioni['ID'] === (int)$rowOpzioneScelta['opzione_ID'] ? ' selected ' : '') . ' 
                                        value="' . $rowOpzioni['ID'] . '">' . $rowOpzioni['opzione_nome'] . ' <!-- [CDM:' . $checkDimensioni . '] -->
                                </option>';
                    }
                } else {
                    $countOpzioni++;
                    $partSelect .= '<option ' . ((int)$rowOpzioni['ID'] === (int)$rowOpzioneScelta['opzione_ID'] ? ' selected ' : '') . ' 
                                        value="' . $rowOpzioni['ID'] . '">' . $rowOpzioni['opzione_nome'] . ' <!-- [CDM:' . $checkDimensioni . '] -->
                                </option>';
                }
            }
            $partSelect .= '</select>';
        }

        if ($countOpzioni === 0)
            $partSelect = 'Attenzione. Nessuna opzione sembra essere valida.';

        $part = '<div class="layoutEditorSottostep" id="editorSottostep-' . $linea_ID.'">
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

    function checkOpzioneDipendenzaDaOpzione (int $documento_ID, int $opzione_valore_ID) : int{
        global $db;

        $query = 'SELECT stato FROM documenti_corpo_opzioni 
                  WHERE documento_ID = ' . $documento_ID .' 
                    AND opzione_ID = ' . $opzione_valore_ID . ' 
                  LIMIT 1';
        $risultato = $db->query($query);

        if (!$db->affected_rows)
            return -1;

        $row = mysqli_fetch_assoc($risultato);

        return (int) $row['stato'];

    }

    function totaleDocumento (int $documento_ID) : float {
        global $db;

        $this->getDimensioni($documento_ID);

        // Ottiene la valorizzazione iniziale che deriva dalla categoria del progetto
        $query = 'SELECT  configuratore_categorie.categoria_formula_valore
                        , configuratore_formule.formula_sigla
                  FROM documenti 
                  LEFT JOIN configuratore_categorie
                    ON configuratore_categorie.ID = documenti.categoria_ID
                  LEFT JOIN configuratore_formule
                    ON configuratore_formule.ID = configuratore_categorie.categoria_formula_ID
                  WHERE documenti.ID = ' . $documento_ID;


        if (!$result = $db->query($query))
            return -1;

        if (!$db->affected_rows)
            return -2;

        $row = mysqli_fetch_assoc($result);

        $totale = 0;
        $valore = $row['categoria_formula_valore'];

        $sigla = strtolower($row['formula_sigla']);
        switch ($sigla) {
            case 'coeff-k':
                /* Aumento in termini di coeff. ad esempio 1.2 = 20% */
                $totale = $totale * $valore;
                break;
            case 'somma-v':
                $totale +=  $valore;
                break;
            case 'somma-kdim':
                $totale += $valore * ( ($this->larghezza * $this->lunghezza) / 1000);
                break;
            case 'coeff-p':
                /* Aumento in percentuale. Ad esempio 20% */
                $totale += ($totale / 100) * $valore;
                break;
            default:
                echo 'Categoria di formula ' . $sigla . ' non trovata. <br/>';
                break;
        }

        // Ottiene i vari elementi di linea
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
            $this->aggiornaTotaleDocumento($documento_ID, $totale);
            return $totale;
        }

        while ($row = mysqli_fetch_assoc( $result )) {

            $sigla = strtolower($row['formula_sigla']);
            $valore = (float) $row['opzione_formula_valore'];

            switch ( $sigla) {
                case 'coeff-k':
                    $totale = $totale * $valore;
                    break;
                case 'somma-v':
                    $totale += $valore;
                    break;
                case 'somma-kdim':
                    $totale += $valore * (($this->larghezza * $this->lunghezza)) / 1000;
                    break;
                case 'coeff-p':
                    $totale += ($totale / 100) * $valore;
                    break;
                default:
                    echo 'Opzione ' . $sigla . ' non trovata. <br/>';
                    break;
            }

            // Aggiorna la linea di corpo con il totale progressivo
            $query = 'UPDATE documenti_corpo 
                      SET importo = ' . $totale . ' 
                      WHERE ID = ' . $row['corpo_ID'] . ' 
                      LIMIT 1';

            $db->query($query);
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

    function documentoCompletato( int $documento_ID) : bool {
        global $db;

        $query = 'SELECT CORPO.ID 
			  FROM documenti_corpo CORPO
			  WHERE (CORPO.valorizzata = 0 AND CORPO.visibile = 1)
				  AND CORPO.documento_ID = ' . $documento_ID;

        $result = $db->query($query);

        if ($db->affected_rows) {
            return false;
        }  else {
            return true;
        }
    }

    function cambiaStatoDocumento( int $documento_ID, int $stato) : void
    {
        global $db;
        global $user;

        $query = 'UPDATE documenti
                  SET stato = ' . $stato . '
                  WHERE ID = ' . $documento_ID . ' 
                    AND user_ID = ' . $user->ID . '
                  LIMIT 1';

        $db->query($query);
    }
}

