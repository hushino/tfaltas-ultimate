<?php require_once('../Connections/tfx.php'); ?>
<?php require_once('../Connections/elec.php'); ?>
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
  $insertSQL = sprintf("UPDATE CAUSAS SET DNINOMCONST = %s WHERE ID = $sddd",
                       GetSQLValueString($_POST['cumplis'], "text"));

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

$colname_du = $row_cosvehi['DNI'];
mysql_select_db($database_elecx, $elecx);
$query_cosvehi2 = sprintf("SELECT NOMDOM, PADNI FROM padron2015 WHERE PADNI = %s LIMIT 1", GetSQLValueString($colname_du, "int"));
$cosvehi2 = mysql_query($query_cosvehi2, $elecx) or die(mysql_error());
$row_cosvehi2 = mysql_fetch_assoc($cosvehi2);
$totalRows_cosvehi2 = mysql_num_rows($cosvehi2);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Verificar datos de la Persona</title>
<script language="javascript" type="text/javascript" src="../js/disableSubmits.js"></script>
</head>

<body><div><form method="POST" action="<?php echo $editFormAction; ?>" name="form">
<table width="100%" border="1">
  <tbody>
    <tr>
      <td>Verificar datos de:</td>
      <td><?php echo $row_cosvehi['APELLIDOS'] . ', ' . $row_cosvehi['NOMBRES'] . '<br>DNI: ' . $row_cosvehi['DNI']; ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Padron Formosa 2015</td>
      <td><?php echo $row_cosvehi2['NOMDOM'] . ', <br>' . $row_cosvehi2['PADNI']; ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label for="textfield">Verificado:</label></td>
      <td>
        <input type="text" name="cumplis" id="cumplis" value="V" autofocus></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="submit" id="submit" value="Verificar" ></td>
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
