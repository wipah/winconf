<?php

$this->noTemplateParse = true;
if(!$user->validateLogin())
    return;

$documento_ID     = (int) $_POST['documento_ID'];
$note       =  $core->in( $_POST['note']);

$query = 'UPDATE documenti 
          SET note = \'' . $note . '\'
          WHERE ID = ' . $documento_ID . ' 
          LIMIT 1';

echo $query;
$db->query($query);

echo '--OK--';