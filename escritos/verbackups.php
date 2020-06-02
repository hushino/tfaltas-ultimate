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

$colname_backs = "-1";
if (isset($_GET['exp'])) {
  $ex2_backs = $_GET['exp'];
}

mysql_select_db($database_tfx, $tfx);
$query_backs = sprintf("SELECT * FROM blackupcausas WHERE IDESC = %s", GetSQLValueString($ex2_backs, "int"));
$backs = mysql_query($query_backs, $tfx) or die(mysql_error());
$row_backs = mysql_fetch_assoc($backs);
$totalRows_backs = mysql_num_rows($backs);

$fxxx = $row_backs['PREVE'];
$row_ar = explode(',',$fxxx);

$fzzz = $row_backs['POSTE'];
$borcau =  ' WHERE ID=' . $row_backs['BACAU'];
$fzzz2 = str_replace($borcau, '', $fzzz);
$row_ar2 = explode(',',$fzzz2);
?>

<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>TFaltas - <?php echo $row_backs['BACAU']; ?></title>
<link rel="stylesheet" type="text/css" href="../css/imprimir.css" media="print" />
<link rel="stylesheet" type="text/css" href="../css/escritos.css" />
</head>

<body style = "font-family: Calibri; font-size: 11pt; line-height: 175%;">

            <table>
                         <tr>
                             <td align="right"></td>
                             <td align="right">Cambios realizados por: <?php echo $row_backs['USUR']; ?></td>
                             <td align="right">En Fecha: <?php echo $row_backs['CAMBFECHA']; ?></td>
                         </tr>
                         <tr>
                             <td align="right"></td>
                             <td align="right">Estado Anterior</td>
                             <td align="right">Cambios realizados</td>
                         </tr>
                         <?php $xc = 0; for($i = 0; $i < (count($row_ar)-1); ++$i) { $xc=$xc+1; ?>
                         <tr style = "<?php If ($row_ar[$i] != $row_ar2[$i]) { ?>background-color:green;color:white;<?php } ?>">
                             <td align="right"><?php echo $xc; ?></td>
                             <td align="right"><?php echo $row_ar[$i]; ?></td>
                             <td align="right"><?php echo $row_ar2[$i]; ?></td>
                         </tr>
                         <?php }; ?>
            </table>>
</body>
</html>
<?php
mysql_free_result($backs);
?>
