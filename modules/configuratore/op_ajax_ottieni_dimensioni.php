<?php

$this->noTemplateParse = true;

if(!$user->validateLogin())
    return;

$documento_ID = (int) $_POST['documento_ID'];

$query = 'SELECT lunghezza
               , larghezza
               , spessore 
          FROM documenti 
          WHERE ID = ' . $documento_ID . ' 
          LIMIT 1';

$result = $db->query($query);

if (!$db->affected_rows) {
    echo 'Nessun documento trovato!';
    return;
} else {
    $row = mysqli_fetch_assoc($result);
    echo '<div class="col-md-5"><span style="font-size: xx-large">' . (int) $row['larghezza'] . '</span> <br/><small>mm</small></div>
            <div class="col-md-2" style="vertical-align: center;"> X </div>
            <div class="col-md-5"><span style="font-size: xx-large">' . (int) $row['lunghezza'] . '</span> <br/><small>mm</small>
          </div>';
}
