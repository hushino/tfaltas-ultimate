<?php require_once('../Connections/tfx.php'); ?>
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
  $insertSQL = sprintf("INSERT INTO causas (ID, DEPORIG, INFACTA, INFFECHA, INFHORA, INFLUG, INFART, INFVEHI, INFAUTOPAT, INFOBSER, APELLIDOS, NOMBRES, DNI, DIRCALLE, DIRNRO, DIRPISO, DIRDPTO, DIRMZ, DIRCS, DIRMBK, LOCALIDAD, DESCPROVINCIA, OBLADA, DNINAC, RETLICE, RETVEHI) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['IDx'], "text"),
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
                       GetSQLValueString($_POST['retvehi'], "text"));

  mysql_select_db($database_tfx, $tfx);
  $Result1 = mysql_query($insertSQL, $tfx) or die(mysql_error());

  $insertGoTo = "../site.php";
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
<link rel="stylesheet" href="../css/token-input.css" type="text/css" />
<link rel="stylesheet" href="../css/token-input-facebook.css" type="text/css" />
<link rel="stylesheet" href="../css/token-input-mac.css" type="text/css" />
<link href="../css/barra.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
        $(document).ready(function() {
            $("#infartx").tokenInput("/tfaltas/consultas/listadeinfracciones.php", {
                tokenLimit: 7
            });
        });
</script>

</head>

<body>
<?php include('../barra.php'); ?>
<div id="contenido">
CARGAR DATOS DE LA INFRACCION<!--  Breadcrumb de Navegación (inicio) -->		DE TRANSITO
		<div class="breadCrumbHolder breadCrumbModule">
		<div id="breadCrumb0" class="breadCrumb breadCrumbModule"> 
		</div>
		</div>
<!--  Breadcrumb de Navegación (fin) -->
		
<!-- Contenido (inicio) -->

<div id="div_formulario">
  <form name="formulario" id="formulario" action="<?php echo $editFormAction; ?>" method="POST">
  <div style="margin-left: auto;">
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="width: 500px; margin-left: auto; margin-right: auto;">
<legend class="ui-widget-header ui-corner-all ui-state-highlight" style="padding:1px 10px;" id="grupoContacto">Datos de la Falta Cometida</legend>
<table width="400" border="0" cellspacing="1" class="formulario" style="width: 95%; text-align: right;">
	<tr>
	  <td style="width: 200px; text-align: right;"><label for="deporig">Seleccionar Dependencia de Origen:</label></td>
	  <td align="left" style="width: 200px;"><span style="width: 200px; text-align: right;">
	    <select name="deporig" id="deporig">
	      <?php
do {  
?>
	      <option value="<?php echo $row_depend['IDC']?>"><?php echo $row_depend['DETALLES']?></option>
	      <?php
} while ($row_depend = mysql_fetch_assoc($depend));
  $rows = mysql_num_rows($depend);
  if($rows > 0) {
      mysql_data_seek($depend, 0);
	  $row_depend = mysql_fetch_assoc($depend);
  }
?>
	      </select>
	  </span></td>
	  </tr>
	<tr id="filaActaInfr">
	  <td style="width: 200px; text-align: right;"> 
	    Nº de expediente:
	    <input name="IDx" type="text" autofocus required="required" id="IDxx" style="width:100px;" tabindex="1" autocomplete="off" /></td>
	  <td style="width: 200px;"><span style="width:100px;">Acta de Inf. Nº</span>	    <input name="infacta" type="text" required="required" id="infacta" style="width:100px;" tabindex="1" autocomplete="off" /></td></tr>
	<tr id="filaActaInfr">
	  <td style="width: 200px; text-align: right;">Fecha	    <input name="inffecha" type="date" required="required" id="infdate" style="width: 130px;" tabindex="2" autocomplete="off" /></td>
	  <td style="width: 200px; text-align: right;"><b>Hora</b>	    <input name="infhora" type="time" required="required" id="infhora" style="width:70px;" tabindex="3" autocomplete="off" /></td></tr>
      <tr>
        <td colspan="2">Lugar en que se cometió la falta
          <input name="influg" type="text" required="required" id="influg" style="width:315px;" tabindex="4" autocomplete="off" /></td>
      </tr>
    <tr>
      <td colspan="2" align="left" style="width:auto;">Infracción<br>
        <input name="infart" type="text" id="infartx" /></td
        >
      </tr>
      <tr>
		<td colspan="2">&nbsp;</td>
	</tr>
      <tr>
		<td colspan="2" align="right"><label for="infvehi">		   Tipo de Vehículo:</label>
          <select name="infvehi" id="infvehi" style="width:100px;" tabindex="6">
            <?php
do {  
?>
            <option value="<?php echo $row_opvehi['IDC']?>"><?php echo $row_opvehi['DETALLES']?></option>
            <?php
} while ($row_opvehi = mysql_fetch_assoc($opvehi));
  $rows = mysql_num_rows($opvehi);
  if($rows > 0) {
      mysql_data_seek($opvehi, 0);
	  $row_opvehi = mysql_fetch_assoc($opvehi);
  }
?>
          </select>
          <b>Dominio</b>:
		<input name="infautopat" type="text" id="infautopat" style="width:50px;" pattern="[A-Z0-9]{6}" tabindex="7" title="sólo 6 letras o números. Las letras en mayúsculas" maxlength="6" /></td>
	</tr>
      <tr>
        <td colspan="2">Oblada
          <label for="infvehi2">
            <input name="oblada" type="checkbox" id="oblada" title="Marcar si surge pago espontáneo del acta" value="S">
            Retención Licencia
  <input name="retlice" type="checkbox" id="retlice" value="S">
  Retención de Vehículo 
  <input name="retvehi" type="checkbox" id="retvehi" value="S">
          </label></td>
      </tr>
      <tr>
		<td colspan="2">Observaciones
		  <input name="infobser" type="text" id="infobser" style="width: 380px;" tabindex="8" autocomplete="off" /></td>
	</tr>
</table>
</fieldset>
</div>
  <br />
<div style="">
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="width: auto; margin-left: auto; margin-right: auto;">
<legend class="ui-widget-header ui-corner-all ui-state-highlight" style="padding:1px 10px;" id="grupoApellidoNombre">Datos del Infractor</legend>
<div style="width: 50%; float:left">
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="width: 200px; margin-left: auto; margin-right: auto;">
<legend class="ui-widget-header ui-corner-all ui-state-highlight" style="padding:1px 10px;" id="grupoDomicilio">Datos Personales</legend><table class="formulario" style="width: 45%; text-align: justify;">
	<tr>
	  <th style="width:40px;" id="tituloApellido">Apellidos</th><td><input name="apellidos" type="text" id="apellido" style="width:205px;" tabindex="9" autocomplete="off" /></td></tr>
	<tr>
	  <th style="width:40px;">Nombres</th><td><input name="nombres" type="text" id="nombres" style="width:205px;" tabindex="10" autocomplete="off" /></td></tr>
      <tr>
        <th style="width:40px;">D.N.I.</th>
        <td><input name="dni" type="text" id="dni" style="width:85px;" tabindex="11" autocomplete="off" /></td>
      </tr>
      <tr>
	  <th style="width:50px;">Doc.Nac.</th><td><input name="dninac" type="text" id="dninac" style="width: 50px; float: left;" pattern="[A-Z]{3}" tabindex="11" autocomplete="off" value="ARG" /></td></tr>  
</table></fieldset></div>
<div style="width: 50%; float: right;">
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="width: 200px; margin-left: auto; margin-right: auto;">
<legend class="ui-widget-header ui-corner-all ui-state-highlight" style="padding:1px 10px;" id="grupoDomicilio">Domicilio</legend>
<table class="formulario" style="width:30%;">
  
	<tr>
	  <th style="width:30px;">Calle o Barrio</th><td><input name="dirCalle" type="text" id="dirCalle" style="width:200px;" tabindex="12" autocomplete="off" /></td></tr>  
	<tr>
		<th style="width:30px;">Nro.</th><td><input name="dirNro" type="text" id="dirNro" style="width:36px;" tabindex="13" autocomplete="off" />
		<b>&nbsp;&nbsp;&nbsp;Piso&nbsp;&nbsp;</b><input name="dirPiso" type="text" id="dirPiso" style="width:30px;" tabindex="14" autocomplete="off" />
		<b>&nbsp;&nbsp;&nbsp;Dpto.&nbsp;&nbsp;</b><input name="dirDpto" type="text" id="dirDpto" style="width:30px;" tabindex="15" autocomplete="off" /></td>
	</tr><tr>
		<th style="width:30px;">Mz.</th>
		<td><input name="dirMz" type="text" id="dirMz" style="width:36px;" tabindex="16" autocomplete="off" />
		<b>&nbsp;&nbsp;&nbsp;Casa&nbsp;</b>
		<input name="dirCs" type="text" id="dirCs" style="width:30px;" tabindex="17" autocomplete="off" />
		<b>&nbsp;&nbsp;&nbsp;Mbk.&nbsp;&nbsp;</b>
		<input name="dirMbk" type="text" id="dirMbk" style="width:30px;" tabindex="18" autocomplete="off" /></td>
	</tr>
	<tr id="filaLocalidad"><th style="width:30px;">Localidad</th><td><input name="localidad" type="text" id="localidad" style="width:200px;" tabindex="19" value="Clorinda" /></td></tr>
    <tr><th style="width:30px;">Provincia</th><td><input type="hidden" name="idProvincia" id="idProvincia" /><input name="descProvincia" type="text" id="descProvincia" style="width:200px;" tabindex="20" value="Formosa" /></td></tr>
	

</table>
</fieldset>
</div></fieldset>
</div>
<br />

<table align="center" border="0">
	<tr>
		<td><input type="button" name="button" id="button" value="Volver"></td>
		<td><input type="submit" name="submit" id="submit" value="Ingresar" tabindex="18" ></td>
	</tr>
</table>
<input type="hidden" name="MM_insert" value="formulario">
  </form>
</div>

<div id="div_mensaje" align="center" style="display:none;padding:30px;"></div>
<table align="center" id="controles_mensaje" style="display:none;">
	<tr>
		<td><a href="verMenuEmpleado.do" id="btn_volver_menu">Menú Principal</a></td>
	</tr>
</table>
</div>

</body>
</html>
<?php
mysql_free_result($opvehi);

mysql_free_result($depend);
?>
