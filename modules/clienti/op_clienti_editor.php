<?php

if (!$core)
    die("Accesso diretto rilevato");

if (!$user->validateLogin())
    return;

use framework\customers;

$this->title = 'WinConf - Editor clienti';

$action = $conf['URI'] . 'clienti/editor/?save';

$this->menuItems[] = '<a href="' . $conf['URI'] . 'clienti/">Clienti</a>';
$this->menuItems[] = '<a href="' . $conf['URI'] . 'clienti/editor/">Editor</a>';
$this->menuItems[] = '<em>Editor clienti</em>';

require_once 'lib/class_customers.php';
$customer = new customers();

$companyName            =   $core->in($_POST['companyName']);
$name                   =   $core->in($_POST['name']);
$surname                =   $core->in($_POST['surname']);
$addressStreet          =   $core->in($_POST['addressStreet']);
$addressNumber          =   $core->in($_POST['addressNumber']);
$addressCity            =   $core->in($_POST['addressCity']);
$addressProvince        =   $core->in($_POST['addressProvince']);
$addressPostalCode      =   $core->in($_POST['addressPostalCode']);
$codeERP                =   $core->in($_POST['codeERP']);
$VAT                    =   $core->in($_POST['VAT']);
$phone                  =   $core->in($_POST['phone']);
$fiscalCode             =   $core->in($_POST['fiscalCode']);
$email                  =   $core->in($_POST['email']);
$PEC                    =   $core->in($_POST['PEC']);
$bankName               =   $core->in($_POST['bankName']);
$iban                   =   $core->in($_POST['iban']);
$electronicInvoiceCode  =   $core->in($_POST['electronicInvoiceCode']);
$privacy                =   (int) $_POST['privacy'];
$privacySignature       =   $core->in($_POST['privacySignature']);


if (isset($_GET['save'])) {
    $this->noTemplateParse = true;
    $action = 'new';

    if (!isset($_GET['ID'])) {

        if ($customer->checkCustomerByVAT($VAT)) {
            echo 'Partita IVA già esistente.';
            return;
        }

        if (empty($companyName)) {
            echo 'Manca il nome della società.';
            return;
        }

        $query = 'INSERT INTO clienti
                    (
                         erp_ID,
                         company_ID,
                         ragione_sociale,
                         nome,
                         cognome,
                         codice_fiscale,
                         partita_iva,
                         telefono,
                         indirizzo_via,
                         indirizzo_numero,
                         indirizzo_citta,
                         indirizzo_provincia,
                         indirizzo_cap,
                         indirizzo_nazione,
                         banca_nome,
                         iban,
                         fatturazione_elettronica,
                         email,
                         pec,
                         privacy_data,
                         privacy,
                         privacy_firma 
                    ) VALUES (
                        \'' . $codeERP .'\',          
                        \'' . $user->company_ID .'\',          
                        \'' . $companyName .'\',          
                        \'' . $name .'\',          
                        \'' . $surname .'\',          
                        \'' . $fiscalCode .'\',          
                        \'' . $VAT .'\',          
                        \'' . $phone .'\',          
                        \'' . $addressStreet .'\',          
                        \'' . $addressNumber .'\',          
                        \'' . $addressCity .'\',          
                        \'' . $addressProvince .'\',          
                        \'' . $addressPostalCode .'\',          
                        \'italy\',                   
                        \'' . $bankName .'\',          
                        \'' . $iban .'\',          
                        \'' . $electronicInvoiceCode .'\',          
                        \'' . $email .'\',          
                        \'' . $PEC .'\',          
                        NOW(),                   
                        \'' . $privacy .'\',          
                        \'' . $privacySignature .'\'          
                    )  ';

        if (!$db->query($query)) {
            echo 'Query error. ' . $query;
            return;
        }

        echo '<div class="alert alert-success" role="alert">Cliente inserito con successo.</div>';

        return;
    } else {
        $ID = (int) $_GET['ID'];
        $customerAddresses = getCustomerAddresses($ID);
        $this->noTemplateParse = true;

        $query = "UPDATE clienti
                  SET
                         erp_ID             =   '{$codeERP}',
                         ragione_sociale      =   '{$companyName}',
                         nome               =   '{$name}',
                         cognome            =   '{$surname}',
                         codice_fiscale        =   '{$fiscalCode}',
                         partita_iva                =   '{$VAT}',
                         telefono              =   '{$phone}',
                         indirizzo_via     =   '{$addressStreet}',
                         indirizzo_numero     =   '{$addressNumber}',
                         indirizzo_citta       =   '{$addressCity}',
                         indirizzo_provincia       =   '{$addressProvince}',
                         indirizzo_cap   =   '{$addressPostalCode}',
                         banca_nome          =   '{$bankName}',
                         iban               =   '{$iban}',
                         fatturazione_elettronica =   '{$electronicInvoiceCode}',
                         email              =   '{$email}',
                         pec                =   '{$PEC}',
                         privacy            =   '{$privacy}',
                         privacy_firma  =   '{$privacySignature}',
                         is_updated         =    1,
                         ultimo_aggiornamento_data   =    NOW()
                  WHERE ID = $ID
                  AND company_ID = {$user->company_ID}
                  LIMIT 1";

            if (!$db->query($query)) {
                echo 'Query error. ' . $query;
                return;
            }

            echo '<div class="alert alert-success" role="alert">Cliente aggiornato con successo.</div>';
            return;

    }

}elseif (isset($_GET['ID'])) {
    $ID = (int) $_GET['ID'];
    $action = 'update';

    $customerAddresses = getCustomerAddresses($ID);

    $query = "SELECT * 
                  FROM clienti 
                  WHERE ID          =   $ID 
                  AND company_ID    =   {$user->company_ID}
                  LIMIT 1;";

    if (!$result = $db->query($query)) {
        echo 'Query error: ' . $query;
        return;
    }

    if (!$db->affected_rows) {
        echo 'Non esiste alcun cliente.';
        return;
    }

    $row = mysqli_fetch_assoc($result);
} else {
    $customerAddresses = 'Per creare delle destinazioni aggiuntive devi prima memorizzare il cliente.';
}

echo '
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>

<h1>Editor clienti</h1>';

$customerEditor = '
<div id="customerEditor">
  <div class="form-group row">
    <label for="codeERP" class="col-2 col-form-label">Codice Gestionale</label> 
    <div class="col-4">
      <input disabled value="' . $row['erp_ID'] . '" id="codeERP" name="codeERP" type="text" class="form-control">
    </div>
    <div class="col-4">
</div>
  </div>
  
  <div class="form-group row">
    <label for="companyName" class="col-2 col-form-label">Ragione sociale</label> 
    <div class="col-8">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fa fa-address-book-o"></i>
          </div>
        </div> 
        <input required="required" value="' . $row['ragione_sociale'] . '" id="companyName" name="companyName" placeholder="Ragione Sociale cliente" type="text" class="form-control">

      </div>
    </div>
  </div>
  
  <div class="form-group row">
    <label for="name" class="col-2 col-form-label">Nome</label> 
    <div class="col-3">
      <input  value="' . $row['nome'] . '" id="name" name="name" type="text" class="form-control">
    </div>
    
    <label for="surname" class="col-1 col-form-label">Cognome</label> 
    <div class="col-2">
      <input value="' . $row['cognome'] . '" id="surname" name="surname" type="text" class="form-control">
    </div>
  </div>
  
  <div class="form-group row">
    <label for="addressStreet" class="col-2 col-form-label">Via/piazza</label> 
    <div class="col-2">
      <input  required="required" value="' . $row['indirizzo_via'] . '" id="addressStreet" name="addressStreet" type="text" class="form-control">
    </div>
    
    <label for="addressStreet" class="col-xs-1 col-form-label">N.</label> 
    <div class="col-1">
      <input required="required" value="' . $row['indirizzo_numero'] . '" id="addressNumber" name="addressNumber" type="text" class="form-control">
    </div>
    
    <label for="addressCity" class="col-1 col-form-label">Città</label> 
    <div class="col-2">
      <input  required="required" value="' . $row['indirizzo_citta'] . '" id="addressCity" name="addressCity" type="text" class="form-control">
    </div>
    
    <label for="addressProvince" class="col-1 col-form-label">Prov</label> 
    <div class="col-1">
      <input  required="required" value="' . $row['indirizzo_provincia'] . '" id="addressProvince" name="addressProvince" type="text" class="form-control" maxlength="3">
      
    </div>
  </div>
  
  <div class="form-group row">
    <label for="addressPostalCode" class="col-2 col-form-label">Cap</label> 
    <div class="col-3">
      <input  required="required" value="' . $row['indirizzo_cap'] . '" id="addressPostalCode" name="addressPostalCode" type="text" class="form-control">
    </div>
  </div>
  
  <div class="form-group row">
    <label for="phone" class="col-2 col-form-label">Telefono</label> 
    <div class="col-8">
      <input required="required" value="' . $row['telefono'] . '" id="phone" name="phone" type="text" class="form-control">
    </div>
  </div>
  
  <div class="form-group row">
    <label for="VAT" class="col-2 col-form-label">Partita IVA</label> 
    <div class="col-6">
      <input  required="required" value="' . $row['partita_iva'] . '" id="VAT" name="VAT" placeholder="Partita  " type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="fiscalCode" class="col-2 col-form-label">Codice fiscale</label> 
    <div class="col-6">
      <input  required="required" value="' . $row['codice_fiscale'] . '" id="fiscalCode" name="fiscalCode" placeholder="Codice Fiscale" type="text" class="form-control">
    </div>
    <div class="col-md-2">
        <button onclick="$(\'#fiscalCode\').val( $(\'#VAT\').val() );" class="btn btn-secondary btn-sm">Copia da P.IVA</button>
    </div>
  </div>
  <div class="form-group row">
    <label for="email" class="col-2 col-form-label">Email</label> 
    <div class="col-8">
      <input  required="required" value="' . $row['email'] . '" id="email" name="email" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="PEC" class="col-2 col-form-label">PEC</label> 
    <div class="col-8">
      <input  required="required" value="' . $row['pec'] . '"  id="PEC" name="PEC" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="electronicInvoiceCode" class="col-2 col-form-label">Fatturazione Elettronica</label> 
    <div class="col-8">
      <input  required="required" value="' . $row['fatturazione_elettronica'] . '" id="electronicInvoiceCode" name="electronicInvoiceCode" type="text" class="form-control">
    </div>
  </div>
  
  <hr />
  <h3>Dati bancari</h3>
  <!-- <div class="form-group row">
    <label for="bankName" class="col-2 col-form-label">Istituto bancario</label> 
    <div class="col-8">
      <input value="' . $row['bank_name'] . '" id="bankName" name="bankName" type="text" class="form-control">
    </div>
  </div>
 -->
  <div class="form-group row">
    <label for="iban" class="col-2 col-form-label">IBAN</label> 
    <div class="col-8">
      <input  required="required" value="' . $row['iban'] . '" id="iban" name="iban" type="text" class="form-control">
    </div>
  </div>
 
  <hr />
  <h3>Trattamento dati</h3>
  <a name="privacy"></a>
  <div class="form-group row">
    <label for="privacy" class="col-2 col-form-label">Consenso privacy</label> 
    <div class="col-8"> <!--
      <select id="privacy" name="privacy" class="custom-select">
        <option ' . ( (int) $row['privacy'] === 0 ? ' selected ' : '' )  . ' value="0">non acconsento al trattamento dei dati</option>
        <option ' . ( (int) $row['privacy'] === 1 ? ' selected ' : '' )  . ' value="1">acconsento al trattamento dei dati</option>
      </select> -->
        <input ' . ( (int) $row['privacy'] === 0 ? ' checked ' : '' )  . ' type="radio" id="privacyKO" name="privacy" value="0">
        <label for="privacyKO">non acconsento al trattamento dei dati</label>
        
        <input ' . ( (int) $row['privacy'] === 1 || is_null($row['privacy']) ? ' checked ' : '' )  . ' type="radio" id="privacyOK" name="privacy" value="1">
        <label for="privacyOK">acconsento al trattamento dei dati</label>
    </div>
  </div> 
  
  <div class="form-group row">
    <label for="signatureCanvas" class="col-2 col-form-label">Firma privacy</label> 
    <div class="col-8">
        <canvas style="border: 4px solid gray; width: 100%" id="signatureCanvas">
            
        </canvas>
        <button onclick="signaturePad.clear();">Cancella firma</button>
    </div>
  </div> 
  
  <div class="form-group row">
    <div class="offset-4 col-8 pull-right">
      <button id="btnSendCustomer" onclick="sendData();" name="submit" type="submit" class="btn btn-primary">Aggiungi/Aggiorna cliente</button>
    </div>
  </div>
</div>

<div id="crudResult">
</div>';


echo '<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Anagrafica</a>
    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Destinazioni</a>

  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">' . $customerEditor . '</div>
  <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
    <h3>Destinazioni merce per cliente</h3>';

if ($isNewCustomer !== true) {
    echo '<div class="float-right">
            <a class="btn btn-primary" href="' . $conf['URI'] . 'clienti/addresses/editor/?op=new&ID=' . ( (int) $_GET['ID'] ) . '">Nuova destinazione</a>
          </div>';
}
    echo $customerAddresses . '
  </div>
</div>

<script>
var canvas = document.querySelector("#signatureCanvas");

var signaturePad = new SignaturePad(canvas);
signaturePad.backgroundColor = "rgb(190, 190, 190)";
signaturePad.minWidth = 1;
signaturePad.maxWidth = 3;
signaturePad.penColor = "rgb(20, 20, 20)";';

if (isset($_GET['ID']))
    echo 'signaturePad.fromDataURL("' . $row['privacy_signature'] . '")';

echo '
function sendData()
{
    customerErrors = false;
    $("#customerEditor input:required").each(function() {
      if ($(this).val() === "") {
        customerErrors = true;
        $(this).css("background-color", "#FBB");
      }    
    });

    if (customerErrors === true) {
        alert("Per favore, compila tutti i campi!");
        return;
    }
    
    $("#btnSendCustomer").prop("disabled", true);
    companyName             = $("#companyName").val();
    name                    = $("#name").val();
    surname                 = $("#surname").val();
    addressStreet           = $("#addressStreet").val();
    addressNumber           = $("#addressNumber").val();
    addressCity             = $("#addressCity").val();
    addressProvince         = $("#addressProvince").val();
    addressPostalCode       = $("#addressPostalCode").val();
    codeERP                 = $("#codeERP").val();
    phone                   = $("#phone").val();
    VAT                     = $("#VAT").val();
    fiscalCode              = $("#fiscalCode").val();
    email                   = $("#email").val();
    PEC                     = $("#PEC").val();
    electronicInvoiceCode   = $("#electronicInvoiceCode").val();
    bankName                = $("#bankName").val();
    iban                    = $("#iban").val();
    privacy                 = $(\'input[name=privacy]:checked\').val();
    privacySignature        = signaturePad.toDataURL();
    
    postURI                 = "' . $conf['URI'] .  '/clienti/editor/?save' . ($action === 'update' ? '&ID=' . $ID : '') .'";
    
    $.post(postURI , { companyName          : companyName, 
                       name                 : name, 
                       surname              : surname, 
                       codeERP              : codeERP, 
                       addressStreet        : addressStreet,
                       addressNumber        : addressNumber,
                       addressCity          : addressCity,
                       addressProvince      : addressProvince,
                       addressPostalCode    : addressPostalCode ,
                       phone                : phone, 
                       VAT                  : VAT, 
                       fiscalCode           : fiscalCode, 
                       email                : email, 
                       PEC                  : PEC, 
                       bankName             : bankName, 
                       iban                 : iban, 
                       electronicInvoiceCode: electronicInvoiceCode, 
                       privacy              : privacy, 
                       privacySignature     : privacySignature })
    .done(function( data ) {
       $("#crudResult").html(data);
    });
}

function resizeCanvas() 
{
    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
    signaturePad.clear(); // otherwise isEmpty() might return incorrect value
}

window.addEventListener("resize", resizeCanvas);
resizeCanvas();

</script>';

function getCustomerAddresses(int $ID) :string
{
    global $db;
    global $user;
    global $conf;

    $query = 'SELECT * 
          FROM clienti_destinazioni
          WHERE cliente_ID = ' . $ID . '
            AND company_ID = ' . $user->company_ID;

    if (!$result = $db->query($query)) {
        return 'Query error. ' . $query;

    }

    if (!$db->affected_rows) {
        return 'Il cliente non possiede destinazioni.';
    } else {

        $return = '
        <table class="table table-bordered table-striped table-condensed">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Business name</th>
                    <th>P.IVA</th>
                    <th>Città</th>
                    <th>Provincia</th>
                    <th>Nazione</th>
                    <th>Operazioni</th>
                </tr>    
            </thead>    
            <tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            $return .= '<tr>
                <td>' . $row['ID'] . '</td>
                <td>' . $row['ragione_sociale'] . '</td>
                <td>' . $row['vat'] . '</td>
                <td>' . $row['address_city'] . '</td>
                <td>' . $row['address_town'] . '</td>
                <td>' . $row['address_nation'] . '</td>
                <td> <a href="' . $conf['URI'] . 'clienti/addresses/editor/?op=edit&ID=' . $row['ID'] . '">Modifica</a></td>
              </tr>';
        }
        $return .= '</tbody>
    </table>';

        return $return;
    }
}