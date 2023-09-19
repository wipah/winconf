<?php
namespace framework;

class user
{
    public bool $logged = false;

    public int  $company_ID = -1;

    public int $ID;
    public $name;
    public $surname;
    public int $group_ID = -1;
    public $isAdmin;


    public $api_uri  = '';

    public function login($email, $password) :bool
    {
        global $db;


        $query = '
		SELECT U.ID, 
		       U.name,
		       U.surname,
		       U.company_ID,
		       U.group_ID,
		       U.is_admin
		FROM users AS U
		WHERE U.email       = \'' . $email . '\'		
		    AND U.password  = \'' . md5($password . 'WINCONF') . '\'
		    AND U.enabled   = 1
		LIMIT 1';


        if (!$result = $db->query($query)) {
            die ("Errore in query." . $query);
        }

        if (!$db->affected_rows) {
            return false;
        } else {
            $row = mysqli_fetch_array($result);

            $this->ID           =   $row['ID'];
            $this->name         =   $row['name'];
            $this->surname      =   $row['surname'];
            $this->company_ID   =   $row['company_ID'];
            $this->group_ID     =   $row['group_ID'];
            $this->isAdmin      =   $row['is_admin'];

            $this->logged = true;
            // $this->getZones($row['zones']);

            $sessionID = md5(rand());

            $cookieSecurity = md5($sessionID . $this->ID . 'WINCONF');

            $query = 'INSERT INTO sessions 
					  (user_ID, 
					   session, 
					   security_hash, 
					   start, 
					   end,
					   IP)
					  VALUES
					  (
					  ' . $this->ID . ',
					  \'' . $sessionID . '\',
					  \'' . $cookieSecurity . '\',
					  NOW(),
					  NOW() + INTERVAL 1 MONTH,
					  \'' . $_SERVER['REMOTE_ADDR'] . '\'
					  )';

            if (!$db->query($query)) {
                echo 'Errore. ' . $query;

                return false;
            }

            setcookie('ID', $this->ID, time() + 2592000, '/');
            setcookie('session', $sessionID, time() + 2592000, '/');
            setcookie('security', $cookieSecurity, time() + 2592000, '/');

            $this->loadCompanyData();

            return true;
        }
    }

    public function loginFromCookie() :bool
    {
        global $db;
        global $core;
        global $log;



        $ID         = (int) $_COOKIE['ID'];
        $session    = $core->in($_COOKIE['session']);
        $security   = $core->in($_COOKIE['security']);

        $query = '
		SELECT U.*
		FROM sessions S
		LEFT JOIN users AS U
			ON U.ID = S.user_ID
		WHERE 
		      U.enabled = 1 AND
		      S.user_ID = \'' . $ID . '\' AND
			  session = \'' . $session . '\' AND
			  security_hash = \'' . $security . '\'';

        if (!$result = $db->query($query)) {
            echo 'Query error while trying to login from cookie. ' . $query;
            return false;
        }

        if (!$db->affected_rows) {
           
            // echo ( '<pre>Security hack?' .$query . '</pre>');

            unset ($_COOKIE['session']);
            unset ($_COOKIE['security']);
            unset ($_COOKIE['ID']);

            return false;
        } else {
            $row = mysqli_fetch_array($result);

            $this->ID           =   $row['ID'];
            $this->name         =   $row['name'];
            $this->surname      =   $row['surname'];
            $this->company_ID   =   $row['company_ID'];
            $this->group_ID     =   $row['group_ID'];
            $this->isAdmin      =   $row['is_admin'];


            $this->logged   = true;

            $this->loadCompanyData();
            return true;
        }
    }


    function loadCompanyData()
    {
        global $db;
        global $conf;

        $query = 'SELECT * 
              FROM companies 
              WHERE ID = ' . $this->company_ID . '
              LIMIT 1';

        if (!$result = $db->query($query)){
            die ("Query error. " . $query);
        }

        if (!$db->affected_rows) {
            die ("Nessuna azienda trovata." . $query);
            return;
        }

        $row = mysqli_fetch_assoc($result);

        $this->api_uri                          = $row['api_uri'];
    }

    function validateLogin() :bool
    {
        if (!$this->logged) {
            echo 'Devi aver effettuato il login per visualizzare questo modulo.';
            return false;
        } else {
            return true;
        }
    }

}