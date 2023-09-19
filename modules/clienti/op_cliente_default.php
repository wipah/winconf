<?php

if (!$core)
    die("Accesso diretto rilevato");

if (!$user->validateLogin())
    return;

$this->title = 'WinConfig - Clienti';

echo '<h1>Gestione clienti</h1>';

$this->menuItems[] = '<a href="' . $conf['URI'] . 'clienti/">Clienti</a>';
$this->menuItems[] = '<em>Gestione clienti</em>';


/*
if ($user->group_ID === 3) {
    $queryTown = ' AND (';
    $queryAddressTown = ' OR CLIENTI.ID IN (
                            SELECT ADDRESSES.customer_ID 
                            FROM customers_addresses ADDRESSES WHERE ';

    foreach ($user->zones as $zone) {
        $queryTown .= ' CUSTOMERS.address_town = \'' . $zone . '\'  OR';
        $queryAddressTown .= ' ADDRESSES.address_town = \'' . $zone . '\'  OR';
    }

    $queryTown = substr($queryTown,0, -3) . ')';
    $queryAddressTown = substr($queryAddressTown,0, -3) . ')';
}
*/
/*
SELECT *
FROM customers
LEFT JOIN customers_addresses
ON customers_addresses.customer_ID = customers.ID
    WHERE customers.company_ID = 1 AND ( customers.address_town = 'FI' OR customers.address_town = 'PO' OR customers.address_town = 'PT' OR customers_addresses.address_town = 'FI' OR customers_addresses.address_town = 'PO' OR customers_addresses.address_town = 'PT' ) ORDER BY customers.ID desc;
 */

$query = '
SELECT CLIENTI.*
FROM clienti CLIENTI
WHERE CLIENTI.company_ID = ' . $user->company_ID . ';';

if (!$result = $db->query($query)) {
    echo 'Errore nella query. ' . $query;
    return;
}

if (!$db->affected_rows) {
    echo $this->infoBox('Nessun cliente', 'Non hai inserito ancora alcun cliente', ['Aggiungi un cliente' => $conf['URI'] . 'clienti/editor/']);
    return;
}

echo '
<a style="margin-bottom: 12px !important" class="btn btn-primary float-right" href="' . $conf['URI'] . 'clienti/editor/">Nuovo cliente</a>

<table id="customerClients" class="table table-bordered table-striped table-condensed">
    <thead>
        <tr>
            <th>ID</th>
            <th>ERP</th>
            <th>Ragione sociale</th>
            <th>P.IVA</th>
            <th>Città</th>
            <th>Provincia</th>
            <th>Operazioni cliente</th>
        </tr>
    </thead>
    <tbody>';

while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>
            <td>' . $row['ID'] . '</td>
            <td>' . $row['erp_ID'] . '</td>
            <td>' . $row['ragione_sociale'] . '</td>
            <td>' . $row['partita_iva'] . '</td>
            <td>' . $row['indirizzo_citta'] . '</td>
            <td>' . $row['indirizzo_provinica'] . '</td>
            <td>
                <a href="' . $conf['URI'] . 'clienti/editor/?ID=' . $row['ID'] . '">Modifica cliente</a>
            </td>
          </tr>';
}
echo '</tbody>
</table>

<script>
$(document).ready(function() {
    $(\'#customerClients\').DataTable(
    {
        "order": [[ 0, "desc" ]]
    }
    );
} );
</script>
';