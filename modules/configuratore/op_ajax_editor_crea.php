<?php

$this->noTemplateParse = true;
if(!$user->validateLogin())
    return;

$customer_ID    = (int) $_POST['cliente'];
$categoria_ID   = (int) $_POST['categoria'];
$lunghezza      = (float) $_POST['lunghezza'];
$larghezza      = (float) $_POST['larghezza'];
$metri_quadri   = $larghezza * $lunghezza;

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
    $query = 'SELECT * 
              FROM configuratore_step 
              WHERE categoria_ID = ' . $rowCategorie['ID'] . ' 
                AND visibile = 1
              ORDER BY ordine ASC';

    if (!$resultStep = $db->query($query)) {
        echo '--KO-- Query error' . $query;
        return;
    }

    $primo = true;
    while ($rowStep = mysqli_fetch_assoc($resultStep)) {

        $query = 'SELECT * 
                  FROM configuratore_sottostep 
                  WHERE step_ID = ' . $rowStep['ID'] . ' 
                  AND visibile = 1 
                  ORDER BY ordine ASC';

        if (!$risultatoSottostep = $db->query($query)) {
            echo '--KO-- Errore nella query' . $query;
            return;
        }

        while ($rowSottostep = mysqli_fetch_assoc($risultatoSottostep)) {

            // Controlla se è il primo sottostep visibile.
            if ($primo) {
                $primoStep = 1;
                $primo = false;
            } else {
                $primoStep = 0;
            }

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
                  );';
            if (!$db->query($query)) {
                echo '--KO-- Errore nella query';
                return;
            }
        }

    }
}

echo $documento_ID;