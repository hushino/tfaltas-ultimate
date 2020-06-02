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
  $insertSQL = sprintf("UPDATE CAUSAS SET RES_CUMPLI = %s WHERE ID = $sddd",
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
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Modificar Cumplimiento</title>
</head>

<body><div><form method="POST" action="<?php echo $editFormAction; ?>" name="form">
<table width="100%" border="1">
  <tbody>
    <tr>
      <td>N&uacute;mero de Resoluci&oacute;n:</td>
      <td><?php echo $row_cosvehi['RES_DEFININRO']; ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label for="textfield">cumplimiento:</label></td>
      <td>
        <input type="text" name="cumplis" id="cumplis" autofocus value="<?php echo $row_cosvehi['RES_CUMPLI']; ?>"></td>
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
