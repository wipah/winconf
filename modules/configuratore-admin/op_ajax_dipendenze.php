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
$ID           = (int) $_POST['ID']; /* Eventuale ID della dipendenza in tabella */


/*
 * Cerca le descrizioni delle categorie, degli step, dei sottostep e delle opzioni
 */
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
          WHERE configuratore_opzioni.ID = ' . $opzione_ID . '
            ';
$rowTitolo = $dbHelper->getSingleRow($query);

echo '<h2>' . $rowTitolo['categoria_nome'] . ' > ' . $rowTitolo['step_nome'] .' > ' . $rowTitolo['sottostep_nome'] . ' > ' . $rowTitolo['opzione_nome'] . '  > Editor dipendenze</h2>';

$query = "SELECT  configuratore_opzioni_check_dipendenze.ID dipendenza_ID,
                  configuratore_opzioni_check_dipendenze.*
                , configuratore_opzioni.opzione_nome
          FROM configuratore_opzioni_check_dipendenze
          LEFT JOIN configuratore_opzioni
            ON configuratore_opzioni_check_dipendenze.opzione_valore_ID = configuratore_opzioni.ID
          WHERE configuratore_opzioni_check_dipendenze.sottostep_ID = $sottostep_ID;";

if (!$result = $db->query($query)) {
    echo 'Query error.' . $query;
    return;
}

if (!$db->affected_rows) {
    echo '<div>Nessuna dipendenza trovata.</div>';
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



    /*
     * La funzione dipendenzeEditor dovrebbe ricevere i seguenti parametri:
     *  $categoria_ID
     *  $step_ID
     *  $sottostep_ID
     *  $opzione_ID : l'ID dell'opzione della tabella configuratore_opzioni
     *  $opzione_check_ID : l'ID dell'opzione della tabella configuratore_opzioni_check_dipendenze
     *  $opzione_valore_ID : l'ID dell'opzione che triggera il controllo
     */
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
            <td>' . $row['dipendenza_ID'] . '</td>
            <td>' . $row['opzione_nome']  . '</td>
            <td>' . $arrayConfronti[ (int) $row['confronto']]  . '</td>
            <!-- <td>' . $row['valore']  . '</td> -->
            <td>' . ( (int) $row['esito'] === 0 ? 'Escludi' : 'Includi' )  . '</td>
            <td>
                <span class="spanClickable" onclick="dipendenzeEditor(' . $categoria_ID . ',' . $step_ID . ', ' . $sottostep_ID . ', ' . $row['ID'] . ', ' . $row['opzione_valore_ID'] . ')">Modifica</span> | 
                Elimina
            </td>
          </tr>';
    }
    echo '</tbody></table>';
}

echo '<span class="btn btn-info btn-default" onclick="dipendenzeEditor('. $categoria_ID .', ' . $step_ID . ',' . $sottostep_ID . ', '. $opzione_ID . ')">Nuova dipendenza</span>';