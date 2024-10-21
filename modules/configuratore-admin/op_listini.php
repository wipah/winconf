<?php

if (!$core)
    die ('Direct access');

if (!$user->logged) {
    echo 'Devi aver effettuato il login';
    return;
}


switch ($path[3]) {
    case 'ajax-visualizza-aree':
        require_once 'op_ajax_listini_aree.php';
        return;
    case 'ajax-visualizza-categorie':
        require_once 'op_ajax_listini_categorie.php';
        return;
    case 'ajax-listini-parametri':
        require_once 'op_ajax_listini_parametri.php';
        return;
    case 'editor':
        require_once 'op_listini_editor.php';
        return;
}
$this->menuItems[] = '<a href="' . $conf['URI'] . 'configuratore-admin/">Backend</a>';
$this->menuItems[] = '<a href="' . $conf['URI'] . 'configuratore-admin/listini/">listini</a>';

$this->title = 'WinConf - Editor di categoria';
echo '<h1>Gestione listini</h1>';


$query = 'SELECT * FROM listini';

if (!$db->query($query)) {
    echo 'Query error. ' . $query;
    return;
}

if (!$result = $db->query($query)) {
    echo 'Nessun listino creato. Creane uno!';
    return;
}

echo '<table class="table table-bordered table-condensed winconf-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Operazioni</th>
            </tr>
        </thead>
        <tbody>';

while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '    <td>' . $row['ID'] . '</td>';
    echo '    <td>' . $row['nome'] . '</td>';
    echo '    <td>  <span onclick="parametri('.  $row['ID'] .')">Parametri</span>
                    <a href="' . $conf['URI'] .'/configuratore-admin/listini/editor/?ID=' . $row['ID'] .'">Editor</td>';
    echo '</tr>';
}

echo '</tbody>
</table>
<div id="listiniAree">
    
</div>
<div class="mt-3" id="listiniSottoAree"></div>
<script>
function parametri(ID) {
    
    $.post( jsPath + "configuratore-admin/listini/ajax-visualizza-aree", { ID: ID })
      .done(function( data ) {
        $("#listiniSottoAree").html("");
        $("#listiniAree").html(data);
      });
}

function listiniCategorie(ID) {
    
    $.post( jsPath + "configuratore-admin/listini/ajax-visualizza-categorie", { ID: ID })
      .done(function( data ) {
        $("#listiniSottoAree").html(data);
      });
    
}

function listiniAggiornaParametro(tipo, IDX, listino_ID, valore) {
        $.post( jsPath + "configuratore-admin/listini/ajax-listini-parametri", { tipo: tipo, listino_ID: listino_ID, IDX: IDX, valore: valore })
      .done(function( data ) {
        //
      });
}
</script>
';