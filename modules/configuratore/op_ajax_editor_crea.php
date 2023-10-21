<?php

$this->noTemplateParse = true;
if (!$user->validateLogin())
    return;

if(!$user->validateLogin())
    return;

$customer_ID    = (int) $_POST['cliente'];
$categoria_ID   = (int) $_POST['categoria'];
$lunghezza      = (float) $_POST['lunghezza'];
$larghezza      = (float) $_POST['larghezza'];
$metri_quadri   = $larghezza * $lunghezza;

$configuratore->lunghezza = $lunghezza;
$configuratore->larghezza = $larghezza;

$query = 'INSERT INTO documenti 
          ( user_ID
          , customer_ID
          , tipo_ordine_ID
          , categoria_ID
          , configuratore_versione
          , lunghezza
          , larghezza
          , spessore
          , metri_quadri
          , data_ordine
          )
          VALUES 
         (
            ' . $user->ID . '        
          , ' . $customer_ID . '        
          , ' . 0 . '        
          , ' . $categoria_ID . '        
          , \'FAB001\'        
          , ' . $lunghezza . '        
          , ' . $larghezza . '        
          , ' . 0 . '        
          , ' . $metri_quadri . '        
          , NOW()       
          );';


if (!$db->query($query)) {
    echo '--KO-- Query error. ' . $query;
    return;
}

$documento_ID = $db->insert_id;

/*
 * Inserimento delle categorie. Le categorie sono sempre visualizzate quindi, qualsiasi categoria non visibile non
 * sarà inserita nel documento.
 */
$query = 'SELECT * 
          FROM configuratore_categorie 
          WHERE ID = ' . $categoria_ID . ' 
          AND visibile = 1
          ORDER BY ordine ASC';
if (!$risultatoCategoria = $db->query($query)) {
    echo '--KO-- Errore nella query. ' . $query;
    return;
}

while ($rowCategorie = mysqli_fetch_assoc($risultatoCategoria)) {

    /*
     * Gli step sono sempre visibili, dunque sono inseriti soltanto se nel backend sono settati come tali
     */
    $query = 'SELECT * 
              FROM configuratore_step 
              WHERE categoria_ID = ' . $rowCategorie['ID'] . ' 
                AND visibile = 1
              ORDER BY ordine ASC';

    if (!$resultStep = $db->query($query)) {
        echo '--KO-- Query error' . $query;
        return;
    }

    $primo = 0;
    while ($rowStep = mysqli_fetch_assoc($resultStep)) {
        unset($checkDimensioni);
        /*
         * I sottostep possono essere, da configurazione, invisibili ma possono diventare visibili a seguito di una
         * opzione.
         */
        $query = 'SELECT * 
                  FROM configuratore_sottostep 
                  WHERE step_ID = ' . $rowStep['ID'] . ' 
                  ORDER BY ordine ASC';

        if (!$risultatoSottostep = $db->query($query)) {
            echo '--KO-- Errore nella query' . $query;
            return;
        }

        while ($rowSottostep = mysqli_fetch_assoc($risultatoSottostep)) {

            $origineVisibile = (int) $rowSottostep['visibile'];

            // Controlla le dipendenze dalle dimensioni
            if ( (int) $rowSottostep['check_dimensioni'] === 1) {
                $checkDimensioni = $configuratore->checkDipendenzaDimensione($documento_ID,0, $rowSottostep['ID']);

                switch ($checkDimensioni) {
                    case -1:
                        continue 2;
                    case 1:
                        $rowSottostep['visibile'] = 1;
                        continue;
                }

            }

            /*
             * In base al valore della variabile $checkDimensioni viene settata la visibilità del sottostep
             */

            // Controlla se è il primo sottostep visibile.
            if (!$primo && (int) $rowSottostep['visibile'] === 1) {
                $primoStep = 1;
                $primo = 1;
            } else {
                $primoStep = 0;
                $rowSottostep['visibile'] = 0;
            }

            // $primoStep = ($primo) ? 1 : 0;
            // $primo = false;

            $query = 'INSERT INTO documenti_corpo 
                  ( documento_ID
                  , user_ID
                  , categoria_ID
                  , step_ID
                  , sottostep_ID
                  , sigla
                  , opzione_ID
                  , formula_ID
                  , formula_valore
                  , importo
                  , qta
                  , primo_step
                  , esclusa
                  , origine_visibile
                  , visibile
                  )
                  VALUES (
                  ' . $documento_ID .'      
                  , ' . $user->ID .'      
                  , ' . $categoria_ID .'      
                  , ' . $rowStep['ID'] .'      
                  , ' . $rowSottostep['ID'] .'      
                  , \'' . $rowSottostep['sottostep_sigla'] . '\'      
                  , 0      
                  , 0      
                  , 0      
                  , 0      
                  , 0
                  , ' . $primoStep . '
                  , ' . ( 0 ) . ' 
                  , ' . ( $origineVisibile ) . ' 
                  , ' . ( (int) $rowSottostep['visibile'] ) . ' 
                  );';

            if (!$db->query($query)) {
                echo '--KO-- Errore nella query';
                return;
            }

            $corpoLinea_ID = $db->insert_id;

            /*
             * Seleziona tutte le opzioni di corpo che hanno un controllo sulle dipendenze
             */
            $query = 'SELECT * 
                      FROM configuratore_opzioni 
                      WHERE sottostep_ID = ' . $rowSottostep['ID']  . '
                      AND check_dipendenze = 1';

            if (!$resultOpzioni = $db->query($query)) {
                echo 'Errore nella query' . $query;
                return;
            }

            while ($rowOpzioni = mysqli_fetch_assoc($resultOpzioni)) {
                $query = 'INSERT INTO documenti_corpo_opzioni 
                          (
                            documento_ID
                          , categoria_ID
                          , step_ID
                          , sottostep_ID
                          , opzione_ID 
                          , corpo_ID
                          , stato
                          )
                          VALUES 
                          (
                            ' . $documento_ID . ' 
                          , ' . $categoria_ID . ' 
                          , ' . $rowStep['ID'] . ' 
                          , ' . $rowSottostep['ID'] . ' 
                          , ' . $rowOpzioni['ID'] . ' 
                          , ' . $corpoLinea_ID . ' 
                          , ' . $rowOpzioni['visibile'] . ' 
                          )    
                          ';

                $db->query($query);
            }

        }

    }
}

echo $documento_ID;