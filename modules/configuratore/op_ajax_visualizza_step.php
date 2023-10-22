<?php

$this->noTemplateParse = true;

if(!$user->validateLogin())
    return;

if (!isset($_POST['step_ID'])) {
    echo '--KO-- Manca l\'ID dello step';
    return;
}

$documento_ID   = (int) $_POST['documento_ID'];
$step_ID        = (int) $_POST['step_ID'];

$query = 'SELECT  *
                , CORPO.ID linea_ID
                , STEP.step_nome
          FROM documenti_corpo CORPO
          LEFT JOIN configuratore_step STEP 
            ON STEP.ID = CORPO.step_ID
          WHERE CORPO.step_ID = ' . $step_ID . '
          AND CORPO.documento_ID = ' . $documento_ID . '
          AND ( CORPO.primo_step = 1 OR CORPO.visibile = true)
          ORDER BY CORPO.ID ASC';

$risultato = $db->query($query);

if (!$db->affected_rows) {
    echo 'Nessuna opzione disponibile per lo step';
    return;
}

echo '<!-- <h2>Step: ' . $row['step_nome'] .'</h2>-->';
while ($row = mysqli_fetch_assoc($risultato)) {
    echo $configuratore->layoutCreaSottoStep($documento_ID, $step_ID, $row['sottostep_ID'], $row['linea_ID']);
}