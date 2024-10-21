<?php
if (!$core)
    die ('Accesso diretto');


if (!$user->logged) {
    echo 'Devi essere loggato';
    return;
}
$this->menuItems[] = '<a href="' . $conf['URI'] . 'configuratore-admin/">Backend</a>';
$this->menuItems[] = '<a href="' . $conf['URI'] . 'configuratore-admin/listini/">Listini</a>';

$this->title = 'WinConf - Editor listini';
if (isset($_GET['save'])) {
    $nome       = $core->in($_POST['nome']);
    $descrizione = $core->in($_POST['descrizione']);
}

if (isset($_GET['ID'])) {
    $button = 'Salva modifiche';
    $ID     = (int) $_GET['ID'];

    if (isset($_GET['save'])) {


        $query = 'UPDATE listini 
                  SET nome = \'' . $nome . '\' 
                    , descrizione = \'' . $descrizione . '\' 
                  WHERE ID = ' .  $ID . ' 
                  LIMIT 1';

        if (!$db->query($query)) {
            echo 'Query error ' . $query;
        } else {
            echo '<div class="alert alert-success" role="alert">Listino aggiornato con successo!</div>';
        }

    }

    $action = $conf['URI'] . 'configuratore-admin/listini/editor/?ID=' . $ID .'&save';

    $query = 'SELECT * 
          FROM listini 
          WHERE ID = ' . $ID . ' 
          LIMIT 1';

    $result = $db->query($query);
    $row = mysqli_fetch_assoc($result);

} else {

    if (isset($_GET['save'])) {
        if (!isset($_POST['dummy'])) {
            echo 'Reload detected';
            return;
        }

        $query = 'INSERT INTO listini 
                  ( nome
                  , descrizione
                  )  
                  VALUES (
                      \'' . $nome .'\'                                 
                    , \'' . $descrizione .'\'                                 
                  )';
        if (!$db->query($query)) {
            echo 'Query error ' . $query;
        } else {
            echo '<div class="alert alert-success" role="alert">Listino creato con successo!</div>';
        }
        return;
    }


    $button = 'Crea listino';
    $action = $conf['URI'] . 'configuratore-admin/listini/editor/?save';
}

echo '<h1>Editor listino</h1>
<form method="post" action="' . $action .  '">
  <input type="hidden" name="dummy" value="dummiest">
  <div class="form-group row">
    <label for="text" class="col-4 col-form-label">Nome del listino</label> 
    <div class="col-8">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fa fa-address-card"></i>
          </div>
        </div> 
        <input id="nome" name="nome" required type="text" class="form-control" value="' . $row['nome'] .'">
      </div>
    </div>
  </div> 
  <div class="form-group row">
    <label for="descrizione" class="col-4 col-form-label">Text Area</label> 
    <div class="col-8">
      <textarea id="descrizione" name="descrizione" cols="40" rows="5" class="form-control">' . $row['descrizione'] .'</textarea>
    </div>
  </div> 
  <div class="form-group row">
    <div class="offset-4 col-8">
      <button name="submit" type="submit" class="btn btn-primary">' . $button .'</button>
    </div>
  </div>
</form>
';