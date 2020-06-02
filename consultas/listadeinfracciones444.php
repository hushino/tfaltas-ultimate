<?php // include_once ('http://tecnojus.com.ar/wp-load.php'); ?>
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
?>
<?php
//Configuracion de la conexion a base de datos
// global $current_user,$wpdb;
//get_currentuserinfo();
//$dtx = $wpdb->get_row("SELECT * FROM wp_users_db WHERE db_user = " . $current_user->ID);

//$bd_host = "localhost";
//$bd_usuario = "root";
//$bd_password = "";
//$bd_base = "tfaltas";
//$con = mysql_connect($bd_host, $bd_usuario, $bd_password);
// mysql_query("SET NAMES 'utf8'");
//mysql_select_db($bd_base, $con);
//consulta todos los empleados

$colname_consul = "-1";
if (isset($_GET['q'])) {
  $colname_consul = $_GET['q'];
}
mysql_select_db($database_tfx, $tfx);
$query_consul = sprintf("SELECT IDINF, CONCAT(ART,' - ', HIPOTESIS) AS namesx FROM infrac WHERE ART LIKE %s OR HIPOTESIS LIKE %s LIMIT 20", GetSQLValueString('%'.$colname_consul.'%', "text"), GetSQLValueString('%'.$colname_consul.'%', "text"));
$consul = mysql_query($query_consul, $tfx) or die(mysql_error());
$row_consul = mysql_fetch_assoc($consul);
$totalRows_consul = mysql_num_rows($consul);

echo "[";
while($row_consul = mysql_fetch_array($consul)){
        echo "{\"id\": \"".$row_consul['IDINF']."\", \"name\": \"".$row_consul['namesx']."\"}, ";
}
echo "{\"id\": \"\", \"name\": \"\"}]";
?>
