<?php
namespace framework;

class template {
    public string $title = "Nessun titolo";
    public string $buffer;
    public $noTemplateParse = false;
    public $menuItems = [];

    protected $content;

    public function getPage()
    {
        if ($this->noTemplateParse === true) {
            return $this->content;
        } else {
            return $this->parsePage();
        }
    }

    public function getMenu(){
        global $conf;
        $menu = '<a href="' . $conf['URI'] . '">Homepage</a>';

        foreach ($this->menuItems as $singleMenu) {
            $menu .= ' > ' . $singleMenu;

        }

        return $menu;
    }

    public function infoBox(string $title, string $message, array $CTA = []) {

        if (count($CTA) > 0 ) {
            $actions = '';
            foreach ($CTA AS $text => $link  ) {
                $actions = '<a href="' . $link . '">' . $text . '</a> - ';
            }
            $actions = substr($actions,0,-3);
        }

        echo '<div class="clearfix" style="border: 1px solid gray; background-color: aliceblue; padding: 4px;">
                <h3>' . $title .'</h3>
                <div>' . $message . '</div>
                <div class="float-right" style="border:1px solid gray; background-color:white; padding: 8px">' . $actions . '</div>
             </div>';
    }

    public function getBox( string $type, string $message) {

        switch ($type) {
            case 'danger':
                $class = 'alert-danger';
                break;
            case 'warning':
                $class = 'alert-warning';
                break;
            default:
            case 'info':
                $class = 'alert-primary';
                break;
        }

        return '<div class="alert ' . $class . '" role="alert">' . $message . '</div>';
    }

    public function loadModule( string $module)
    {
        global $conf;
        global $path;
        global $db;
        global $core;
        global $URI;
        global $user;
        global $order;
        global $dbHelper;
        global $arrayConfronti;
        global $configuratore;
        ob_start();
            require_once $conf['path'] . 'modules/' . $module . '/' . $module . '.php';

            $this->content = ob_get_contents();
        ob_end_clean();
    }

    public function parsePage()
    {
        global $conf;
        global $user;

        if ($user->logged) {
            $loginBox = 'Bentornato <a href="' . $conf['URI'] .'profile/">' . $user->name . '</a>. [<a href="' . $conf['URI'] . 'user/logout/">LOGOUT</a>]';
        } else {
            $loginBox = '<form method="post" action="' .$conf['URI'] . 'user/login/">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-user"></i>
                                        </div>
                                    </div> 
                                    <input id="login_email" name="login_email" type="text" class="form-control form-control-sm">
                                    </div>            
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <div class="input-group-text">
                                        <i class="fa fa-key"></i>
                                    </div>
                                </div> 
                                
                            <input id="login_password" name="login_password" type="password" class="form-control form-control-sm">
                            </div>
                                 </div>
                            
                            
                            <div class="col-md-4">
                                <button class="form-control form-control-sm" type="submit">Login</button>
                            </div>
    
                            </div>
                         </form>';
        }

        $thePage =
            '<!DOCTYPE html>
<html lang="it">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <title>' . $this->title . '</title>

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="' . $conf['URI'] . 'core/js/input-spinner.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  
  <script>
  
        jsPath = "' . $conf['URI'] . '";
        
        Number.prototype.round = function(places) {
            return +(Math.round(this + "e+" + places)  + "e-" + places);
        }
  </script>
  
  <script src="https://unpkg.com/tiny-editor/dist/bundle.js"></script>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css" integrity="sha512-ELV+xyi8IhEApPS/pSj66+Jiw+sOT1Mqkzlh8ExXihe4zfqbWkxPRi8wptXIO9g73FSlhmquFlUOuMSoXz5IRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  <link href="https://cdn.jsdelivr.net/npm/css.gg/icons/icons.css" rel="stylesheet" />
  <link rel="stylesheet" 
      href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" 
      integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
      crossorigin="anonymous">
  <style>

:root {
/* CSS HEX */
--lavender-web: #d1ccdcff;
--charcoal: #424c55ff;
--lavender-blush: #f5edf0ff;
--cinereous: #886f68ff;
--van-dyke: #3d2c2eff;

}

html, body {
background-color: #fdf7f7;
}
.lds-dual-ring {
  display: inline-block;
  width: 64px;
  height: 64px;
}
.lds-dual-ring:after {
  content: " ";
  display: block;
  width: 35px;
  height: 35px;
  margin: 1px;
  border-radius: 50%;
  border: 5px solid #e8b08a;
  border-color: #e8b08a transparent #e8b08a transparent;
  animation: lds-dual-ring 1.6s linear infinite;
}
@keyframes lds-dual-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.alert-primary {
    background-color: var(--cinereous) !important;
    color: white;
    border: 1px solid var(--van-dyke);
}

.alert-primary > a {
    background-color: var(--cinereous) !important;
    color: white;
    text-decoration: underline;
    
}

.btn-info {
    background-color: var(--charcoal) !important;
    color: white !important;
    border: 1px solid var(--van-dyke);
     font-weight: bold;
}

.btn-info:hover {
    background-color: var(--lavender-web) !important;
    color: var(--van-dyke) !important;
    font-weight: bold;
}
.spanClickable {
    text-decoration: underline;
    color: blue !important;
    cursor: pointer;
}
    
.winConfMenu {
    border-bottom: 1px solid gainsboro;
}
    
.table > thead > tr {
    background-color: var(--charcoal) !important;
    color: white;
}

.table > tbody > tr > td {
   background-color: var(--lavender-web) !important;
   font-weight: normal;
}
    
.table > thead > tr {
    background-color: lightgray;
}

.table > tfoot > tr > td {
   background-color: var(--lavender-web) !important;
   font-weight: bolder;
}
.layoutEditorSottostepNome {
    font-weight: bolder;
    text-transform: uppercase;
}

.layoutEditorSottostepDescrizione {
    font-size: smaller;
}    

.footer {
    margin-top: 12px;
    background-color: #3f3d3d;
    color: white;
    padding: 8px;
}
    
h1, h2 {
    background: lightgray;
    padding: 12px;
    font-size: large;
    font-weight: bolder;
    text-transform: uppercase;
    color: #555;
    border-left: 4px solid darkgray;
}
    
input:focus{
    background-color: #e0e8a9;
    border-bottom: 1px solid darkgreen;
    font-weight: bolder;
}

.nav-tabs {
    border-bottom: 1px solid #9b7b2e !important;
}

.nav-link.active {
    border-color: #9b7b2e #9b7b2e #FFF !important;
    background-color: #FFBC35 !important;
    color: #333;
    font-weight: bolder;
}

.layoutLaterale {
    background-color: #2a64a6;
    color: white;
}

.layoutLaterale > h2 {
    border: 0px;
    border-bottom: 1px solid white !important;
    color: white;
    background-color: transparent;
}
.layoutLaterale > a {
    border: 0px;
    color: white;
}

  </style>
</head>

<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top" style="background-color: #4053BF !important;">
      <a class="navbar-brand" href="' . $conf['URI'] . '">WinConfig</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <a class="nav-link" href="' . $conf['URI'] . '">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          
          
          <li class="nav-item">
            <a class="nav-link" href="' .$conf['URI'] . 'clienti/">Clienti</a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="' .$conf['URI'] . 'configuratore/">Configuratore</a>
          </li>
          
          
          
          <li class="nav-item">
            <a class="nav-link" href="' .$conf['URI'] . 'configuratore-admin/">Backend</a>
          </li>
        </ul>
      </div>
  </nav>

<div>

</div>
<!-- Page Content -->
<div class="container" style="max-width: 95% !important;">
<div class="row" style=" margin-bottom: 24px; margin-top: 24px;">
    <div class="col-md-8">
        <div class="winConfMenu"><!--WINCONFmenu--></div>
    </div>
    <div class="col-md-4">
        <div class="winConfMenu float-right">' . $loginBox . '</div> 
    </div>
</div>
' . $this->content  . '
</div>
<footer class="footer">
WinConf v0.1 alpha
</footer>
' . ($user->isAdmin ? '<div style="border:1px solid gray; padding: 6px;"><!--WINCONFdebug--></div>' : '') . '

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

</body>

</html>';

        $thePage = str_replace('<!--WINCONFmenu-->', $this->getMenu(), $thePage);

        return $thePage;
    }
}