<?php require_once('../Connections/tfx.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "4,5";
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
<?php $arrayMeses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
   'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
   $fecha_mesyanio = $arrayMeses[date('m')-1]." de ".date('Y'); ?>
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
$query_causa = sprintf("SELECT *, operop(DEPORIG) AS DIOR, operpersona(ID) AS HIDXX, IF(mod(ID,2) = 0,'Rolando Manuel Pankow','Carlos Arturo Serravalle') AS SECRE, concat(if(isnull(DIRCALLE),'',DIRCALLE),if((DIRNRO is not null),' N&#176; ',''),if((DIRNRO is not null),DIRNRO,''),if((DIRPISO is not null),'Pº ',''),if((DIRPISO is not null),DIRPISO,''),if((DIRDPTO is not null),'Dpto ',''),if((DIRDPTO is not null),DIRDPTO,''),if((DIRMZ is not null),' Mz ',''),if((DIRMZ is not null),DIRMZ,''),if((DIRCS is not null),' Cs ',''),if((DIRCS is not null),DIRCS,''),if((DIRMBK is not null),' Mbk ',''),if((DIRMBK is not null),DIRMBK,'')) AS DIREC, concat('Expte. N&#176; ',`causas`.`EXPTE`,' - A&#241;o ',year(`causas`.`INFFECHA`)) AS EXPTEX, fechames(INFFECHA) INFFECHAX FROM causas WHERE ID = %s", GetSQLValueString($colname_causa, "int"));
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
mysql_select_db($database_tfx, $tfx);
$query_antec = sprintf("SELECT CONCAT('<br>Acta N&#176; ',acta,', de fecha ',fechasql(fechahoraacta),', pagada mediante recibo N&#176; ',recibonro,';') AS descrip FROM actasantec WHERE documento = %s", GetSQLValueString($ex2_antec, "int"));
$antec = mysql_query($query_antec, $tfx) or die(mysql_error());
$row_antec = mysql_fetch_assoc($antec);
$totalRows_antec = mysql_num_rows($antec);

$ex2_antec2 = $row_causa['DNI'];
mysql_select_db($database_tfx, $tfx);
$query_antec2 = sprintf("SELECT INFACTA, CONCAT('<br>Acta N&#176; ',INFACTA,', de fecha ',fechasql(INFFECHA),', ',IF(OBLADA = 'S','Fue oblada','no fue oblada'),', expte. N&#176; ',ID,'/',YEAR(FECHASIEN),'',IF(RES_DEFINI = 'S',(SELECT CONCAT(', con Resoluci&oacute;n Definitiva N&#176; ',NRORESOL,', de fecha ',fechasql(RESFECHA),';') AS RESOLUT FROM listainfractor WHERE NROCAUSA = $colname_causa),', sin Resoluci&oacute;n;')) AS descrip FROM causas WHERE DNI = %s", GetSQLValueString($ex2_antec, "int"));
$antec2 = mysql_query($query_antec2, $tfx) or die(mysql_error());
$row_antec2 = mysql_fetch_assoc($antec2);
$totalRows_antec2 = mysql_num_rows($antec2);

$ex2_dats = $row_causa['ID'];
mysql_select_db($database_tfx, $tfx);
$query_dats = sprintf("SELECT * FROM cau_dats WHERE dats_cau = %s ORDER BY dats_id DESC", GetSQLValueString($ex2_dats, "int"));
$dats = mysql_query($query_dats, $tfx) or die(mysql_error());
$row_dats = mysql_fetch_assoc($dats);
$totalRows_dats = mysql_num_rows($dats);
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>TFaltas - Oficio a Transito</title>
<link rel="stylesheet" type="text/css" href="../css/imprimir.css" media="print" />
<link rel="stylesheet" type="text/css" href="../css/escritos.css" />
</head>

<body style = "font-family: Calibri; font-size: 11pt; line-height: 175%;">
<div contenteditable = "true" style = "text-align:justify;" id = "exa">
<div style ="text-align:center;text-decoration:underline;">O F I C I O</div>
<div style ="text-align:right;">Clorinda, ___ de <?php echo $fecha_mesyanio; ?>.-</div>

<br>Sr. Director
<br>Direcci&oacute;n de Tr&aacute;nsito
<br>Municipalidad de Clorinda
<br>SU DESPACHO

<p class="primeralinea">C&uacute;mpleme dirigirme a Usted desde el <?php echo $row_causa['EXPTEX']; ?>, caratulado: "<strong><?php echo $row_causa['APELLIDOS']; ?>, <?php echo $row_causa['NOMBRES']; ?> s/ Acta de Infracci&oacute;n N&#176; <?php echo $row_causa['INFACTA']; ?> - (Dir. <?php echo $row_causa['DIOR']; ?>)"</strong>, del registro del Tribunal Municipal de Faltas de la Ciudad de Clorinda, a cargo del Dr. OSCAR ALBERTO VIRGONA, Secretar&iacute;a a mi cargo, a fin de solicitarle la restituci&oacute;n del ciclomotor dominio <?php echo $row_causa['INFAUTOPAT']; ?>, <?php do { ?><?php echo $row_dats['dats_value']; ?>;
        <?php } while ($row_dats = mysql_fetch_assoc($dats)); ?>__________, marca __________, modelo _____________, al Sr. Rodolfo Alejandro Suarez, D.N.I. N&#176; 22.927.399, previa verificaci&oacute;n de las condiciones de circulaci&oacute;n. -</p>
<p class="primeralinea">La resoluci&oacute;n que ordena la presente, dice: "Clorinda, ___ de <?php echo $fecha_mesyanio; ?>.-... ... RESUELVO: Restituir al Sr. Rodolfo Alejandro Suarez, D.N.I. N&#176; 22.927.399, motoveh&iacute;culo dominio colocado N&#176; 073JRW, marca Honda, modelo BIZ 125 ES, que se encuentra bajo custodia de la Direcci&oacute;n de Tr&aacute;nsito de la Municipalidad de Clorinda, previa verificaci&oacute;n de las condiciones de circulaci&oacute;n, en el mismo estado en que fuera retenido. L&iacute;brese oficio para la mencionada Direcci&oacute;n, encomendando haga saber a este Tribunal el cumplimiento efectivo de la medida.. Fdo.: Dr. Oscar Alberto Virgona - Juez. -". -</p>
<p class="primeralinea">Saludo a Usted muy atentamente.-</p>

</div>
</div>
</body>
</html>
<?php
mysql_free_result($causa);
mysql_free_result($montosmultas);
mysql_free_result($summultas);
?>
