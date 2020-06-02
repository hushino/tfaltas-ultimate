<?php require_once('../Connections/tfx.php'); ?>
<?php
if (!isset($_SESSION)) {
    session_start();
}
$MM_authorizedUsers = "3,4,5";
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

$MM_restrictGoTo = "../ronglogin.php";
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

$colname_modinf = "-1";
if (isset($_GET['ID'])) {
    $colname_modinf = $_GET['ID'];
}
mysql_select_db($database_tfx, $tfx);
$query_modinf = sprintf("SELECT * FROM causas WHERE ID = %s", GetSQLValueString($colname_modinf, "int"));
$modinf = mysql_query($query_modinf, $tfx) or die(mysql_error());
$row_modinf = mysql_fetch_assoc($modinf);
$totalRows_modinf = mysql_num_rows($modinf);

$updateBACKUP = sprintf("UPDATE causas SET DEPORIG=%s, INFACTA=%s, INFFECHA=%s, INFHORA=%s, INFLUG=%s, INFART=%s, INFVEHI=%s, INFAUTOPAT=%s, INFOBSER=%s, APELLIDOS=%s, NOMBRES=%s, DNI=%s, DIRCALLE=%s, DIRNRO=%s, DIRPISO=%s, DIRDPTO=%s, DIRMZ=%s, DIRCS=%s, DIRMBK=%s, LOCALIDAD=%s, DESCPROVINCIA=%s, OBLADA=%s, DNINAC=%s, RETLICE=%s, RETVEHI=%s, OBLATIPO=%s, OBLANRO=%s, USUR=%s WHERE ID=%s",
    GetSQLValueString($row_modinf['DEPORIG'], "text"),
    GetSQLValueString($row_modinf['INFACTA'], "text"),
    GetSQLValueString($row_modinf['INFFECHA'], "date"),
    GetSQLValueString($row_modinf['INFHORA'], "date"),
    GetSQLValueString($row_modinf['INFLUG'], "text"),
    GetSQLValueString($row_modinf['INFART'], "text"),
    GetSQLValueString($row_modinf['INFVEHI'], "text"),
    GetSQLValueString($row_modinf['INFAUTOPAT'], "text"),
    GetSQLValueString($row_modinf['INFOBSER'], "text"),
    GetSQLValueString($row_modinf['APELLIDOS'], "text"),
    GetSQLValueString($row_modinf['NOMBRES'], "text"),
    GetSQLValueString($row_modinf['DNI'], "text"),
    GetSQLValueString($row_modinf['DIRCALLE'], "text"),
    GetSQLValueString($row_modinf['DIRNRO'], "text"),
    GetSQLValueString($row_modinf['DIRPISO'], "text"),
    GetSQLValueString($row_modinf['DIRDPTO'], "text"),
    GetSQLValueString($row_modinf['DIRMZ'], "text"),
    GetSQLValueString($row_modinf['DIRCS'], "text"),
    GetSQLValueString($row_modinf['DIRMBK'], "text"),
    GetSQLValueString($row_modinf['LOCALIDAD'], "text"),
    GetSQLValueString($row_modinf['DESCPROVINCIA'], "text"),
    GetSQLValueString($row_modinf['OBLADA'], "text"),
    GetSQLValueString($row_modinf['DNINAC'], "text"),
    GetSQLValueString($row_modinf['RETLICE'], "text"),
    GetSQLValueString($row_modinf['RETVEHI'], "text"),
    GetSQLValueString($row_modinf['OBLATIPO'], "text"),
    GetSQLValueString($row_modinf['OBLANRO'], "text"),
    GetSQLValueString($row_modinf['USUR'], "int"),
    GetSQLValueString($row_modinf['ID'], "int"));


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario")) {
    $updateSQL = sprintf("UPDATE causas SET DEPORIG=%s, INFACTA=%s, INFFECHA=%s, INFHORA=%s, INFLUG=%s, INFART=%s, INFVEHI=%s, INFAUTOPAT=%s, INFOBSER=%s, APELLIDOS=%s, NOMBRES=%s, DNI=%s, DIRCALLE=%s, DIRNRO=%s, DIRPISO=%s, DIRDPTO=%s, DIRMZ=%s, DIRCS=%s, DIRMBK=%s, LOCALIDAD=%s, DESCPROVINCIA=%s, OBLADA=%s, DNINAC=%s, RETLICE=%s, RETVEHI=%s, OBLATIPO=%s, OBLANRO=%s WHERE ID=%s",
        GetSQLValueString($_POST['deporig'], "text"),
        GetSQLValueString($_POST['infacta'], "text"),
        GetSQLValueString($_POST['inffecha'], "date"),
        GetSQLValueString($_POST['infhora'], "date"),
        GetSQLValueString($_POST['influg'], "text"),
        GetSQLValueString($_POST['infart'], "text"),
        GetSQLValueString($_POST['infvehi'], "text"),
        GetSQLValueString($_POST['infautopat'], "text"),
        GetSQLValueString($_POST['infobser'], "text"),
        GetSQLValueString($_POST['apellidos'], "text"),
        GetSQLValueString($_POST['nombres'], "text"),
        GetSQLValueString($_POST['dni'], "text"),
        GetSQLValueString($_POST['dirCalle'], "text"),
        GetSQLValueString($_POST['dirNro'], "text"),
        GetSQLValueString($_POST['dirPiso'], "text"),
        GetSQLValueString($_POST['dirDpto'], "text"),
        GetSQLValueString($_POST['dirMz'], "text"),
        GetSQLValueString($_POST['dirCs'], "text"),
        GetSQLValueString($_POST['dirMbk'], "text"),
        GetSQLValueString($_POST['localidad'], "text"),
        GetSQLValueString($_POST['descProvincia'], "text"),
        GetSQLValueString($_POST['oblada'], "text"),
        GetSQLValueString($_POST['dninac'], "text"),
        GetSQLValueString($_POST['retlice'], "text"),
        GetSQLValueString($_POST['retvehi'], "text"),
        GetSQLValueString($_POST['infpago'], "text"),
        GetSQLValueString($_POST['obladarec'], "text"),
        GetSQLValueString($_POST['IDx'], "int"));
    $insertSQL2 = sprintf("INSERT INTO blackupcausas (BACAU,PREVE,POSTE, USUR, CAMBFECHA) VALUES(%s, %s, %s, %s, now())",
        GetSQLValueString($_POST['IDx'], "text"),
        GetSQLValueString($updateBACKUP, "text"),
        GetSQLValueString($updateSQL, "text"),
        GetSQLValueString($_SESSION['MM_Username'], "text"));
    mysql_select_db($database_tfx, $tfx);
    $Result1 = mysql_query($updateSQL, $tfx) or die(mysql_error());
    $Result2 = mysql_query($insertSQL2, $tfx) or die(mysql_error());

    $updateGoTo = "../exptes/expte.php?exp=" . $_POST['IDx'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        $updateGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $updateGoTo));
}

$colname_modinf = "-1";
if (isset($_GET['ID'])) {
    $colname_modinf = $_GET['ID'];
}
mysql_select_db($database_tfx, $tfx);
$query_modinf = sprintf("SELECT * FROM causas WHERE ID = %s", GetSQLValueString($colname_modinf, "int"));
$modinf = mysql_query($query_modinf, $tfx) or die(mysql_error());
$row_modinf = mysql_fetch_assoc($modinf);
$totalRows_modinf = mysql_num_rows($modinf);

mysql_select_db($database_tfx, $tfx);
$query_opvehi = "SELECT IDC, CAT, DETALLES FROM opciones WHERE CAT = 'vehiculo'";
$opvehi = mysql_query($query_opvehi, $tfx) or die(mysql_error());
$row_opvehi = mysql_fetch_assoc($opvehi);
$totalRows_opvehi = mysql_num_rows($opvehi);

$IDXX_multas = "-1";
if (isset($_GET['ID'])) {
    $IDXX_multas = $_GET['ID'];
}
mysql_select_db($database_tfx, $tfx);
$query_multas = sprintf("SELECT IDINF, CONCAT(art, ' - ',HIPOTESIS) AS names FROM infrac WHERE (SELECT INFART FROM causas WHERE ID=%s) LIKE CONCAT('%%',IDINF,'%%')", GetSQLValueString($IDXX_multas, "int"));
$multas = mysql_query($query_multas, $tfx) or die(mysql_error());
$row_multas = mysql_fetch_assoc($multas);
$totalRows_multas = mysql_num_rows($multas);

mysql_select_db($database_tfx, $tfx);
$query_depend = "SELECT IDC, CAT, DETALLES FROM opciones WHERE CAT = 'dependenci'";
$depend = mysql_query($query_depend, $tfx) or die(mysql_error());
$row_depend = mysql_fetch_assoc($depend);
$totalRows_depend = mysql_num_rows($depend);

mysql_select_db($database_tfx, $tfx);
$query_oppago = "SELECT IDC, CAT, DETALLES FROM opciones WHERE CAT = 'pagomedio'";
$oppago = mysql_query($query_oppago, $tfx) or die(mysql_error());
$row_oppago = mysql_fetch_assoc($oppago);
$totalRows_oppago = mysql_num_rows($oppago);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>T Faltas - Modificar Infraccion</title>

    <link rel="stylesheet" href="../css/fontawesome58all.css">
    <link rel="stylesheet" href="../css/fontgooglerobotocss.css">
    <link href="../css/mdboostrap/bootstrap.css" rel="stylesheet">
    <link href="../css/mdboostrap/mdb.css" rel="stylesheet">
    <script type="text/javascript" src="../css/mdboostrap/js/jquery.js"></script>
    <script type="text/javascript" src="../css/mdboostrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="../css/mdboostrap/js/mdb.js"></script>

    <link href="../css/tfaltas.css" rel="stylesheet" type="text/css">
    <link href="../css/validationengine.jquery.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/jquery.tokenjuzg.js"></script>
    <script language="javascript" type="text/javascript" src="../js/disableSubmits.js"></script>
    <link rel="stylesheet" href="../css/token-input.css" type="text/css"/>
    <link rel="stylesheet" href="../css/token-input-facebook.css" type="text/css"/>
    <link rel="stylesheet" href="../css/token-input-mac.css" type="text/css"/>
    <link href="../css/barra.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">
        $(document).ready(function () {
            $("#infartx").tokenInput("/tfaltas/consultas/listadeinfracciones.php", {
                prePopulate: <?php
                echo "["; do {
                    echo "{\"id\": \"" . $row_multas['IDINF'] . "\", \"name\": \"" . $row_multas['names'] . "\"}, ";
                } while ($row_multas = mysql_fetch_assoc($multas));
                echo "]";   ?>
                ,
                preventDuplicates: true,
                tokenLimit: 7
            });
        });

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
<?php include('../barra.php'); ?>
<div id="contenido">
    Modificar DATOS DE LA INFRACCION
    <div class="breadCrumbHolder breadCrumbModule">
        <div id="breadCrumb0" class="breadCrumb breadCrumbModule">
        </div>
    </div>
    <!--  Breadcrumb de Navegación (fin) -->

    <!-- Contenido (inicio) -->

    <div id="div_formulario">
        <form name="formulario" id="formulario" action="<?php echo $editFormAction; ?>" method="POST">
            <div style="margin-left: auto;">
                <fieldset class="ui-widget ui-widget-content ui-corner-all"
                          style="width: 500px; margin-left: auto; margin-right: auto;">
                    <legend class="ui-widget-header ui-corner-all ui-state-highlight" style="padding:1px 10px;"
                            id="grupoContacto">Datos de la Falta Cometida
                    </legend>
                    <table width="400" border="0" cellspacing="1" class="formulario"
                           style="width: 95%; text-align: right;">
                        <tr>
                            <td style="width: 200px; text-align: right;">Modif. Depend. de Origen:</td>
                            <td align="left" style="width: 200px;"><label for="deporig">
                                    <select name="deporig" id="deporig">
                                        <?php
                                        do {
                                            ?>
                                            <option value="<?php echo $row_depend['IDC'] ?>"<?php if (!(strcmp($row_depend['IDC'], $row_modinf['DEPORIG']))) {
                                                echo "selected=\"selected\"";
                                            } ?>><?php echo $row_depend['DETALLES'] ?></option>
                                            <?php
                                        } while ($row_depend = mysql_fetch_assoc($depend));
                                        $rows = mysql_num_rows($depend);
                                        if ($rows > 0) {
                                            mysql_data_seek($depend, 0);
                                            $row_depend = mysql_fetch_assoc($depend);
                                        }
                                        ?>
                                    </select>
                                </label></td>
                        </tr>
                        <tr id="filaActaInfr">
                            <td style="width: 200px; text-align: right;">Nº de expediente:
                                <input name="IDx" type="text" autofocus required="required" id="IDxx"
                                       style="width:100px;" tabindex="1" autocomplete="off"
                                       value="<?php echo $row_modinf['ID']; ?>" readonly/></td>
                            <td style="width: 200px;"><span style="width:100px;">Acta de Inf. Nº</span> <input
                                        name="infacta" type="text" required="required" id="infacta" style="width:100px;"
                                        tabindex="1" autocomplete="off" value="<?php echo $row_modinf['INFACTA']; ?>"/>
                            </td>
                        </tr>
                        <tr id="filaActaInfr">
                            <td style="width: 200px; text-align: right;">Fecha <input name="inffecha" type="date"
                                                                                      required="required" id="infdate"
                                                                                      style="width: 130px;" tabindex="2"
                                                                                      autocomplete="off"
                                                                                      value="<?php echo $row_modinf['INFFECHA']; ?>"/>
                            </td>
                            <td style="width: 200px; text-align: right;"><b>Hora</b> <input name="infhora" type="time"
                                                                                            required="required"
                                                                                            id="infhora"
                                                                                            style="width:70px;"
                                                                                            tabindex="3"
                                                                                            autocomplete="off"
                                                                                            value="<?php echo $row_modinf['INFHORA']; ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">Lugar en que se cometió la falta
                                <input name="influg" type="text" required="required" id="influg" style="width:315px;"
                                       tabindex="4" autocomplete="off" value="<?php echo $row_modinf['INFLUG']; ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="left" style="width:auto;">Infracción<br>

                                <input name="infart" type="text" id="infartx"/>
                            </td
                            >
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" align="right"><label for="infvehi">Tipo de Vehículo:</label>
                                <select name="infvehi" id="infvehi" value="<?php echo $row_modinf['INFVEHI']; ?>"
                                        style="width:100px;" tabindex="6">
                                    <?php
                                    do {
                                        ?>
                                        <option value="<?php echo $row_opvehi['IDC'] ?>"<?php if (!(strcmp($row_opvehi['IDC'], $row_modinf['INFVEHI']))) {
                                            echo "selected=\"selected\"";
                                        } ?>><?php echo $row_opvehi['DETALLES'] ?></option>
                                        <?php
                                    } while ($row_opvehi = mysql_fetch_assoc($opvehi));
                                    $rows = mysql_num_rows($opvehi);
                                    if ($rows > 0) {
                                        mysql_data_seek($opvehi, 0);
                                        $row_opvehi = mysql_fetch_assoc($opvehi);
                                    }
                                    ?>
                                </select>
                                <b>Dominio</b>:
                                <input name="infautopat" type="text" id="infautopat" style="width:70px;" tabindex="7"
                                       title="sólo 7 letras o números. Las letras en mayúsculas"
                                       value="<?php echo $row_modinf['INFAUTOPAT']; ?>"
                                       maxlength="7"/><?php // pattern="[A-Z0-9]{7}" ?></td>
                        </tr>
                        <tr>
                            <td>Oblada
                                <input <?php if (!(strcmp($row_modinf['OBLADA'], "S"))) {
                                    echo "checked=\"checked\"";
                                } ?> name="oblada" type="checkbox" id="oblada"
                                     title="Marcar si surge pago espontáneo del acta" value="S"
                                     onchange="javascript:showContent()">
                                <div id="contoblada" <?php if (!(strcmp($row_modinf['OBLADA'], "S"))) {
                                    echo "style=\"display: block; text-align: left; \"";
                                } else {
                                    echo "style=\"display: none; text-align: left; \"";
                                } ?> >
                                    <select name="infpago" id="infpago" style="width:150px;" tabindex="6">
                                        <?php do { ?>
                                            <option value="<?php echo $row_oppago['IDC'] ?>"<?php if (!(strcmp($row_oppago['IDC'], $row_modinf['OBLATIPO']))) {
                                                echo "selected=\"selected\"";
                                            } ?>><?php echo $row_oppago['DETALLES'] ?></option>
                                            <?php
                                        } while ($row_oppago = mysql_fetch_assoc($oppago));
                                        $rows = mysql_num_rows($oppago);
                                        if ($rows > 0) {
                                            mysql_data_seek($oppago, 0);
                                            $row_oppago = mysql_fetch_assoc($oppago);
                                        }
                                        ?></select><br>Recibo: <input name="obladarec" type="text" id="obladarec"
                                                                      style="width:150px;"
                                                                      title="Ingresar el Número de Recibo."
                                                                      value="<?php echo $row_modinf['OBLANRO']; ?>"/>
                                </div>
                            </td>
                            <td>Retención Licencia
                                <input <?php if (!(strcmp($row_modinf['RETLICE'], "S"))) {
                                    echo "checked=\"checked\"";
                                } ?> name="retlice" type="checkbox" id="retlice" value="S">
                                Retención de Vehículo
                                <input <?php if (!(strcmp($row_modinf['RETVEHI'], "S"))) {
                                    echo "checked=\"checked\"";
                                } ?> name="retvehi" type="checkbox" id="retvehi" value="S">
                                </label></td>
                        </tr>
                        <tr>
                            <td colspan="2">Observaciones
                                <input name="infobser" type="text" id="infobser" style="width: 380px;" tabindex="8"
                                       autocomplete="off" value="<?php echo $row_modinf['INFOBSER']; ?>"/></td>
                        </tr>
                    </table>
                </fieldset>
            </div>
            <br/>
            <div style="">
                <fieldset class="ui-widget ui-widget-content ui-corner-all"
                          style="width: auto; margin-left: auto; margin-right: auto;">
                    <legend class="ui-widget-header ui-corner-all ui-state-highlight" style="padding:1px 10px;"
                            id="grupoApellidoNombre">Datos del Infractor
                    </legend>
                    <div style="width: 50%; float:left">
                        <fieldset class="ui-widget ui-widget-content ui-corner-all"
                                  style="width: 200px; margin-left: auto; margin-right: auto;">
                            <legend class="ui-widget-header ui-corner-all ui-state-highlight" style="padding:1px 10px;"
                                    id="grupoDomicilio">Datos Personales
                            </legend>
                            <table class="formulario" style="width: 45%; text-align: justify;">
                                <tr>
                                    <th style="width:40px;" id="tituloApellido">Apellidos</th>
                                    <td><input name="apellidos" type="text" id="apellido" style="width:205px;"
                                               tabindex="9" autocomplete="off"
                                               value="<?php echo $row_modinf['APELLIDOS']; ?>"/></td>
                                </tr>
                                <tr>
                                    <th style="width:40px;">Nombres</th>
                                    <td><input name="nombres" type="text" id="nombres" style="width:205px;"
                                               tabindex="10" autocomplete="off"
                                               value="<?php echo $row_modinf['NOMBRES']; ?>"/></td>
                                </tr>
                                <tr>
                                    <th style="width:40px;">D.N.I.</th>
                                    <td><input name="dni" type="text" id="dni" style="width:85px;" tabindex="11"
                                               autocomplete="off" value="<?php echo $row_modinf['DNI']; ?>"/></td>
                                </tr>
                                <tr>
                                    <th style="width:50px;">Doc.Nac.</th>
                                    <td><input name="dninac" type="text" id="dninac" style="width: 50px; float: left;"
                                               pattern="[A-Z]{3}" tabindex="11" autocomplete="off"
                                               value="<?php echo $row_modinf['DNINAC']; ?>"/></td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    <div style="width: 50%; float: right;">
                        <fieldset class="ui-widget ui-widget-content ui-corner-all"
                                  style="width: 200px; margin-left: auto; margin-right: auto;">
                            <legend class="ui-widget-header ui-corner-all ui-state-highlight" style="padding:1px 10px;"
                                    id="grupoDomicilio">Domicilio
                            </legend>
                            <table class="formulario" style="width:30%;">

                                <tr>
                                    <th style="width:30px;">Calle o Barrio</th>
                                    <td><input name="dirCalle" type="text" id="dirCalle" style="width:200px;"
                                               tabindex="12" autocomplete="off"
                                               value="<?php echo $row_modinf['DIRCALLE']; ?>"/></td>
                                </tr>
                                <tr>
                                    <th style="width:30px;">Nro.</th>
                                    <td><input name="dirNro" type="text" id="dirNro" style="width:36px;" tabindex="13"
                                               autocomplete="off" value="<?php echo $row_modinf['DIRNRO']; ?>"/>
                                        <b>&nbsp;&nbsp;&nbsp;Piso&nbsp;&nbsp;</b><input name="dirPiso" type="text"
                                                                                        id="dirPiso" style="width:30px;"
                                                                                        tabindex="14" autocomplete="off"
                                                                                        value="<?php echo $row_modinf['DIRPISO']; ?>"/>
                                        <b>&nbsp;&nbsp;&nbsp;Dpto.&nbsp;&nbsp;</b><input name="dirDpto" type="text"
                                                                                         id="dirDpto"
                                                                                         style="width:30px;"
                                                                                         tabindex="15"
                                                                                         autocomplete="off"/></td>
                                </tr>
                                <tr>
                                    <th style="width:30px;">Mz.</th>
                                    <td><input name="dirMz" type="text" id="dirMz" style="width:36px;" tabindex="16"
                                               autocomplete="off" value="<?php echo $row_modinf['DIRMZ']; ?>"/>
                                        <b>&nbsp;&nbsp;&nbsp;Casa&nbsp;</b>
                                        <input name="dirCs" type="text" id="dirCs" style="width:30px;" tabindex="17"
                                               autocomplete="off" value="<?php echo $row_modinf['DIRCS']; ?>"/>
                                        <b>&nbsp;&nbsp;&nbsp;Mbk.&nbsp;&nbsp;</b>
                                        <input name="dirMbk" type="text" id="dirMbk" style="width:30px;" tabindex="18"
                                               autocomplete="off" value="<?php echo $row_modinf['DIRMBK']; ?>"/></td>
                                </tr>
                                <tr id="filaLocalidad">
                                    <th style="width:30px;">Localidad</th>
                                    <td><input name="localidad" type="text" id="localidad" style="width:200px;"
                                               tabindex="19" value="<?php echo $row_modinf['LOCALIDAD']; ?>"/></td>
                                </tr>
                                <tr>
                                    <th style="width:30px;">Provincia</th>
                                    <td><input type="hidden" name="idProvincia" id="idProvincia"/><input
                                                name="descProvincia" type="text" id="descProvincia" style="width:200px;"
                                                tabindex="20" value="<?php echo $row_modinf['DESCPROVINCIA']; ?>"/></td>
                                </tr>


                            </table>
                        </fieldset>
                    </div>
                </fieldset>
            </div>
            <br/>

            <table align="center" border="0">
                <tr>
                    <td><a href="../exptes/expte.php?exp=<?php echo $row_modinf['ID']; ?>"><input type="button"
                                                                                                  name="button"
                                                                                                  id="button"
                                                                                                  value=" ir a la causa "></a>
                    </td>
                    <td><input type="submit" name="submit" id="submit" value="Modificar" tabindex="18"></td>
                </tr>
            </table>
            <input type="hidden" name="MM_insert" value="formulario">
            <input type="hidden" name="MM_update" value="formulario">
        </form>
    </div>

    <div id="div_mensaje" align="center" style="display:none;padding:30px;"></div>
    <table align="center" id="controles_mensaje" style="display:none;">
        <tr>
            <td><a href="verMenuEmpleado.do" id="btn_volver_menu">Menú Principal</a></td>
        </tr>
    </table>
</div>

</body>
</html>
<?php
mysql_free_result($modinf);

mysql_free_result($opvehi);

mysql_free_result($multas);

mysql_free_result($depend);
?>
