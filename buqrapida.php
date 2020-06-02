<?php require_once('Connections/tfx.php'); ?>
<?php $xand = ''; $xandz = ''; $xand02 = ''; $xand03 = ''; $xand04 = ''; $xand05 = ''; $xand06 = ''; $xand07 = ''; $quest_fech1 = ''; $filtrado = ''; ?>
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

if (isset($_GET['filt'])) {
  $infrax_listadeexptes = $_GET['filt'];
  if ($infrax_listadeexptes == 4){ $xandz = ''; }
  if ($infrax_listadeexptes == 3){ $xandz = 'AND SECRETARIANRO = 3'; $filtrado = 'Secretaria 3; '; }
  if ($infrax_listadeexptes == 2){ $xandz = 'AND SECRETARIANRO = 2'; $filtrado = 'Secretaria 2; ';}
  if ($infrax_listadeexptes == 1){ $xandz = 'AND SECRETARIANRO = 1'; $filtrado = 'Secretaria 1; ';}
}

$quest_listadeexptes = "WHERE ID >= 0 ";
if (isset($_GET['search'])) {
  $quest_listadeexptes = " WHERE CONCAT_WS(' ',ID,EXPTE,YEAR(FECHASIEN),APELLIDOS,NOMBRES,INFACTA,DNI,INFAUTOPAT) LIKE '%" . $_GET['search'] . "%' ";
}

$quest_esct = "";
if (isset($_GET['esctipo'])) {
  $quest_esct = $_GET['esctipo'];
  if ($quest_esct == 'solicitud') {$xand07 = 'solicitud'; $filtrado = $filtrado . 'esc: solicitud; '; }
  if ($quest_esct == 'descargo') {$xand07 = 'descargo'; $filtrado = $filtrado . 'esc: descargo; '; }
  if ($quest_esct == 'restitucion') {$xand07 = 'restitucion'; $filtrado = $filtrado . 'esc: restitucion; '; }
}

$quest_auc = "";
if (isset($_GET['ausen'])) {
  $quest_auc = $_GET['ausen'];
  if ($quest_auc == 'Ape') {$xand02 = ' AND APELLIDOS IS NULL '; $filtrado = $filtrado . 'por sin apellido; '; }
  if ($quest_auc == 'Dni') {$xand02 = ' AND DNI IS NULL '; $filtrado = $filtrado. 'por DNI; '; }
  if ($quest_auc == 'Pat') {$xand02 = ' AND INFAUTOPAT IS NULL '; $filtrado = $filtrado . 'por sin patente; '; }
  if ($quest_auc == 'Valv') {$xand02 = ' AND DNINOMCONST LIKE "V" ';}
  if ($quest_auc == 'Valx') {$xand02 = ' AND DNINOMCONST NOT LIKE "V" ';}
  if ($quest_auc == 'Valn') {$xand02 = ' AND DNINOMCONST IS NULL ';}
  if ($quest_auc == 'SinInf') {$xand02 = ' AND INFART IS NULL ';}
  if ($quest_auc == 'RetVehi') {$xand02 = ' AND RETVEHI LIKE "S" '; $filtrado = $filtrado . 'por vehiculo retenido; '; }
  if ($quest_auc == 'RetLice') {$xand02 = ' AND RETLICE LIKE "S" '; $filtrado = $filtrado . 'por licencia retenida; '; }
  if ($quest_auc == 'ConEscri') {$xand02 = ' AND (SELECT count(*) FROM escpresent WHERE ESCAU = ID AND ESCTIPO = "' . $xand07 . '") >= 1 '; $filtrado = $filtrado . 'por escrito presentado; '; }
}

$quest_resol = "";
if (isset($_GET['resol'])) {
  $quest_resol = $_GET['resol'];
  if ($quest_resol == 'ConRes') {$xand06 = ' AND RES_DEFINI LIKE "S" '; $filtrado = $filtrado . 'con resolucion definitiva; '; }
  if ($quest_resol == 'SinRes') {$xand06 = ' AND RES_DEFINI LIKE "N" '; $filtrado = $filtrado . 'sin resolucion definitiva; '; }
}


$quest_oblad = "";
if (isset($_GET['obla'])) {
  $quest_oblad = $_GET['obla'];
  if ($quest_oblad == 'S') {$xand03 = ' AND OBLADA = "S" '; $filtrado = $filtrado . 'oblada; '; }
  if ($quest_oblad == 'N') {$xand03 = ' AND OBLADA IS NULL '; $filtrado = $filtrado . 'sin oblar; '; }
}

$quest_fech1 = "";
$quest_fech2 = "";
$xand04 = '';
if (isset($_GET['bdate1']) && isset($_GET['bdate2'])) {
  $quest_fech1 = $_GET['bdate1'];
  $quest_fech2 = $_GET['bdate2'];
  $xand04 = " AND FECHASIEN between '" . $quest_fech1 . "' AND '" . $quest_fech2 . "' "; $filtrado = $filtrado . 'fechas entre: ' . $quest_fech1 . ' y ' . $quest_fech2 . '';;

} else { $xand04 = '';}
if ($quest_fech1 == '') {$xand04 = '';};

$xand05 = '';
if (isset($_GET['domic'])) {
  $dom = $_GET['domic'];
  $xand05 = " AND INFLUG LIKE '%" . $dom . "%' ";
  }

mysql_select_db($database_tfx, $tfx);
$query_listadeexptes = "SELECT *, concat('',causas.EXPTE,'/',right(year(causas.FECHASIEN),2)) AS EXPTE, CONCAT(IF(APELLIDOS IS NULL,'',TRIM(APELLIDOS)),IF(NOMBRES IS NULL,'',', '),IF(NOMBRES IS NULL,'',TRIM(IF(LOCATE(' ',NOMBRES) = 0,NOMBRES,LEFT(NOMBRES,LOCATE(' ',NOMBRES)-1)))),' S/ Acta N ', IF(causas.INFACTA IS NULL,'XXXCCC',causas.INFACTA), ' (Dir. ', IF(DEPORIG IS NULL,'',operop(DEPORIG)),')') AS CARAT FROM causas $quest_listadeexptes $xand04 $xandz $xand02 $xand03 $xand05 $xand06 ORDER BY ID DESC";
$query_limit_listadeexptes = sprintf("%s LIMIT %d, %d", $query_listadeexptes, $startRow_listadeexptes, $maxRows_listadeexptes);
$listadeexptes = mysql_query($query_limit_listadeexptes, $tfx) or die(mysql_error());
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
<link href="css/tfaltas.css" rel="stylesheet" type="text/css">
<link href="css/barra.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
        function showContent() {
        element = document.getElementById("contoblada");
        check = document.getElementById("oblada");
        if (check.checked) {
            element.style.display='block';
            $('#obladarec').prop('required',true);
        }
        else {
            element.style.display='none';
            $('#obladarec').prop('required',false);
        }
    }
</script>
</head>

<body>
<?php include('barra.php'); ?>
<?php //echo 'bx = ' . $query_listadeexptes; ?><br><?php //echo 'ax = ' . $query_limit_listadeexptes; ?><br>
<div id="contenido">Busqueda especial de Causas<br>
  <form action="buqrapida.php" method="get"><input name="search" type="search" autofocus autocomplete="off" id="search" placeholder="ingresar algún criterio de busqueda" size="60">   B&uacute;squeda Avanzada <input name="oblada" type="checkbox" id="oblada" title="Marcar si surge pago espontáneo del acta" value="S" onchange="javascript:showContent()"><br>
  <div id="contoblada" style="display: none; text-align: left; ">
  Por Secretaria :
  <select name="filt">
    <option value="4">todas</option>
    <option value="2">Rolando Pankow</option>
    <option value="1">Arturo Serravalle</option>
    <option value="3">Federico Parola</option>
  </select>
  <br>
<input type="radio" name="ausen" value="Ape" /> no hay cargado nombre y apellido<br>
<input type="radio" name="ausen" value="Dni" /> no hay dni<br>
<input type="radio" name="ausen" value="Pat" /> veh&iacute;culo sin patente<br>
<input type="radio" name="ausen" value="Valv" /> Verificados<br>
<input type="radio" name="ausen" value="Valx" /> No corresponden o hay que verificar<br>
<input type="radio" name="ausen" value="Valn" /> Sin verificar<br>
<input type="radio" name="ausen" value="SinInf" /> Sin Infracci&oacute;n<br>
<input type="radio" name="ausen" value="RetVehi" /> Vehiculo Retenido<br>
<input type="radio" name="ausen" value="RetLice" /> Licencia Retenida<br>
<input type="radio" name="ausen" value="ConEscri" /> Con Escrito<br>
---------------<br>
Escrito: <input type="radio" name="esctipo" value="solicitud" /> Solicitud <br>
Escrito: <input type="radio" name="esctipo" value="descargo" /> descargo <br>
Escrito: <input type="radio" name="esctipo" value="restitucion" /> Restitucion <br>
---------------<br>
<input type="radio" name="resol" value="ConRes" /> Resolucion Definitiva<br>
<input type="radio" name="resol" value="SinRes" /> Sin Resolucion Definitiva<br>
---------------<br>
<input name="obla" type="radio" value="S" />Obladas<br>
<input name="obla" type="radio" value="N" />No Obladas<br>
Desde<input type="date" name="bdate1"> - hasta <input type="date" name="bdate2"><br>
Lugar de la Infracci�n <input type="text" name="domic"><br>
  <input type="submit" name="buscar" value="Filtrar" />
  </div>

  </form>
  <?php
  echo 'Filtrado por: ' . $filtrado;
 ?><br>
<?php echo "P&aacute;gina Nº " . $pageNum_listadeexptes . " del reg. Nº " . $startRow_listadeexptes . " al "  . $startmax_listadeexptes .  " de " . $totalRows_listadeexptes . " registros."; ?><br>
<table width="700" cellspacing="0" >
    <tbody>
        <tr>
          <td colspan="5">
          <table>
          <tr>
          <td style= "aling:center">
          <?php if ($pageNum_listadeexptes >= 2) { ?><a href="<?php printf("%s?pageNum_listadeexptes=%d%s", $currentPage, 0, $queryString_listadeexptes); ?>">Primero</a><?php } ?>
          </td>
          <td style= "aling:center">
          <?php if ($pageNum_listadeexptes >= 1) { ?><a href="<?php printf("%s?pageNum_listadeexptes=%d%s", $currentPage, max(0, $pageNum_listadeexptes - 1), $queryString_listadeexptes); ?>">Anterior</a><?php } ?>
          </td>
          <td style= "aling:center">
          <?php if ($totalRows_listadeexptes >= 101) { ?><a href="<?php printf("%s?pageNum_listadeexptes=%d%s", $currentPage, min($totalPages_listadeexptes, $pageNum_listadeexptes + 1), $queryString_listadeexptes); ?>">Siguiente</a><?php } ?>
          </td>
          <td style= "aling:center">
          <?php if ($totalRows_listadeexptes >= 201) { ?><a href="<?php printf("%s?pageNum_listadeexptes=%d%s", $currentPage, $totalPages_listadeexptes, $queryString_listadeexptes); ?>">Último</a><?php } ?>
          </td>
          <td style= "aling:center">

          </td>
          </tr>
          </table>
          </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr class="lineas2">
          <td>Exte.Nº</td>
          <td>&nbsp;</td>
          <td>Infrac. Nº</td>
          <td>Infracciones Cometidas</td>
          <td>Infractor</td>
          <td> OBSERVACIONES</td>
          <td>Retenciones</td>
          <td>OBLADA</td>
          <td>Cumplimiento</td>
        </tr>
  <?php do { ?>
        <tr class="lineas1" STYLE="background-color: <?php if ($row_listadeexptes['RES_DEFINI'] == 'S') { echo 'GREEN'; } ?>; ">
          <td rowspan="2" align="right"><a href="personas/modinf.php?ID=<?php echo $row_listadeexptes['ID']; ?>"><?php echo $row_listadeexptes['ID']; ?></a><br><span style="font-size: 2em;"><?php echo $row_listadeexptes['EXPTE']; ?></span><br><?php echo date_format(date_create($row_listadeexptes['FECHASIEN']),'d/m/Y'); ?></td>
          <td align="right" STYLE="background-color: <?php if ($row_listadeexptes['DNINOMCONST'] == 'V') { echo 'YELLOW'; } elseif ($row_listadeexptes['DNINOMCONST'] == 'N') { echo 'RED'; } elseif ($row_listadeexptes['DNINOMCONST'] == 'X') { echo 'ORANGE'; } ?>; ">&nbsp;<a href="exptes/expte.php?exp=<?php echo $row_listadeexptes['ID']; ?>"><img src="images/flechita.png" width="16" height="18" alt=""/></a></td>
          <td align="right"><?php echo $row_listadeexptes['INFACTA']; ?></td>
          <td colspan="2"></td>
          <td rowspan="2" style="width: 150px"><span style="width: 300px"><?php echo $row_listadeexptes['INFOBSER']; ?></span><br><?php if (isset($row_listadeexptes['RES_DEFININRO'])) { ?><br>Resoluci&oacute;n: <?php } ?><?php echo $row_listadeexptes['RES_DEFININRO']; ?><br><?php echo $row_listadeexptes['RES_CUMPLI']; ?>

          </td>
          <td align="center"><?php if ($row_listadeexptes['RETLICE'] == 'S') { // Show if recordset not empty ?>
              Licencia
  <?php } // Show if recordset not empty ?></td>
          <td align="center"><?php echo $row_listadeexptes['OBLADA']; ?></td>
          <td rowspan="2" align="left" valign="top">
                          <?php echo $row_listadeexptes['RES_CUMPLI']; ?><br>
          </td>
        </tr>
        <tr class="lineas2" STYLE="background-color: <?php if ($row_listadeexptes['RES_DEFINI'] == 'S') { echo 'GREEN'; } ?>; ">
          <td>&nbsp;</td>
          <td colspan="2">Datos de la Infracción:<br>Fecha: <?php echo date_format(date_create($row_listadeexptes['INFFECHA']),'d/m/Y'); ?><br>
            Hora: <?php echo date_format(date_create($row_listadeexptes['INFHORA']),'H:i'); ?><br>
          Lugar: <?php echo $row_listadeexptes['INFLUG']; ?></td>
          <td><p><span style="width: 300px"><span class="boton_calendario"><strong><?php echo $row_listadeexptes['APELLIDOS']; ?>
                  <?php if ($row_listadeexptes['APELLIDOS'] != NULL) { // Show if recordset not empty ?>
                  ,
                  <?php } // Show if recordset not empty ?>
                  <?php echo $row_listadeexptes['NOMBRES']; ?></strong></span><br>
                  <?php echo $row_listadeexptes['DNI']; ?></span></p>
            <p>Datos del Vehículo:<br>
              <?php if ($row_listadeexptes['INFAUTOPAT'] <> NULL) { // Show if recordset not empty ?>
              Patente Nº
              <?php } // Show if recordset not empty ?><?php if($row_listadeexptes['INFAUTOPAT'] == NULL) { echo 'no hay datos'; } else { echo $row_listadeexptes['INFAUTOPAT']; } ?>
          </p></td>
          <td><?php if ($row_listadeexptes['RETVEHI'] == 'S') { // Show if recordset not empty ?>
              Vehículo
  <?php } // Show if recordset not empty ?></td>
          <td></td>
        </tr>
        <?php } while ($row_listadeexptes = mysql_fetch_assoc($listadeexptes)); ?>
        <tr>
          <td></td>
          <td></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
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
mysql_free_result($listadeexptes);

?>
