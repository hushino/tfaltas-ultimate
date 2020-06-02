<?php require_once('../Connections/tfx.php'); ?>
<?php $armado = ''; ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formulario")) {
  $armx = "','" . $_POST['depdestino'] . "', now() ,'" . $_POST['infobser'] . "','" . $_SESSION['MM_Username'] . "'),('";
  $armado = str_replace(',', $armx, $_POST['listacau']);
  $armado = "('" . $armado . "','" . $_POST['depdestino'] . "', now() ,'" . $_POST['infobser'] . "','" . $_SESSION['MM_Username'] . "')";
//  $armado = "SELECT LPAD(ID,6,'0')," . $_POST['depdestino'] . ", now(),'" . $_SESSION['MM_Username'] . "' FROM causas WHERE '" . $_POST['listacau'] . "' LIKE CONCAT('%',LPAD(ID,6,'0'),'%')";
  $insertSQL = sprintf("INSERT INTO pasesinter(CAUS,DESTINT,FEPAS,MOTIVO,USURPAS) VALUES %s",$armado);
//$insertSQL2 = "";
echo $insertSQL;
  mysql_select_db($database_tfx, $tfx);
  $Result1 = mysql_query($insertSQL, $tfx) or die(mysql_error());
//  $Result2 = mysql_query($insertSQL2, $tfx) or die(mysql_error());

  $insertGoTo = "../pases/pases.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_tfx, $tfx);
$query_depend = "SELECT IDC, CAT, DETALLES FROM opciones WHERE CAT = 'depint'";
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
<script type="text/javascript" src="../js/jquery.validaracta.js"></script>
<script language="javascript" type="text/javascript" src="../js/disableSubmits.js"></script>
<link rel="stylesheet" href="../css/token-input.css" type="text/css" />
<link rel="stylesheet" href="../css/token-input-facebook.css" type="text/css" />
<link rel="stylesheet" href="../css/token-input-mac.css" type="text/css" />
<link href="../css/barra.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
        $(document).ready(function() {
            $("#infartx").tokenInput("/tfaltas/consultas/listadecausas.php", {
                theme: "facebook",
                preventDuplicates: true,
                tokenLimit: 50
            });
        });
        function showContent() {
        element = document.getElementById("contoblada");
        check = document.getElementById("oblada");
        if (check.checked) {
            element.style.display='block';
            $('#obladarec').prop('required',true);
        }
        else {
            element.style.display='none';
            $('#obladarec').prop('required',false);
        }
    }
</script>

</head>

<body>
<?php include('../barra.php'); ?>
<?php echo $armado; ?>
<div id="contenido">
CARGAR CAUSAS PARA PASAR EXPEDIENTES
		<div class="breadCrumbHolder breadCrumbModule">
		<div id="breadCrumb0" class="breadCrumb breadCrumbModule">
		</div>
		</div>


<!-- Contenido (inicio) -->

<div id="div_formulario">
  <form name="formulario" id="formulario" action="<?php echo $editFormAction; ?>" method="POST">
  <div style="margin-left: auto;">
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="width: 500px; margin-left: auto; margin-right: auto;">
<legend class="ui-widget-header ui-corner-all ui-state-highlight" style="padding:1px 10px;" id="grupoContacto">Lista de expedientes a Pasar</legend>
<table width="400" border="0" cellspacing="1" class="formulario" style="width: 95%; text-align: right;">

	<tr>
            <td style="width: 200px; text-align: right;">
                <label for="deporig">Usuario que realiza el Pase:</label>
            </td>
            <td align="left" style="width: 200px;">
                <input name="usuario" type="text" value="<?php echo $_SESSION['MM_Username']; ?>" required="required" id="influg" style="width:315px;" tabindex="4" autocomplete="off" readonly />

            </td>
        </tr>
        <tr>

          <td style="width: 200px; text-align: right;"><label for="deporig">Dependencia de Destino:</label></td>
	  <td align="left" style="width: 200px;"><span style="width: 200px; text-align: right;">
	    <select name="depdestino" id="depdestino">
	      <?php do { ?>
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
         </td>
	 <td style="width: 200px;">
         </td>
</tr>
<tr id="filaActaInfr">
	<td style="width: 200px; text-align: right;">
        </td>
	<td style="width: 200px; text-align: right;">
        </td>
</tr>
<tr>
        <td colspan="2">

            Motivo:

        </td>
</tr>
<tr>
        <td colspan="2" align="left" style="width:auto;">Lista de Causas a Pasar<br>
             <input name="listacau" type="text" id="infartx" />
        </td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="right"><label for="infvehi">
        </td>
</tr>
<tr>
        <td>
        </td>
        <td>
        </td>
</tr>
<tr>
		<td colspan="2">Observaciones
		  <input name="infobser" type="text" id="infobser" style="width: 380px;" tabindex="8" autocomplete="off" /></td>
	</tr>
</table>
</fieldset>
</div>
  <br />

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
mysql_free_result($depend);
?>
