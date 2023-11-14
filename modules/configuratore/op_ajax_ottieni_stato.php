<?php

$this->noTemplateParse = true;

if(!$user->validateLogin())
    return;

$documento_ID = (int) $_POST['documento_ID'];

$stato = $configuratore->documentoCompletato($documento_ID);

echo $stato;






