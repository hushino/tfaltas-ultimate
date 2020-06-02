<?php require_once('../Connections/tfx.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formulario")) {
    $insertSQL = sprintf("UPDATE escmodelos SET ESCAU = %s, ESCDOC = %s, CONTEN = %s WHERE IDESC =%s",
        GetSQLValueString($_POST['caux'], "text"),
        GetSQLValueString($_POST['escx'], "text"),
        GetSQLValueString($_POST['area2'], "text"),
        GetSQLValueString($_GET['esc'], "text"));

    mysql_select_db($database_tfx, $tfx);
    $Result1 = mysql_query($insertSQL, $tfx) or die(mysql_error());

    $insertGoTo = "escritover.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $insertGoTo));
}

$colname_escrito = "-1";
if (isset($_GET['esc'])) {
    $colname_escrito = $_GET['esc'];
}
mysql_select_db($database_tfx, $tfx);
$query_escrito = "SELECT (SELECT concat('',causas.EXPTE,'/',right(year(causas.FECHASIEN),2)) FROM CAUSAS WHERE ID = CAUS ) AS EXPTEX, (SELECT CONCAT(IF(APELLIDOS IS NULL,'',TRIM(APELLIDOS)),IF(NOMBRES IS NULL,'',', '),IF(NOMBRES IS NULL,'',TRIM(IF(LOCATE(' ',NOMBRES) = 0,NOMBRES,LEFT(NOMBRES,LOCATE(' ',NOMBRES)-1)))),' S/ Acta N ', IF(causas.INFACTA IS NULL,'XXXCCC',causas.INFACTA), ' (Dir. ', IF(DEPORIG IS NULL,'',operop(DEPORIG)),')') FROM CAUSAS WHERE ID = CAUS) AS CARAT, operop(DESTINT) AS DESTINO, operop(MOTIVO) AS MOTIV, fechasql(FEPAS) AS FECHAPAS, IDPAS, USURPAS FROM pasesinter ORDER BY DESTINO, FEPAS DESC";
$escrito = mysql_query($query_escrito, $tfx) or die(mysql_error());
$row_escrito = mysql_fetch_assoc($escrito);
$totalRows_escrito = mysql_num_rows($escrito);

?>
    <!doctype html>
    <html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Tribunal de Faltas - Libro de Pases</title>
        <link href="../css/barra.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="../css/fontawesome58all.css">
        <link rel="stylesheet" href="../css/fontgooglerobotocss.css">
        <link href="../css/mdboostrap/bootstrap.css" rel="stylesheet">
        <link href="../css/mdboostrap/mdb.css" rel="stylesheet">
        <script type="text/javascript" src="../css/mdboostrap/js/jquery.js"></script>
        <script type="text/javascript" src="../css/mdboostrap/js/bootstrap.js"></script>
        <script type="text/javascript" src="../css/mdboostrap/js/mdb.js"></script>
    </head>
    <body>
    <?php include('../barra.php'); ?>
    <div>Escritos</div>
    <div><a href="ingpases.php">Nuevo</a></div>
    <div>
        <table>
            <tr>
                <td>Expte.</td>
                <td>Caratula</td>
                <td>Fecha</td>
            </tr>
            <?php do { ?>
                <tr>
                    <td><?php echo $row_escrito['EXPTEX']; ?></td>
                    <td><?php echo $row_escrito['CARAT']; ?></td>
                    <td><?php echo $row_escrito['FECHAPAS']; ?></td>
                </tr>
            <?php } while ($row_escrito = mysql_fetch_assoc($escrito)); ?>
        </table>


        <div>
            <a href="escritover.php?esc=<?php echo $row_escrito['IDPAS']; ?>"><?php echo $row_escrito['IDPAS'] . ' - ' . $row_escrito['EXPTEX'] . ' - ' . $row_escrito['CARAT'] . ' ------> ' . $row_escrito['DESTINO'] . '----' . $row_escrito['FECHAPAS']; ?></a>
        </div>

    </div>

    </body>
    </html>
<?php
mysql_free_result($escrito);
?>