<?php
if (!$core)
    die("Accesso diretto rilevato");

if (!$user->logged) {
    echo 'Devi aver effettuato il login';
    return;
}

$this->menuItems[] = '<a href="' . $conf['URI'] . 'configuratore-admin/">Backend</a>';

echo '<h1>Configurazione</h1>';

$query = 'SELECT * FROM configuratore_categorie';

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
    <table class="table table-bordered table-condensed">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Sigla</th>
            
            <th>Step</th>
            <th>Operazioni</th>
        </tr>
    </thead>
    <tbody>';

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
            while ($rowSteps = mysqli_fetch_assoc($resultStep)) {
                $step .= '[<a href="' . $conf['URI']  .'configuratore-admin/step/editor/?ID=' . $rowSteps['ID'] . '">'. $rowSteps ['step_nome'] .'</a>] - 
                           <a href="' . $conf['URI'] . 'configuratore-admin/sottostep/editor/?step_ID=' . $rowSteps['ID']  . '">Sottostep</a>. <br/> ';
            }
        }

        echo '<tr>
                <td>' . $row['ID'] . '</td>
                <td>' . $row['categoria_nome'] . '</td>
                <td>' . $row['categoria_sigla'] . '</td>
                
                <td>' . $step . ' <div class="configuratoreTabellaStep"><a href="' . $conf['URI'] . 'configuratore-admin/step/editor/?categoria_ID=' . $row['ID'] . '">Aggiungi step</a></div> </td>
                <td>
                    <a href="' . $conf['URI'] . 'configuratore-admin/categorie/editor/?ID=' . $row['ID'] . '">Modifica</a> |
                    <span onclick="if( confirm(\'Vuoi eliminare la categoria? Questa operazione non è annullabile\')){ location.href = jsPath + \'configuratore-admin/elimina-categoria/?ID=' . $row['ID'] . '\'  } " class="spanClickable">Elimina</span>
                </td>
              </tr>';
    }

    echo '
    </tbody>
    </table>';
}