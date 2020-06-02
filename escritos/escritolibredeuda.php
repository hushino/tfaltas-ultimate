<?php require_once('../Connections/tfx.php'); ?>
<?php
$antnombre = $_GET['nombres'];
$antdomicilio = $_GET['domic'];
$antdni = $_GET['DNI'];

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
if (isset($_GET['DNI'])) {
  $colname_causa = $_GET['DNI'];
}
mysql_select_db($database_tfx, $tfx);
$query_causa = sprintf("SELECT *, femes(FECHASIEN) FECHAX, operop(DEPORIG) AS DIOR, operop(OBLATIPO) AS OBLATIPOX, operpersona(ID) AS HIDXX, IF(mod(ID,2) = 0,'Rolando Manuel Pankow','Carlos Arturo Serravalle') AS SECRE, concat(if(isnull(DIRCALLE),'',DIRCALLE),if((DIRNRO is not null),' N&#176; ',''),if((DIRNRO is not null),DIRNRO,''),if((DIRPISO is not null),'Pº ',''),if((DIRPISO is not null),DIRPISO,''),if((DIRDPTO is not null),'Dpto ',''),if((DIRDPTO is not null),DIRDPTO,''),if((DIRMZ is not null),' Mz ',''),if((DIRMZ is not null),DIRMZ,''),if((DIRCS is not null),' Cs ',''),if((DIRCS is not null),DIRCS,''),if((DIRMBK is not null),' Mbk ',''),if((DIRMBK is not null),DIRMBK,'')) AS DIREC, concat('expte. N&#176; ',`causas`.`EXPTE`,' - A&#241;o ',year(`causas`.`INFFECHA`)) AS EXPTEX, fechames(INFFECHA) INFFECHAX, CONCAT(TRIM(APELLIDOS),' ', TRIM(IF(LOCATE(' ',NOMBRES) = 0,NOMBRES,LEFT(NOMBRES,LOCATE(' ',NOMBRES)-1)))) AS NOMBUSQ, (SELECT GROUP_CONCAT('Art. ',ART,' - ',HIPOTESIS SEPARATOR '; ') AS INFX FROM infrac WHERE causas.INFART LIKE CONCAT('%%',IDINF,'%%')) AS LISTINFRAC FROM causas WHERE DNI = %s AND OBLADA IS NULL AND RES_CUMPLI IS NULL", GetSQLValueString($colname_causa, "int"));
$causa = mysql_query($query_causa, $tfx) or die(mysql_error());
$row_causa = mysql_fetch_assoc($causa);
$totalRows_causa = mysql_num_rows($causa);

$ex2_antec2 = $row_causa['DNI'];
$ex2_antec2cau = $row_causa['ID'];
mysql_select_db($database_tfx, $tfx);
$query_antec2 = sprintf("SELECT CONCAT('Expte. N&#176; ',EXPTE,'/',YEAR(FECHASIEN),', ', ' ') AS descrip2 FROM causas WHERE DNI LIKE %s", GetSQLValueString($ex2_antec2, "int"));
$antec2 = mysql_query($query_antec2, $tfx) or die(mysql_error());
$row_antec2 = mysql_fetch_assoc($antec2);
$totalRows_antec2 = mysql_num_rows($antec2);


$descripantec = '';
if (isset($row_antec2['descrip2'])) {
$descripantec = $descripantec . 'Registra Faltas Pendientes de Cumplimiento ante este Tribunal Municipal de Faltas dentro del ejido de esta Municipalidad de Clorinda: ';
$antnombre = $row_causa['NOMBRES'] . $row_causa['APELLIDOS'];
$antdomicilio = $row_causa['DIREC'];
$antdni = $_GET['DNI'];
do {
$descripantec = $descripantec . '' . $row_antec2['descrip2'];
} while ($row_antec2 = mysql_fetch_assoc($antec2));
} else { $descripantec = $descripantec . 'NO POSEE sanciones pendientes de cumplimiento ante este Tribunal Municipal de Faltas dentro del ejido de esta Municipalidad de Clorinda';

}



?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TFaltas - Certificado de Libre Deuda</title>
<link rel="stylesheet" type="text/css" href="../css/imprimiroficio.css" media="print" />
</head>

<body style = "font-family: Calibri; font-size: 11pt; line-height: 175%;">
<div contenteditable = "false" style = "text-align:justify;" id="exax">
<div style = "text-align: center;">CERTIFICADO DE LIBRE DEUDA N&deg; _______/2019</div>
<div style = "text-align: center;">EL TRIBUNAL DE FALTAS</div>
<div style = "text-align: center;">DE LA MUNICIPALIDAD DE CLORINDA</div>
<div style = "text-align: center;">CERTIFICA</div><br>

Que <strong><?php echo $antnombre; ?></strong>, titular del D.N.I. N&deg; <strong><?php echo $antdni; ?></strong>. -<br>
Con domicilio en <strong><?php echo $antdomicilio; ?><?php echo $row_causa['DIREC']; ?></strong>, Clorinda. -<br>
<?php echo $descripantec; ?> seg&uacute;n los registros existentes hasta el d&iacute;a de la fecha. -<br>
Se extiende el presente a su pedido y a los fines de su presentaci&oacute;n ante las personas f&iacute;sicas o jur&iacute;dicas, de car&aacute;cter p&uacute;blico o privado que resulten pertinentes. -
<br>
<br>
<br>
<table>
<tr>
<td style="border: 1px solid black;">
<br>
Verif.
</td>
</tr>
<tr>
<td style="border: 1px solid black;">
<br>
Num.
</td>
</tr>
<tr>
<td style="border: 1px solid black;">
<br>
<br>
</td>
</tr>
</table>

</div>

</body>
</html>
<?php
mysql_free_result($causa);
mysql_free_result($antec2);
?>
