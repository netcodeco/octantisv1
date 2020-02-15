<?php
session_start();
require '../../lib/funcs_db/conexion.php';
require '../../lib/funcs_octlogin/funcs.php';

if (!isset($_SESSION["id_usuario"])){
  header('Location:../login/octlogin.php');
}

$idUsuario=$_SESSION["id_usuario"];
$us1 = $_SESSION["usuario"];
$sql = "SELECT id, nombre, id_tipo FROM t_users WHERE id='$idUsuario'";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();

//Verificar que corresponda al tipo de usuario requerido.
$verifica_id = $row['id_tipo'];
settype($verifica_id, 'integer');//Se define de esta manera para hacer la comparación estricta.

if ($verifica_id !== 2){
  header('Location: ../login/octlogin.php');
}

?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Welcome OCTANTIS Web.</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <script  src="../js/main1.js"></script>
  </head>

  <body>
    <header>

    </header>

    <div  class="contenedor">
      <section class="main">
        <div class="ingreso">
          <span class="hola"><?php echo 'Bienvenid@ '.utf8_encode(ucwords(strtolower($row['nombre']))); ?> </span>
          <div>
            <a href="case_create.php">Crear nueva solicitud de soporte</a>
          </div>
          <div>
            <a href="case_history.php">Consultar mis solicitudes de soporte.</a>
          </div>
          <div>
            <a href="us01_profile_edit.php">Editar mi perfil de usuario</a>
          </div>
          <div>
            <a href="support_docs.php">Consultar documentación de soporte.</a>
          </div>
          <div>
            <a href="state_services.php">Estado de plataformas y servicios</a>
          </div>
          <div>
            <a href="bclient_profile.php">Perfil de la organización</a>
          </div>
          <a href="../login/octlogin.php" class="button1">Volver a Inicio</a>
        </div>
        <div class="salir">
        		<div><a href="../login/logout.php"><button class="exit">Cerrar Sesión</button></a></div>
      	</div>
      </section>
    </div>
    <footer>

    </footer>
  </body>
</html>
