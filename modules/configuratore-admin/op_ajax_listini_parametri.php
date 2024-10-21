<?php
if (!$core)
    die ('Accesso diretto');


if (!$user->logged) {
    echo 'Devi essere loggato';
    return;
}
$this->noTemplateParse = true;

$tipo       =   (int) $_POST['tipo'];
$IDX        =   (int) $_POST['IDX'];
$listino_ID =   (int) $_POST['listino_ID'];
$valore     =   (float) $_POST['valore'];

$query = 'SELECT * 
          FROM listini_parametri 
          WHERE tipo        = ' . $tipo . ' 
            AND IDX         = ' . $IDX . ' 
            AND listino_ID  = ' . $listino_ID . ' 
          LIMIT 1';

$db->query($query);

if ($db->affected_rows) {
    $query = 'UPDATE listini_parametri 
                SET valore = ' . $valore . ' 
              WHERE tipo = ' . $tipo . ' 
              AND IDX = ' . $IDX  . ' 
              AND listino_ID = ' . $listino_ID  . ' 
              LIMIT 1';

    $db->query($query);
} else {

    $query = 'INSERT INTO listini_parametri 
                ( 
                  tipo
                , IDX
                , listino_ID
                , valore
                )
              VALUES 
                (
                ' . $tipo . '
                , '. $IDX . '
                , ' . $listino_ID . '
                , ' . $valore . '
                );';

    $db->query($query);
}