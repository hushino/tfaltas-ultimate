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
$sddd = $_GET['exp'];
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
  $insertSQL = sprintf("INSERT INTO escpresent(ESCAU,ESCTIPO,PERSIMPUTA,APELLIDOS,NOMBRES,DNI,FECHANAC,TELDADO,DOMREAL,DOMCONST,ESCFECHA,ESCHORA,ESCCONTEN) VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                       GetSQLValueString($_POST['idcau'], "text"),
                       GetSQLValueString($_POST['esctipo'], "text"),
                       GetSQLValueString($_POST['oblada'], "text"),
                       GetSQLValueString($_POST['apellidos'], "text"),
                       GetSQLValueString($_POST['nombres'], "text"),
                       GetSQLValueString($_POST['dni'], "text"),
                       GetSQLValueString($_POST['fenacim'], "text"),
                       GetSQLValueString($_POST['telcontac'], "text"),
                       GetSQLValueString($_POST['domreal'], "text"),
                       GetSQLValueString($_POST['domconst'], "text"),
                       GetSQLValueString($_POST['fechax'], "text"),
                       GetSQLValueString($_POST['horax'], "text"),
                       GetSQLValueString($_POST['escconten'], "text"));

  mysql_select_db($database_tfx, $tfx);
  $Result1 = mysql_query($insertSQL, $tfx) or die(mysql_error());

echo "<script>window.opener.location.reload(); window.close();</script>";exit;
}

$colname_cosvehi = "-1";
if (isset($_GET['exp'])) {
  $colname_cosvehi = $_GET['exp'];
}
mysql_select_db($database_tfx, $tfx);
$query_cosvehi = sprintf("SELECT *, concat('expte. N&#176; ',`causas`.`EXPTE`,' - A&#241;o ',year(`causas`.`INFFECHA`)) AS EXPTEX FROM causas WHERE ID = %s", GetSQLValueString($colname_cosvehi, "int"));
$cosvehi = mysql_query($query_cosvehi, $tfx) or die(mysql_error());
$row_cosvehi = mysql_fetch_assoc($cosvehi);
$totalRows_cosvehi = mysql_num_rows($cosvehi);

$colname_escr = "-1";
if (isset($_GET['esc'])) {
  $colname_escr = $_GET['esc'];
}
mysql_select_db($database_tfx, $tfx);
$query_escr = sprintf("SELECT * FROM escpresent WHERE IDESC = %s", GetSQLValueString($colname_escr, "int"));
$escr = mysql_query($query_escr, $tfx) or die(mysql_error());
$row_escr = mysql_fetch_assoc($escr);
$totalRows_escr = mysql_num_rows($escr);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Modificar datos del Escrito</title>
<script type="text/javascript" src="../js/jquery.js"></script>
<script language="javascript" type="text/javascript" src="../js/disableSubmits.js"></script>
<script type="text/javascript">
        $(document).ready(function() {
            $("#infartx").tokenInput("/tfaltas/consultas/listadeinfracciones.php", {
                tokenLimit: 7
            });
        });
        function showContent() {
        element = document.getElementById("contoblada");
        check = document.getElementById("oblada");
        if (check.checked) {
            element.style.display='block';
            $('#apellidos').prop('required',true);
            $('#nombres').prop('required',true);
            $('#dni').prop('required',true);
        }
        else {
            element.style.display='none';
            $('#apellidos').prop('required',false);
            $('#nombres').prop('required',false);
            $('#dni').prop('required',false);
            $('#apellidos').prop('value','<?php echo $row_escr['APELLIDOS']; ?>');
            $('#nombres').prop('value','<?php echo $row_escr['NOMBRES']; ?>');
            $('#dni').prop('value','<?php echo $row_escr['DNI']; ?>');
            $('#fenacim').prop('value','<?php echo $row_escr['FECHANAC']; ?>');
        } }
        function putContent() {
        elementx = document.getElementById("domreal").value;
        check = document.getElementById("domigual");
        if (check.checked) {

            $('#domconst').prop('value',elementx);
        }
        else {
            $('#domconst').prop('value','');

        } }

</script>

</head>

<body><div><form method="POST" action="<?php echo $editFormAction; ?>" name="form">
<table width="100%" border="1">
  <tbody>
    <tr>
      <td>Ingresar Escrito Presentado:</td>
      <td><input type="hidden" id="idcau" name="idcau" value="<?php echo $row_cosvehi['ID']; ?>" /><?php echo $row_cosvehi['EXPTEX']; ?></td>
    </tr>
    <tr>
      <td><label for="textfield"></label></td>
      <td>
      Selecciona el tipo de escrito:
               <br />
               <input name="esctipo" value="descargo" type="radio" />Descargo
               <br />
               <input name="esctipo" value="solicitud" type="radio" checked="checked" />Solicitudes Varias
               <br />
               <input name="esctipo" value="restitucion" type="radio" />Pedido de restitucion


               </td>
    </tr>
    <tr>
      <td><label for="textfield2"></label></td>
      <td>El Imputado es: <?php echo $row_cosvehi['APELLIDOS']; ?>, <?php echo $row_cosvehi['NOMBRES']; ?>, DNI <?php echo $row_cosvehi['DNI']; ?>
      <br><input name="oblada" type="checkbox" id="oblada" title="Si el Presentante no es el mismo Imputado" onchange="javascript:showContent()" <?php if (isset($row_escr['APELLIDOS'])) { echo 'checked'; } ?>>
      Si el Imputado no es el mismo Presentante
      <div id="contoblada" style="display: <?php if (isset($row_escr['APELLIDOS'])) { echo 'inline'; } else { echo 'none'; }?>; text-align: left; ">
               Apellidos:
        <input type="text" name="apellidos" id="apellidos" value="<?php echo $row_escr['APELLIDOS']; ?>"><br>
        Nombres:<input type="text" name="nombres" id="nombres" value="<?php echo $row_escr['NOMBRES']; ?>"><br>
        D.N.I.:<input type="text" name="dni" id="dni" value="<?php echo $row_escr['DNI']; ?>"><br>
        Fecha de Nacimiento:<input type="date" name="fenacim" id="fenacim" value="<?php echo $row_escr['FECHANAC']; ?>">

        </div>
        </td>
    </tr>
    <tr>
      <td><label for="textfield3"></label></td>
      <td>
        </td>
    </tr>
    <tr>
      <td><label for="textfield4">Domicilio:</label></td>
      <td>
        Telefono de contacto: <input name="telcontac" type="text" id="telcontac" style="width:150px;" title="Telefono de Contacto" value="<?php echo $row_escr['TELDADO']; ?>" /><br>
        Domicilio Real: <input type="text" name="domreal" placeholder="Cargar Domicilio Real" size="50" id="domreal" value="<?php echo $row_escr['DOMREAL']; ?>">
        <input name="domigual" type="checkbox" id="domigual" title="Si el Domicilio es el mismo que el Constituido" value="S" onchange="javascript:putContent()"><br>Domicilio Constituido: <input type="text" size="50" name="domconst" id="domconst" placeholder="Cargar Domicilio Constituido" value="<?php echo $row_escr['DOMCONST']; ?>" required /> </td>
    </tr>
    <tr>
      <td>
      </td>
           <td></td>
    </tr>
    <tr>
      <td><label for="textfield6">Fecha de presentacion:</label></td>
      <td>
        Fecha de Presentacion: <input type="date" name="fechax" id="fechax" value="<?php echo $row_escr['ESCFECHA']; ?>" required> - Hora: <input type="time" name="horax" id="horax" title="La hora es necesaria" value="<?php echo $row_escr['ESCHORA']; ?>" required></td>
    </tr>
    <tr>
      <td></td>
      <td>
        </td>
    </tr>
    <tr>
      <td></td>
      <td>
        <textarea name="escconten" id="escconten" placeholder="Resumen del Contenido del Escrito" rows="5" cols="60"><?php echo $row_escr['ESCCONTEN']; ?></textarea></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="submit" id="submit" value="Ingresar Escrito" ></td>
    </tr>
  </tbody>
</table>
<input type="hidden" name="MM_insert" value="form">
</form>
</div>
</body>
</html>
<?php
mysql_free_result($cosvehi);
?>
