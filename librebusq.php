<?php require_once('Connections/tfx.php'); ?>
<?php $xand = ''; $xandz = ''; $xand02 = ''; $xand03 = ''; $xand04 = ''; $quest_fech1 = ''; ?>
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
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "";
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

$currentPage = $_SERVER["PHP_SELF"];
$maxRows_listadeexptes = 50;
$pageNum_listadeexptes = 0;
$startRow_listadeexptes = 0;
if (isset($_GET['pageNum_listadeexptes'])) {
  $pageNum_listadeexptes = $_GET['pageNum_listadeexptes'];
}
$startRow_listadeexptes = $pageNum_listadeexptes * $maxRows_listadeexptes;
$startmax_listadeexptes = $startRow_listadeexptes + $maxRows_listadeexptes;

$infra_listadeexptes = "";
if (isset($_GET['inf'])) {

  $xand = " AND INFART LIKE " . "'%" . $_GET['inf'] . "%'";
}

$infrax_listadeexptes = "";
if (isset($_GET['filt'])) {
  $infrax_listadeexptes = $_GET['filt'];
  if ($infrax_listadeexptes == 4){ $xandz = ''; }
  else { $xandz = sprintf("AND mod(ID,2) = %s",GetSQLValueString($infrax_listadeexptes, "text")); }
}

$quest_listadeexptes = "WHERE ID >= 0";
if (isset($_GET['search'])) {
  $quest_listadeexptes = " WHERE CONCAT_WS(' ',ID,EXPTE,YEAR(FECHASIEN),APELLIDOS,NOMBRES,INFACTA,DNI,INFAUTOPAT) LIKE '%" . $_GET['search'] . "%'";
}

mysql_select_db($database_tfx, $tfx);
$query_listadeexptes = "SELECT *, (SELECT group_concat(ART,' : ', LEFT(HIPOTESIS,40) SEPARATOR '<strong>;<br></strong> ') FROM infrac WHERE INFART LIKE CONCAT('%%',IDINF,'%%')) AS INFRACCIONES, (SELECT group_concat(ESCTIPO,': ',fechasql(ESCFECHA) SEPARATOR '<strong>;<br></strong> ') FROM escpresent WHERE ESCAU = ID) AS ESCRITOS, (SELECT GROUP_CONCAT('-- a ', operop(DESTINT),' el ',fechasql(FEPAS),'  ',MOTIVO) FROM pasesinter WHERE CAUS = LPAD(ID,6,'0')) AS PASESS FROM causas $quest_listadeexptes $xand $xandz $xand02 $xand03 $xand04 ORDER BY ID DESC LIMIT 10";
$query_limit_listadeexptes = sprintf("%s LIMIT %d, %d", $query_listadeexptes, $startRow_listadeexptes, $maxRows_listadeexptes);
$listadeexptes = mysql_query($query_listadeexptes, $tfx) or die(mysql_error());
$row_listadeexptes = mysql_fetch_assoc($listadeexptes);

if (isset($_GET['totalRows_listadeexptes'])) {
  $totalRows_listadeexptes = $_GET['totalRows_listadeexptes'];
} else {
  $all_listadeexptes = mysql_query($query_listadeexptes);
  $totalRows_listadeexptes = mysql_num_rows($all_listadeexptes);
}
$totalPages_listadeexptes = ceil($totalRows_listadeexptes/$maxRows_listadeexptes)-1;

$queryString_listadeexptes = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_listadeexptes") == false &&
        stristr($param, "totalRows_listadeexptes") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_listadeexptes = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_listadeexptes = sprintf("&totalRows_listadeexptes=%d%s", $totalRows_listadeexptes, $queryString_listadeexptes);

?>

<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Tribunal de Faltas</title>

  <!--  <link rel="stylesheet" href="./css/fontawesome58all.css">
  <link rel="stylesheet" href="./css/fontgooglerobotocss.css">
  <link href="./css/mdboostrap/bootstrap.min.css" rel="stylesheet">
  <link href="./css/mdboostrap/mdb.min.css" rel="stylesheet">
  <script type="text/javascript" src="./css/mdboostrap/js/jquery.min.js"></script>
  <script type="text/javascript" src="./css/mdboostrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="./css/mdboostrap/js/mdb.min.js"></script> -->



  <?php/*  include('modules/header.php'); */ ?>
  <?php include('modules/headerfirst.php'); ?>
  <link href="css/tfaltas.css" rel="stylesheet" type="text/css">
  <link href="css/barra.css" rel="stylesheet" type="text/css">
  <!--   <script type="text/javascript" src="./js/jquery.js"></script>
  <script type="text/javascript" src="./js/jquery.min.js"></script> -->
  <script type="text/javascript" src="./js/jquery.validaracta.js"></script>
</head>

<body>
  <?php include('barra.php'); ?>
  <?php //echo $query_limit_listadeexptes; ?>
  <?php //echo $quest_fech1; ?>
  <div id="contenido">Ingresar Numero de D.N.I.<br>
    <form action="escritos/escritolibredeuda.php" href="escritos/escritolibredeuda.php" target="popup" method="GET">
      <input name="DNI" type="search" autofocus autocomplete="off" id="DNI" placeholder="ingresar DNI" size="60"
        required> <br>
      <input name="nombres" type="search" autocomplete="off" id="nombres" placeholder="ingresar NOMBRES y APELLIDOS"
        size="60" required> <br>
      <input name="domic" type="search" autocomplete="off" id="domic" placeholder="ingresar domicilio" size="60"
        required> <br>
      <br>
      <input type="submit" name="buscar" value="Generar Formulario" />
  </div>

  </form>

  <br>


  </div>
</body>

</html>
<?php
mysql_free_result($listadeexptes);
?>