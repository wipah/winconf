<?php

class configuratore
{
    public float  $larghezza;
    public float $lunghezza;

    function creaDocumento()
    {

    }

    /**
     * @param int $tipo 0 = sottostep, 1 = opzione
     * @param int $ID
     * @return bool
     */
    function checkDipendenzaDimensione(int $tipo, int $ID): bool
    {
        global $db;

        switch ($tipo) {
            case 0: // Controllo dimensioni per sottostep
                $query = 'SELECT * FROM configuratore_opzioni_check_dimensioni 
                      WHERE sottostep_ID = ' . $ID . ' AND (opzione_ID = 0 || opzione_ID IS NULL)';
                break;
            case 1: // Controllo dimensioni per opzione
                break;

        }

        $result = $db->query($query);

        // Non esistono controlli, quindi il sottostep oppure l'opzione è visibile
        if (!$db->affected_rows)
            return true;

        while ($row = mysqli_fetch_assoc($result)) {


            switch ((int)$row['dimensione']) {
                case 0:
                    break;
                case 1:
                    break;
        }
            /*
             *
    0: < (minore);
    1: <= minore uguale;
    2: = (uguale);
    3: >= (maggiore o uguale)
    4: > (maggiore)
    5: != diverso
             */
            switch ((int)$row['confronto']) {

            }

        }
    }
}