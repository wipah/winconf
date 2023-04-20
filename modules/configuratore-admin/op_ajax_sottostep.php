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

echo '
<div class="row" style="border-bottom: 1px solid #444">
    <div class="col-md-4">Nome</div>
    <div class="col-md-4">Sigla</div>
    <div class="col-md-4">Operazioni</div>
</div>';
if (!$db->affected_rows) {
    echo 'Nessuno step <br/>';
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '
                <div class="row">
                    <div class="col-md-4"><strong><span>' . $row['sottostep_nome'] . '</span></strong></div>
                    <div class="col-md-4">' . $row['sigla']  .'</div>
                    <div class="col-md-4"><span onclick="sottoStepEditor(' . $row['ID'] .');">Modifica sottostep</span> | <span onclick="mostraOpzioni(' . $row['ID'] . ');">Editor opzioni</a></div>
                </div>';

    }
}

echo '<span class="btn btn-info" onclick="sottoStepEditor(0);">Aggiungi sottostep</span>';