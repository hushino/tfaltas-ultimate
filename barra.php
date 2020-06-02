<!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.12.0/css/mdb.min.css" rel="stylesheet">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.12.0/js/mdb.min.js"></script>
-->

<link rel="stylesheet" href="css/fontawesome58all.css">
<link rel="stylesheet" href="css/fontgooglerobotocss.css">
<link href="css/mdboostrap/bootstrap.css" rel="stylesheet">
<link href="css/mdboostrap/mdb.css" rel="stylesheet">
<script type="text/javascript" src="css/mdboostrap/js/jquery.js"></script>
<script type="text/javascript" src="css/mdboostrap/js/bootstrap.js"></script>
<script type="text/javascript" src="css/mdboostrap/js/mdb.js"></script>

<script type="text/javascript">
function showNav(el) { el.getElementsByTagName('UL')[0].style.left='auto'; }
function hideNav(el) { el.getElementsByTagName('UL')[0].style.left='-999em'; }
</script>


<div class="quicklinks" id="wpcombar">
<nav class="navbar navbar-expand-lg navbar-dark">
  <a class="navbar-brand" href="#">Tribunal de faltas</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="/tfaltas/personas/ingper.php">Igresar nueva infracción <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/tfaltas/sitecomp.php">lista de causas</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/tfaltas/exptes/infraclist.php">estadisticas</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/tfaltas/resoluciones/listaderes.php">Resoluciones</a>
      </li>
			<li class="nav-item">
        <a class="nav-link" href="/tfaltas/escritos/escritos.php">Modelos de Escritos</a>
      </li>
			<li class="nav-item">
        <a class="nav-link" href="/tfaltas/pases/pases.php">Pases</a>
      </li>

			 <!-- Dropdown -->
			 <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">SUBMENU</a>
        <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="/tfaltas/productividad.php">Productividad</a>
          <a class="dropdown-item" href="/tfaltas/siteselecvarios.php">Listas Especiales</a>
          <a class="dropdown-item" href="/tfaltas/buqrapida.php">busqueda especial</a>
        </div>
      </li>

			<li class="nav-item">
        <a class="nav-link" href="/tfaltas/librebusq.php">Libre Deuda</a>
      </li>

		
			
    </ul>
  </div>
</nav>
</div>


<!-- 
<?php if (isset($_COOKIE["nombre"])) {
  $usur = $_COOKIE["nombre"];
} else { $usur = ''; } ?>
<div id="wpcombar">
	<div class="quicklinks">
	  <ul>
		  <li class="menupop" onmouseover="showNav(this)" onmouseout="hideNav(this)">
			  <ul> 
					  <?
							foreach ($menuitem[1] as $item) {
								?><li><a href="<? echo $item[1]; ?>"><? echo $item[0]; ?></a></li><?
							} ?>
						</ul>
					</li>

				</ul>
	  <ul>	
	    <li class="menupop" style="height: 28px; font-size:20px; line-height: 28px;"></li>
    		</ul>
		<ul>
			<li>
				<a href="/tfaltas/personas/ingper.php">
					<b>Igresar nueva infracción</b>
				</a>
		  </li>
	  </ul>
		<ul>
			<li>
					<a href="/tfaltas/sitecomp.php">
						<b>lista de causas</b>
					</a>
				</li>
	  </ul>
		<ul>
			<li>
					<a href="/tfaltas/exptes/infraclist.php">
						<b>estadisticas</b>
					</a>
				</li>
	  </ul>
		<ul>
			<li>
					<a href="/tfaltas/resoluciones/listaderes.php">
						<b>Resoluciones</b>
					</a>
				</li>
			<li>
					<a href="/tfaltas/escritos/escritos.php">
						<b>Modelos de Escritos</b>
					</a>
				</li>
			<li>
					<a href="/tfaltas/pases/pases.php">
						<b>Pases</b>
					</a>
				</li>
			<li class="menupop" onmouseover="showNav(this)" onmouseout="hideNav(this)">
                            SUBMENU
                            <ul>
					<a href="/tfaltas/productividad.php">
						<b>Productividad</b>
					</a>
					<a href="/tfaltas/siteselecvarios.php">
						<b>Listas Especiales</b>
					<a href="/tfaltas/buqrapida.php">
						<b>busqueda especial</b>
					</a>

			    </ul>
                        </li>
				<li>
					<a href="/tfaltas/librebusq.php">
						<b>Libre Deuda</b>
					</a>
				</li>
	  </ul>

  </div>
	<div id="admin-bar-rightlinks" class="quicklinks" style="position: absolute; right: 0;"><a><?php echo "Hola ". $usur . ", Bienvenido."; ?></a>
	</div>
</div> -->


