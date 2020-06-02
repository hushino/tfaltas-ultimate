<?php require_once('../Connections/tfx.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "5";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
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

$MM_restrictGoTo = "../index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario")) {
  $updateSQL = sprintf("UPDATE listainfractor SET NRORESOL=%s, APELLIDOS=%s, NOMBRES=%s, DNI=%s, TIPINFRAC=%s, NROCAUSA=%s, RESFECHA=%s, INFRACC=%s, DEPORIG=%s, DNINAC=%s WHERE IDRES=%s",
                       GetSQLValueString($_POST['resolnro'], "text"),
                       GetSQLValueString($_POST['apellidos'], "text"),
                       GetSQLValueString($_POST['nombres'], "text"),
                       GetSQLValueString($_POST['dni'], "text"),
                       GetSQLValueString($_POST['RadioGroup1'], "text"),
                       GetSQLValueString($_POST['IDx'], "int"),
                       GetSQLValueString($_POST['resolfecha'], "date"),
                       GetSQLValueString($_POST['infart'], "text"),
                       GetSQLValueString($_POST['deporig'], "text"),
                       GetSQLValueString($_POST['dninac'], "text"),
                       GetSQLValueString($_POST['idres'], "int"));
$updateSQL2 = sprintf("UPDATE causas SET RES_DEFINI = 'S', RES_DEFININRO = %s WHERE ID = %s",
                       GetSQLValueString($_POST['resolnro'], "text"),
                       GetSQLValueString($_POST['IDx'], "int"));

  mysql_select_db($database_tfx, $tfx);
  $Result1 = mysql_query($updateSQL, $tfx) or die(mysql_error());
  $Result2 = mysql_query($updateSQL2, $tfx) or die(mysql_error());

  $updateGoTo = "listaderes.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
$colname_cauresol = "-1";
if (isset($_GET['idres'])) {
  $colname_cauresol = $_GET['idres'];
}
mysql_select_db($database_tfx, $tfx);
$query_cauresol = sprintf("SELECT * FROM listainfractor WHERE IDRES = %s", GetSQLValueString($colname_cauresol, "int"));
$cauresol = mysql_query($query_cauresol, $tfx) or die(mysql_error());
$row_cauresol = mysql_fetch_assoc($cauresol);
$totalRows_cauresol = mysql_num_rows($cauresol);

mysql_select_db($database_tfx, $tfx);
$query_depend = "SELECT IDC, CAT, DETALLES FROM opciones WHERE CAT = 'dependenci'";
$depend = mysql_query($query_depend, $tfx) or die(mysql_error());
$row_depend = mysql_fetch_assoc($depend);
$totalRows_depend = mysql_num_rows($depend);

$IDXX_multas = "-1";
if (isset($_GET['idres'])) {
  $IDXX_multas = $_GET['idres'];
}
mysql_select_db($database_tfx, $tfx);
$query_multas = sprintf("SELECT IDINF, CONCAT(art, ' - ',HIPOTESIS) AS names FROM infrac WHERE (SELECT INFRACC FROM listainfractor WHERE IDRES=%s) LIKE CONCAT('%%',IDINF,'%%')", GetSQLValueString($IDXX_multas, "int"));
$multas = mysql_query($query_multas, $tfx) or die(mysql_error());
$row_multas = mysql_fetch_assoc($multas);
$totalRows_multas = mysql_num_rows($multas);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>T Faltas - Infracciones</title>
<link href="../css/tfaltas.css" rel="stylesheet" type="text/css">
<link href="../css/validationengine.jquery.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery.tokenjuzg.js"></script>
<script type="text/javascript" src="../js/jquery.validaracta.js"></script>
<link rel="stylesheet" href="../css/token-input.css" type="text/css" />
<link rel="stylesheet" href="../css/token-input-facebook.css" type="text/css" />
<link rel="stylesheet" href="../css/token-input-mac.css" type="text/css" />
<link href="../css/barra.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
        $(document).ready(function() {
            $("#infartx").tokenInput("/tfaltas/consultas/listadeinfracciones.php", {
                prePopulate: <?php
                echo "["; do {
	echo "{\"id\": \"".$row_multas['IDINF']."\", \"name\": \"".$row_multas['names']."\"}, ";
} while($row_multas = mysql_fetch_assoc($multas));
echo "]";   ?>
				,
				preventDuplicates: true,
				tokenLimit: 7
            });
        });
</script>

</head>

<body>
<?php include('../barra.php'); ?>
<div id="contenido">
CARGAR RESOLUCION 
		<div class="breadCrumbHolder breadCrumbModule">
		<div id="breadCrumb0" class="breadCrumb breadCrumbModule"> 
		</div>
		</div>
<!--  Breadcrumb de Navegación (fin) -->
		
<!-- Contenido (inicio) -->

<div id="div_formulario">
  <form name="formulario" id="formulario" action="<?php echo $editFormAction; ?>" method="POST">
  
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="width: 500px; margin-left: auto; margin-right: auto;">
<legend class="ui-widget-header ui-corner-all ui-state-highlight" style="padding:1px 10px;" id="grupoContacto">Datos de la Falta Cometida</legend>
<table width="400" border="0" cellspacing="1" class="formulario" style="width: 95%; text-align: right;">
	<tr>
	  <td style="width: 200px; text-align: right;"><label for="deporig">
	    <input name="idres" type="hidden" id="idres" value="<?php echo $row_cauresol['IDRES']; ?>">
	    Seleccionar Dependencia de Origen:</label></td>
	  <td align="left" style="width: 200px;"><span style="width: 200px; text-align: right;">
	    <input name="deporig" id="deporig" type="text" required="required" style="width:100px;" tabindex="1" autocomplete="off" value="<?php echo $row_cauresol['DEPORIG']; ?>" readonly />
	  </span></td>
	  </tr>
	<tr id="filaActaInfr">
	  <td style="width: 200px; text-align: right;"> 
	    Nº de expediente:
	    <input name="IDx" type="text" required="required" id="IDxx" style="width:100px;" tabindex="1" autocomplete="off" value="<?php echo $row_cauresol['NROCAUSA']; ?>" readonly /></td>
	  <td style="width: 200px;"><span style="width:100px;">Acta de Inf. Nº</span>	    <input name="infacta" type="text" required="required" id="infacta" style="width:100px;" tabindex="1" autocomplete="off" readonly onFocusOut="javascript:verificar()" /><div id="final"></div></td></tr>
	<tr id="filaActaInfr">
	  <td style="width: 200px; text-align: right;">Fecha	    </td>
	  <td style="width: 200px; text-align: right;"><b>Hora</b></td></tr>
      <tr>
        <td colspan="2">Lugar en que se cometió la falta          </td>
      </tr>
    <tr>
      <td colspan="2" align="left" style="width:auto;">Infracción<br>
        <input name="infart" type="text" id="infartx" /></td
        >
    </tr>
      </table>
</fieldset>

  <br />
<div style="">
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="width: auto; margin-left: auto; margin-right: auto;">
<legend class="ui-widget-header ui-corner-all ui-state-highlight" style="padding:1px 10px;" id="grupoApellidoNombre">Datos del Infractor</legend>

<fieldset class="ui-widget ui-widget-content ui-corner-all" style="width: 200px; margin-left: auto; margin-right: auto;">
<legend class="ui-widget-header ui-corner-all ui-state-highlight" style="padding:1px 10px;" id="grupoDomicilio">Datos Para el Registro de Infractores</legend><table class="formulario" style="width: 45%; text-align: justify;">
	<tr>
	  <th style="width:40px;" id="tituloApellido3">Nro. de resolución</th>
	  <td><input name="resolnro" type="text" autofocus required id="resolnro" style="width:205px;" tabindex="9" autocomplete="off" value="<?php echo $row_cauresol['NRORESOL']; ?>" /></td>
	  </tr>
	<tr>
	  <th style="width:40px;" id="tituloApellido2">Fecha</th>
	  <td><input name="resolfecha" type="date" required id="resolfecha" style="width:205px;" tabindex="9" autocomplete="off" value="<?php echo $row_cauresol['RESFECHA']; ?>" /></td>
	  </tr>
	<tr>
	  <th style="width:40px;" id="tituloApellido4">&nbsp;</th>
	  <td><p>
	    <label>
	      <input <?php if (!(strcmp($row_cauresol['TIPINFRAC'],"Leve"))) {echo "checked=\"checked\"";} ?> type="radio" name="RadioGroup1" value="Leve" id="RadioGroup1_0">
	      Leve</label>
	    
	    <label>
	      <input <?php if (!(strcmp($row_cauresol['TIPINFRAC'],"Grave"))) {echo "checked=\"checked\"";} ?> type="radio" name="RadioGroup1" value="Grave" id="RadioGroup1_1">
	      Grave</label>
	    <br>
	    </p></td>
	  </tr>
	<tr>
	  <th style="width:40px;" id="tituloApellido">Apellidos</th><td><input name="apellidos" type="text" id="apellido" style="width:205px;" tabindex="9" autocomplete="off" value="<?php echo $row_cauresol['APELLIDOS']; ?>" /></td></tr>
	<tr>
	  <th style="width:40px;">Nombres</th><td><input name="nombres" type="text" id="nombres" style="width:205px;" tabindex="10" autocomplete="off" value="<?php echo $row_cauresol['NOMBRES']; ?>" /></td></tr>
      <tr>
        <th style="width:40px;">D.N.I.</th>
        <td><input name="dni" type="text" id="dni" style="width:85px;" tabindex="11" autocomplete="off" value="<?php echo $row_cauresol['DNI']; ?>" /></td>
      </tr>
      <tr>
	  <th style="width:50px;">Doc.Nac.</th><td><input name="dninac" type="text" id="dninac" style="width: 50px; float: left;" pattern="[A-Z]{3}" tabindex="11" autocomplete="off" value="<?php echo $row_cauresol['DNINAC']; ?>" /></td></tr>  
</table></fieldset></fieldset>
</div>
<br />

<table align="center" border="0">
	<tr>
		<td><input type="button" name="button" id="button" value="Volver"></td>
		<td><input type="submit" name="submit" id="submit" value="Actualizar" tabindex="18" ></td>
	</tr>
</table>
<input type="hidden" name="MM_update" value="formulario">
  </form>
</div>

<div id="div_mensaje" align="center" style="display:none;padding:30px;"></div>
<table align="center" id="controles_mensaje" style="display:none;">
	<tr>
		<td><a href="../personas/verMenuEmpleado.do" id="btn_volver_menu">Menú Principal</a></td>
	</tr>
</table>
</div>

</body>
</html>
<?php
mysql_free_result($cauresol);

mysql_free_result($depend);

mysql_free_result($multas);
?>
