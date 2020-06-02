<?php require_once('../Connections/tfx.php'); ?>
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
<?php $arrayMeses = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
   'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
   $fecha_mesyanio = $arrayMeses[date('m')-1]." de ".date('Y'); ?>
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

$colname_causa = "-1";
if (isset($_GET['exp'])) {
  $colname_causa = $_GET['exp'];
}
mysql_select_db($database_tfx, $tfx);
$query_causa = sprintf("SELECT *, femes(FECHASIEN) FECHAX, operop(DEPORIG) AS DIOR, operop(OBLATIPO) AS OBLATIPOX, operpersona(ID) AS HIDXX, IF(mod(ID,2) = 0,'Rolando Manuel Pankow','Carlos Arturo Serravalle') AS SECRE, concat(if(isnull(DIRCALLE),'',DIRCALLE),if((DIRNRO is not null),' N&#176; ',''),if((DIRNRO is not null),DIRNRO,''),if((DIRPISO is not null),'Pº ',''),if((DIRPISO is not null),DIRPISO,''),if((DIRDPTO is not null),'Dpto ',''),if((DIRDPTO is not null),DIRDPTO,''),if((DIRMZ is not null),' Mz ',''),if((DIRMZ is not null),DIRMZ,''),if((DIRCS is not null),' Cs ',''),if((DIRCS is not null),DIRCS,''),if((DIRMBK is not null),' Mbk ',''),if((DIRMBK is not null),DIRMBK,'')) AS DIREC, concat('expte. N&#176; ',`causas`.`EXPTE`,' - A&#241;o ',year(`causas`.`FECHASIEN`)) AS EXPTEX, fechames(INFFECHA) INFFECHAX, CONCAT(TRIM(APELLIDOS),' ', TRIM(IF(LOCATE(' ',NOMBRES) = 0,NOMBRES,LEFT(NOMBRES,LOCATE(' ',NOMBRES)-1)))) AS NOMBUSQ, (SELECT GROUP_CONCAT('Art. ',ART,' - ',HIPOTESIS SEPARATOR '; ') AS INFX FROM infrac WHERE causas.INFART LIKE CONCAT('%%',IDINF,'%%')) AS LISTINFRAC, (SELECT GROUP_CONCAT('',ART SEPARATOR ', ') AS INFX FROM infrac WHERE causas.INFART LIKE CONCAT('%%',IDINF,'%%')) AS LISTINFARTIC FROM causas WHERE ID = %s", GetSQLValueString($colname_causa, "int"));
$causa = mysql_query($query_causa, $tfx) or die(mysql_error());
$row_causa = mysql_fetch_assoc($causa);
$totalRows_causa = mysql_num_rows($causa);

$dni_canant = $row_causa['DNI'];
$fech_canant = $row_causa['INFFECHA'];
$antemulti = 1;

mysql_select_db($database_tfx, $tfx);
$query_canant = sprintf("SELECT
sum(if(DATE_ADD(INFFECHA, INTERVAL (SELECT REINCI FROM infrac WHERE causas.INFART LIKE CONCAT('%%',IDINF,'%%') ORDER BY REINCI DESC LIMIT 1) YEAR) > '$fech_canant', '1','')) AS sumasC FROM causas WHERE DEPORIG = '82' AND DNI = '$dni_canant' AND ID <> %s AND INFFECHA < '$fech_canant'", GetSQLValueString($colname_causa, "int"));
$canant = mysql_query($query_canant, $tfx) or die(mysql_error());
$row_canant = mysql_fetch_assoc($canant);
$totalRows_canant = mysql_num_rows($canant);
$cantantec = $row_canant['sumasC'];
switch ($cantantec) {
case '1':
$antemulti = 1.25;
break;
case '2':
$antemulti = 1.50;
break;
case '3':
$antemulti = 1.75;
break;
case '4':
case '5':
case '6':
case '7':
case '8':
case '9':
$antemulti = $cantantec - 2;
break;

}



$ex_montosmultas = "-1";
if (isset($_GET['exp'])) {
  $ex_montosmultas = $_GET['exp'];
}
mysql_select_db($database_tfx, $tfx);
$query_montosmultas = sprintf("SELECT CONCAT('Art. ',ART,' - ',HIPOTESIS) AS INFX, (sum(UT) * $antemulti) AS SUMUT, UT*(SELECT DETALLES FROM opciones WHERE IDC=85) AS UTX, (UT*(SELECT DETALLES FROM opciones WHERE IDC=85)/2) AS UTX50, (nrosenletras(sum(UT))*$antemulti) AS UTLETRAS, nrosenletras((sum(UT) * $antemulti)*3) AS UTX3LET, ((sum(UT) * $antemulti)*3) AS UTX3 FROM infrac WHERE (SELECT INFART FROM causas WHERE ID=%s) LIKE CONCAT('%%',IDINF,'%%')", GetSQLValueString($ex_montosmultas, "int"));
$montosmultas = mysql_query($query_montosmultas, $tfx) or die(mysql_error());
$row_montosmultas = mysql_fetch_assoc($montosmultas);
$totalRows_montosmultas = mysql_num_rows($montosmultas);

$ex_lai = "-1";
if (isset($_GET['exp'])) {
  $ex_lai = $_GET['exp'];
}
mysql_select_db($database_tfx, $tfx);
$query_leyartinc = sprintf("SELECT GERIUNDO, GERIUTIPO, LEYNRO, LEYINC, LEYART, LEYGRAART, LEYGRAINC FROM infrac WHERE (SELECT INFART FROM causas WHERE ID=%s) LIKE CONCAT('%%',IDINF,'%%') ORDER BY GERIUTIPO", GetSQLValueString($ex_lai, "int"));
$leyartinc = mysql_query($query_leyartinc, $tfx) or die(mysql_error());
$row_leyartinc = mysql_fetch_assoc($leyartinc);
$totalRows_leyartinc = mysql_num_rows($leyartinc);

$sumartinc = '';
$sumincgrav = '';
$sumgeriundo = '';
$plural = '';

if (isset($row_leyartinc['GERIUNDO'])) {
$contador = '0'; $comas = ''; $texto2 = ''; $sumgeriundo = ''; $sumartinc = '' . '';

$sumincgrav = '' . $row_leyartinc['LEYGRAART'] . ' inc. ';
do {
  if($contador >= '1') {$comas = ', ';}
  if($totalRows_leyartinc >= '2') {$plural = ', ';}

$contador = $contador + 1;

$sumgeriundo = $sumgeriundo . $comas . $row_leyartinc['GERIUNDO'];
$sumartinc = $sumartinc . $comas . $row_leyartinc['LEYART'] . ' inc. ' . $row_leyartinc['LEYINC'];
$sumincgrav = $sumincgrav . $comas . $row_leyartinc['LEYGRAINC'];
} while ($row_leyartinc = mysql_fetch_assoc($leyartinc));
} else { $sumgeriundo = ' _._._._._._._._._._._._._ '; $sumartinc = ' ***______*** '; $sumincgrav = '**___**';}
if($totalRows_leyartinc >= '2') {$plural = ' a los arts. ';}
if($totalRows_leyartinc == '1') {$plural = ' al art. ';}
$sumartinc = $plural . $sumartinc;

$ex2_summultas = "-1";
if (isset($_GET['exp'])) {
  $ex2_summultas = $_GET['exp'];
}
mysql_select_db($database_tfx, $tfx);
$query_summultas = sprintf("SELECT SUM(UT) AS UTSUM,  SUM(UT*(SELECT DETALLES FROM opciones WHERE IDC=85)) AS UTXSUM,  SUM((UT*(SELECT DETALLES FROM opciones WHERE IDC=85)/2)) AS UTX50SUM  FROM infrac WHERE (SELECT INFART FROM causas WHERE ID=%s) LIKE CONCAT('%%',IDINF,'%%')", GetSQLValueString($ex2_summultas, "int"));
$summultas = mysql_query($query_summultas, $tfx) or die(mysql_error());
$row_summultas = mysql_fetch_assoc($summultas);
$totalRows_summultas = mysql_num_rows($summultas);

$colname_escrito = "-1";
if (isset($_GET['esc'])) {
  $colname_escrito = $_GET['esc'];
}
mysql_select_db($database_tfx, $tfx);
$query_escrito = sprintf("SELECT IDESC,ESCAU,ESCTIPO,ESCDOC,CONTEN,ESCNOM,CONTEN2,CONTEN2FORMT FROM escmodelos WHERE IDESC = %s", GetSQLValueString($colname_escrito, "int"));
$escrito = mysql_query($query_escrito, $tfx) or die(mysql_error());
$row_escrito = mysql_fetch_assoc($escrito);
$totalRows_escrito = mysql_num_rows($escrito);
$contenido = $row_escrito['CONTEN'];
$contenido2 = $row_escrito['CONTEN2'];

mysql_select_db($database_tfx, $tfx);
$query_repla = "SELECT COMODIN,CODIGO,AGRUPAMIENTO FROM escreplace";
$repla = mysql_query($query_repla, $tfx) or die(mysql_error());
$row_repla = mysql_fetch_assoc($repla);
$totalRows_repla = mysql_num_rows($repla);

$ex2_antec = $row_causa['DNI'];
$VNOBU = $row_causa['NOMBUSQ'];
$ex2_antecNOM =  ('' == $VNOBU) ? "%" . '***' . "%" : "%" . $VNOBU . "%" ;
mysql_select_db($database_tfx, $tfx);
$query_antec = sprintf("SELECT CONCAT( IF(documento IS NULL,'','DNI N&#176; '),IF(documento IS NULL,'', documento), IF(acta IS NULL,'',' '), IF(acta IS NULL, '', acta),IF(fechahoraacta IS NULL, '',', de fecha '), IF(fechahoraacta IS NULL, '',fechasql(fechahoraacta)), IF(recibonro IS NULL,'',', recibo N&#176; '),
IF(recibonro IS NULL,'',recibonro), IF(recibofecha IS NULL, '',', de fecha: '), IF(recibofecha IS NULL,'',fechasql(recibofecha)), IF(HECHO IS NULL,'', ', Hecho: '), IF(HECHO IS NULL,'', hecho),'; ' ) AS descrip FROM actasantec WHERE documento = %s", GetSQLValueString($ex2_antec, "text"));
$antec = mysql_query($query_antec, $tfx) or die(mysql_error());
$row_antec = mysql_fetch_assoc($antec);
$totalRows_antec = mysql_num_rows($antec);

$ex2_antec2 = $row_causa['DNI'];
$ex2_antec2cau = $row_causa['ID'];
mysql_select_db($database_tfx, $tfx);
$query_antec2 = sprintf("SELECT INFACTA, CONCAT(' Acta N&#176; ',INFACTA,', de fecha ',fechasql(INFFECHA),', ',IF(OBLADA = 'S','Fue oblada','no fue oblada'),', expte. N&#176; ',EXPTE,'/',YEAR(FECHASIEN),', ',(SELECT GROUP_CONCAT('Art. ',ART,' - ',HIPOTESIS SEPARATOR '; ') AS INFX FROM infrac WHERE causas.INFART LIKE CONCAT('%%',IDINF,'%%')), IF(RES_DEFINI = 'S',(SELECT CONCAT(', con Resoluci&oacute;n Definitiva N&#176; ',NRORESOL,', de fecha ',fechasql(RESFECHA),';') AS RESOLUT FROM listainfractor WHERE NROCAUSA = causas.ID),', sin Resoluci&oacute;n'),'; ') AS descrip2 FROM causas WHERE DNI = %s AND ID NOT LIKE %s", GetSQLValueString($ex2_antec2, "text"),GetSQLValueString($ex2_antec2cau, "int"));
$antec2 = mysql_query($query_antec2, $tfx) or die(mysql_error());
$row_antec2 = mysql_fetch_assoc($antec2);
$totalRows_antec2 = mysql_num_rows($antec2);



$ex2_libre = $row_causa['DNI'];
mysql_select_db($database_tfx, $tfx);
$query_libred = sprintf("SELECT CONCAT('Expte. N&#176; ',EXPTE,'/',YEAR(FECHASIEN),', ', ' Acta N&#176; ',INFACTA,' (',fechasql(INFFECHA),')',operop(DEPORIG),', ',
IF(OBLADA = 'S','Fue oblada','no oblada'), IF(RES_DEFINI = 'S',CONCAT(', Resol. Def. N&#176; ',RES_DEFININRO,IF(RES_CUMPLI IS NULL,'',': '),IF(RES_CUMPLI IS NULL,'',RES_CUMPLI)),', sin Resoluci&oacute;n'),'*** ') AS LIBREDEUDA FROM causas WHERE DNI = %s ORDER BY INFFECHA", GetSQLValueString($ex2_libre, "text"));
$libred = mysql_query($query_libred, $tfx) or die(mysql_error());
$row_libred = mysql_fetch_assoc($libred);
$totalRows_libred = mysql_num_rows($libred);


$descripantec = '';
if (isset($row_antec2['descrip2'])) {
$descripantec = $descripantec . 'Tiene antecedentes: ';
do {
$descripantec = $descripantec . '' . $row_antec2['descrip2'];
} while ($row_antec2 = mysql_fetch_assoc($antec2));
} else { $descripantec = $descripantec . 'no tiene antecedentes'; }


$descrilibre = '';
if (isset($row_libred['LIBREDEUDA'])) {
$descrilibre = 'Registra las siguientes faltas: ';
do {
$descrilibre = $descrilibre . ' ' . $row_libred['LIBREDEUDA'];
} while ($row_libred = mysql_fetch_assoc($libred));
} else { $descrilibre = 'No registra faltas pendientes de cumplimiento. '; }



// $contenido = str_replace('@#MesyAnhoActual', $fecha_mesyanio, $contenido);
do {
$bbbbbb = $row_repla['CODIGO'];
if ($row_repla['AGRUPAMIENTO'] == 'causa') { $aaaaaa = $row_causa['' . $bbbbbb . '']; }
else if ($row_repla['AGRUPAMIENTO'] == 'montosmultas') { $aaaaaa = $row_montosmultas['' . $bbbbbb . '']; }
else if ($row_repla['AGRUPAMIENTO'] == 'antec') { $aaaaaa = $descripantec; }
else if ($row_repla['AGRUPAMIENTO'] == 'libre') { $aaaaaa = $descrilibre; }
else if ($row_repla['AGRUPAMIENTO'] == 'leyinc') { $aaaaaa = $sumartinc; }
else if ($row_repla['AGRUPAMIENTO'] == 'graveinc') { $aaaaaa = $sumincgrav; }
else if ($row_repla['AGRUPAMIENTO'] == 'geriu') { $aaaaaa = $sumgeriundo; }
else { $aaaaaa = $fecha_mesyanio; }
$contenido = str_replace($row_repla['COMODIN'], $aaaaaa, $contenido);
$contenido2 = str_replace($row_repla['COMODIN'], $aaaaaa, $contenido2);
} while ($row_repla = mysql_fetch_assoc($repla));


?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TFaltas - <?php echo $row_escrito['ESCNOM']; ?></title>
<script type="text/javascript" src="../js/froala_editor.min.js"></script>
<script>
      $(function(){
        $('#exa')
          .on('froalaEditor.initialized', function (e, editor) {
            $('#edit').parents('form').on('submit', function () {
              console.log($('#edit').val());
              return false;
            })
          })
          .froalaEditor({enter: $.FroalaEditor.ENTER_P, placeholderText: null})
      });
  </script>
<link rel="stylesheet" type="text/css" href="../css/imprimir.css" media="print" />
<link rel="stylesheet" type="text/css" href="../css/escritos.css" />
<link rel="stylesheet" type="text/css" href="../css/froala_editor.min.css" />
</head>

<body style = "font-family: Calibri; font-size: 11pt; line-height: 175%;">
<br><div style = "position: absolute; font-size:xx-small;">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row_causa['EXPTEX']; ?><br><?php echo  $row_antec2['descrip2']; ?></div>

<div contenteditable = "true" style = "text-align:justify;" id = "exa">
<?php //echo $antemulti . $cantantec . $query_canant . $query_montosmultas; ?>
<?php echo $contenido; ?>
</div>
<?php if (isset($row_escrito['CONTEN2'])) { ?>
<div contenteditable = "true" id = "<?php echo $row_escrito['CONTEN2FORMT']; ?>">
<?php echo $contenido2; ?>
</div>
<?php } ?>
</body>
</html>
<?php
mysql_free_result($causa);
mysql_free_result($montosmultas);
mysql_free_result($summultas);
mysql_free_result($repla);
mysql_free_result($antec);
mysql_free_result($antec2);
mysql_free_result($escrito);
?>
