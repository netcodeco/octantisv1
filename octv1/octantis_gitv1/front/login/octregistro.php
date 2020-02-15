<?php
require "../../lib/funcs_db/conexion.php";
require "../../lib/funcs_db/conexion2.php";
require "../../lib/funcs_octlogin/funcs.php";
$errors=array();

	if(!empty($_POST))
	{
		$nombre=$mysqli->real_escape_string(strtoupper($_POST['nombre']));
		$usuario=$mysqli->real_escape_string($_POST['usuario']);
		$password=$mysqli->real_escape_string($_POST['password']);
		$con_password=$mysqli->real_escape_string($_POST['con_password']);
		$email=$mysqli->real_escape_string($_POST['email']);
		$captcha=$mysqli->real_escape_string($_POST['g-recaptcha-response']);

		$activo=0; //asegura incio de variable en 0
		$tipo_usuario=2;//asigna privilegios usuario normal
		$secret='6LfTktEUAAAAALwRG4_HI5cztmbQ24WmioAUn-l0'; //escribe clave secreta de captcha

	//siguente:validacion de captcha por si tiene error

		if(!$captcha){

		$errors[]="Por favor verifica el captcha";

		}

		//valida todas las variables introducidas a continaución

		if(isNull($nombre, $usuario, $password, $con_password, $email)){
			$errors[]=	 "Estimado usuario, verifique que todos los campos se encuentren diligenciados correctamente.";
		}
		//invoca la funcion isemail en carpeta funcs
		if(!isEmail($email)){
			$errors[]="Dirección de correo inválida, por favor verifique que esta sea una dirección de correo vigente a la que tenga acceso.";
		}
		if(!validaPassword($password, $con_password)){
			$errors[]="Las contraseñas no coinciden, por favor verifique nuevamente.";
		}
		if(!preg_match("/^[0-9]{6,12}$/",$_POST['usuario'])){
		    $error_usuario="<p> Estimado asociado: solo se permiten los numeros de su documento de identidad, no se admiten simbolos, espacios o nombres. Intente nuevamente por favor.</p>";
		}
		//validaciones contra la BD
		//Verificar si la cedula existe en la tabla de usuarios, caso contrario salir.
		//*******************************************************************************************
		$busqueda=$usuario;
		try{
			
			$base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$base->exec("SET CHARACTER SET utf8");
			$sql="SELECT usuarioCedula, usuarioNombres, usuarioApellidos, usuarioCorreoCorp FROM t_validate_register WHERE usuarioCedula=?";
			//variable $resultado que almacena PDO statement
			$resultado=$base->prepare($sql);
			//ejecutar la funcion
			$resultado->bindvalue("usuarioCedula", $usuario);
			$resultado->execute(array($busqueda));
			$num1=$resultado->rowcount();
			
				if ($num1 == 0){//if it´s true
					include ("msj_no_registrado.php");
					exit;
				} else {
			//if it's false
						//echo "Usuario existe puede seguir con el proceso";
				}
				$resultado->closecursor();

		}catch(exception $e){
				echo "Codigo del error: " . $e->getCode() . "<br>";
				echo "Linea del error:" . $e->getLine() . "<br>";
				die('Error: ' . $e->GetMessage());

		}finally{
			$base=null;
		}
		//****************************************************************************************************
		if(usuarioExiste($usuario)){
			$errors[]="El nombre de usuario $usuario ya existe";
		}
		if(emailExiste($email)){
			$errors[]="El correo electrónico $email ya existe";
		}
		//Se van a mostrar resultados, primero si hay errores:
		if(count($errors)==0){
		//aca va el tema de cpatcvha video 19 minuto 16:09
		$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha");
		$arr=json_decode($response, TRUE);
			if($arr['success']){

				$pass_hash=hashPassword($password);
				$token=generateToken();

				$registro=registraUsuario($usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario);
				//function registraUsuario($usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario)
				if($registro>0){
				//C:\wamp64\www\FEBIMBO2018\FEBAPPS\login http://localhost/octantis_v1/front/login/
					$url='http://'.$_SERVER["SERVER_NAME"].'/octantis_v1/front/login/activar.php?id='.$registro.'&val='.$token;
					//$url='https://'.$_SERVER["SERVER_NAME"].'/login/activar.php?id='.$registro.'&val='.$token; //En producción.
					//funcion enviar email usando libreria php mailer
					$asunto="Activación de cuenta - Sistema Octantis I.T Web Support Netcode.";
					$cuerpo="Estimado $nombre: <br /><br />Para continuar con el proceso de registro es indispensable dar click en el siguiente enlace <a href='$url'>Activar cuenta </a>";
					if(enviarEmail($email, $nombre, $asunto, $cuerpo)){
					echo "<h3 align='center' style='text-align:center; font-family: 'Marcellus', serif;'> Estimad@ $nombre <br> Para terminar el proceso de registro siga las instrucciones <br> que le hemos enviado a la dirección de correo electrónico <br> $email </h3>";

					echo "<br><a href='octlogin.php'  style='text-align:center; text-decoration:none; margin-left:900px; font-family: 'Marcellus', serif;'>Iniciar Sesión</a>";
					exit();
					}

				}else{
					$errors[]="Error al registrar";

				}

		}else{
			$errors[]="Error al comprobar Captcha";
		}

	}
}
?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Registro Usuarios</title>
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
		<script src="../js/main1.js"></script>
		<script src='https://www.google.com/recaptcha/api.js'></script>

		<link href="https://fonts.googleapis.com/css?family=Josefin+Slab" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Questrial" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Arima+Madurai" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Marcellus" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=K2D" rel="stylesheet">


		<link rel="stylesheet" type="text/css" href="../css/footer_abr2019.css">
		<link rel="stylesheet" type="text/css" href="../css/struct_barra.css">
		<link rel="stylesheet" type="text/css" href="../css/responsive.css">
		<link rel="stylesheet" type="text/css" href="../css/struct_registro2.css">
		<link rel="stylesheet" type="text/css" href="../styles.css">

		<script>
			$(function(){
				$("body").hide().fadeIn(3000);
			});
		</script>
	</head>

	<body>
		<header>

		</header>

		<div  class="contenedor">
			<section class="main">
				<div class="ingreso">

					<h4>Regístrate para acceder a OCTANTIS WEB.</h4>

					<form id="signupform" role="form" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">

						<label for="nombre"> Nombre:</label>
						<input type="text"  name="nombre" placeholder="Nombre" value="<?php if(isset($nombre)){ echo $nombre;} ?>" required >

						<label for="usuario"> Usuario</label><span class="error"><?php if(isset($error_usuario)){ echo $error_usuario;}  ?></span>
						<input type="text"  name="usuario" placeholder="Cédula de Ciudadanía" value="<?php echo htmlentities(!empty($usuario)) ? htmlentities($usuario) : ''; ?>" pattern="[0-9]{6,12}" aria-live="polite" required>

						<label for="password"> Password</label>
						<input type="password"  name="password" placeholder="Password" required><br>
						<input type="password"  name="con_password" placeholder="Confirmar Password" required>

						<label for="email"> Correo electrónico</label><span class="error"><?php if(isset($error_correo)){ echo $error_correo;}  ?></span>
						<input type="email"  name="email" placeholder="Email" value="<?php if(isset($email)) echo $email; ?>" required><br>

						<div class="form-group">
							<label for="captcha" class="col-md-3 control-label"></label>
							<div class="g-recaptcha" data-sitekey="6LfTktEUAAAAAG8rJw3kv-tLt8S7cM3khcDbjiKc"></div>
						</div>

						<button id="btn-signup" type="submit" >Registrar</button>

					</form>
				</div>
					 <?php echo resultBlock($errors);?>
	 	  </section>
	 	</div>
		<footer>

		</footer>
	</body>
</html>
