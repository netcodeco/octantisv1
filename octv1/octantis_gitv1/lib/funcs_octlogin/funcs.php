<?php

	function isNull($nombre, $user, $pass, $pass_con, $email){
		if(strlen(trim($nombre)) < 1 || strlen(trim($user)) < 1 || strlen(trim($pass)) < 1 || strlen(trim($pass_con)) < 1 || strlen(trim($email)) < 1)
		{
			return true;
			} else {
			return false;
		}
	}

	function isEmail($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL)){
			return true;
			} else {
			return false;
		}
	}

	function validaPassword($var1, $var2)
	{
		if (strcmp($var1, $var2) !== 0){
			return false;
			} else {
			return true;
		}
	}

	function minMax($min, $max, $valor){
		if(strlen(trim($valor)) < $min)
		{
			return true;
		}
		else if(strlen(trim($valor)) > $max)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function usuarioExiste($usuario)
	{
		global $mysqli;

		$stmt = $mysqli->prepare("SELECT id FROM t_users WHERE usuario = ? LIMIT 1");
		$stmt->bind_param("s", $usuario);
		$stmt->execute();
		$stmt->store_result();
		$num = $stmt->num_rows;
		$stmt->close();

		if ($num > 0){
			return true;
			} else {
			return false;
		}
	}

	function emailExiste($email)
	{
		global $mysqli;

		$stmt = $mysqli->prepare("SELECT id FROM t_users WHERE correo = ? LIMIT 1");
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$stmt->store_result();
		$num = $stmt->num_rows;
		$stmt->close();

		if ($num > 0){
			return true;
			} else {
			return false;
		}
	}

	function generateToken()
	{
		$gen = md5(uniqid(mt_rand(), false));
		return $gen;
	}

	function hashPassword($password)
	{
		//$hash = password_hash($password, PASSWORD_DEFAULT);
		$hash = password_hash($password, PASSWORD_BCRYPT);
		return $hash;
	}

	function resultBlock($errors){
		if(count($errors) > 0)
		{
			echo "<div id='error' class='alerta' role='alert'><span class='icow icon-exclamation-triangle'>
			<a class='alerta' href='#' onclick=\"showHide('error');\"></span></a>
			<ul>";
			foreach($errors as $error)
			{
				echo "<li>".$error."</li>";
			}
			echo "</ul>";
			echo "</div>";
		}
	}

	function registraUsuario($usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario){

		global $mysqli;

		$stmt = $mysqli->prepare("INSERT INTO t_users (usuario, password, nombre, correo, activacion, token, id_tipo) VALUES(?,?,?,?,?,?,?)");
		$stmt->bind_param('ssssisi', $usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario);

		if ($stmt->execute()){
			return $mysqli->insert_id;
			} else {
			return 0;
		}
	}

	function enviarEmail($email, $nombre, $asunto, $cuerpo){
		require_once '../../vendors/PHPMailer/PHPMailerAutoload.php';
		$mail = new PHPMailer();
		//$mail->SMTPDebug = SMTP::DEBUG_SERVER;
    //$mail->SMTPDebug = 2; //Alternative to above constant
		$mail->isSMTP();
		$mail->SMTPAuth = true;
		//$mail->SMTPSecure = 'tls';
		$mail->SMTPSecure = 'ssl';
		$mail->Host = 'mail.netcode.com.co';
		//$mail->Host = 'localhost';
		//$mail->Port = '587';
			$mail->Port = '465';
		$mail->SMTPOptions = array(
 'ssl' => array(
  'verify_peer' => false,
  'verify_peer_name' => false,
  'allow_self_signed' => true
 ));

		$mail->Username = 'registro_octantis@netcode.com.co';
		$mail->Password = 'Colombia2020***';

		$mail->setFrom('registro_octantis@netcode.com.co', 'Sistema de Usuarios OCTANTIS WEB NETCODE');
		$mail->addAddress($email, $nombre);

		$mail->Subject = $asunto;
		$mail->Body    = $cuerpo;
		$mail->IsHTML(true);

		if($mail->send())
		return true;
		else
		return false;
	}

	function validaIdToken($id, $token){
		global $mysqli;

		$stmt = $mysqli->prepare("SELECT activacion FROM t_users WHERE id = ? AND token = ? LIMIT 1");
		$stmt->bind_param("is", $id, $token);
		$stmt->execute();
		$stmt->store_result();
		$rows = $stmt->num_rows;

		if($rows > 0) {
			$stmt->bind_result($activacion);
			$stmt->fetch();

			if($activacion == 1){
				$msg = "La cuenta ya se activó anteriormente.";
				} else {
				if(activarUsuario($id)){
					$msg = 'Estimado usuario, su cuenta se ha activado correctamente. Ahora podrá ingresar al sistema Octantis Web de Netcode para efectuar sus solicitudes de soporte en la infraestructura de sistermas y aplicaciones desarrolladas o gestionadas por Netcode. Bienvenid@!!';//incluir documento PHP bien estructurado.
					} else {
					$msg = 'Error al Activar Cuenta';
				}
			}
			} else {
			$msg = 'No existe el registro para activar.';
		}
		return $msg;
	}

	function activarUsuario($id)
	{
		global $mysqli;

		$stmt = $mysqli->prepare("UPDATE t_users SET activacion=1 WHERE id = ?");
		$stmt->bind_param('s', $id);
		$result = $stmt->execute();
		$stmt->close();
		return $result;
	}

	function isNullLogin($usuario, $password){
		if(strlen(trim($usuario)) < 1 || strlen(trim($password)) < 1)
		{
			//si son nulos envia true si no false
			return true;
		}
		else
		{
			return false;
		}
	}

	function login($usuario, $password)
	{
		global $mysqli;

		$stmt = $mysqli->prepare("SELECT id, usuario, id_tipo, password FROM t_users WHERE usuario = ? || correo = ? LIMIT 1");
		$stmt->bind_param("ss", $usuario, $usuario); //se emplea doble vez $usuario porque esta validando en el select ya sea el usuario o el correo.
		$stmt->execute();
		$stmt->store_result();
		$rows = $stmt->num_rows;

		if($rows > 0) {

			if(isActivo($usuario)){

				$stmt->bind_result($id, $us1, $id_tipo, $passwd);
				$stmt->fetch();
				//echo "El tipo de usuario es: " . $id_tipo . "<br>";
				$validaPassw = password_verify($password, $passwd);

				if($validaPassw){

							//=========================================================================================================
					//BEGINmodificacion perfiles 28 nov 2018
					//=========================================================================================================
						$id_tipo;
							switch(true){
												case($id_tipo===2)://pagina para clientes que van a gestionar casos.

													lastSession($id);
													$_SESSION['id_usuario'] = $id;
													$_SESSION['tipo_usuario'] = $id_tipo;
													$_SESSION['usuario']=$us1;
													header("Location: ../client01/welcome.php");
													exit;

													break;

												case($id_tipo===3): //pagina empleados FEBIMBO modo consultas sin mayores privilegios.
													lastSession($id);
													$_SESSION['id_usuario'] = $id;
													$_SESSION['tipo_usuario'] = $id_tipo;
													$_SESSION['usuario']=$us1;
													header("location: welcome_user_consulta.php");

													break;

												case($id_tipo===4): //pagina empleados FEBIMBO modo consultas con privilegios.
													lastSession($id);
													$_SESSION['id_usuario'] = $id;
													$_SESSION['tipo_usuario'] = $id_tipo;
													$_SESSION['usuario']=$us1;
													header("location: welcome_user_advanced.php");

													break;

													case($id_tipo===5): //Interfaz de administración Netcode, administrador aplicativo para necesidades especificas.
													lastSession($id);
													$_SESSION['id_usuario'] = $id;
													$_SESSION['tipo_usuario'] = $id_tipo;
													$_SESSION['usuario']=$us1;
													header("location: welcome_user_admin.php");

													break;
							}


					//=========================================================================================================
					//END Modificacion perfiles 28 nov
					//=========================================================================================================




						/*lastSession($id);
						$_SESSION['id_usuario'] = $id;
						$_SESSION['tipo_usuario'] = $id_tipo;
						$_SESSION['usuario']=$us1;
						header("location: welcome.php");*/

					} else {

					$errors = "La contraseña es incorrecta";
				}
				} else {
				$errors = 'El usuario no está activo';
			}
			} else {
			$errors = "El nombre de usuario o correo electrónico no existe";
		}
		return $errors;
	}

	function lastSession($id)
	{
		global $mysqli;

		$stmt = $mysqli->prepare("UPDATE t_users SET last_login=NOW(), token_password='', password_request=1 WHERE id = ?");
		$stmt->bind_param('s', $id);
		$stmt->execute();
		$stmt->close();
	}

	function isActivo($usuario)
	{
		global $mysqli;

		$stmt = $mysqli->prepare("SELECT activacion FROM t_users WHERE usuario = ? || correo = ? LIMIT 1");
		$stmt->bind_param('ss', $usuario, $usuario);
		$stmt->execute();
		$stmt->bind_result($activacion);
		$stmt->fetch();

		if ($activacion == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function generaTokenPass($user_id)
	{
		global $mysqli;

		$token = generateToken();

		$stmt = $mysqli->prepare("UPDATE t_users SET token_password=?, password_request=1 WHERE id = ?");
		$stmt->bind_param('ss', $token, $user_id);
		$stmt->execute();
		$stmt->close();

		return $token;
	}

	function getValor($campo, $campoWhere, $valor)
	{
		global $mysqli;

		$stmt = $mysqli->prepare("SELECT $campo FROM t_users WHERE $campoWhere = ? LIMIT 1");
		$stmt->bind_param('s', $valor);
		$stmt->execute();
		$stmt->store_result();
		$num = $stmt->num_rows;

		if ($num > 0)
		{
			$stmt->bind_result($_campo);
			$stmt->fetch();
			return $_campo;
		}
		else
		{
			return null;
		}
	}

	function getPasswordRequest($id)
	{
		global $mysqli;

		$stmt = $mysqli->prepare("SELECT password_request FROM t_users WHERE id = ?");
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($_id);
		$stmt->fetch();

		if ($_id == 1)
		{
			return true;
		}
		else
		{
			return null;
		}
	}

	function verificaTokenPass($user_id, $token){

		global $mysqli;

		$stmt = $mysqli->prepare("SELECT activacion FROM t_users WHERE id = ? AND token_password = ? AND password_request = 1 LIMIT 1");
		$stmt->bind_param('is', $user_id, $token);
		$stmt->execute();
		$stmt->store_result();
		$num = $stmt->num_rows;

		if ($num > 0)
		{
			$stmt->bind_result($activacion);
			$stmt->fetch();
			if($activacion == 1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	function cambiaPassword($password, $user_id, $token){

		global $mysqli;

		$stmt = $mysqli->prepare("UPDATE t_users SET password = ?, token_password='', password_request=0 WHERE id = ? AND token_password = ?");
		$stmt->bind_param('sis', $password, $user_id, $token);

		if($stmt->execute()){
			return true;
			} else {
			return false;
		}
	}
