<?php

if (!$core)
    die("Accesso diretto rilevato");

if (!$user->validateLogin())
    return;

if ($user->group_ID === 3) {
    echo 'Accesso non consentito. ';
    return;
}

if (isset($_GET['import'])) {

    if (!isset($_POST['dummy'])){
        echo 'Reload detected';
        return;
    }

    $data = $_POST['importCustomer'];

    preg_match_all('#\[CLIENTE\](.*?)\[\/CLIENTE]#is', $data, $matches);

    foreach ($matches[1] as $customerData) {

        $customerLines = explode("\r\n", $customerData);
        foreach ($customerLines as $singleLine) {
            if (strlen($singleLine) === 0)
                continue;

            echo '<br/><br/><stong>=== PARSING ===</stong><br/>';


            $fragments = explode('|-|', $singleLine);

            switch ($fragments[0]) {
                case 'A':
                    echo '<strong>*** A type ***</strong><br/>';
                    $data = explode(';', $fragments[1]);

                    $clifor         = $core->in(fix($data[0]));
                    $businessName   = $core->in(fix($data[1]));
                    $address        = $core->in(fix($data[2]));
                    $postcode       = $core->in(fix($data[3]));
                    $city           = $core->in(fix($data[4]));
                    $town           = $core->in(fix($data[5]));
                    $vat            = $core->in(fix($data[6]));
                    $fiscalCode     = $core->in(fix($data[7]));
                    $phone          = $core->in(fix($data[8]));
                    $email          = $core->in(fix($data[9]));
                    $pec            = $core->in(fix($data[10]));
                    $mobilePhone    = $core->in(fix($data[11]));
                    $category_ID    = $core->in(fix($data[12]));

                    $queryCheck = 'SELECT ID 
                                   FROM customers
                                   WHERE erp_ID = \'' . $clifor . '\' 
                                    AND company_ID = \'' . $user->company_ID . '\' 
                                   LIMIT 1';

                    if (!$resultCheck = $db->query($queryCheck)) {
                        echo 'Query error. ' . $queryCheck;
                        return;
                    }

                    if ($db->affected_rows) {
                        $rowCheck = mysqli_fetch_assoc($resultCheck);
                        $ID = (int) $rowCheck['ID'];

                        echo '*** Updating customer. ID is ' . $ID . ' <br/>';
                        $query =  "UPDATE customers 
                                   SET
                                    erp_ID              = '$clifor',
                                    ragione_sociale       = '$businessName',
                                    address_street      = '$address',
                                    address_postcode    = '$postcode',
                                    address_city        = '$city',
                                    address_town        = '$town',
                                    vat                 = '$vat',
                                    codice_fiscale         = '$fiscalCode',
                                    phone               = '$phone',
                                    email               = '$email',
                                    pec                 = '$pec',
                                    mobile_phone        = '$mobilePhone',
                                    category_ID         = '$category_ID',
                                    last_update_date    = NOW()
                                    update_source       = 1   
                                  WHERE ID = $ID
                                  LIMIT 1";


                        if (!$db->query($queryCheck)) {
                            echo '--> Query error. ' . $queryCheck . '<br/>';
                        } else {
                            echo '--> OK. Customer updated<br/>';
                        }

                    } else {
                        echo '*** Creating new customer. <br/>';
                        $query =  "INSERT INTO customers 
                                   (
                                    company_ID,
                                    erp_ID,
                                    user_ID,
                                    ragione_sociale,
                                    address_street,
                                    address_postcode,
                                    address_city,
                                    address_town,
                                    vat,
                                    codice_fiscale,
                                    phone,
                                    email,
                                    pec,
                                    mobile_phone,
                                    category_ID,
                                    update_source,
                                    last_update_date
                                   )
                                   VALUES
                                   (
                                    '{$user->company_ID}',
                                    '$clifor',
                                    '{$user->ID}',
                                    '$businessName',
                                    '$address',
                                    '$postcode',
                                    '$city',
                                    '$town',
                                    '$vat',
                                    '$fiscalCode',
                                    '$phone',
                                    '$email',
                                    '$pec',
                                    '$mobilePhone',
                                    $category_ID,
                                    1,
                                    NOW()
                                   );";

                        if (!$db->query($query)) {
                            echo '--> Query error. ' . $query . '<br/>';
                        } else {
                            $ID = $db->insert_id;
                            echo '--> OK. ID is ' . $ID . '<br/>';
                        }
                    }
                    break;
                CASE 'D':
                    echo '<strong>*** D type over customer ID ' . $ID . '***</strong><br/>';
                    $data = explode(';', $fragments[1]);

                    $erp_ID             = $core->in(fix($data[0]));
                    $businessName       = $core->in(fix($data[1]));
                    $address            = $core->in(fix($data[2]));
                    $postcode           = $core->in(fix($data[3]));
                    $city               = $core->in(fix($data[4]));
                    $town               = $core->in(fix($data[5]));
                    $phone              = $core->in(fix($data[6]));
                    $mobilePhone        = $core->in(fix($data[7]));
                    $email              = $core->in(fix($data[8]));
                    $note               = $core->in(fix($data[9]));

                    $query = 'SELECT ID 
                              FROM customers_addresses 
                              WHERE erp_ID = \'' . $erp_ID . '\' 
                              AND company_ID = ' . $user->company_ID . ' 
                              AND customer_ID = ' . $ID . ' 
                              LIMIT 1';

                    if (!$resultDestination = $db->query($query)) {
                        echo '--> Query error. ' . $query . '<br/>';
                    } else {

                        if (!$db->affected_rows) {
                            echo '--> No destination exists. Creating one now.<br/>';

                            $query = "INSERT INTO customers_addresses
                                      (
                                       customer_ID,
                                       company_ID,
                                       erp_ID,
                                       ragione_sociale,
                                       address_street,
                                       address_city,
                                       address_postcode,
                                       address_town,
                                       phone,
                                       mobile_phone,
                                       email,
                                       pec,
                                       notes,
                                       update_source
                                      ) 
                                      VALUES
                                      (
                                      $ID,
                                      {$user->company_ID},
                                      '$erp_ID',
                                      '$businessName',
                                      '$address',
                                      '$city',
                                      '$postcode',
                                      '$town',
                                      '$phone',
                                      '$mobilePhone',
                                      '$email',
                                      '$pec',
                                      '$note',
                                      1
                                      )";

                            if (!$db->query($query)) {
                                echo '--> Query error. ' . $query . '<br/>';
                            } else {
                                echo '--> Destination placed into DB. <br/>';
                            }
                        } else {
                            $rowDestination = mysqli_fetch_assoc($resultDestination);
                            $destination_ID = $rowDestination['ID'];

                            echo '--> Destination already exists. ID is ' . $destination_ID .'<br/>';

                            $query = "UPDATE customers_addresses
                                      SET
                                       ragione_sociale    = '$businessName',
                                       address_street   = '$address',
                                       address_city     = '$city',
                                       address_postcode = '$postcode',
                                       address_town     = '$town',
                                       phone            = '$phone',
                                       mobile_phone     = '$mobilePhone',
                                       email            = '$email',
                                       pec              = '$pec',
                                       notes            = '$note',
                                       update_source    = 1
                                      WHERE ID = $destination_ID
                                      LIMIT 1";

                            if (!$db->query($query)) {
                                echo '--> Query error. ' . $query . '<br/>';
                            } else {
                                echo '--> Updated.<br/>';
                            }
                        }

                    }
                    break;
            }
        }
    }
    return;
}

echo '
<h1>Importazione clienti</h1>
<form method="post" action="' . $conf['URI'] . '/clienti/import/?import">
    <input type="hidden" name="dummy" id="dummy">
    <textarea id="importCustomer" name="importCustomer" style="width: 100%"></textarea>
    <button type="submit">Importa tracciato clienti</button>
</form>';

function fix($string) {
    return trim(str_replace(';','', $string));
}