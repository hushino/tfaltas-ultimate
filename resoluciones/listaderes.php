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

mysql_select_db($database_tfx, $tfx);
$query_listaresol = "SELECT *, operop(DEPORIG) DEPEN,(SELECT group_concat(ART,' : ', LEFT(HIPOTESIS,40) SEPARATOR '<strong>;<br></strong> ') FROM infrac 
WHERE INFRACC LIKE CONCAT('%%',IDINF,'%%')) AS INFRACCIONES FROM listainfractor ORDER BY RESFECHA";
$listaresol = mysql_query($query_listaresol, $tfx) or die(mysql_error());
$row_listaresol = mysql_fetch_assoc($listaresol);
$totalRows_listaresol = mysql_num_rows($listaresol);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lista de Resoluciones</title>
    <link rel="stylesheet" href="../css/fontawesome58all.css">
    <link rel="stylesheet" href="../css/fontgooglerobotocss.css">
    <link href="../css/mdboostrap/bootstrap.css" rel="stylesheet">
    <link href="../css/mdboostrap/mdb.css" rel="stylesheet">
    <script type="text/javascript" src="../css/mdboostrap/js/jquery.js"></script>
    <script type="text/javascript" src="../css/mdboostrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="../css/mdboostrap/js/mdb.js"></script>
    <link href="../css/barra.css" rel="stylesheet" type="text/css">
    <link href="../css/tfaltas.css" rel="stylesheet" type="text/css">
</head>

<body><?php include('../barra.php'); ?>
<div id="contenido">
    <fieldset>
        <legend>Lista de Resoluciones</legend>

        <table width="100%" border="1">
            <tbody>
            <tr>
                <td>Fecha</td>
                <td>Resolución</td>
                <td>Nombre</td>
                <td>DNI</td>
                <td>Tipo de Infracc.</td>
                <td>Causa</td>
                <td>Dependencia</td>
                <td>Infracción</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <?php do { ?>
                <tr>
                <td><?php echo date_format(date_create($row_listaresol['RESFECHA']), 'd/m/Y'); ?></td>
                <td><?php echo $row_listaresol['NRORESOL']; ?></td>
                <td><?php echo $row_listaresol['APELLIDOS']; ?>, <?php echo $row_listaresol['NOMBRES']; ?></td>
                <td><?php echo $row_listaresol['DNI']; ?></td>
                <td><?php echo $row_listaresol['TIPINFRAC']; ?></td>
                <td><?php echo $row_listaresol['NROCAUSA']; ?></td>
                <td><?php echo $row_listaresol['DEPORIG']; ?></td>
                <td><?php echo $row_listaresol['INFRACCIONES']; ?></td>
                <td><a href="mod-resolu.php?idres=<?php echo $row_listaresol['IDRES']; ?>"><img
                                src="../images/flechita.png" width="16" height="18" alt=""/></a></td>
                <td>&nbsp;</td>
                </tr>
            <?php } while ($row_listaresol = mysql_fetch_assoc($listaresol)); ?>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
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

    </fieldset>
</div>
</body>
</html>
<?php
mysql_free_result($listaresol);
?>
