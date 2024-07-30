<?php
$this->noTemplateParse = true;
if (!$user->validateLogin())
    return;
$header = $core->in($_POST['header']);
$footer = $core->in($_POST['footer']);

$query = 'UPDATE COMPANIES 
            SET header = \'' . $header . '\'
             ,  footer = \'' . $footer . '\' 
          WHERE ID = ' . $user->company_ID . ' 
          LIMIT 1';

if (!$db->query($query))  {
    echo '--KO--' . $query;
} else {
    echo '--OK--';
}