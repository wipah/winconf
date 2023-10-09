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
$opzione_ID   = (int) $_POST['opzione_ID'];
$ID           = (int) $_POST['ID'];


$query = 'SELECT categoria_nome, 
                 configuratore_step.step_nome,
                 configuratore_sottostep.sottostep_nome,
                 configuratore_opzioni.opzione_nome
          FROM configuratore_categorie 
          LEFT JOIN configuratore_step 
            ON configuratore_step.categoria_ID = configuratore_categorie.ID
          LEFT JOIN configuratore_sottostep
            ON configuratore_sottostep.step_ID = configuratore_step.ID
          LEFT JOIN configuratore_opzioni
            ON configuratore_opzioni.sottostep_ID = configuratore_sottostep.ID
          WHERE configuratore_opzioni.ID = ' . $opzione_ID .'
          LIMIT 1;';

$rowTitolo = $dbHelper->getSingleRow($query);
echo '<h2>' . $rowTitolo['categoria_nome'] . ' > ' . $rowTitolo['step_nome'] .' > ' . $rowTitolo['sottostep_nome'] . ' > ' . $rowTitolo['opzione_nome'] . '  > Check dimensioni</h2>';

$query = "SELECT    configuratore_opzioni_check_dimensioni.ID dimensione_ID
                ,   configuratore_opzioni_check_dimensioni.*
                ,   configuratore_opzioni.opzione_nome
          FROM configuratore_opzioni_check_dimensioni
          LEFT JOIN configuratore_opzioni
                ON configuratore_opzioni_check_dimensioni.opzione_ID    =   configuratore_opzioni.ID
          WHERE configuratore_opzioni_check_dimensioni.sottostep_ID     =   $sottostep_ID
          AND configuratore_opzioni_check_dimensioni.opzione_ID         =   $opzione_ID";

if (!$result = $db->query($query)) {
    echo 'Query error.' . $query;
    return;
}

if (!$db->affected_rows) {
    echo $this->getBox('info','Nessun check dimensioni trovato');
} else {
    echo '<table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Opzione</th>
                    <th>Dimensione</th>
                    <th>Confronto</th>
                    <th>Valore</th>
                    <th>Esito</th>
                    <th>Operazioni</th>
                </tr>
            </thead>
            <tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
        switch ( (int) $row['dimensione']) {
            case 0:
                $dimensione = 'Larghezza';
                break;
            case 1:
                $dimensione = 'Lunghezza';
                break;
            case 2:
                $dimensione = 'Spessore';
                break;
        }

        echo '<tr id="dimensione-' . $row['ID'] . '">
            <td>' . $row['dimensione_ID'] . '</td>
            <td>' . $row['opzione_nome']  . '</td>
            <td>' . $dimensione . '</td>
            <td>' . $arrayConfronti[ (int) $row['confronto']]  . '</td>
            <td>' . $row['valore']  . '</td>
            <td>' . ( (int) $row['esito'] === 0 ? 'Escludi' : 'Includi' )  . '</td>
            <td>
                <span class="spanClickable" onclick="dimensioniEditor(' . $categoria_ID . ',' . $step_ID . ', ' . $sottostep_ID . ', ' . $opzione_ID . ', ' . $row['ID'] .')">Modifica</span> | 
                <span class="spanClickable" onclick="if(confirm(\'Vuoi eliminare il ckeck sulla dimensione?\')) { dimensioniElimina(' . $row['ID'] .') }">Elimina</span>
            </td>
          </tr>';
    }
    echo '</tbody>
</table>';
}

echo '<span class="btn btn-info btn-default" onclick="dimensioniEditor('. $categoria_ID .', ' . $step_ID . ',' . $sottostep_ID . ',' . $opzione_ID . ', 0)">Nuovo check dimensioni</span>';
