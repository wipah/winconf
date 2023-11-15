<?php

$this->noTemplateParse = true;

if(!$user->validateLogin())
    return;


if (!isset($_POST['documento_ID'])) {
    echo 'Manca l\'ID del documento';
    return;
}

$documento_ID = (int) $_POST['documento_ID'];

echo $core->valuta($configuratore->totaleDocumento($documento_ID));