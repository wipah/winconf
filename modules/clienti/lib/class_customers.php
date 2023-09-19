<?php
namespace framework;

class customers {
    function checkCustomerByVAT( string $vat) : bool
    {
        global $db;
        global $core;

        $vat = $core->in($vat);

        $query = 'SELECT * 
                  FROM clienti 
                  WHERE partita_iva = \'' . $vat . '\' 
                  LIMIT 1;';

        if (!$db->query($query))
            die($query);


        if ($db->affected_rows > 0 ) {
            return true;
        } else {
            return false;
        }

    }
}