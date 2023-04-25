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
          ORDER BY ordine ASC;' ;

if (!$result = $db->query($query)) {
    echo 'Query error';
    return;
}

if (!$db->affected_rows) {
    echo 'Nessuno step <br/>';
} else {

    echo '
<table id="sottostepSortable" class="table table-bordered table-condensed">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Sigla</th>
            <th>Check dipendenze</th>
            <th>Operazioni</th>
            <th>Ordine</th>
        </tr>
    </thead>
    <tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '
                <tr data-sort-id="' . $row['ID'] . '">
                    <td>' . $row['ID'] . '</td>
                    <td>' . $row['sottostep_nome'] . '</td>
                    <td>' . $row['sottostep_sigla']  .'</td>
                    <td>' . ( (int) $row['check_dipendenze'] === 1 ? 'Si' : 'No') . '</td>
                    <td>
                        <span class="spanClickable" onclick="sottoStepEditor(' . $categoria_ID .', ' . $step_ID . ',' . $row['ID'] .');">Modifica sottostep</span> | <span class="spanClickable" onclick="mostraOpzioni(' . $categoria_ID . ', ' . $step_ID . ', ' . $row['ID'] . ');">Editor opzioni</a>
                    </td>
                    <td align="center"><i class="fa fa-fw fa-arrows-alt"></i></td>
                </tr>';
    }

    echo '</tbody>
    </table>';
}

echo '
 <script>
 $(\'#sottostepSortable tbody\').sortable({
    handle: \'i.fa-arrows-alt\',
    placeholder: "ui-state-highlight",
    opacity: 0.9,
    update : function () {
        order =  $(\'#sottostepSortable tbody\').sortable(\'toArray\', { attribute: \'data-sort-id\'}); 
        console.log(order.join(\',\'));
        sortOrder = order.join(\',\');
        $.post( jsPath + \'configuratore-admin/ajax-riordina-sottostep/\',
            {\'action\':\'updateSortedRows\',\'sortOrder\':sortOrder},
            function(data){
                var a   =   data.split(\'|***|\');
                if(a[1]=="update"){
                    $(\'#msg\').html(a[0]);
                }
            }
        );
    } 
});
$( "#sortable" ).disableSelection();
</script>

<span class="btn btn-info" onclick="sottoStepEditor(' . $categoria_ID .', ' . $step_ID . ',0);">Aggiungi sottostep</span>';