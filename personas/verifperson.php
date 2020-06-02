<?php
//Configuracion de la conexion a base de datos
// global $current_user,$wpdb;
//get_currentuserinfo();
//$dtx = $wpdb->get_row("SELECT * FROM wp_users_db WHERE db_user = " . $current_user->ID);

$bd_host = "localhost";
$bd_usuario = "root";
$bd_password = "";
$bd_base = "elecciones";

$con = mysql_connect($bd_host, $bd_usuario, $bd_password);
mysql_query("SET NAMES 'utf8'");
mysql_select_db($bd_base, $con);

//consulta del acta

$sql=mysql_query("SELECT NOMDOM, PADNI FROM padron2015 WHERE PADNI = '". $_GET["verperx"] ."' LIMIT 1",$con);
$row = mysql_fetch_array($sql);
//muestra los datos consultados

?>
<?php
$nick=$_GET['verperx'];
if($nick == $row['PADNI']) {
   echo "<div > existe una persona en el padron de formosa con ese DNI</div>"; echo $row['NOMDOM'];
} else{
   echo " el DNI " . $row['NOMDOM'] . " no existe en el padron de Formosa";
}
?>