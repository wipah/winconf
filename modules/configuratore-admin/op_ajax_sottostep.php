<?php
if (!$core)
    die ("Accesso diretto");

$this->noTemplateParse = true;

if (!$step_ID = (int)  $_POST['step_ID']) {
    echo 'Manca lo step ID';
}

$query = 'SELECT * 
          FROM configuratore_sottostep 
          WHERE step_ID = ' . $step_ID . ' 
          
          ;' ;

if (!$result = $db->query($query)) {
    echo 'Query error';
    return;
}

if (!$db->affected_rows) {
    echo 'Nessuno step <br/>';
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '[' . $row['sottostep_nome'] . '] - <span onclick="mostraOpzioni(' . $row['ID'] . ');">Editor opzioni</a><br/>';
    }
}

echo '<span class="btn btn-info" onclick="sottoStepEditor(0);">Aggiungi sottostep</span>';