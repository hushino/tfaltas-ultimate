<?php require_once('Connections/tfx.php'); ?>
<?php $xand = '';
$xandz = '';
$xand02 = '';
$xand03 = '';
$xand04 = '';
$quest_fech1 = ''; ?>
<?php
if (!isset($_SESSION)) {
    session_start();
}
$MM_authorizedUsers = "5";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup)
{
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

$MM_restrictGoTo = "ronglogin.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {
    $MM_qsChar = "?";
    $MM_referrer = $_SERVER['PHP_SELF'];
    if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
    if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0)
        $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
    $MM_restrictGoTo = $MM_restrictGoTo . $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
    header("Location: " . $MM_restrictGoTo);
    exit;
}
?>
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


$quest_listadeexptes = "WHERE ID >= 0";
if (isset($_GET['search'])) {
    $quest_listadeexptes = " WHERE CONCAT_WS(' ',ID,EXPTE,YEAR(FECHASIEN),APELLIDOS,NOMBRES,INFACTA,DNI,INFAUTOPAT) LIKE '%" . $_GET['search'] . "%'";
}

$quest_fech1 = "";
$quest_fech2 = "";
$xand04 = '';
if (isset($_GET['bdate1']) && isset($_GET['bdate2'])) {
    $quest_fech1 = $_GET['bdate1'];
    $quest_fech2 = $_GET['bdate2'];
    $xand04 = " WHERE FECHASIEN between '" . $quest_fech1 . "' AND '" . $quest_fech2 . "' ";

} else {
    $xand04 = '';
}
if ($quest_fech1 == '') {
    $xand04 = '';
};
mysql_select_db($database_tfx, $tfx);
$query_productividad = "SELECT USUR, COUNT(*) AS CANT FROM causas $xand04 GROUP BY USUR";
$productividad = mysql_query($query_productividad, $tfx) or die(mysql_error());
$row_productividad = mysql_fetch_assoc($productividad);

?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Tribunal de Faltas</title>

  <?php include('modules/headerfirst.php'); ?>

  <link href="css/tfaltas.css" rel="stylesheet" type="text/css">
  <link href="css/barra.css" rel="stylesheet" type="text/css">
  <script type="text/javascript">
  function showContent() {
    element = document.getElementById("contoblada");
    check = document.getElementById("oblada");
    if (check.checked) {
      element.style.display = 'block';
      $('#obladarec').prop('required', true);
    } else {
      element.style.display = 'none';
      $('#obladarec').prop('required', false);
    }
  }
  </script>
</head>

<body>
  <?php include('barra.php'); ?>
  <?php //echo $query_limit_listadeexptes; ?>
  <?php //echo $quest_fech1; ?>
  <div id="contenido">Productividad<br>
    <form action="productividad.php" method="get">
      Rango de Fechas a Buscar<br>

      <br>

      Desde<input type="date" name="bdate1"> - hasta <input type="date" name="bdate2"><br>
      <input type="submit" name="buscar" value="Filtrar" />


    </form>
    <?php
    if (isset($_GET['inf'])) {
        echo 'Filtrado por: ' . $row_infracc['HIPOTESIS'];
    } ?><?php
    if (isset($_GET['search'])) {
        echo "Filtrado por: '" . $_GET['search'] . "'. ";
    } ?>
    <br>
    <table width="700" cellspacing="0">
      <tbody>
        <tr>
          <td COLSPAN="2">Cantidad de actas cargadas por Usuario</td>
          <td>&nbsp;</td>

        </tr>
        <tr class="lineas2">
          <td>USUARIO</td>
          <td>CANTIDAD</td>
          <td></td>

        </tr>
        <?php do { ?>

        <tr class="lineas1">

          <td align="right"><?php echo $row_productividad['USUR']; ?></td>
          <td align="right"><?php echo $row_productividad['CANT']; ?></td>
          <td align="right"></td>


        </tr>

        <?php } while ($row_productividad = mysql_fetch_assoc($productividad)); ?>
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
mysql_free_result($productividad);
?>