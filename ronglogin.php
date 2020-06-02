<?php require_once('Connections/tfx.php'); ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['textfield'])) {
  $loginUsername=$_POST['textfield'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "PRIVILEGIOS";
  $MM_redirectLoginSuccess = "sitecomp.php";
  $MM_redirectLoginFailed = "ronglogin.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_tfx, $tfx);

  $LoginRS__query=sprintf("SELECT LONGIN, PASSWORD, PRIVILEGIOS FROM users WHERE LONGIN=%s AND PASSWORD=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text"));

  $LoginRS = mysql_query($LoginRS__query, $tfx) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {

    $loginStrGroup  = mysql_result($LoginRS,0,'PRIVILEGIOS');

	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>inicio</title>
  <?php include('modules/headerfirst.php'); ?>
  <!-- <link href="css/tfaltas.css" rel="stylesheet" type="text/css"> -->
</head>

<body>
  <div class="container-fluid" style="background-image: url('public/ondas.jpg');height: 900px;">
    <div>
      <div class="row justify-content-center" style="/* margin: 0 auto; width: 400px */">
        <!-- Card -->
        <div class="card" style="padding-top: 30px;margin-top: 30px;">

          <!-- Card image -->
          <img class="card-img-top" src="images/logo-tfatas.png" alt="Card image cap"
            style="align-content: center;max-width: 50%;align-items: center;background: center;align-self: center;">

          <!-- Card content -->
          <div class="card-body">
            <!-- Card -->
            <form class="text-center p-5" ACTION="<?php echo $loginFormAction; ?>" id="form1" name="form1"
              method="POST">

              <p class="h4 mb-4">Iniciar Sesion</p>

              <!-- Email -->
              <input type="text" name="textfield" id="textfield" class="form-control mb-4" placeholder="Usuario">

              <!-- Password -->
              <input name="password" type="password" id="password" class="form-control mb-4" placeholder="Contraseña">

              <!-- Sign in button -->
              <button id="submit" name="submit" class="btn btn-info btn-block my-4" type="submit">Ingresar</button>

            </form>

          </div>

        </div>

        <!-- <form ACTION="<?php echo $loginFormAction; ?>" id="form1" name="form1" method="POST">
          <table width="200" border="0">
            <tbody>
              <tr>
                <td><img src="images/logo-tfatas.png" width="200" height="209" alt="" /></td>
              </tr>
              <tr>
                <td align="center">Ingrese un usuario con privilegios suficientes</td>
              </tr>
              <tr>
                <td align="center"><label for="textfield">Usuario:</label>
                  <input name="textfield" type="text" autofocus="autofocus" id="textfield" tabindex="1"
                    autocomplete="off"></td>
              </tr>
              <tr>
                <td align="center"><label for="password">Clave:</label>
                  <input name="password" type="password" id="password" tabindex="2"></td>
              </tr>
              <tr>
                <td align="center"><input name="submit" type="submit" id="submit" tabindex="3" value="Ingresar"></td>
              </tr>
            </tbody>
          </table>

        </form> -->
      </div>
    </div>
  </div>
</body>

</html>