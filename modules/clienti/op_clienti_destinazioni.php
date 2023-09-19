<?php

if (!$core)
    die("Accesso diretto rilevato");

if (!$user->validateLogin())
    return;

$this->title = 'WinConfig - Clienti indirizzi secondari';

switch ($path[3]) {
    case 'editor':
        require_once 'op_clienti_destinazioni_editor.php';
        return;
}

if (!isset($_GET['customer_ID'])) {
    echo 'Manca l\'ID del cliente.';
    return;
}

$customer_ID = (int) $_GET['customer_ID'];

$query = 'SELECT * 
          FROM customers_addresses
          WHERE customer_ID = ' . $customer_ID  . '
            AND company_ID = ' . $user->company_ID;

if (!$result = $db->query($query)) {
    echo 'Query error. ' . $query;
    return;
}

if (!$db->affected_rows) {
    echo 'Il cliente non possiede destinazioni.';
} else {

    echo '<table class="table table-bordered table-striped table-condensed">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Business name</th>
                    <th>P.IVA</th>
                    <th>Città</th>
                    <th>Provincia</th>
                    <th>Nazione</th>
                    <th>Operazioni</th>
                </tr>    
            </thead>    
            <tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
                <td>' . $row['ID'] . '</td>
                <td>' . $row['ragione_sociale'] . '</td>
                <td>' . $row['vat'] . '</td>
                <td>' . $row['address_city'] . '</td>
                <td>' . $row['address_town'] . '</td>
                <td>' . $row['address_nation'] . '</td>
                <td> <a href="' . $conf['URI'] . 'clienti/addresses/editor/?op=edit&ID=' . $row['ID'] .'">Modifica</a></td>
              </tr>';
    }
    echo '</tbody>
    </table>';

}