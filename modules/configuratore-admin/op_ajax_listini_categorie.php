<?php
if (!$core)
    die ('Accesso diretto');


if (!$user->logged) {
    echo 'Devi essere loggato';
    return;
}
$this->noTemplateParse = true;

$ID = (int) $_POST['ID']; //Listino_ID

$query = 'SELECT * FROM LISTINI WHERE ID = ' . $ID . ' LIMIT 1';
$result = $db->query($query);
$row = mysqli_fetch_assoc($result);

echo '<h2>' . $row['nome'] . '</h2>';
$query = 'SELECT 
    CATEGORIA.ID AS categoria_ID,
    CATEGORIA.categoria_nome,
    CATEGORIA.categoria_descrizione,
    PARAMETRI.valore,
    PARAMETRI.listino_ID,
    PARAMETRI.IDX
FROM 
    configuratore_categorie CATEGORIA
LEFT JOIN 
    listini_parametri PARAMETRI
    ON PARAMETRI.tipo = 0
    AND CATEGORIA.ID = PARAMETRI.IDX
    AND PARAMETRI.listino_ID = '. $ID .'
           ;';

if (!$result = $db->query($query)) {
    echo 'Query error '. $query;
    return;
}

echo '<table class="table table-bordered table-striped table-condensed winconf-table">
    <thead>
        <tr>
            <th>Categoria</th>
            <th>Valore</th>
        </tr>
    </thead>
    <tbody>
';

while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr><td>' . $row['categoria_nome'] . '</td>
              <td> <input onchange="listiniAggiornaParametro(0,' . $row['categoria_ID'] . ', ' . $ID .', this.value )" type="number" step="0.01" value="' . $row['valore'] .'"></td>
          </tr>';
}

echo '</tbody></table>';