<?php

if (!$core)
    die ("Direct access");

if (!$user->logged) {
    echo 'Devi aver effettuato il login';
    return;
}

$this->noTemplateParse = true;
$categoria_ID = (int) $_POST['categoria_ID'];
$step_ID      = (int) $_POST['step_ID'];
$sottostep_ID = (int) $_POST['sottostep_ID'];

$query = 'SELECT categoria_nome, 
                 configuratore_step.step_nome,
                 configuratore_sottostep.sottostep_nome
          FROM configuratore_categorie 
          LEFT JOIN configuratore_step 
            ON configuratore_step.categoria_ID = configuratore_categorie.ID
          LEFT JOIN configuratore_sottostep
            ON configuratore_sottostep.step_ID = configuratore_step.ID
          WHERE configuratore_sottostep.ID = ' . $sottostep_ID .'
          LIMIT 1;';

$rowTitolo = $dbHelper->getSingleRow($query);
echo '<h2>' . $rowTitolo['categoria_nome'] . ' > ' . $rowTitolo['step_nome'] .' > ' . $rowTitolo['sottostep_nome'] . '  > Check dimensioni [sottostep]</h2>';

$query = "SELECT    configuratore_opzioni_check_dimensioni.ID dimensione_ID
                ,   configuratore_opzioni_check_dimensioni.*
          FROM configuratore_opzioni_check_dimensioni
          WHERE opzione_ID = 0 AND 
                configuratore_opzioni_check_dimensioni.sottostep_ID     =   $sottostep_ID
                 ";

if (!$result = $db->query($query)) {
    echo 'Query error.' . $query;
    return;
}

if (!$db->affected_rows) {
    echo $this->getBox('info','Nessun check dimensioni trovato per il sottostep <strong>' . $rowTitolo['sottostep_nome'] . '</strong>');
} else {
    echo '<table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Confronto</th>
                    <th>Valore</th>
                    <th>Esito</th>
                    <th>Operazioni</th>
                </tr>
            </thead>
            <tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr id="dimensione-' . $row['ID'] . '">
            <td>' . $row['dimensione_ID'] . '</td>
            <td>' . $arrayConfronti[ (int) $row['confronto']]  . '</td>
            <td>' . $row['valore']  . '</td>
            <td>' . ( (int) $row['esito'] === 0 ? 'Escludi' : 'Includi' )  . '</td>
            <td>
                <span class="spanClickable" onclick="dimensioniEditor(' . $categoria_ID . ',' . $step_ID . ', ' . $sottostep_ID . ', 0,  ' . $row['ID'] .')">Modifica</span> | 
                <span class="spanClickable" onclick="if(confirm(\'Vuoi eliminare il ckeck sulla dimensione?\')) { dimensioniElimina(' . $row['ID'] .') }">Elimina</span>
            </td>
          </tr>';
    }
    echo '</tbody>
</table>';
}

echo '<span class="btn btn-info btn-default" onclick="dimensioniEditor('. $categoria_ID .', ' . $step_ID . ',' . $sottostep_ID . ', 0)">Nuovo check dimensioni</span>';
