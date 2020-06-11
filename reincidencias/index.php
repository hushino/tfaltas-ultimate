<?php require_once('../Connections/tfx.php');
if (!isset($_SESSION)) {
    session_start();
}

?>

<?php require_once('../modules/header.php'); ?>

<?php
mysql_select_db($database_tfx, $tfx);
$query_depend = "SELECT vehitipo, art, reinci FROM infrac WHERE vehitipo = 'Automotores' OR vehitipo = 'Motocicletas y Motonetas' ORDER BY vehitipo";
$depend = mysql_query($query_depend, $tfx) or die(mysql_error());
$row_depend = mysql_fetch_assoc($depend);
$totalRows_depend = mysql_num_rows($depend);
?>

<body>
  <div id="contenido" class="container-fluid">
    <?php do { ?>
    <option value="<?php echo $row_depend['vehitipo'] ?>"><?php echo $row_depend['vehitipo'] ?></option>
    <?php
                    } while ($row_depend = mysql_fetch_assoc($depend));
                    $rows = mysql_num_rows($depend);
                    if ($rows > 0) {
                        mysql_data_seek($depend, 0);
                        $row_depend = mysql_fetch_assoc($depend);
                    }
                    ?>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">First</th>
          <th scope="col">Last</th>
          <th scope="col">Handle</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td>Mark</td>
          <td>Otto</td>
          <td>@mdo</td>
        </tr>
        <tr>
          <th scope="row">2</th>
          <td>Jacob</td>
          <td>Thornton</td>
          <td>@fat</td>
        </tr>
        <tr>
          <th scope="row">3</th>
          <td>Larry</td>
          <td>the Bird</td>
          <td>@twitter</td>
        </tr>
      </tbody>
    </table>
  </div>
</body>