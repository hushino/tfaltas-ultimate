<?php require_once('../Connections/tfx.php');
if (!isset($_SESSION)) {
    session_start();
}

?>

<?php
mysql_select_db($database_tfx, $tfx);
$query_depend = "SELECT vehitipo, art, reinci FROM infrac WHERE vehitipo = 'Automotores' OR vehitipo = 'Motocicletas y Motonetas' ORDER BY vehitipo";
$depend = mysql_query($query_depend, $tfx) or die(mysql_error());
$row_depend = mysql_fetch_assoc($depend);
$totalRows_depend = mysql_num_rows($depend);
?>

<body>
  <?php require_once('../modules/header.php'); ?>
  <?php include('../barra.php'); ?>

  <div id="contenido" class="container-fluid">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Articulo</th>
          <th scope="col">Tipo de vehiculo</th>
          <th scope="col">Reincidencia</th>
        </tr>
      </thead>
      <tbody>
        <?php do { ?>
        <tr>
          <th scope="row"><?php echo $row_depend['art'] ?></th>
          <td><?php echo $row_depend['vehitipo'] ?></td>
          <td><?php echo $row_depend['reinci'] ?></td>
        </tr>
        <?php
        } while ($row_depend = mysql_fetch_assoc($depend));
                    $rows = mysql_num_rows($depend);
                    if ($rows > 0) {
                        mysql_data_seek($depend, 0);
                        $row_depend = mysql_fetch_assoc($depend);
                    }
                    ?>
      </tbody>
    </table>
  </div>
</body>