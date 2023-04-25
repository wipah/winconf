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
$ID           = (int) $_POST['ID'];

echo '<h2>Editor check dimensioni</h2>';

$query = "SELECT  configuratore_opzioni_check_dimensioni.ID dimensione_ID,
                  configuratore_opzioni_check_dimensioni.*
                , configuratore_opzioni.opzione_nome
          FROM configuratore_opzioni_check_dimensioni
          LEFT JOIN configuratore_opzioni
            ON configuratore_opzioni_check_dimensioni.opzione_ID = configuratore_opzioni.ID
          WHERE configuratore_opzioni_check_dimensioni.sottostep_ID = $sottostep_ID;";

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
                <th>Confronto</th>
                <th>Valore</th>
                <th>Esito</th>
                <th>Operazioni</th>
            </tr>
        </thead>
        <tbody>';



    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
            <td>' . $row['dipendenza_ID'] . '</td>
            <td>' . $row['opzione_nome']  . '</td>
            <td>' . $arrayConfronti[ (int) $row['confronto']]  . '</td>
            <td>' . $row['valore']  . '</td>
            <td>' . ( (int) $row['esito'] === 0 ? 'Escludi' : 'Includi' )  . '</td>
            <td>
                <span class="spanClickable" onclick="dimensioniEditor(' . $categoria_ID . ',' . $step_ID . ', ' . $sottostep_ID . ', ' . $row['ID'] .')">Modifica</span> | 
                Elimina
            </td>
          </tr>';
    }
    echo '</tbody>
</table>';
}

echo '<span class="btn btn-info btn-default" onclick="dimensioniEditor('. $categoria_ID .', ' . $step_ID . ',' . $sottostep_ID . ', 0)">Nuovo check dimensioni</span>';
