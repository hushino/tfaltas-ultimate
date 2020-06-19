<?php require_once('../Connections/tfx.php'); ?>
<?php
if (!isset($_SESSION)) {
    session_start();
}
$MM_authorizedUsers = "3,4,5";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup)
{
    // For security, start by assuming the visitor is NOT authorized.
    $isValid = False;

    // When a visitor has logged into this site, the Session variable MM_Username set equal to their username.
    // Therefore, we know that a user is NOT logged in if that Session variable is blank.
    if (!empty($UserName)) {
        // Besides being logged in, you may restrict access to only certain users based on an ID established when they login.
        // Parse the strings into arrays.
        $arrUsers = Explode(",", $strUsers);
        $arrGroups = Explode(",", $strGroups);
        if (in_array($UserName, $arrUsers)) {
            $isValid = true;
        }
        // Or, you may restrict access to only certain users based on their username.
        if (in_array($UserGroup, $arrGroups)) {
            $isValid = true;
        }
        if (($strUsers == "") && false) {
            $isValid = true;
        }
    }
    return $isValid;
}

$MM_restrictGoTo = "../ronglogin.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {
    $MM_qsChar = "?";
    $MM_referrer = $_SERVER['PHP_SELF'];
    if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
    if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0)
        $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
    $MM_restrictGoTo = $MM_restrictGoTo . $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
    header("Location: " . $MM_restrictGoTo);
    exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    {
        if (PHP_VERSION < 6) {
            $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
        }

        $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }
        return $theValue;
    }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formulario")) {
    $insertSQL = sprintf("INSERT INTO causas (DEPORIG, INFACTA, INFFECHA, INFHORA, INFLUG, INFART, INFVEHI, INFAUTOPAT, INFOBSER, APELLIDOS, NOMBRES, DNI, DIRCALLE, DIRNRO, DIRPISO, DIRDPTO, DIRMZ, DIRCS, DIRMBK, LOCALIDAD, DESCPROVINCIA, OBLADA, DNINAC, RETLICE, RETVEHI,FECHASIEN,OBLATIPO,OBLANRO,USUR) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, now(),%s, %s, %s)",
        GetSQLValueString($_POST['deporig'], "text"),
        GetSQLValueString($_POST['infacta'], "text"),
        GetSQLValueString($_POST['inffecha'], "date"),
        GetSQLValueString($_POST['infhora'], "date"),
        GetSQLValueString($_POST['influg'], "text"),
        GetSQLValueString($_POST['infart'], "text"),
        GetSQLValueString($_POST['infvehi'], "text"),
        GetSQLValueString($_POST['infautopat'], "text"),
        GetSQLValueString($_POST['infobser'], "text"),
        GetSQLValueString($_POST['apellidos'], "text"),
        GetSQLValueString($_POST['nombres'], "text"),
        GetSQLValueString($_POST['dni'], "text"),
        GetSQLValueString($_POST['dirCalle'], "text"),
        GetSQLValueString($_POST['dirNro'], "text"),
        GetSQLValueString($_POST['dirPiso'], "text"),
        GetSQLValueString($_POST['dirDpto'], "text"),
        GetSQLValueString($_POST['dirMz'], "text"),
        GetSQLValueString($_POST['dirCs'], "text"),
        GetSQLValueString($_POST['dirMbk'], "text"),
        GetSQLValueString($_POST['localidad'], "text"),
        GetSQLValueString($_POST['descProvincia'], "text"),
        GetSQLValueString($_POST['oblada'], "text"),
        GetSQLValueString($_POST['dninac'], "text"),
        GetSQLValueString($_POST['retlice'], "text"),
        GetSQLValueString($_POST['retvehi'], "text"),
        GetSQLValueString($_POST['infpago'], "text"),
        GetSQLValueString($_POST['obladarec'], "text"),
        GetSQLValueString($_SESSION['MM_Username'], "text"));
    $insertSQL2 = "CALL ponerexpte()";
    mysql_select_db($database_tfx, $tfx);
    $Result1 = mysql_query($insertSQL, $tfx) or die(mysql_error());
    $Result2 = mysql_query($insertSQL2, $tfx) or die(mysql_error());

    $insertGoTo = "../sitecomp.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_tfx, $tfx);
$query_opvehi = "SELECT IDC, CAT, DETALLES FROM opciones WHERE CAT = 'vehiculo'";
$opvehi = mysql_query($query_opvehi, $tfx) or die(mysql_error());
$row_opvehi = mysql_fetch_assoc($opvehi);
$totalRows_opvehi = mysql_num_rows($opvehi);

mysql_select_db($database_tfx, $tfx);
$query_depend = "SELECT IDC, CAT, DETALLES FROM opciones WHERE CAT = 'dependenci'";
$depend = mysql_query($query_depend, $tfx) or die(mysql_error());
$row_depend = mysql_fetch_assoc($depend);
$totalRows_depend = mysql_num_rows($depend);

mysql_select_db($database_tfx, $tfx);
$query_oppago = "SELECT IDC, CAT, DETALLES FROM opciones WHERE CAT = 'pagomedio'";
$oppago = mysql_query($query_oppago, $tfx) or die(mysql_error());
$row_oppago = mysql_fetch_assoc($oppago);
$totalRows_oppago = mysql_num_rows($oppago);

?>

<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>T Faltas - Infracciones</title>

  <?php require_once('../modules/header.php'); ?>

  <link href="../css/tfaltas.css" rel="stylesheet" type="text/css">
  <link href="../css/validationengine.jquery.css" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="../js/jquery.js"></script>
  <script type="text/javascript" src="../js/jquery.tokenjuzg.js"></script>
  <script type="text/javascript" src="../js/jquery.validaracta.js"></script>
  <script language="javascript" type="text/javascript" src="../js/disableSubmits.js"></script>
  <link rel="stylesheet" href="../css/token-input.css" type="text/css" />
  <link rel="stylesheet" href="../css/token-input-facebook.css" type="text/css" />
  <link rel="stylesheet" href="../css/token-input-mac.css" type="text/css" />
  <link href="../css/barra.css" rel="stylesheet" type="text/css">
  <script type="text/javascript">
  $(document).ready(function() {
    $("#infartx").tokenInput("/tfaltas/consultas/listadeinfracciones.php", {
      tokenLimit: 50//7
    });
  });

  function showContent() {
    element = document.getElementById("contoblada");
    check = document.getElementById("oblada");
    if (check.checked) {
      element.style.display = 'block';
      $('#obladarec').prop('required', true);
    } else {
      element.style.display = 'none';
      $('#obladarec').prop('required', false);
    }
  }
  </script>

</head>

<body>
  <?php include('../barra.php'); ?>
  <div id="contenido" class="container-fluid">
    <h5>CARGAR DATOS DE LA INFRACCION
      <!--  Breadcrumb de Navegación (inicio) --> DE TRANSITO</h5>
    <div class="breadCrumbHolder breadCrumbModule">
      <div id="breadCrumb0" class="breadCrumb breadCrumbModule">
      </div>
    </div>
    <!--  Breadcrumb de Navegación (fin) -->

    <!-- Contenido (inicio) -->

    <div class="row">

      <form class="text-center p-5" name="formulario" id="formulario" action="<?php echo $editFormAction; ?>"
        method="POST">
        <p class="h4 mb-4">Datos de la Falta Cometida</p>
        <div class="col">
          <div class="col">
            <label for="basic-url">Seleccionar Dependencia de Origen:</label>
            <div class="input-group mb-3">
              <select class="browser-default custom-select" name="deporig" id="deporig">
                <?php do { ?>
                <option value="<?php echo $row_depend['IDC'] ?>"><?php echo $row_depend['DETALLES'] ?></option>
                <?php
                    } while ($row_depend = mysql_fetch_assoc($depend));
                    $rows = mysql_num_rows($depend);
                    if ($rows > 0) {
                        mysql_data_seek($depend, 0);
                        $row_depend = mysql_fetch_assoc($depend);
                    }
                    ?>
              </select>
            </div>
          </div>
          <div class="col">
            <label for="basic-url">Acta de Inf. Nº</label>
            <div class="input-group mb-3">
            <!-- disabled -->
              <input name="infacta" id="infacta" type="text"
                id="infacta" class="form-control" aria-describedby="basic-addon3" onFocusOut="javascript:verificar()"
                autocomplete="off" />
              <div id="final"></div>
            </div>
          </div>
          <div class="col">
            <label for="basic-url">Nº de expediente</label>
            <div class="input-group mb-3">
              <input class="form-control" name="IDx" autofocus type="text"
                id="IDxx" />
            </div>
          </div>
          <div class="form-row mb-4">
            <div class="col">
              <label for="basic-url">Fecha</label>
              <div class="input-group">
                <input class="form-control" name="inffecha" type="date"  id="infdate" />
              </div>
            </div>
            <div class="col">
              <label for="basic-url">Hora</label>
              <div class="input-group ">
                <input class="form-control" name="infhora" type="time"  id="infhora"
                  autocomplete="off" />
              </div>
            </div>
          </div>

          <div class="col">
            <label for="basic-url">Lugar en que se cometió la falta</label>
            <div class="input-group mb-3">
              <input class="form-control" name="influg" type="text" id="influg" />
            </div>
          </div>
          <div class="col">
            <label for="basic-url">Infracción</label>
            <div class="input-group mb-3">
              <input class="form-control" name="infart" type="text" id="infartx" />
            </div>
          </div>

          <div class="form-row mb-4">
            <div class="col">
              <label for="basic-url">Datos del Vehículo:</label>
              <div class="input-group mb-3">
                <select class="browser-default custom-select" name="infvehi" id="infvehi">
                  <?php do { ?>
                  <option value="<?php echo $row_opvehi['IDC'] ?>"><?php echo $row_opvehi['DETALLES'] ?></option>
                  <?php
                        } while ($row_opvehi = mysql_fetch_assoc($opvehi));
                            $rows = mysql_num_rows($opvehi);
                            if ($rows > 0) {
                              mysql_data_seek($opvehi, 0);
                              $row_opvehi = mysql_fetch_assoc($opvehi);
                        }
                    ?>
                </select>
              </div>
            </div>
            <div class="col">
              <label for="basic-url">Dominio</label>
              <div class="input-group mb-3">
                <input class="form-control" name="infautopat" type="text" id="infautopat"
                  title="sólo 7 letras o números. Las letras en mayúsculas" maxlength="7"
                  onkeyup="javascript:this.value=this.value.toUpperCase();" autocomplete="off" />
              </div>
            </div>
          </div>
          <div class="col">
            <label for="basic-url">Oblada</label>
            <div class="custom-control custom-checkbox">
              <input value="S" onchange="javascript:showContent()" name="oblada" type="checkbox" id="oblada"
                class="custom-control-input" title="Marcar si surge pago espontáneo del acta">
              <label class="custom-control-label" for="oblada">Marcar si surge pago espontáneo del
                acta</label>
            </div>
            <div class="w-100"></div>
            <div id="contoblada" style="display: none;">
              <div class="col">
                <select class="browser-default custom-select" name="infpago" id="infpago">
                  <?php do { ?>
                  <option value="<?php echo $row_oppago['IDC'] ?>"><?php echo $row_oppago['DETALLES'] ?></option>
                  <?php
                      } while ($row_oppago = mysql_fetch_assoc($oppago));
                      $rows = mysql_num_rows($oppago);
                      if ($rows > 0) {
                      mysql_data_seek($oppago, 0);
                      $row_oppago = mysql_fetch_assoc($oppago);
                     }
                   ?>
                </select>
              </div>
              <div class="col">
                <label for="basic-url">Recibo</label>
                <div class="input-group">
                  <input class="form-control" name="obladarec" type="text" id="obladarec"
                    title="Ingresar el Número de Recibo." />
                </div>
              </div>
            </div>
          </div>

          <div class="col">
            <div class="custom-control custom-checkbox">
              <input class="custom-control-input" name="retlice" type="checkbox" id="retlice" value="S" />
              <label class="custom-control-label" for="retlice">Retención Licencia</label>
            </div>
          </div>
          <div class="col">
            <div class="custom-control custom-checkbox">
              <input class="custom-control-input" name="retvehi" type="checkbox" id="retvehi" value="S" />
              <label class="custom-control-label" for="retvehi">Retención de Vehículo</label>
            </div>
          </div>


          <div class="col">
            <label for="basic-url">Observaciones</label>
            <div class="input-group">
              <input class="form-control" name="infobser" type="text" id="infobser" />
            </div>
          </div>
        </div>
        <p class="h4 mb-4">Datos del Infractor</p>

        <div class="col">
          <legend class="ui-widget-header ui-corner-all ui-state-highlight" style="padding:1px 10px;"
            id="grupoContacto">
            Datos Personales
          </legend>
          <div class="form-row mb-4">
            <div class="col">
              <label for="basic-url">Apellidos</label>
              <div class="input-group">
                <input class="form-control" autocomplete="off" name="apellidos" type="text" id="apellido" />
              </div>
            </div>
            <div class="col">
              <label for="basic-url">Nombres</label>
              <div class="input-group">
                <input class="form-control" autocomplete="off" name="nombres" type="text" id="nombres" />
              </div>
            </div>
          </div>
          <div class="form-row mb-4">
            <div class="col">
              <label for="basic-url">D.N.I.</label>
              <div class="input-group">
                <input class="form-control" autocomplete="off" name="dni" type="text" id="dni"
                  onFocusOut="javascript:verificardni()" />
              </div>
            </div>
            <div class="col">
              <label for="basic-url">Doc.Nac.</label>
              <div class="input-group">
                <input class="form-control" autocomplete="off" pattern="[A-Z]{3}" value="ARG" name="dninac" type="text"
                  id="dninac" />
              </div>
            </div>
          </div>
        </div>



        <div class="col">
          <legend class="ui-widget-header ui-corner-all ui-state-highlight" style="padding:1px 10px;"
            id="grupoContacto">
            Domicilio
          </legend>



          <div class="form-row mb-4">
            <div class="col">
              <label for="basic-url">Calle o Barrio</label>
              <div class="input-group">
                <input class="form-control" name="dirCalle" type="text" id="dirCalle" />
              </div>
            </div>
            <div class="col">
              <label for="basic-url">Nro.</label>
              <div class="input-group">
                <input class="form-control" name="dirNro" type="text" id="dirNro" />
              </div>
            </div>
            <div class="col">
              <label for="basic-url">Piso</label>
              <div class="input-group">
                <input class="form-control" name="dirPiso" type="text" id="dirPiso" />
              </div>
            </div>
          </div>

          <div class="form-row mb-4">
            <div class="col">
              <label for="basic-url">Dpto.</label>
              <div class="input-group">
                <input class="form-control" name="dirDpto" type="text" id="dirDpto" />
              </div>
            </div>
            <div class="col">
              <label for="basic-url">Mz.</label>
              <div class="input-group">
                <input class="form-control" name="dirMz" type="text" id="dirMz" />
              </div>
            </div>
            <div class="col">
              <label for="basic-url">Casa</label>
              <div class="input-group">
                <input class="form-control" name="dirCs" type="text" id="dirCs" />
              </div>
            </div>
          </div>

          <div class="form-row mb-4">
            <div class="col">
              <label for="basic-url">Mbk.</label>
              <div class="input-group">
                <input class="form-control" name="dirMbk" type="text" id="dirMbk" />
              </div>
            </div>
            <div class="col">
              <label for="basic-url">Localidad</label>
              <div class="input-group">
                <input class="form-control" value="Clorinda" name="localidad" type="text" id="localidad" />
              </div>
            </div>
            <div class="col">
              <label for="basic-url">Provincia</label>
              <div class="input-group">
                <input class="form-control" type="hidden" name="idProvincia" id="idProvincia" />
                <input class="form-control" name="descProvincia" type="text" id="descProvincia" style="width:200px;"
                  tabindex="20" value="Formosa" />
              </div>
            </div>
          </div>
        </div>

        <button type="button" name="button" id="button" onclick="history.back()"
          class="btn btn-danger btn-rounded">Volver
        </button>

        <button class="btn btn-success btn-rounded" type="submit" name="submit" id="submit">Ingresar
          infraccion
        </button>
        <input type="hidden" name="MM_insert" value="formulario">
      </form>



    </div>


  </div>

</body>

</html>
<?php
mysql_free_result($opvehi);

mysql_free_result($depend);
?>