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

$query = 'SELECT * 
          FROM documenti_corpo 
          WHERE step_ID = ' . $step_ID . '
          AND documento_ID = ' . $documento_ID . '
          AND ( primo_step = 1 OR visibile = true)
          ORDER BY ID ASC';


$risultato = $db->query($query);

if (!$db->affected_rows) {
    echo 'Nessuna opzione disponibile per lo step';
    return;
}

echo '<h2>Step: ' . $step_ID .'</h2>';
while ($row = mysqli_fetch_assoc($risultato)) {
    echo $configuratore->layoutCreaSottoStep($documento_ID, $step_ID, $row['sottostep_ID'], $row['ID']);
}