<?php require_once('../Connections/tfx.php'); ?>
<?php $arrayMeses = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
   'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
   $fecha_mesyanio = $arrayMeses[date('m')-1]." de ".date('Y'); ?>
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
    $String = ereg_replace("[�����]","a",$String);
    $String = ereg_replace("[�����]","A",$String);
    $String = ereg_replace("[����]","I",$String);
    $String = ereg_replace("[����]","i",$String);
    $String = ereg_replace("[����]","e",$String);
    $String = ereg_replace("[����]","E",$String);
    $String = ereg_replace("[������]","o",$String);
    $String = ereg_replace("[�����]","O",$String);
    $String = ereg_replace("[����]","u",$String);
    $String = ereg_replace("[����]","U",$String);
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
$query_causa = sprintf("SELECT *, operop(DEPORIG) AS DIOR, operpersona(ID) AS HIDXX, CONCAT('expte. N&#176; ',EXPTE,'/',YEAR(FECHASIEN)) AS EXPTENRO, concat('expte. N&#176; ',`causas`.`EXPTE`,' - A&#241;o ',year(`causas`.`INFFECHA`)) AS EXPTEX, CONCAT(TRIM(APELLIDOS),' ', TRIM(IF(LOCATE(' ',NOMBRES) = 0,NOMBRES,LEFT(NOMBRES,LOCATE(' ',NOMBRES)-1)))) AS NOMBUSQ, IF(mod(ID,2) = 0,'Rolando Manuel Pankow','Carlos Arturo Serravalle') AS SECRE FROM causas WHERE ID = %s", GetSQLValueString($colname_causa, "int"));
$causa = mysql_query($query_causa, $tfx) or die(mysql_error());
$row_causa = mysql_fetch_assoc($causa);
$totalRows_causa = mysql_num_rows($causa);

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
$query_antec = sprintf("SELECT CONCAT( IF(documento IS NULL,'','DNI N&#176; '),IF(documento IS NULL,'', documento), IF(acta IS NULL,'',' '), IF(acta IS NULL, '', acta),IF(fechahoraacta IS NULL, '',', de fecha '), IF(fechahoraacta IS NULL, '',fechasql(fechahoraacta)), IF(recibonro IS NULL,'',', recibo N&#176; '),
IF(recibonro IS NULL,'',recibonro), IF(recibofecha IS NULL, '',', de fecha: '), IF(recibofecha IS NULL,'',fechasql(recibofecha)), IF(HECHO IS NULL,'', ', Hecho: '), IF(HECHO IS NULL,'', hecho),'; ' ) AS descrip FROM actasantec WHERE documento = %s", GetSQLValueString($ex2_antec, "int"));
$antec = mysql_query($query_antec, $tfx) or die(mysql_error());
$row_antec = mysql_fetch_assoc($antec);
$totalRows_antec = mysql_num_rows($antec);

$ex2_antec2 = $row_causa['DNI'];
$ex2_antec2cau = $row_causa['ID'];
mysql_select_db($database_tfx, $tfx);
$query_antec2 = sprintf("SELECT INFACTA, CONCAT(' Acta N&#176; ',INFACTA,', de fecha ',fechasql(INFFECHA),', ',IF(OBLADA = 'S','Fue oblada','no fue oblada'),', expte. N&#176; ',EXPTE,'/',YEAR(FECHASIEN),', ',(SELECT GROUP_CONCAT('Art. ',ART,' - ',HIPOTESIS SEPARATOR '; ') AS INFX FROM infrac WHERE causas.INFART LIKE CONCAT('%%',IDINF,'%%')), IF(RES_DEFINI = 'S',(SELECT CONCAT(', con Resoluci&oacute;n Definitiva N&#176; ',NRORESOL,', de fecha ',fechasql(RESFECHA),';') AS RESOLUT FROM listainfractor WHERE NROCAUSA = causas.ID),', sin Resoluci&oacute;n'),'; ') AS descrip2 FROM causas WHERE DNI = %s AND ID NOT LIKE %s", GetSQLValueString($ex2_antec2, "int"),GetSQLValueString($ex2_antec2cau, "int"));
$antec2 = mysql_query($query_antec2, $tfx) or die(mysql_error());
$row_antec2 = mysql_fetch_assoc($antec2);
$totalRows_antec2 = mysql_num_rows($antec2);

$descripantec = '';
if (isset($row_antec['descrip'])) {
$descripantec = 'tiene antecedentes anteriores a junio de 2015: ';
do {
$descripantec = $descripantec . '; ' . $row_antec['descrip'];
} while ($row_antec = mysql_fetch_assoc($antec));
} else { $descripantec = 'No tiene antecedentes anteriores a junio de 2015. '; }
if (isset($row_antec2['descrip2'])) {
$descripantec = $descripantec . 'Tiene antecedentes posteriores a junio de 2015: ';
do {
$descripantec = $descripantec . '' . $row_antec2['descrip2'];
} while ($row_antec2 = mysql_fetch_assoc($antec2));
} else { $descripantec = $descripantec . 'No tiene antecedentes posteriores a junio de 2015'; }
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>TFaltas - 2do provehido desp de averiguar la matricula</title>
<link rel="stylesheet" type="text/css" href="../css/imprimir.css" media="print" />
<link rel="stylesheet" type="text/css" href="../css/escritos.css" />
</head>

<body style = "font-family: Calibri; font-size: 11pt; line-height: 175%;">
<div style = "position: absolute; font-size:xx-small;"><br>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row_causa['EXPTEX']; ?><br></div>
<div contenteditable = "true" style = "text-align:justify;" id = "exa">Clorinda, ___ de <?php echo $fecha_mesyanio; ?>. - Visto el informe que antecede, por Secretar&iacute;a verif&iacute;quese los antecedentes que pudiera registrar <strong><?php echo $row_causa['NOMBRES']; ?> <?php echo $row_causa['APELLIDOS']; ?></strong>,  D.N.I. N&#176; <strong><?php echo $row_causa['DNI']; ?></strong> y d&eacute;jese constancia. Y c&iacute;tese al TITULAR registral para que en el t&eacute;rmino de cinco (5) d&iacute;as h&aacute;biles formule descargo por escrito y ofrezca las pruebas que hagan a su derecho, haci&eacute;ndole saber que dentro del mismo plazo se podr&aacute; recibir su descargo en forma verbal, o en su defecto denuncie al conductor del veh&iacute;culo al momento de la infracci&oacute;n (Ley 24.449, art. 75 Inc. c.). L&iacute;brese c&eacute;dula con los recaudos del art. 41 Ord. N&#176; 625/2014. -

<br><br><br><br><br><br><br><br><br><br>Nota de Secretar&iacute;a:<br>
Verificadas las bases de datos de la Direcci&oacute;n de Tr&aacute;nsito de la Municipalidad de Clorinda y el Registro de Infractores de este Tribunal, se constat&oacute; que <strong><?php echo $row_causa['NOMBRES']; ?> <?php echo $row_causa['APELLIDOS']; ?></strong>,  D.N.I. N&#176; <strong><?php echo $row_causa['DNI']; ?></strong>, <?php echo $descripantec; ?>. Es todo cuanto puedo informar.-

<div style ="text-align:right;">Secretar&iacute;a, ____ de <?php echo $fecha_mesyanio; ?>.-</div></div>

  
</body>
</html>
<?php
mysql_free_result($causa);
mysql_free_result($montosmultas);
mysql_free_result($summultas);
?>
