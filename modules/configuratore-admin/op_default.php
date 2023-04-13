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
    echo '
    <a href="' . $conf['URI'] . 'configuratore-admin/editor/" class="btn btn-info float-right">Crea nuova categoria</a>
    <table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Sigla</th>
            <th>Nome</th>
            <th>Step</th>
            <th>Operazioni</th>
        </tr>
    </thead>
    <tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
                <td>' . $row['ID'] . '</td>
                <td>' . $row['categoria_sigla'] . '</td>
                <td>' . $row['categoria_nome'] . '</td>
                <td>???</td>
                <td>
                    <a href="' . $conf['URI'] . 'configuratore-admin/categorie/editor/?ID=' . $row['ID'] . '">Modifica</a>
                </td>
              </tr>';
    }

    echo '
    </tbody>
    </table>';
}
