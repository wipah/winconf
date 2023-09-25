<?php

class configuratore {
    function aggiornaHash()
    {
        global $db;
        global $user;
        $hash = substr(md5(microtime(true)), 0, 5);

        $query = 'UPDATE companies SET configuratore_hash = \'' . $hash . '\'
        WHERE ID = ' . $user->company_ID . '
        LIMIT 1;';

        $db->query($query);

    }

    function stepDaOrdine( int $ordine_ID) :array|false
    {
        global $db;

        $query = 'SELECT STEP.ID,
                         STEP.step_nome
                  FROM documenti_corpo CORPO
                  LEFT JOIN configuratore_step STEP
                    ON STEP.ID = CORPO.step_ID
                  WHERE CORPO.documento_ID = ' . $ordine_ID;


        $result = $db->query($query);

        if (!$db->affected_rows) {
            return false;
        } else {
            $return = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $return[ (int) $row['ID']] = $row['step_nome'];
            }

            return $return;
        }
    }

    function layoutCreaSottoStep(int $step_ID, int $sottostep_ID, $linea_ID) :string
    {
        global $db;

        $query = 'SELECT * 
                 FROM configuratore_sottostep 
                 WHERE ID = ' . $sottostep_ID . ' LIMIT 1';

     ;
        $result = $db->query($query);

        if (!$db->affected_rows) {
            return 'Sottostep di ID ' . $sottostep_ID . ' non trovato';
        }

        $row = mysqli_fetch_assoc($result);

        $query = 'SELECT opzione_ID FROM documenti_corpo WHERE ID = ' . $linea_ID;
        $risultatoOpzioneScelta = $db->query($query);
        $rowOpzioneScelta = mysqli_fetch_assoc($risultatoOpzioneScelta);

        $partSelect = '';

        if ( (int) $row['tipo_scelta'] === 0) {
            $query = 'SELECT * 
                      FROM configuratore_opzioni 
                      WHERE sottostep_ID = ' . $sottostep_ID;

            $risultatoOpzioni = $db->query($query);

            $partSelect = '<select class="form-control"  onchange="cambiaSingolaOpzione(\'' . $linea_ID . '\', $(this).val(), ' . $step_ID . ');" id="">';
            while ($rowOpzioni = mysqli_fetch_assoc($risultatoOpzioni)) {
                $partSelect .= '<option ' . ( (int) $rowOpzioni['ID'] === (int) $rowOpzioneScelta['opzione_ID'] ? ' selected ' : '') . ' 
                                        value="' . $rowOpzioni['ID'] .'">' . $rowOpzioni['opzione_nome'] . '
                                </option>';
            }
            $partSelect .= '</select>';
        }

        $part = '<div class="layoutEditorSottostep">
                    <div class="row">
                        <div class="col-md-3 layoutEditorSottostepNome">' . $row['sottostep_nome'] . '</div>
                        <div class="col-md-8">' . $partSelect . '</div>
                        <div class="col-md-1"><div id="layoutEditorSottostepStatus-' . $linea_ID . '"></div></div>
                    </div>
                </div>';

        return  $part;

    }
}