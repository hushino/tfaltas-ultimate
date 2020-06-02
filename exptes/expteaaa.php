<?php require_once('../Connections/tfx.php'); ?>
<?php $clas = ''; ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "3,4,5";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) {
  // For security, start by assuming the visitor is NOT authorized.
  $isValid = False;

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username.
  // Therefore, we know that a user is NOT logged in if that Session variable is blank.
  if (!empty($UserName)) {
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login.
    // Parse the strings into arrays.
    $arrUsers = Explode(",", $strUsers);
    $arrGroups = Explode(",", $strGroups);
    if (in_array($UserName, $arrUsers)) {
      $isValid = true;
    }
    // Or, you may restrict access to only certain users based on their username.
    if (in_array($UserGroup, $arrGroups)) {
      $isValid = true;
    }
    if (($strUsers == "") && false) {
      $isValid = true;
    }
  }
  return $isValid;
}

$MM_restrictGoTo = "../ronglogin.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0)
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo);
  exit;
}
?>
<?php
Function stripAccents($String)
{
    $String = ereg_replace("[äáàâãª]","a",$String);
    $String = ereg_replace("[ÁÀÂÃÄ]","A",$String);
    $String = ereg_replace("[ÍÌÎÏ]","I",$String);
    $String = ereg_replace("[íìîï]","i",$String);
    $String = ereg_replace("[éèêë]","e",$String);
    $String = ereg_replace("[ÉÈÊË]","E",$String);
    $String = ereg_replace("[óòôõöº]","o",$String);
    $String = ereg_replace("[ÓÒÔÕÖ]","O",$String);
    $String = ereg_replace("[úùûü]","u",$String);
    $String = ereg_replace("[ÚÙÛÜ]","U",$String);
    return $String;
}

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

$colname_causa = "-1";
if (isset($_GET['exp'])) {
  $colname_causa = $_GET['exp'];
}
mysql_select_db($database_tfx, $tfx);
$query_causa = sprintf("SELECT *, operop(DEPORIG) AS DIOR, operop(OBLATIPO) AS OBLTIP, operpersona(ID) AS HIDXX, CONCAT(TRIM(APELLIDOS),' ', TRIM(IF(LOCATE(' ',NOMBRES) = 0,NOMBRES,LEFT(NOMBRES,LOCATE(' ',NOMBRES)-1)))) AS NOMBUSQ FROM causas WHERE ID = %s", GetSQLValueString($colname_causa, "int"));
$causa = mysql_query($query_causa, $tfx) or die(mysql_error());
$row_causa = mysql_fetch_assoc($causa);
$totalRows_causa = mysql_num_rows($causa);

mysql_select_db($database_tfx, $tfx);
$query_codema = sprintf("SELECT *, CONCAT(TRIM(APELLIDOS),' ', TRIM(IF(LOCATE(' ',NOMBRES) = 0,NOMBRES,LEFT(NOMBRES,LOCATE(' ',NOMBRES)-1)))) AS NOMBUSQ FROM causas_codem WHERE COOEXPTE = %s", GetSQLValueString($colname_causa, "int"));
$codema = mysql_query($query_codema, $tfx) or die(mysql_error());
$row_codema = mysql_fetch_assoc($codema);
$totalRows_codema = mysql_num_rows($codema);

$ex_montosmultas = "-1";
if (isset($_GET['exp'])) {
  $ex_montosmultas = $_GET['exp'];
}
mysql_select_db($database_tfx, $tfx);
$query_montosmultas = sprintf("SELECT CONCAT('Art. ',ART,' - ',HIPOTESIS) AS INFX, UT, UT*(SELECT DETALLES FROM opciones WHERE IDC=85) AS UTX, (UT*(SELECT DETALLES FROM opciones WHERE IDC=85)/2) AS UTX50   FROM infrac WHERE (SELECT INFART FROM causas WHERE ID=%s) LIKE CONCAT('%%',IDINF,'%%')", GetSQLValueString($ex_montosmultas, "int"));
$montosmultas = mysql_query($query_montosmultas, $tfx) or die(mysql_error());
$row_montosmultas = mysql_fetch_assoc($montosmultas);
$totalRows_montosmultas = mysql_num_rows($montosmultas);

$ex2_summultas = "-1";
if (isset($_GET['exp'])) {
  $ex2_summultas = $_GET['exp'];
}
mysql_select_db($database_tfx, $tfx);
$query_summultas = sprintf("SELECT SUM(UT) AS UTSUM,  SUM(UT*(SELECT DETALLES FROM opciones WHERE IDC=85)) AS UTXSUM,  SUM((UT*(SELECT DETALLES FROM opciones WHERE IDC=85)/2)) AS UTX50SUM  FROM infrac WHERE (SELECT INFART FROM causas WHERE ID=%s) LIKE CONCAT('%%',IDINF,'%%')", GetSQLValueString($ex2_summultas, "int"));
$summultas = mysql_query($query_summultas, $tfx) or die(mysql_error());
$row_summultas = mysql_fetch_assoc($summultas);
$totalRows_summultas = mysql_num_rows($summultas);

$ex2_antec = $row_causa['DNI'];
$VNOBU = $row_causa['NOMBUSQ'];
$ex2_antecNOM =  ('' == $VNOBU) ? "%" . '***' . "%" : "%" . $VNOBU . "%" ;
mysql_select_db($database_tfx, $tfx);
$query_antec = sprintf("SELECT CONCAT( IF(documento IS NULL,'<br>','<br>DNI N&#176; '),IF(documento IS NULL,'', documento), IF(acta IS NULL,'',', acta N&#176; '), IF(acta IS NULL, '', acta),IF(fechahoraacta IS NULL, '',', de fecha '), IF(fechahoraacta IS NULL, '',fechasql(fechahoraacta)), IF(recibonro IS NULL,'',', recibo N&#176; '), IF(recibonro IS NULL,'',recibonro), IF(recibofecha IS NULL, '',', de fecha: '), IF(recibofecha IS NULL,'',fechasql(recibofecha)), IF(HECHO IS NULL,'', ', Hecho: '), IF(HECHO IS NULL,'', hecho),'; ' ) AS descrip FROM actasantec WHERE documento = %s", GetSQLValueString($ex2_antec, "text"));
$antec = mysql_query($query_antec, $tfx) or die(mysql_error());
$row_antec = mysql_fetch_assoc($antec);
$totalRows_antec = mysql_num_rows($antec);

$ex2_antec2 = $row_causa['DNI'];
mysql_select_db($database_tfx, $tfx);
$query_antec2 = sprintf("SELECT INFACTA, CONCAT('<br>Acta N&#176; ',INFACTA,', de fecha ',fechasql(INFFECHA),', ',IF(OBLADA = 'S','Fue oblada','no fue oblada'),', expte. N&#176; ',EXPTE,'/',YEAR(FECHASIEN),', ',(SELECT GROUP_CONCAT('Art. ',ART,' - ',HIPOTESIS SEPARATOR '; ') AS INFX FROM infrac WHERE causas.INFART LIKE CONCAT('%%',IDINF,'%%')), IF(RES_DEFINI = 'S',(SELECT GROUP_CONCAT(', con Resoluci&oacute;n Definitiva N&#176; ',NRORESOL,', de fecha ',fechasql(RESFECHA) SEPARATOR '; ') AS RESOLUT FROM listainfractor WHERE NROCAUSA = causas.ID),', sin Resoluci&oacute;n;')) AS descrip FROM causas WHERE DNI = %s", GetSQLValueString($ex2_antec2, "text"));
$antec2 = mysql_query($query_antec2, $tfx) or die(mysql_error());
$row_antec2 = mysql_fetch_assoc($antec2);
$totalRows_antec2 = mysql_num_rows($antec2);

$ex2_dats = $row_causa['ID'];
mysql_select_db($database_tfx, $tfx);
$query_dats = sprintf("SELECT * FROM cau_dats WHERE dats_cau = %s ORDER BY dats_id ASC", GetSQLValueString($ex2_dats, "int"));
$dats = mysql_query($query_dats, $tfx) or die(mysql_error());
$row_dats = mysql_fetch_assoc($dats);
$totalRows_dats = mysql_num_rows($dats);

mysql_select_db($database_tfx, $tfx);
$query_escrito = "SELECT IDESC,ESCAU,ESCTIPO,ESCDOC,CONTEN,ESCNOM,CLASIF FROM escmodelos ORDER BY CLASIF";
$escrito = mysql_query($query_escrito, $tfx) or die(mysql_error());
$row_escrito = mysql_fetch_assoc($escrito);
$totalRows_escrito = mysql_num_rows($escrito);

mysql_select_db($database_tfx, $tfx);
$query_escpresenta = sprintf("SELECT * FROM escpresent WHERE ESCAU = %s", GetSQLValueString($colname_causa, "int"));
$escpresenta = mysql_query($query_escpresenta, $tfx) or die(mysql_error());
$row_escpresenta = mysql_fetch_assoc($escpresenta);
$totalRows_escpresenta = mysql_num_rows($escpresenta);


$ex2_backs = $row_causa['ID'];
mysql_select_db($database_tfx, $tfx);
$query_backs = sprintf("SELECT * FROM blackupcausas WHERE BACAU = %s ORDER BY CAMBFECHA DESC", GetSQLValueString($ex2_backs, "int"));
$backs = mysql_query($query_backs, $tfx) or die(mysql_error());
$row_backs = mysql_fetch_assoc($backs);
$totalRows_backs = mysql_num_rows($backs);


$ex2_resolut = $row_causa['ID'];
mysql_select_db($database_tfx, $tfx);
$query_resolut = sprintf("SELECT fechados(RESFECHA) AS FECHH, NRORESOL, CONCAT(NROCAUSA,'/',(SELECT YEAR(FECHASIEN) FROM causas WHERE ID = NROCAUSA)) AS EXPTE FROM listainfractor WHERE NROCAUSA = '%s' ORDER BY IDRES", GetSQLValueString($ex2_resolut, "int"));
$resolut = mysql_query($query_resolut, $tfx) or die(mysql_error());
$row_resolut = mysql_fetch_assoc($resolut);
$totalRows_resolut = mysql_num_rows($resolut);



?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>TFaltas - expediente</title>
<link href="../css/tfaltas.css" rel="stylesheet" type="text/css">
<link href="../css/barra.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/jquery.validaracta.js"></script>
<!-- Your Reload Script -->
<script type='text/javascript'>
    $(document).ready(function(){
        $(window).focus(function(){ window.location.reload(); });
    });
</script>
</head>

<body>
<?php include('../barra.php'); ?>
<?php // echo $ex2_antecNOM; ?>
<?php // echo $query_antec; ?>
<div id="contenido">
  <fieldset class="ui-widget-header">
    <legend>Datos de la Causa</legend><table width="100%" border="1">
  <tbody>
    <tr>
      <td>CARATULA: <a href="../personas/modinf.php?ID=<?php echo $row_causa['ID']; ?>">modificar</a></td>
      <td>&quot;<?php echo $row_causa['APELLIDOS']; ?>
        <?php if ($row_causa['APELLIDOS'] != NULL) { // Show if recordset not empty ?>,
  <?php } // Show if recordset not empty ?>
        <?php echo $row_causa['NOMBRES']; ?>
        <?php if ($row_causa['APELLIDOS'] == NULL) { // Show if recordset not empty ?>
        <?php echo $row_causa['INFAUTOPAT']; ?><?php } // Show if recordset not empty ?> S/ Acta de Infracc. NÂº <?php echo $row_causa['INFACTA']; ?>&quot; Dep.: <?php echo $row_causa['DIOR']; ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Expte. NÂº <?php echo $row_causa['EXPTE']; ?> - AÃ±o <?php echo $row_causa['FECHASIEN']; ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><?php if ($row_causa['RETLICE'] === "S") { ?>La licencia de Conducir fue Retenida<?php } ?><br><?php echo $row_causa['APELLIDOS']; ?><?php if ($row_causa['APELLIDOS'] != NULL) { ?>, <?php } ?><?php echo $row_causa['NOMBRES']; ?>. DNI <?php echo $row_causa['DNI']; ?> <form name="formx" id="formx" action="" method="POST"><input type ="hidden" name = "valordni" id ="valordni" value ="<?php echo $row_causa['DNI']; ?>" /><input type ="hidden" name = "expid" id ="expid" value ="<?php echo $row_causa['ID']; ?>" /></form><div id="verper"></div>
      <?php if ($row_causa['DNINOMCONST'] != 'V') { ?>
        <a href="../personas/verificarperson.php?exp=<?php echo $row_causa['ID']; ?>" target="popup" onClick="window.open(this.href, this.target,'width=600,height=250'); return false;">Verificar Datos</a>
        <?php } ?>
        <?php if ($row_causa['DNINOMCONST'] == 'V') { ?>Identidad Verificada<?php } ?>
        </td>
        <td>
                 <?php if (isset($row_causa['RES_DEFININRO'])) { ?>Resoluci&oacute;n: <?php } ?>
                          <?php do { ?><?php echo ' Nro. ' . $row_resolut['NRORESOL']; ?><br><?php } while ($row_resolut = mysql_fetch_assoc($resolut)); ?>
                          <?php if ($row_causa['RES_CUMPLI'] == NULL) { ?>
                                <a href="../personas/carcumpli.php?exp=<?php echo $row_causa['ID']; ?>" target="popup" onClick="window.open(this.href, this.target,'width=600,height=150'); return false;">cargar cumplimiento</a>
                          <?php } ?>
                          <br>
                          <?php echo $row_causa['RES_CUMPLI']; ?>
                          <?php if (isset($row_causa['RES_CUMPLI'])) { ?>
                                - <a href="../personas/carcumplimod.php?exp=<?php echo $row_causa['ID']; ?>" target="popup" onClick="window.open(this.href, this.target,'width=600,height=150'); return false;">mod</a>
                          <?php } ?>
        </td>
      <td>
                      <?php if (isset($row_codema['IDCODE'])) { ?><?php do { ?><?php echo $row_codema['APELLIDOS']; ?>, <?php echo $row_codema['NOMBRES']; ?>; <a href="../personas/modicodem.php?codemm=<?php echo $row_codema['IDCODE']; ?>" target="popup" onClick="window.open(this.href, this.target,'width=900,height=400'); return false;">mod</a><br>
        <?php } while ($row_codema = mysql_fetch_assoc($codema)); ?><?php } ?>
                      <a href="../personas/cargcodem.php?exp=<?php echo $row_causa['ID']; ?>" target="popup" onClick="window.open(this.href, this.target,'width=900,height=400'); return false;">Cargar Coimputado</a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><?php if (isset($row_causa['INFAUTOPAT'])) { // Show if recordset not empty ?>
        Patente <?php echo $row_causa['INFAUTOPAT']; ?> - <a href="../personas/carvehi.php?exp=<?php echo $row_causa['ID']; ?>" target="popup" onClick="window.open(this.href, this.target,'width=600,height=700'); return false;">cargar datos del vehiculo</a>
        <br> a nombre de: <?php do { ?><?php echo $row_dats['dats_value']; ?>;
        <?php } while ($row_dats = mysql_fetch_assoc($dats)); ?>
        <?php } // Show if recordset not empty ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><?php if ($row_causa['RETVEHI'] === "S") { // Show if recordset not empty ?>
        EL vehÃ­culo fue Retenido
          <?php } // Show if recordset not empty ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><a href="http://www.cuitonline.com/search.php?q=<?php echo stripAccents($row_causa['HIDXX']); ?>" TARGET = "_blank">Constatar datos del infractor</a></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Presentacion de Escritos
      <a href="../personas/cargescritos.php?exp=<?php echo $row_causa['ID']; ?>" target="popup" onClick="window.open(this.href, this.target,'width=600,height=700'); return false;">cargar escrito</a><br>
      <?php do { ?>
    <a href="../personas/modiescritos.php?esc=<?php echo $row_escpresenta['IDESC']; ?>&exp=<?php echo $row_causa['ID']; ?>" target="popup" onClick="window.open(this.href, this.target,'width=600,height=700'); return false;"><?php echo $row_escpresenta['ESCTIPO']; ?></a><br>
    <?php } while ($row_escpresenta = mysql_fetch_assoc($escpresenta)); ?>
      </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>

  </fieldset><fieldset><legend>infracciones</legend>

      <table width="100%" border="1">
        <tbody>
          <tr>
            <td>InfracciÃ³n</td>
            <td>Unidades Tributarias</td>
            <td>Monto</td>
            <td>Reduc. 50 %</td>
          </tr>
          <?php do { ?><tr>
            <td><?php echo $row_montosmultas['INFX']; ?></td>
            <td align="right"><?php echo $row_montosmultas['UT']; ?></td>
            <td align="right"><?php echo $row_montosmultas['UTX']; ?></td>
            <td align="right"><?php echo $row_montosmultas['UTX50']; ?></td>
          </tr>
          <?php } while ($row_montosmultas = mysql_fetch_assoc($montosmultas)); ?><tr>
            <td align="right">Totales</td>
            <td align="right"><?php echo $row_summultas['UTSUM']; ?></td>
            <td align="right"><?php echo $row_summultas['UTXSUM']; ?></td>
            <td align="right"><?php echo $row_summultas['UTX50SUM']; ?></td>
          </tr>
<tr>
            <td><?php if ($totalRows_antec2 >= 1) { ?><?php echo 'Reincidencias: ' . ($totalRows_antec2-1);?><?php } ?>

              <?php do { ?><?php if ($row_antec2['INFACTA'] <> $row_causa['INFACTA']) {echo $row_antec2['descrip'];} ?><?php } while ($row_antec2 = mysql_fetch_assoc($antec2)); ?>
</td>
            <td></td>
            <td><?php if ($totalRows_antec2 >= 1) { ?><?php echo '$ ' . ($row_summultas['UTXSUM'] * ((($totalRows_antec2-1)*0.25)+1)); ?><?php } ?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><?php if ($row_causa['OBLADA'] === "S") { ?>Consta que fue Pagada <?php } ?>
              <?php if ($row_causa['OBLADA'] === "S") { ?><br><?php echo $row_causa['OBLTIP'] . ' Nro '; ?><?php echo $row_causa['OBLANRO']; ?><?php echo $row_causa['OBLAFEC']; ?><?php echo $row_causa['OBLAPORC']; ?><?php } ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
<tr>
            <td><?php if ($totalRows_antec >= 1 OR $totalRows_antec2 >= 1) { ?>El infractor tiene los Siguientes antecedentes:<br> <?php } ?>
              <?php do { ?> <?php echo $row_antec['descrip']; ?><?php } while ($row_antec = mysql_fetch_assoc($antec)); ?>
              <?php do { ?><?php if ($row_antec2['INFACTA'] <> $row_causa['INFACTA']) {echo $row_antec2['descrip'];} ?><?php } while ($row_antec2 = mysql_fetch_assoc($antec2)); ?>
</td>
            <td><?php if ($totalRows_antec2 >= 1) { ?>Reincidencia:<br> <?php echo 'incremento: ' . ($row_summultas['UTXSUM'] * ((($totalRows_antec2-1)*0.25)+1)); echo 'Reincidencias: ' . ($totalRows_antec2-1);?><?php } ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </tbody>
  </table>

  </fieldset>
  <fieldset><legend>Procedimiento de la Causa</legend>
    <br>
    CUANDO PRIMERAMENTE NO SURGE EL IMPUTADO:<br><a href="../exptes/provavervehi.php?exp=<?php echo $row_causa['ID']; ?>" target="popup" onClick="window.open(this.href, this.target,'width=600,height=700'); return false;">averiguar de quien es la patente</a> - <a href="../exptes/prov2do.php?exp=<?php echo $row_causa['ID']; ?>" target="popup" onClick="window.open(this.href, this.target,'width=600,height=700'); return false;">2do provehido despues de saber patente</a> - <a href="../exptes/prov2doextjur.php?exp=<?php echo $row_causa['ID']; ?>" target="popup" onClick="window.open(this.href, this.target,'width=600,height=700'); return false;">2do provehido despues de saber patente extra&nacute;a jurisdicci&oacute;n</a><br></p>
    <p>Escritos Automaticos:</p>
    <?php do { ?>
    <?php if ($row_escrito['CLASIF'] != $clas) { echo $row_escrito['CLASIF'] . '<br>'; } ?>
    <a href="../escritos/escritocomodin.php?exp=<?php echo $row_causa['ID']; ?>&esc=<?php echo $row_escrito['IDESC']; ?>" target="popup" onClick="window.open(this.href, this.target,'width=600,height=700'); return false;"><?php echo $row_escrito['ESCNOM']; ?></a><br><?php $clas = $row_escrito['CLASIF']; ?>
    <?php } while ($row_escrito = mysql_fetch_assoc($escrito)); ?>
    <p>Medidas de Prueba</p>
    <p> Notificaciones</p>
      </fieldset>
<fieldset><legend>ResoluciÃ³n de la Causa</legend>
  <p><a href="../resoluciones/ing-resolu.php?exp=<?php echo $row_causa['ID']; ?>">Cargar Registro de Infractores</a> - <a href="../resoluciones/ing-resolu-plus.php?exp=<?php echo $row_causa['ID']; ?>">Cargar Resolución de Resultado Distinto</a></p>
</fieldset>

<fieldset><legend>Actualizaciones</legend>
            <table>
            <tr>Cargo inicialmente: <?php echo $row_causa['USUR']; ?></tr>
            <?php do { ?><tr>
                         <td align="right"><a href="../escritos/verbackups.php?exp=<?php echo $row_backs['IDESC']; ?>" target="popup" onClick="window.open(this.href, this.target,'width=800,height=700'); return false;"><?php echo $row_backs['CAMBFECHA']; ?></a></td>
                         <td align="right"><?php echo $row_backs['USUR']; ?></td>
                         </tr>
            <?php } while ($row_backs = mysql_fetch_assoc($backs)); ?>
            </table>
</fieldset>
</div>
</body>
</html>

<?php
mysql_free_result($causa);
mysql_free_result($montosmultas);
mysql_free_result($summultas);
mysql_free_result($antec);
mysql_free_result($antec2);
mysql_free_result($backs);
mysql_free_result($escpresenta);
mysql_free_result($escrito);
?>
