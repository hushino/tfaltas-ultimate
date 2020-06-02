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

$quest_fech1 = "";
$quest_fech2 = "";
$xand04 = '';
$xandlimit = '';
if (isset($_GET['bdate1']) && isset($_GET['bdate2'])) {
  $quest_fech1 = $_GET['bdate1'];
  $quest_fech2 = $_GET['bdate2'];
  $xand04 = " AND FECHASIEN between '" . $quest_fech1 . "' AND '" . $quest_fech2 . "' ";
  $xandlimit = ' desc ';
} else { $xand04 = ''; $xandlimit = ' asc limit 1 ';}
if ($quest_fech1 == '') {$xand04 = ''; $xandlimit = ' asc limit 1 ';};

mysql_select_db($database_tfx, $tfx);
$query_caulist = "SELECT IDINF,ART, HIPOTESIS, (SELECT COUNT(*) FROM CAUSAS WHERE INFART LIKE CONCAT('%',IDINF,'%') $xand04) AS NRO FROM infrac ORDER BY NRO $xandlimit";
$caulist = mysql_query($query_caulist, $tfx) or die(mysql_error());
$row_caulist = mysql_fetch_assoc($caulist);
$totalRows_caulist = mysql_num_rows($caulist);
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Documento sin título</title>
  <?php include('../modules/header.php'); ?>
  <link href="../css/tfaltas.css" rel="stylesheet" type="text/css">
  <link href="../css/barra.css" rel="stylesheet" type="text/css">
</head>

<body><?php include('../barra.php'); ?>
  <div id="contenido">
    <form action="infraclist.php" method="get">

      Desde<input type="date" name="bdate1"> - hasta <input type="date" name="bdate2"><br>
      <input type="submit" name="buscar" value="Filtrar" />

    </form>

    <table width="100%" border="1" class="tabla_gris">
      <tbody>
        <tr>
          <td>Art.</td>
          <td>Infracción</td>
          <td>Cant</td>
          <td>&nbsp;</td>
        </tr>
        <?php do { ?><tr>
          <td><?php echo $row_caulist['ART']; ?></td>
          <td><?php echo $row_caulist['HIPOTESIS']; ?></td>
          <td><?php echo $row_caulist['NRO']; ?></td>
          <td><a href="../site.php?inf=<?php echo $row_caulist['IDINF']; ?>"><img src="../images/bullet_arrow_down.gif"
                width="16" height="16" alt="" /></a></td>
        </tr>
        <?php } while ($row_caulist = mysql_fetch_assoc($caulist)); ?><tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </tbody>
    </table>

  </div>
</body>

</html>
<?php
mysql_free_result($caulist);
?>