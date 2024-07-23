<?php

/*
 * Visualizza le dipendenze per i sottostep
*/
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
$ID           = (int) $_POST['ID'];

$query = 'SELECT categoria_nome, 
                 configuratore_step.step_nome,
                 configuratore_sottostep.sottostep_nome
          FROM configuratore_categorie 
          LEFT JOIN configuratore_step 
            ON configuratore_step.categoria_ID = configuratore_categorie.ID
          LEFT JOIN configuratore_sottostep
            ON configuratore_sottostep.step_ID = configuratore_step.ID
          WHERE configuratore_sottostep.ID = ' . $sottostep_ID . '
            ';
$rowTitolo = $dbHelper->getSingleRow($query);

echo '<h2>' . $rowTitolo['categoria_nome'] . ' > ' . $rowTitolo['step_nome'] .' > ' . $rowTitolo['sottostep_nome']  . '  > Editor dipendenze [SOTTOSTEP]</h2>';

$query = "SELECT  configuratore_opzioni_check_dipendenze.ID dipendenza_ID,
                  configuratore_opzioni_check_dipendenze.*
                , configuratore_opzioni.opzione_nome
          FROM configuratore_opzioni_check_dipendenze
          LEFT JOIN configuratore_opzioni
            ON configuratore_opzioni_check_dipendenze.opzione_valore_ID = configuratore_opzioni.ID
          WHERE configuratore_opzioni_check_dipendenze.sottostep_ID = $sottostep_ID
            AND (configuratore_opzioni_check_dipendenze.opzione_ID = 0 OR configuratore_opzioni_check_dipendenze.opzione_ID = NULL);";

if (!$result = $db->query($query)) {
    echo 'Query error.' . $query;
    return;
}

if (!$db->affected_rows) {
    echo $this->getBox('info', 'Nessuna dipendenza trovata.');
} else {
    echo '<table class="table table-bordered table-condensed winconf-table-secondary">
        <thead>
            <tr>
                <th>ID</th>
                <th>Opzione</th>
                <th>Confronto</th>
                <th>Valore</th>
                <th>Esito</th>
                <th>Operazioni</th>
            </tr>
        </thead>
        <tbody>';



    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr id="dipendenza-' . $row['ID'] . '">
            <td>' . $row['dipendenza_ID'] . '</td>
            <td>' . $row['opzione_nome']  . '</td>
            <td>' . $arrayConfronti[ (int) $row['confronto']]  . '</td>
            <td>' . $row['valore']  . '</td>
            <td>' . ( (int) $row['esito'] === 0 ? 'Escludi' : 'Includi' )  . '</td>
            <td>
                <span class="spanClickable" onclick="dipendenzeEditor(' . $categoria_ID . ',' . $step_ID . ', ' . $sottostep_ID . ', 0, ' . $row['ID'] .')">Modifica</span> | 
                <span class="spanClickable" onclick="dipendenzeElimina(' . $row['ID'] . ')">Elimina</span>
            </td>
          </tr>';
    }
    echo '</tbody></table>';
}

echo '
<div class="clearfix">
    <span class="btn btn-info btn-default float-right" onclick="dipendenzeEditor('. $categoria_ID .', ' . $step_ID . ',' . $sottostep_ID . ', 0, 0)">Nuova dipendenza</span>
</div>';