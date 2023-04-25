<?php
if (!$core)
    die("Accesso diretto rilevato");

if (!$user->logged) {
    echo 'Devi aver effettuato il login';
    return;
}

$this->menuItems[] = '<a href="' . $conf['URI'] . 'configuratore-admin/">Backend</a>';

echo '<h1>Configurazione</h1>';

$query = 'SELECT * FROM configuratore_categorie ORDER BY ordine ASC';

if (!$result = $db->query($query)) {
    echo 'Query error.' . $query;
    return;
}

echo '<a href="' . $conf['URI'] . 'configuratore-admin/editor/" class="btn btn-info float-right">Crea nuova categoria</a>';

if (!$db->affected_rows) {
    echo 'Nessun dato trovato.';
} else {
    echo '
    <h2>Categorie / Step</h2>
    <table id="defaultSortable" class="table table-bordered table-condensed">
    <thead>
        <tr>
         
            <th>ID</th>
            <th>Nome</th>
            <th>Sigla</th>
            <th>Step</th>
            <th>Visibile</th>
            <th>Operazioni</th>
            <th>Ordine</th>
        </tr>
    </thead>
    <tbody id="tb">';

    while ($row = mysqli_fetch_assoc($result)) {

        $queryStep = 'SELECT * 
                      FROM configuratore_step 
                      WHERE categoria_ID = ' . $row['ID'] . '
                        AND visibile = 1 
                      ORDER BY ordine ASC';

        $step = '';

        if (!$resultStep = $db->query($queryStep)) {
            $step .= 'Errore nella query.';
        } else {

            if (!$db->affected_rows) {
                $step = $this->getBox("info", "Nessuno step inserito.");
            } else {

                $step = '<table id="tabellaStep-' . $row['ID'] . '" class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Visibile</th>
                                <th>Operazioni</th>
                                <th>Ordine</th>
                            </tr>
                        </thead>
                        <tbody>';

                while ($rowSteps = mysqli_fetch_assoc($resultStep)) {

                    $step .= '<tr data-sort-id="' . $rowSteps['ID'] . '">
                            <td>'  . $rowSteps['ID'] .'</td>
                            <td>'  . $rowSteps['step_nome'] .'</td>
                            <td>'  . ( (int) $rowSteps['visibile'] === 1 ? 'Si' : 'No') .'</td>
                            <td>
                                <a href="' . $conf['URI']  .'configuratore-admin/step/editor/?ID=' . $rowSteps['ID'] . '">Modifica lo step</a> - 
                                <a href="' . $conf['URI'] . 'configuratore-admin/sottostep/editor/?step_ID=' . $rowSteps['ID']  . '">Editor sottostep</a>
                            </td>
                            <td align="center"><i class="fa fa-fw fa-arrows-alt"></i></td>
                          </tr>';
                }

                $step .=  '</tbody>
            </table>';

            $jsSortable .= '    
            <script>
            
            $(\'#tabellaStep-' . $row['ID'] . ' tbody\').sortable({
                handle: \'i.fa-arrows-alt\',
                placeholder: "ui-state-highlight",
                opacity: 0.5,
                update : function () {
                    order =  $(\'#tabellaStep-' . $row['ID'] . ' tbody\').sortable(\'toArray\', { attribute: \'data-sort-id\'}); 
                    console.log(order.join(\',\'));
                    sortOrder = order.join(\',\');
                    $.post( jsPath + \'configuratore-admin/ajax-riordina-step/?step_ID=' . $row['ID'] . '\',
                        {\'action\':\'updateSortedRows\',\'sortOrder\':sortOrder},
                        function(data){
                        }
                    );
                } 
            });
          
            </script>';

            }

        }

        echo '<tr data-sort-id="' . $row['ID'] . '">
                <td>' . $row['ID'] . '</td>
                <td>' . $row['categoria_nome'] . '</td>
                <td>' . $row['categoria_sigla'] . '</td>
                
                <td>' . $step . ' <div class="configuratoreTabellaStep"><a href="' . $conf['URI'] . 'configuratore-admin/step/editor/?categoria_ID=' . $row['ID'] . '">Aggiungi step</a></div> </td>
                <td>' . ( (int) $row['visibile'] === 1 ? 'Si' :  'No') .'</td>
                <td>
                    <a href="' . $conf['URI'] . 'configuratore-admin/categorie/editor/?ID=' . $row['ID'] . '">Modifica</a> |
                    <span onclick="if( confirm(\'Vuoi eliminare la categoria? Questa operazione non è annullabile\')){ location.href = jsPath + \'configuratore-admin/elimina-categoria/?ID=' . $row['ID'] . '\'  } " class="spanClickable">Elimina</span>
                </td>
                <td align="center"><i class="fa fa-fw fa-arrows-alt"></i></td>
              </tr>';
    }

    echo '
    </tbody>
    </table>
    
 <script>
 $(\'#defaultSortable tbody\').sortable({
    handle: \'i.fa-arrows-alt\',
    placeholder: "ui-state-highlight",
    opacity: 0.9,
    update : function () {
        order =  $(\'#defaultSortable tbody\').sortable(\'toArray\', { attribute: \'data-sort-id\'}); 
        console.log(order.join(\',\'));
        sortOrder = order.join(\',\');
        $.post( jsPath + \'configuratore-admin/ajax-riordina-categorie/\',
            {\'action\':\'updateSortedRows\',\'sortOrder\':sortOrder},
            function(data){

            }
        );
    } 
});
</script>
    ';

    echo $jsSortable;
}