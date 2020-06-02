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
$sddd = $_GET['exp'];
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
  $insertSQL = sprintf("INSERT INTO cau_dats (dats_name, dats_value, dats_cau, dats_group) VALUES ('VEAPEL', %s, $sddd, 'VEHITITU'), ('VENOMB', %s, $sddd, 'VEHITITU'), ('VEDNI', %s, $sddd, 'VEHITITU'), ('VEDOM', %s, $sddd, 'VEHITITU'), ('VEMARCA', %s, $sddd, 'VEHITITU'), ('VEMODEL', %s, $sddd, 'VEHITITU'), ('VEMOTOR', %s, $sddd, 'VEHITITU'), ('VECHASIS', %s, $sddd, 'VEHITITU') ",
                       GetSQLValueString($_POST['apellidos'], "text"),
                       GetSQLValueString($_POST['nombres'], "text"),
                       GetSQLValueString($_POST['dni'], "text"),
                       GetSQLValueString($_POST['domicilio'], "text"),
                       GetSQLValueString($_POST['marca'], "text"),
                       GetSQLValueString($_POST['modelo'], "text"),
                       GetSQLValueString($_POST['motor'], "text"),
                       GetSQLValueString($_POST['chasis'], "text"));

  mysql_select_db($database_tfx, $tfx);
  $Result1 = mysql_query($insertSQL, $tfx) or die(mysql_error());

echo "<script>window.opener.location.reload(); window.close();</script>";exit;
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
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Datos del Vehículo</title>
<script language="javascript" type="text/javascript" src="../js/disableSubmits.js"></script>
</head>

<body><div><form method="POST" action="<?php echo $editFormAction; ?>" name="form">
<table width="100%" border="1">
  <tbody>
    <tr>
      <td>Matrícula del Vehículo:</td>
      <td><?php echo $row_cosvehi['INFAUTOPAT']; ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label for="textfield">Apellidos:</label></td>
      <td>
        <input type="text" name="apellidos" id="apellidos"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label for="textfield2">Nombres:</label></td>
      <td>
        <input type="text" name="nombres" id="nombres"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label for="textfield3">D.N.I.:</label></td>
      <td>
        <input type="text" name="dni" id="dni"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label for="textfield4">Domicilio:</label></td>
      <td>
        <textarea name="domicilio" id="domicilio"></textarea></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label for="textfield5">Marca:</label></td>
      <td>
        <input type="text" name="marca" id="marca"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label for="textfield6">Modelo:</label></td>
      <td>
        <input type="text" name="modelo" id="modelo"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label for="textfield7">Motor Nº:</label></td>
      <td>
        <input type="text" name="motor" id="motor"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label for="textfield8">Cuadro o Chasis Nº:</label></td>
      <td>
        <input type="text" name="chasis" id="chasis"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label for="textfield8">Fecha de Titularidad:</label></td>
      <td>
        <input type="text" name="fetitu" id="fetitu"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="submit" id="submit" value="Enviar" ></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
<input type="hidden" name="MM_insert" value="form">
</form>
</div>
</body>
</html>
<?php
mysql_free_result($cosvehi);
?>
