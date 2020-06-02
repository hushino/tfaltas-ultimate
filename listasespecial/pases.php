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
  $insertSQL = sprintf("UPDATE escmodelos SET ESCAU = %s, ESCDOC = %s, CONTEN = %s WHERE IDESC =%s",
                       GetSQLValueString($_POST['caux'], "text"),
                       GetSQLValueString($_POST['escx'], "text"),
                       GetSQLValueString($_POST['area2'], "text"),
                       GetSQLValueString($_GET['esc'], "text"));

  mysql_select_db($database_tfx, $tfx);
  $Result1 = mysql_query($insertSQL, $tfx) or die(mysql_error());

  $insertGoTo = "escritover.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_escrito = "-1";
if (isset($_GET['esc'])) {
  $colname_escrito = $_GET['esc'];
}
mysql_select_db($database_tfx, $tfx);
$query_escrito = "SELECT (SELECT CONCAT(TRIM(APELLIDOS),', ', TRIM(IF(LOCATE(' ',NOMBRES) = 0,NOMBRES,LEFT(NOMBRES,LOCATE(' ',NOMBRES)-1))), ' S/ Acta de Infrac. Nº ', INFACTA,' (Dir. ',operop(DEPORIG),')') FROM CAUSAS WHERE ID = CAUS) AS CARAT, operop(DESTINT) AS DESTINO, operop(MOTIVO) AS MOTIV, FEPAS, IDPAS, USURPAS FROM pasesinter";
$escrito = mysql_query($query_escrito, $tfx) or die(mysql_error());
$row_escrito = mysql_fetch_assoc($escrito);
$totalRows_escrito = mysql_num_rows($escrito);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Tribunal de Faltas - Libro de Pases</title>
<link href="../css/barra.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php include('../barra.php'); ?>
<div>Escritos</div>
<div><a href="ingpases.php">Nuevo</a></div>
<div><?php do { ?>
         <div>
         <a href="escritover.php?esc=<?php echo $row_escrito['IDPAS']; ?>"><?php echo $row_escrito['IDPAS'] . ' - ' . $row_escrito['CARAT'] . ' - ' . $row_escrito['DESTINO']; ?></a>
         </div>
     <?php } while ($row_escrito = mysql_fetch_assoc($escrito)); ?>
</div>

</body>
</html>
<?php
mysql_free_result($escrito);
?>