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
  $insertSQL = sprintf("INSERT INTO escmodelos(ESCNOM, CONTEN, CONTEN2,CONTEN2FORMT) VALUES(%s,%s,%s,%s)",
                       GetSQLValueString($_POST['escnombre'], "text"),
                       GetSQLValueString($_POST['area2'], "text"),
                       GetSQLValueString($_POST['area3'], "text"),
                       GetSQLValueString($_POST['margen'], "text"));

  mysql_select_db($database_tfx, $tfx);
  $Result1 = mysql_query($insertSQL, $tfx) or die(mysql_error());

  $insertGoTo = "escritonuevo.php";
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
$query_escrito = sprintf("SELECT IDESC,ESCAU,ESCTIPO,ESCDOC,CONTEN,ESCNOM FROM escmodelos WHERE IDESC = %s", GetSQLValueString($colname_escrito, "int"));
$escrito = mysql_query($query_escrito, $tfx) or die(mysql_error());
$row_escrito = mysql_fetch_assoc($escrito);
$totalRows_escrito = mysql_num_rows($escrito);

$colname_causa = "-1";
if (isset($_GET['cau'])) {
  $colname_causa = $_GET['cau'];
}
mysql_select_db($database_tfx, $tfx);
$query_causa = sprintf("SELECT *, operop(DEPORIG) AS DIOR, operpersona(ID) AS HIDXX, IF(mod(ID,2) = 0,'Rolando Manuel Pankow','Carlos Arturo Serravalle') AS SECRE, concat(if(isnull(DIRCALLE),'',DIRCALLE),if((DIRNRO is not null),' N&#176; ',''),if((DIRNRO is not null),DIRNRO,''),if((DIRPISO is not null),'Pº ',''),if((DIRPISO is not null),DIRPISO,''),if((DIRDPTO is not null),'Dpto ',''),if((DIRDPTO is not null),DIRDPTO,''),if((DIRMZ is not null),' Mz ',''),if((DIRMZ is not null),DIRMZ,''),if((DIRCS is not null),' Cs ',''),if((DIRCS is not null),DIRCS,''),if((DIRMBK is not null),' Mbk ',''),if((DIRMBK is not null),DIRMBK,'')) AS DIREC, concat('Expte. N&#176; ',`causas`.`EXPTE`,' - A&#241;o ',year(`causas`.`INFFECHA`)) AS EXPTEX, fechames(INFFECHA) INFFECHAX FROM causas WHERE ID = %s", GetSQLValueString($colname_causa, "int"));
$causa = mysql_query($query_causa, $tfx) or die(mysql_error());
$row_causa = mysql_fetch_assoc($causa);
$totalRows_causa = mysql_num_rows($causa);

mysql_select_db($database_tfx, $tfx);
$query_comodin = "SELECT IDREP,COMODIN,CODIGO,DESCRIPCION,AGRUPAMIENTO FROM escreplace";
$comodin = mysql_query($query_comodin, $tfx) or die(mysql_error());
$row_comodin = mysql_fetch_assoc($comodin);
$totalRows_comodin = mysql_num_rows($comodin);

$contenido = $row_escrito['CONTEN'];

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Tribunal de Faltas - Escritos</title>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/tabs.js"></script>
<script type="text/javascript" src="../js/nicEdit.js"></script>
<script type="text/javascript">
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>

<script>
function dragStart(event) {
    event.dataTransfer.setData("Text", event.target.id);
    document.getElementById("demo").innerHTML = "Started to drag the p element";
}

function allowDrop(event) {
    event.preventDefault();
}

function drop(event) {
    event.preventDefault();
    var data = event.dataTransfer.getData("Text");
    event.target.appendChild(document.getElementById(data));
    document.getElementById("demo").innerHTML = "The p element was dropped";
}
</script>
<link rel="stylesheet" type="text/css" href="../css/escritos.css" />
<link href="../css/barra.css" rel="stylesheet" type="text/css">
<link href="../css/tabs.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php include('../barra.php'); ?>
<table style="width:80%">
  <tr>
    <th><p id="demo"></p></th>
    <th><h4></h4></th>

  </tr>
  <tr>
    <td style="width: 20%; vertical-align: text-top;"><div><?php do { ?><div ondragstart="dragStart(event)" draggable="true" id="<?php echo $row_comodin['COMODIN']; ?>" style="align:right; background-color:lightblue; margin: 2px; padding-left: 5px; border-style: dotted; border-width: 1px; width: auto;"><?php echo $row_comodin['DESCRIPCION']; ?></div><?php } while ($row_comodin = mysql_fetch_assoc($comodin)); ?></div></td>
    <td style="width: 80%; vertical-align: text-top;"><form name="formulario" id="formulario" action="<?php echo $editFormAction; ?>" method="POST">
Nombre del Escrito: <input type="text" name="escnombre" id="escnombre" placeholder="Nombre del Escrito" value="" required autofocus style="width: 350px;" >
<div id="TabbedPanels1" class="TabbedPanels" style="width:680px">
      <ul class="TabbedPanelsTabGroup">
        <li class="TabbedPanelsTab" tabindex="0">&nbsp;&nbsp;Hoja 1&nbsp;&nbsp;</li>
        <li class="TabbedPanelsTab" tabindex="0">&nbsp;&nbsp;Hoja 2&nbsp;&nbsp;</li>
      </ul>

<div class="TabbedPanelsContentGroup">
      <div class="TabbedPanelsContent" width="620">
          <textarea name="area2" style="width: 600px; height: 450px;" ondrop="drop(event)" ondragover="allowDrop(event)" required>
          <?php echo $row_escrito['CONTEN']; ?>
          </textarea>
     </div>
     <div class="TabbedPanelsContent">
          Margen:
          <input type="radio" name="margen" value="exa3" /> Izquierdo
          <input type="radio" name="margen" value="exa2" /> Derecho<br>

          <textarea name="area3" style="width: 600px; height: 450px;" ondrop="drop(event)" ondragover="allowDrop(event)" required>
          <?php echo $row_escrito['CONTEN2']; ?>
          </textarea>
     </div>
</div>
</div>
</div>
<input type="hidden" name="caux" value="<?php echo $row_escrito['ESCAU']; ?>">
<input type="hidden" name="escx" value="<?php echo $row_escrito['ESCDOC']; ?>">
<input type="submit" name="submit" id="submit" value="Guardar" tabindex="18" >
<input type="hidden" name="MM_insert" value="formulario">
  </form></td>

  </tr>
  <tr>
    <td></td>
    <td></td>
  </tr>
</table>
<?php // echo $contenido; ?>
</body>

<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1");
var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1", {contentIsOpen:false});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "date", {format:"dd/mm/yyyy", isRequired:false});
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1", {invalidValue:"(NULL)"});
var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
var spryselect3 = new Spry.Widget.ValidationSelect("spryselect3", {invalidValue:"(NULL)"});
//-->
</script>

</html>
<?php
mysql_free_result($escrito);
?>