<?php
if (!$core)
    die ("Accesso diretto");

if (!$user->logged) {
    echo 'Devi aver effettuato il login';
    return;
}

$this->noTemplateParse = true;

if (!$step_ID = (int)  $_POST['step_ID']) {
    echo 'Manca lo step ID';
}

$query = 'SELECT configuratore_categorie.ID 
          FROM configuratore_categorie
          LEFT JOIN configuratore_step 
              ON configuratore_step.categoria_ID = configuratore_categorie.ID
          WHERE configuratore_step.ID = ' . $step_ID;

if (!$result = $db->query($query)) {
    echo 'Query error.' . $query;
    return;
}

if (!$db->affected_rows) {
    echo 'Step non trovato. ';
    return;
}

$row = mysqli_fetch_assoc($result);

$categoria_ID = (int) $row['ID'];

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

    echo '
<table class="table table-bordered table-condensed">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Sigla</th>
            <th>Operazioni</th>
        </tr>
    </thead>
    <tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '
                <tr>
                    <td>' . $row['sottostep_nome'] . '</td>
                    <td>' . $row['sigla']  .'</td>
                    <td>
                        <span class="spanClickable" onclick="sottoStepEditor(' . $categoria_ID .', ' . $step_ID . ',' . $row['ID'] .');">Modifica sottostep</span> | <span class="spanClickable" onclick="mostraOpzioni(' . $categoria_ID . ', ' . $step_ID . ', ' . $row['ID'] . ');">Editor opzioni</a>
                    </td>
                </tr>';
    }

    echo '</tbody>
    </table>';
}

echo '<span class="btn btn-info" onclick="sottoStepEditor(' . $categoria_ID .', ' . $step_ID . ',0);">Aggiungi sottostep</span>';