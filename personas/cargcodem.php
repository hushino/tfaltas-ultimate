<?php require_once('../Connections/tfx.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "3,4,5";
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

$MM_restrictGoTo = "../ronglogin.php";
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
$colname_cosvehi = "-1";
if (isset($_GET['exp'])) {
  $colname_cosvehi = $_GET['exp'];
}
mysql_select_db($database_tfx, $tfx);
$query_cosvehi = sprintf("SELECT * FROM causas WHERE ID = %s", GetSQLValueString($colname_cosvehi, "int"));
$cosvehi = mysql_query($query_cosvehi, $tfx) or die(mysql_error());
$row_cosvehi = mysql_fetch_assoc($cosvehi);
$totalRows_cosvehi = mysql_num_rows($cosvehi);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$sddd = $_GET['exp'];
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
  $insertSQL = sprintf("INSERT INTO causas_codem(APELLIDOS,NOMBRES,DNI,DIRCALLE,DIRNRO,DIRPISO,DIRDPTO,DIRMZ,DIRCS,DIRMBK,LOCALIDAD,DESCPROVINCIA,DNINAC,USUR,COOEXPTE) VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
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
                       GetSQLValueString($_POST['dninac'], "text"),
                       GetSQLValueString($_SESSION['MM_Username'], "text"),
                       $sddd);

  mysql_select_db($database_tfx, $tfx);
  $Result1 = mysql_query($insertSQL, $tfx) or die(mysql_error());

echo "<script>window.opener.location.reload(); window.close();</script>";exit;
}


?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Verificar datos de la Persona</title>
<script language="javascript" type="text/javascript" src="../js/disableSubmits.js"></script>
</head>

<body>
<div>
<form method="POST" action="<?php echo $editFormAction; ?>" name="form">
<div style="">
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="width: auto; margin-left: auto; margin-right: auto;">
<legend class="ui-widget-header ui-corner-all ui-state-highlight" style="padding:1px 10px;" id="grupoApellidoNombre">Datos del Infractor</legend>
<div style="width: 50%; float:left">
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="width: 200px; margin-left: auto; margin-right: auto;">
<legend class="ui-widget-header ui-corner-all ui-state-highlight" style="padding:1px 10px;" id="grupoDomicilio">Datos Personales</legend><table class="formulario" style="width: 45%; text-align: justify;">
	<tr>
	  <th style="width:40px;" id="tituloApellido">Apellidos</th><td><input name="apellidos" type="text" id="apellido" style="width:205px;" tabindex="9" autocomplete="off" autofocus /></td></tr>
	<tr>
	  <th style="width:40px;">Nombres</th><td><input name="nombres" type="text" id="nombres" style="width:205px;" tabindex="10" autocomplete="off" /></td></tr>
      <tr>
        <th style="width:40px;">D.N.I.</th>
        <td><input name="dni" type="text" id="dni" style="width:85px;" tabindex="11" autocomplete="off" onFocusOut="javascript:verificardni()" /><div id="dnifinal"></div></td>
      </tr>
      <tr>
	  <th style="width:50px;">Doc.Nac.</th><td><input name="dninac" type="text" id="dninac" style="width: 50px; float: left;" pattern="[A-Z]{3}" tabindex="11" autocomplete="off" value="ARG" /></td></tr>
</table></fieldset></div>
<div style="width: 50%; float: right;">
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="width: 250px; margin-left: auto; margin-right: auto;">
<legend class="ui-widget-header ui-corner-all ui-state-highlight" style="padding:1px 10px;" id="grupoDomicilio">Domicilio</legend>
<table class="formulario" style="width:350px;">

	<tr>
	  <th style="width:30px;">Calle o Barrio</th><td><input name="dirCalle" type="text" id="dirCalle" style="width:200px;" tabindex="12" autocomplete="off" /></td></tr>
	<tr>
		<th style="width:30px;">Nro.</th>
                <td style="width:200px;">
                       <input name="dirNro" type="text" id="dirNro" style="width:36px;" tabindex="13" autocomplete="off" />
		       <b>&nbsp;Piso&nbsp;</b>
                       <input name="dirPiso" type="text" id="dirPiso" style="width:30px;" tabindex="14" autocomplete="off" />
		       <b>&nbsp;Dpto.&nbsp;</b>
                       <input name="dirDpto" type="text" id="dirDpto" style="width:30px;" tabindex="15" autocomplete="off" />
                </td>
	</tr>
        <tr>
		<th style="width:30px;">Mz.</th>
		<td style="width:150px;"><input name="dirMz" type="text" id="dirMz" style="width:36px;" tabindex="16" autocomplete="off" />
		<b>&nbsp;Casa&nbsp;</b>
		<input name="dirCs" type="text" id="dirCs" style="width:30px;" tabindex="17" autocomplete="off" />
		<b>&nbsp;Mbk.&nbsp;</b>
		<input name="dirMbk" type="text" id="dirMbk" style="width:30px;" tabindex="18" autocomplete="off" /></td>
	</tr>
	<tr id="filaLocalidad"><th style="width:30px;">Localidad</th><td><input name="localidad" type="text" id="localidad" style="width:200px;" tabindex="19" value="Clorinda" /></td></tr>
    <tr><th style="width:30px;">Provincia</th><td><input type="hidden" name="idProvincia" id="idProvincia" /><input name="descProvincia" type="text" id="descProvincia" style="width:200px;" tabindex="20" value="Formosa" /></td></tr>


</table>
</fieldset>
</div></fieldset>
</div>
<input type="submit" name="submit" id="submit" value="cargar codemandado" >
<input type="hidden" name="MM_insert" value="form">
</form>
</div>
</body>
</html>
<?php
mysql_free_result($cosvehi);
?>
