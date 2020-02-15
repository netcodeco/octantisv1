<?php
require "../../lib/funcs_db/conexion.php";
require "../../lib/funcs_octlogin/funcs.php";

		$user_id = $mysqli->real_escape_string($_POST['user_id']);
		$token = $mysqli->real_escape_string($_POST['token']);
		$password = $mysqli->real_escape_string($_POST['password']);
		$con_password = $mysqli->real_escape_string($_POST['con_password']);

	if(validaPassword($password, $con_password)){

		$pass_hash = hashPassword($password);

		if(cambiaPassword($pass_hash, $user_id, $token)){
			echo "Contraseña Modificada";
			echo "<br> <a href='octlogin.php'>Iniciar Sesión</a>";
		} else {

			echo "Error al modificar contrase&ntilde;a";

		}

	} else {

		echo 'Las contraseñas no coinciden, por favor verifique nuevamente.';

	}

?>
