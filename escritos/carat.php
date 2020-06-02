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

$colname_causas = "-1";
if (isset($_GET['ID'])) {
  $colname_causas = $_GET['ID'];
}
mysql_select_db($database_tfx, $tfx);
$query_causas = sprintf("SELECT * FROM causas WHERE ID = %s", GetSQLValueString($colname_causas, "int"));
$causas = mysql_query($query_causas, $tfx) or die(mysql_error());
$row_causas = mysql_fetch_assoc($causas);
$totalRows_causas = mysql_num_rows($causas);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
<style type="text/css">
body,td,th {
	font-size: 30px;
	font-family: "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", "DejaVu Sans", Verdana, sans-serif;
	font-style: normal;
}
</style>
<link href="letras.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" height="100%" border="0">
  <tbody>
    <tr>
      <td width="27%">&nbsp;</td>
      <td width="73%">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center">Carátula:</td>
    </tr>
    <tr>
      <td rowspan="8" align="center"><img src="../images/logo-tfatas.png" width="200" height="209" alt=""/></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center"><strong><?php echo $row_causas['APELLIDOS']; ?>, <?php echo $row_causas['NOMBRES']; ?></strong></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">S/</td>
    </tr>
    <tr>
      <td align="center">Acta de Infracción Nº <?php echo $row_causas['INFACTA']; ?></td>
    </tr>
    <tr>
      <td align="center">Tránsito<?php echo $row_causas['DEPORIG']; ?></td>
    </tr>
    <tr>
      <td align="center">Expte.: Nº <?php echo $row_causas['ID']; ?> - Año 2015</td>
    </tr>
    <tr>
      <td align="center">Juez: Dr. Oscar Alberto Virgona</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center">Secretaría 1º: Dr. Carlos Arturo Serravalle</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center">Carátula:</td>
    </tr>
    <tr>
      <td rowspan="8" align="center"><img src="../images/logo-tfatas.png" width="200" height="209" alt=""/></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center"><strong>JUAN DE LOS PALOTES</strong></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="center">S/</td>
    </tr>
    <tr>
      <td align="center">Acta de Infracción Nº </td>
    </tr>
    <tr>
      <td align="center">Tránsito</td>
    </tr>
    <tr>
      <td align="center">Expte.: Nº - Año 2015</td>
    </tr>
    <tr>
      <td align="center">Juez: Dr. Oscar Alberto Virgona</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center">Secretaría 2º: Dr. Rolando Manuel Pankow</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
</body>
</html>
<?php
mysql_free_result($causas);
?>
