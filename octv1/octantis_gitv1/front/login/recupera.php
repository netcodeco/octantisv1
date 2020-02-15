<?php
	require "../../lib/funcs_db/conexion.php";
	require "../../lib/funcs_octlogin/funcs.php";

	$errors=array();

	if(!empty($_POST)){
		$email=	$mysqli->real_escape_string($_POST['email']);

		if(!isEmail($email)){
			$errors[]= "Debe ingresar un correo electronico valido";
		}
			if(emailExiste($email)){
				$user_id=getValor('id', 'correo', $email);
				$nombre=getValor('nombre', 'correo', $email);

				$token=generaTokenPass($user_id);

				$url='http://'.$_SERVER["SERVER_NAME"].'/octantis_v1/front/login/cambia_pass.php?user_id='.$user_id.'&token='.$token;
				//$url='https://'.$_SERVER["SERVER_NAME"].'/login/cambia_pass.php?user_id='.$user_id.'&token='.$token;
				$asunto="Recuperar password - Sistema de usuarios FEBIMBO";
				$cuerpo="Hola $nombre: <br /><br />Se ha solicitado un reseteo de tu contraseña para acceder al Portal Virtual FEBIMBO. <br /><br />Para restaurar la contraseña visita la siguiente direccion: <a href='$url'>Cambiar password</a>";

				if(enviarEmail($email, $nombre, $asunto, $cuerpo)){
					echo "Hemos enviado un correo electrónico a la dirección $email para restablecer tu password de acceso al portal virtual FEBIMBO. <br />";
					echo "<a href='octlogin.php' >Iniciar sesión</a>";
					exit;

				}else{
					$errors[]="Se ha presentado un error al enviar el correo electrónico";
				}


			}else{
				$errors[]="No existe el correo electronico";
			}
		}

?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Recupera</title>
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
		<script src="../js/main1.js"></script>
	</head>

	<body>
    <header>

		</header>
			<div class="contenedor">
				<section class="main">
					<div class="ingreso">
						<p>Recuperar Password</p><br>
						<form id="loginform" role="form" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">
							<input id="email" type="email" name="email" placeholder="email" required><br>
							<input type="submit" value="Enviar"><br>
							<div class="recupera"><a class="recupera" href="octlogin.php">Inicia Sesión</a></div>
							<div  class="register"> No tienes una cuenta? <a href="octregistro.php"> Regístrate aquí</a></div>
						</form>
			 				<?php echo resultBlock($errors);?>
					</div>
				</section>
			</div>
		<footer>

		</footer>
	</body>
</html>
