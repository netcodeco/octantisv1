<?php
require "../../lib/funcs_db/conexion.php";
require "../../lib/funcs_octlogin/funcs.php";

	if(empty($_GET['user_id'])){
		header('Location: index.php');
	}

	if(empty($_GET['token'])){
		header('Location: octlogin.php');
	}

	$user_id = $mysqli->real_escape_string($_GET['user_id']);
	$token = $mysqli->real_escape_string($_GET['token']);

	if(!verificaTokenPass($user_id, $token))
	{
echo 'No se han podido verificar los Datos';
exit;
	}


?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Cambia clave OCTANTIS Web.</title>
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
		<script src="../js/main1.js"></script>
	</head>

	<body>
 		<header>

		</header>
			<form id="loginform" class="form-horizontal" role="form" action="guarda_pass.php" method="POST" autocomplete="off">
				<div class="grid-container">
				<h2>Cambio de Password</h2>   <br>
				<div class="link_log"><a href="octlogin.php">Iniciar Sesión</a></div><br>
				<input type="hidden" id="user_id" name="user_id" value ="<?php echo $user_id; ?>" />
				<input type="hidden" id="token" name="token" value ="<?php echo $token; ?>" />
				<div><h5 class="txt_pass">Nuevo Password</h5></div><br>
        <div><input type="password" class="form-control" name="password" placeholder="Password" required></div><br>
      	<div><h5 class="txt_pass"> Confirmar Password</h5></div><br>
        <div><input type="password" class="form-control" name="con_password" placeholder="Confirmar Password" required></div><br>
				<input type="submit" name="enviar" id="enviar" value="Modificar"></div>
			</form>
		<footer>

		</footer>
	</body>
</html>
