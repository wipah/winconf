<?php
if (!$core)
    die("Accesso diretto non consentito");

if (!$user->logged) {
    echo 'Devi aver effettuato il login';
    return;
}

$this->noTemplateParse = true;

if (!$sottostep_ID = (int) $_POST['sottostep_ID'] ) {
    echo 'Manca l\'ID del sottostep';
    return;
}
$categoria_ID = (int) $_POST['categoria_ID'];
$step_ID = (int) $_POST['step_ID'];

$query = 'SELECT   configuratore_step.step_nome 
                 , configuratore_sottostep.sottostep_nome
          FROM configuratore_step 
          LEFT JOIN configuratore_sottostep
            ON configuratore_sottostep.step_ID = configuratore_step.ID
          WHERE configuratore_sottostep.ID = ' . $sottostep_ID . ' 
          LIMIT 1';

$rowStep = $dbHelper->getSingleRow($query);

$query = 'SELECT OPZIONI.*, 
                 FORMULE.formula_sigla
          FROM configuratore_opzioni OPZIONI
          LEFT JOIN configuratore_formule FORMULE
            ON OPZIONI.opzioni_formula_ID = FORMULE.ID
          WHERE OPZIONI.sottostep_ID = ' . $sottostep_ID . ' 
          ORDER BY OPZIONI.ordine ASC';

if (!$result = $db->query($query)) {
    echo 'Query error.' . $query;
    return;
}


echo '<h2 class="mt-3">' . $rowStep['step_nome'] . ' > '. $rowStep['sottostep_nome'] .' Editor opzioni</h2>';


if (!$db->affected_rows) {
    echo $this->getBox('info','<strong>Nessuna opzione inserita</strong>. Per il sottostep selezionato non sono ancora presenti opzioni');
} else {
    echo '<table id="sottoStepOpzioni" class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Sigla</th>
                    <th>Check dipendenze</th>
                    <th>Check dimensioni</th>
                    <th>Formula</th>
                    <th>Valore formula</th>
                    <th>Visibile</th>
                    <th>Operazioni</th>
                    <th>Ordine</th>
                </tr>
            </thead>
            <tbody>';

    while ($rowOpzioni = mysqli_fetch_assoc($result)) {
        echo '<tr data-sort-id="' . $rowOpzioni['ID'] . '" id="opzione-' . $rowOpzioni['ID'] . '">
                <td>' . $rowOpzioni['ID'] . '</td>
                <td>' . $rowOpzioni['opzione_nome'] . '</td>
                <td>' . $rowOpzioni['opzione_sigla'] . '</td>
                <td>' . ( (int) $rowOpzioni['check_dipendenze'] === 1 ? ' Sì ': ' No ' ). ' <hr />
                    <span class="spanClickable" onclick="mostraDipendenze(' . $categoria_ID . ',' . $step_ID . ', ' . $sottostep_ID . ',' . $rowOpzioni['ID'] . ', 0);">Editor dipendenze</span>
                </td>
                <td>' . ( (int) $rowOpzioni['check_dimensioni'] === 1 ? ' Sì ': ' No ' ). ' <hr /><span class="spanClickable" onclick="mostraDimensioni('   . $categoria_ID . ',' . $step_ID . ', ' . $sottostep_ID . ',' . $rowOpzioni['ID'] . ', 0);">Editor check dimensioni</span></td>
               <td>' . $rowOpzioni['formula_sigla'] . '</td> 
               <td>' . (round($rowOpzioni['opzione_formula_valore'], 3)) . '</td> 
               <td>' . ( (int) $rowOpzioni['visibile'] === 1 ? 'Visibile' : 'Non visibile' ) . '</td>
                <td>
                    <span class="spanClickable" onclick="opzioniEditor(' . $categoria_ID . ',' . $step_ID .', ' . $sottostep_ID . ',' . $rowOpzioni['ID'] . ');">Modifica opzione</span> | 
                    <span class="spanClickable" onclick=" if (confirm(\'Sei sicuro di voler eliminare l\\\'opzione selezionata?\')) { opzioniElimina('. $rowOpzioni['ID'] .') } ">Elimina opzione</span>
                </td>
                <td><i class="fa fa-fw fa-arrows-alt"></i></td>
              </tr>
              ';
    }

    echo '</tbody>
    </table>
    
     <script>
     $(\'#sottoStepOpzioni tbody\').sortable({
        handle: \'i.fa-arrows-alt\',
        placeholder: "ui-state-highlight",
        opacity: 0.9,
        update : function () {
            order =  $(\'#sottoStepOpzioni tbody\').sortable(\'toArray\', { attribute: \'data-sort-id\'}); 
            console.log(order.join(\',\'));
            sortOrder = order.join(\',\');
            $.post( jsPath + \'configuratore-admin/ajax-riordina-sottostep-opzioni/\',
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
    </script>';
}

echo '<span class="btn btn-info" onclick="opzioniEditor(' . $categoria_ID . ',' . $step_ID .', ' . $sottostep_ID . ',0);">Aggiungi opzione</span>';