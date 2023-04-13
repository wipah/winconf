<?php

if (!$core)
    die("Accesso diretto rilevato");

$user_ID = (int) $_COOKIE['ID'];
$session = $core->in($_COOKIE['session']);

$query = 'DELETE 
          FROM sessions 
          WHERE user_ID = ' . $user_ID . ' 
            AND session = \'' . $session . '\' LIMIT 1';

if (!$db->query($query))
    echo 'Query error. ' . $query;

setcookie('ID', null, -1, '/');
setcookie('session', null, -1, '/');
setcookie('security', null, -1, '/');

echo 'Logout ok';
