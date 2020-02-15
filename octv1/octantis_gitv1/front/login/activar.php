<?php

require "../../lib/funcs_db/conexion.php";
require "../../lib/funcs_db/conexion2.php";
require "../../lib/funcs_octlogin/funcs.php";

	if(isset($_GET["id"]) AND isset($_GET['val']))
	{

		$idUsuario = $_GET['id'];
		$token = $_GET['val'];

		$mensaje = validaIdToken($idUsuario, $token);

	}
?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Registro Octantis WEB Netcode.</title>

		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
		<script src="../js/main1.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Questrial" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Arima+Madurai" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Marcellus" rel="stylesheet">


		<link rel="stylesheet" type="text/css" href="../css/footer_abr2019.css">
		<link rel="stylesheet" type="text/css" href="../css/struct_barra.css">
		<link rel="stylesheet" type="text/css" href="../css/responsive.css">
		<link rel="stylesheet" type="text/css" href="../css/struct_activar.css">
		<link rel="stylesheet" type="text/css" href="../styles.css">
	</head>
	<body>
		<header>

		</header>
			<div  class="contenedor">
				<section class="main">
					<div class="ingreso">
						<h2><?php echo $mensaje; ?></h2><br>
							<a class="sesion" href="octlogin.php">Iniciar Sesión</a>
					</div>
				</section>
			</div>

		<footer>

		</footer>
	</body>
</html>
