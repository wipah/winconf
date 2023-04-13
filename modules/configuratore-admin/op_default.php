<?php
if (!$core)
    die("Accesso diretto rilevato");
echo '<h1>Configurazione</h1>';

$query = 'SELECT * FROM configuratore_categorie';

if (!$result = $db->query($query)) {
    echo 'Query error.' . $query;
    return;
}

if (!$db->affected_rows) {
    echo 'Nessun dato trovato.';
} else {
    echo '<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Sigla</th>
            <th>Nome</th>
            <th>Step</th>
            <th>Operazioni<th>
        </tr>
    </thead>
    <tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<td>' . $row['ID'] . '</td>';
        echo '<td>' . $row['categoria_sigla'] . '</td>';
        echo '<td>' . $row['categoria_nome'] . '</td>';
        echo '<td>???</td>';
        echo '<td>' . $row['categoria_descrizione'] . '</td>';
        echo '<td><a href="' . $conf['URI'] . 'configuratore-admin/editor/?ID=' . $row['ID'] . '">Modifica</a></td>';
    }

    echo '
    </tbody>
    </table>';
}
