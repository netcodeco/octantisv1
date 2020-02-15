<?php
	session_start();
	require "../../lib/funcs_db/conexion.php";
	require "../../lib/funcs_octlogin/funcs.php";

	$errors=array();
	if(!empty($_POST)){
		//recibe por parámetros tanto el usuario como la clave que se digita en los campos Usuario y Clave.
		$usuario=$mysqli->real_escape_string($_POST['usuario']);
		$password=$mysqli->real_escape_string($_POST['password']);

	//validar que los campos no sean nulos con la funcion isNullLogin
		if(isNullLogin($usuario, $password)){
			$errors[]="Debe llenar todos los campos";
		}

		$errors[]=login($usuario, $password);
	}


?>
<!doctype html>
<html>
	<head>
        <meta charset="utf-8">
        <title>Ingreso Usuarios Registrados</title>
        
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
        <link href="https://fonts.googleapis.com/css?family=Philosopher" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=K2D" rel="stylesheet">
        
        
        <link rel="stylesheet" type="text/css" href="../css/footer_abr2019.css">
        <link rel="stylesheet" type="text/css" href="../css/struct_barra.css">
        <link rel="stylesheet" type="text/css" href="../css/responsive.css">
        <link rel="stylesheet" type="text/css" href="../css/struct_login.css">
        <link rel="stylesheet" type="text/css" href="../styles.css">
        
        <script>
        	$(function(){
        		$("body").hide().fadeIn(1000);
        	});
        </script>

	</head>

	<body>
	<header>

	</header>

        <div  class="contenedor">
        	<section class="main">
        		<h4 class="acceso">Acceso sistema NETCODE - OCTANTIS v1.1</h4><br>
        		<p class="wn"><span class="icoerr icon-android-bulb"></span>Recuerde que para acceder por primera vez deberá registrarse con su correo corporativa y activar la cuenta desde el enlace enviado a la misma. </p><br>
        
                <?php echo resultBlock($errors);?>
            	<div class="ingreso">
        			<form id="loginform" class="form-horizontal" role="form" action='<?php $_SERVER['PHP_SELF'] ?>' method="POST" autocomplete="off">
        				<label for="textfield">Nombre de Usuario:</label><br>
                      	<input id="usuario" type="text" class="form-control" name="usuario" value="" placeholder="usuario o email" required><br>
                      	<label for="textfield">Contraseña:</label><br>
                      	<input id="password" type="password" class="form-control" name="password" placeholder="password" required><br><br>
                      	<input type="reset" value="Borrar">
                      	<input type="submit" value="Ingresar"><br>
                		<div class="recupera"><a class="recupera" href="recupera.php">Recupera tu contraseña</a></div>
                		<div style="font-size:15px;"> No tienes una cuenta? <a class="register" href="octregistro.php"> Regístrate aquí</a></div>
        			</form>	
				</div>
			</section>
		</div>
	<footer>

	</footer>
	</body>
</html>
