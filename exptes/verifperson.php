<script language="javascript" type="text/javascript" src="../js/disableSubmits.js"></script>
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
$nick2=$_GET['expidx'];
if($nick == $row['PADNI']) {
   echo "<div > existe una persona en el padron de formosa con ese DNI</div>"; echo $row['NOMDOM']; ?>
   <br><a href="../personas/verificarperson.php?exp=<?php echo $nick2; ?>" target="popup" onClick="window.open(this.href, this.target,'width=600,height=150'); return false;">Verificado</a><?php
} else{
   echo "no existe persona con ese DNI en el Padron de Formosa";
}
?>