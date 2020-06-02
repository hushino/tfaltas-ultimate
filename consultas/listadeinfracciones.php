<?php // include_once ('http://tecnojus.com.ar/wp-load.php'); ?>

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

//consulta todos los empleados

$sql=mysql_query("SELECT IDINF, CONCAT(ART,' - ', HIPOTESIS) AS namesx FROM infrac WHERE VIGENCIA = 'S' AND ART LIKE '%".$_GET["q"]."%' OR HIPOTESIS LIKE '%".$_GET["q"]."%' LIMIT 20",$con);
mysql_query("SET namesx 'utf8'");
//muestra los datos consultados

echo "[";

while($row = mysql_fetch_array($sql)){

        echo "{\"id\": \"".$row['IDINF']."\", \"name\": \"".$row['namesx']."\"}, ";



}



echo "{\"id\": \"\", \"name\": \"\"}]";




//echo "[";
//		$row = mysql_fetch_array($sql);
//		$arr = array();
//		for ($i=0;$i<count($row);$i++)
//		{
//			$arr[] = "{\"id\": \"".$row['IDPERS']."\", \"name\": \"".$row['APELLIDO']."\"}";
//		}
//		echo implode(", ", $arr);
//		echo "]";
?>







