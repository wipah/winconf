<?php
if (!$user->validateLogin())
    return;

echo '<h2>Finalizzazione documento</h2>';

$documento_ID = (int) $_GET['documento_ID'];

$configuratore->cambiaStatoDocumento($documento_ID, 1);