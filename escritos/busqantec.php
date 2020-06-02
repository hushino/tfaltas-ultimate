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
$query_causa = sprintf("SELECT *, femes(FECHASIEN) FECHAX, operop(DEPORIG) AS DIOR, operop(OBLATIPO) AS OBLATIPOX, operpersona(ID) AS HIDXX, IF(mod(ID,2) = 0,'Rolando Manuel Pankow','Carlos Arturo Serravalle') AS SECRE, concat(if(isnull(DIRCALLE),'',DIRCALLE),if((DIRNRO is not null),' N&#176; ',''),if((DIRNRO is not null),DIRNRO,''),if((DIRPISO is not null),'Pº ',''),if((DIRPISO is not null),DIRPISO,''),if((DIRDPTO is not null),'Dpto ',''),if((DIRDPTO is not null),DIRDPTO,''),if((DIRMZ is not null),' Mz ',''),if((DIRMZ is not null),DIRMZ,''),if((DIRCS is not null),' Cs ',''),if((DIRCS is not null),DIRCS,''),if((DIRMBK is not null),' Mbk ',''),if((DIRMBK is not null),DIRMBK,'')) AS DIREC, concat('expte. N&#176; ',`causas`.`EXPTE`,' - A&#241;o ',year(`causas`.`INFFECHA`)) AS EXPTEX, fechames(INFFECHA) INFFECHAX, CONCAT(TRIM(APELLIDOS),' ', TRIM(IF(LOCATE(' ',NOMBRES) = 0,NOMBRES,LEFT(NOMBRES,LOCATE(' ',NOMBRES)-1)))) AS NOMBUSQ, (SELECT GROUP_CONCAT('Art. ',ART,' - ',HIPOTESIS SEPARATOR '; ') AS INFX FROM infrac WHERE causas.INFART LIKE CONCAT('%%',IDINF,'%%')) AS LISTINFRAC FROM causas WHERE ID = %s", GetSQLValueString($colname_causa, "int"));
$causa = mysql_query($query_causa, $tfx) or die(mysql_error());
$row_causa = mysql_fetch_assoc($causa);
$totalRows_causa = mysql_num_rows($causa);

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
$descripantec = $descripantec . '; <br>' . $row_antec['descrip'];
} while ($row_antec = mysql_fetch_assoc($antec));
} else { $descripantec = ''; }
if (isset($row_antec2['descrip2'])) {
$descripantec = $descripantec . 'Tiene antecedentes posteriores a junio de 2015: ';
do {
$descripantec = $descripantec . '<br>' . $row_antec2['descrip2'];
} while ($row_antec2 = mysql_fetch_assoc($antec2));
} else { $descripantec = $descripantec . 'No tiene antecedentes posteriores a junio de 2015'; }


// $contenido = str_replace('@#MesyAnhoActual', $fecha_mesyanio, $contenido);
do {
$bbbbbb = $row_repla['CODIGO'];
if ($row_repla['AGRUPAMIENTO'] == 'causa') { $aaaaaa = $row_causa['' . $bbbbbb . '']; }
else if ($row_repla['AGRUPAMIENTO'] == 'antec') { $aaaaaa = $descripantec; }
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
<link rel="stylesheet" type="text/css" href="../css/imprimir.css" media="print" />
<link rel="stylesheet" type="text/css" href="../css/escritos.css" />
</head>

<body style = "font-family: Calibri; font-size: 11pt; line-height: 175%;">
<div contenteditable = "true" style = "text-align:justify;" id = "exa">
<?php echo $contenido; ?>
<?php echo $descripantec; ?>
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
mysql_free_result($repla);
mysql_free_result($antec);
mysql_free_result($antec2);
mysql_free_result($escrito);
?>
