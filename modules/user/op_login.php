<?php

if (!$core)
    die("Accesso diretto rilevato");

$email      = $_POST['login_email'];
$password   = $_POST['login_password'];

if (!$user->login($email, $password)) {
    echo '<div class="alert alert-warning" role="alert">
            <strong>Impossibile effettuare il login</strong>. La combinazione email/password non è corretta. Assicurati di aver rispettato le maiuscole/minuscole per la password.
           </div>';
} else {
    echo '<div class="alert alert-success" role="alert">
            <strong>Login ok</strong>. Login effettuato con successo. <a href="' . $conf['URI'] . '">Clicca qui per l\'homepage</a>.
          </div>';;
}