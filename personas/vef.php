<?php
//Configuracion de la conexion a base de datos
// global $current_user,$wpdb;
//get_currentuserinfo();
//$dtx = $wpdb->get_row("SELECT * FROM wp_users_db WHERE db_user = " . $current_user->ID);
require_once('../Connections/tfx.php');
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
//$bd_host = "localhost";
//$bd_usuario = "root";
//$bd_password = "";
//$bd_base = "tfaltas";

//$con = mysql_connect($bd_host, $bd_usuario, $bd_password);
//mysql_query("SET NAMES 'utf8'");
//mysql_select_db($bd_base, $con);

//consulta del acta

//$sql=mysql_query("SELECT INFACTA, concat('Expte. N&#176; ',`causas`.`EXPTE`,' - A&#241;o ',year(`causas`.`INFFECHA`)) AS EXPTEX FROM causas WHERE INFACTA LIKE '%". $_GET["infacta"] ."%'",$con);
//$row = mysql_fetch_array($sql);
$name_esc = "-1";
if (isset($_GET['infacta'])) {
  $name_esc = $_GET['infacta'];
  if ($name_esc == NULL) {$name_esc = '***';}
}

mysql_select_db($database_tfx, $tfx);
$query_cont = sprintf("SELECT INFACTA, concat('Expte. N&#176; ',`causas`.`EXPTE`,' - A&#241;o ',year(`causas`.`INFFECHA`)) AS EXPTEX FROM causas WHERE INFACTA LIKE %s", GetSQLValueString("%" . $name_esc . "%", "text"));
$cont = mysql_query($query_cont, $tfx) or die(mysql_error());
$row_cont = mysql_fetch_assoc($cont);
$totalRows_cont = mysql_num_rows($cont);

//muestra los datos consultados

?>
<?php
$nick=$_GET['infacta'];
if(isset($row_cont['INFACTA'])) {
   echo "<div class=\"formError\"> Ya existe un acta con el mismo N&uacute;mero! corroborar<br>";
   do { echo $row_cont['EXPTEX'] . '<br>'; } while ($row_cont = mysql_fetch_assoc($cont)); echo "</div>";
} else{
   echo "ok";
}
?>