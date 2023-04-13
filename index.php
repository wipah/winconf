<?php
error_reporting(E_ERROR | E_COMPILE_ERROR | E_COMPILE_WARNING | E_CORE_ERROR);
require_once 'inc/config.php';
require_once 'core/core/class_core.php';

require_once 'core/math/class_math.php';
$core = new framework\math();

if (!$db = new mysqli($conf['db_host'], $conf['db_user'], $conf['db_password'], $conf['db_dbname'])) {
    echo 'Errore critico nella connessione al DB. Il server non ha risposto in modo corretto.';
    die();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use framework\core;
use framework\template;
use framework\user;

require_once 'core/phpmailer/PHPMailer.php';
require_once 'core/phpmailer/SMTP.php';

require_once 'core/phpmailer/Exception.php';

require_once 'core/user/class_user.php';

CONST _SEMAPHORE_OK         = '<i style=\'color:green\' class=\'fas fa-traffic-light fa-lg\'></i>';
CONST _SEMAPHORE_WARNING    = '<i style=\'color: #b35900\' class=\'fas fa-traffic-light fa-lg\'></i>';
CONST _SEMAPHORE_KO         = '<i style=\'color:red\' class=\'fas fa-traffic-light fa-lg\'></i>';

$user = new user();
$user->loginFromCookie();

$path = explode('/', $_SERVER['REQUEST_URI']);
$path[0] = 'root';

foreach ($path as $key => $value) {
    if ($value == '') {
        unset($path[$key]);
    }
}
$path = array_values($path);

if (empty($conf['uri_subdirectory']) === false) {
    $subArray = explode('/', $conf['uri_subdirectory']);
    array_shift($path);
    for ($i = 0; $i < count($subArray); $i++) {
        if ($path[0] === $subArray[$i]) {
            array_shift($path);
        }
    }
    array_unshift($path, 'root');
}

if (strlen($path[1]) === 2) { // Yes, it has
    $path[1] = str_replace('\'', '', $path[1]);
    if (strlen($path[1]) !== 2) {
        echo 'No way, sorry.';
        return;
    }

    $core->shortCodeLang = $path[1];

    // 0        1       2       3
    // root     it      module  test
    $tbd = array_shift($path);              // it       module  test
    $tbd = array_shift($path);              // module   test
    array_unshift($path, 'root');   // root     module  test
} else {
    if (!isset($path[1])) {
        // Try to automatically redirect the browser
        $langBrowser = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
        header('Location: ' . ($conf['https'] === true ? 'https://' : 'http://') . $conf['uri_domain'] . '/' . $conf['uri_subdirectory'] . '/' . $langBrowser . '/' . ($conf['defaultModuleForceRedirect'] === true ? $core->router->getRewriteAlias($conf['defaultModule']) . '/' : ''));
        return;
    }
}

require_once 'core/template/class_template.php';
$template = new template();
$template->loadModule($path[1] ?? 'homepage');

echo $template->getPage();