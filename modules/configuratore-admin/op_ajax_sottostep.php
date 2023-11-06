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
            <th>Tipo scelta</th>
            <th>Check dipendenze</th>
            <th>Check dimensioni</th>
            <th>Visibile</th>
            <th>Operazioni</th>
            <th>Ordine</th>
        </tr>
    </thead>
    <tbody>';

    while ($row = mysqli_fetch_assoc($result)) {

        switch ((int) $row['tipo_scelta']) {
            case 0:
                $tipoScelta = 'Scelta singola';
                break;
            case 1:
                $tipoScelta = 'Scelta multipla';
                 break;
            case 2:
                $tipoScelta = 'Testo libero';
                break;
            default:
                $tipoScelta = 'ERRORE';
        }

        echo '
                <tr id="sottostep-' . $row['ID'] . '" data-sort-id="' . $row['ID'] . '">
                    <td>' . $row['ID'] . '</td>
                    <td>' . $row['sottostep_nome'] . '</td>
                    <td>' . $row['sottostep_sigla']  .'</td>
                    <td>' . $tipoScelta  .'</td>
                    <td>' . ( (int) $row['check_dipendenze'] === 1 ? '<i style="color:green" class="gg-check icon"></i>' : '<i style="color:red" class="gg-check icon"></i>') . '
                        
                        <span onclick="mostraDipendenzeSottostep(' . $categoria_ID .', ' . $step_ID . ',' . $row['ID'] .')" 
                        class="gg-extension icon-link"></span>
                    </td>
                    <td>' . ( (int) $row['check_dimensioni'] === 1 ? '<i style="color:green" class="gg-check icon-link"></i>' : '<i style="color:red" class="icon-link gg-check"></i>') . '
                        
                        <span onclick="mostraDimensioniSottostep(' . $categoria_ID .', ' . $step_ID . ',' . $row['ID'] .')" 
                              class="gg-arrows-shrink-v icon-link">
                        </span>
                    </td>
                    <td>' . ( (int) $row['visibile'] === 1 ? '<i style="color:green" class="gg-check-r"></i>' : '<i style="color:red" class="gg-check-r"></i>' ) . '</td>
                    <td>
                        <span class="spanClickable" onclick="sottoStepEditor(' . $categoria_ID .', ' . $step_ID . ',' . $row['ID'] .');">Modifica sottostep</span> | 
                        <span class="spanClickable" onclick="mostraOpzioni(' . $categoria_ID . ', ' . $step_ID . ', ' . $row['ID'] . ');">Editor opzioni</span>
                        | <span class="spanClickable" onclick="sottostepElimina(' . $row['ID'] . ');">Elimina sottostep</a>
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

<div class="clearfix">
    <span class="btn btn-info float-right" onclick="sottoStepEditor(' . $categoria_ID .', ' . $step_ID . ',0);">Aggiungi sottostep</span>
</div>';
