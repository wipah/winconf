<?php
if (!$core)
    die("Accesso diretto rilevato");

if (!$user->logged) {
    echo $this->getBox('info', 'Devi essere loggato.');
    return;
}

$this->title = 'WinConf - Pagina principale';

$query = 'SELECT documenti.*
                , configuratore_categorie.categoria_nome
                , clienti.ID cliente_ID
                , clienti.ragione_sociale cliente_ragione_sociale
          FROM documenti
          LEFT JOIN configuratore_categorie
          ON configuratore_categorie.ID = documenti.categoria_ID
          LEFT JOIN clienti
          ON clienti.ID = documenti.customer_ID
          WHERE documenti.user_ID = ' . $user->ID;


if (!$result = $db->query($query)) {
    echo 'Query error. ' . $query;
    return;
}

$tabellaOrdini = '';
if (!$db->affected_rows) {
    $tabellaOrdini .= $this->getBox('info','Nessun documento trovato. Puoi inserire il tuo primo progetto/documento 
    <a href="' . $conf['URI'] . 'configuratore/">cliccando qui</a>');
} else {
    $tabellaOrdini .= '<table class="table table bordered table-condensed table-striped winconf-table">
            <thead>
                <tr>
                    <th>Numero</th>
                    <th>Cliente</th>
                    <th>Tipo</th>
                    <th>Lunghezza</th>
                    <th>Larghezza</th>
                    <th>Importo totale</th>
                    <th>Stato</th>
                    <th>Operazioni</th>
                </tr>
            </thead>
            <tbody>';
    while ($row = mysqli_fetch_assoc($result)) {
        $tabellaOrdini .= '<tr>
                <td>' . $row['ID'] . '</td>
                <td>
                    <a href="' . $conf['URI'] .'clienti/editor/?ID=' . $row['cliente_ID'] . '">' . $row['cliente_ragione_sociale'] . '</a></td>
                <td>' . $row['categoria_nome'] . '</td>
                <td>' . $row['lunghezza'] . ' mm</td>
                <td>' . $row['larghezza'] . ' mm</td>
                <td>'. $core->valuta($row['totale']) .'</td>
                <td>' . ( (int) $row['stato'] === 0 ? 'Aperto' : 'Chiuso') . '</td>
                <td>
                    <span class="icon-link">
                        <a href="' . $conf['URI'] . 'configuratore/elimina-documento/?ID=' . $row['ID'] . '">
                            <i class="fas fa-trash"></i>
                        </a>
                    </span>
                    <span class="icon-link">
                        <a href="' . $conf['URI'] . 'configuratore/editor/?ID=' . $row['ID'] . '">
                            <i class="fas fa-edit"></i>
                        </a>
                    </span>
                    <span class="icon-link">
                        <a href="' . $conf['URI'] . 'configuratore/editor/stampa/?documento_ID=' . $row['ID'] . '">
                            <i class="fas fa-print"></i>
                        </a>
                        </td>
                    </span>
                </td>
              </tr>';
    }
    $tabellaOrdini .= '</tbody>
    </table>';
}

$ultimiClientiInseriti = '';
$query = 'SELECT * 
          FROM clienti 
          WHERE user_ID = ' . $user->ID . ' ORDER BY ID DESC LIMIT 5';
$risultatoClienti = $db->query($query);

if (!$db->affected_rows) {
    $ultimiClientiInseriti = 'Nessun cliente presente';
} else {
    while ($rowClienti = mysqli_fetch_assoc($risultatoClienti)) {
        $ultimiClientiInseriti .= '&bull; <a href="'. $conf['URI'] . '/clienti/editor/?ID= ' . $rowClienti['ID'] . '">' . $rowClienti['ragione_sociale'] . '</a> <br/>';
    }
}

echo '<div class="row">
    <div class="col-md-9">
        ' . $tabellaOrdini . '
    </div>
    <div class="col-md-3 layoutLaterale">
        <h2>Ultimi clienti inseriti</h2>
        ' . $ultimiClientiInseriti . '
        <a href="' . $conf['URI'] . 'clienti/editor/" class="btn btn-warning float-right">Nuovo cliente</a>
    </div>
</div>';