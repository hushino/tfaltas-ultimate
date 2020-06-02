<?php
//Configuracion de la conexion a base de datos
// global $current_user,$wpdb;
//get_currentuserinfo();
//$dtx = $wpdb->get_row("SELECT * FROM wp_users_db WHERE db_user = " . $current_user->ID);

$bd_host = "localhost"; 
$bd_usuario = "root"; 
$bd_password = ""; 
$bd_base = "tfaltas"; 

$con = mysql_connect($bd_host, $bd_usuario, $bd_password); 
mysql_query("SET NAMES 'utf8'");
mysql_select_db($bd_base, $con); 

//consulta del acta 

$sql=mysql_query("SELECT DNI FROM listainfractor WHERE DNI LIKE '". $_GET["dnicompr"] ."' LIMIT 1",$con);
$row = mysql_fetch_array($sql);
//muestra los datos consultados

?>
<?php
$nick=$_GET['dnicompr'];
if($nick == $row['DNI']) {
   echo "El presunto infractor tiene antecedentes!"; 
} else {
   echo "ok"; 
}
?>