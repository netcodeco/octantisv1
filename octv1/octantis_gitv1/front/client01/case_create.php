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
    <title>OCTANTIS Web case creation.</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>-->


  </head>

  <body>
    <header>

    </header>

    <div  class="contenedor">
      <section class="main">
        <div class="ingreso">

          <div>
            <p>
              <span class="hola"><?php echo 'Estimad@ '.utf8_encode(ucwords(strtolower($row['nombre']))) . ", "; ?> </span>a continuación podrá generar su solicitud de soporte, asegurese de diligenciar todos los campos con el mayor nivel de detalle de ser posible.
            </p>
          </div>
          <div><!-- A partir de este DIV va el formulario para creación de caso de soporte. -->
            <div>
              <label for="tipodesolicitud">Por favor seleccione el caso que desea reportar:</label>
              <select class="" name="tipo_solicitud" id="tipo_solicitud">
                <option value="0">------------------------</option>
                <option value="1">Solicitud de información.</option>
                <option value="2">Solicitud de servicio.</option>
                <option value="3">Reporte de incidente.</option>
              </select>
            </div>
            <div>
              <label for="impact">Este reporte afecta a:</label>
              <select class="" name="impacto" id="impacto">
                <option value="0">------------------</option>
                <option value="1">Negocio.</option>
                <option value="2">Departamento.</option>
                <option value="3">Más de un usuario.</option>
                <option value="4">Un usuario.</option>
              </select>
            </div>
            <div>
              <label for="descripción">Detalle de solicitud:</label>
              <input type="textarea" size="100" name="descripcion" id="descripcion" />
            </div>
            <div>
              <label for="medio_contacto">Medio de contacto inicial:</label>
              <select>
                <option value="0">------------------</option>
                <option value="1">Chat.</option>
                <option value="2">Correo electrónico.</option>
                <option value="3">Llamada telefónica.</option>
                <option value="4">SMS.</option>
                <option value="5">Aplicación web.</option>
              </select>
            </div>
            <div>
              <label for="medio_contacto">Nivel de criticidad:</label>
              <select>
                <option value="0">------------------</option>
                <option value="1">Muy Alta.</option>
                <option value="2">Alta.</option>
                <option value="3">Media.</option>
                <option value="4">Baja.</option>
              </select>
            </div>

            <div>
              
            </div>

            <div>
              <label for="a_anexo">Adjuntar archivo:</label>
              <input type="text" name="anexo" id="anexo" />
            </div>



          </div>
          <a href="welcome.php" class="button1">Volver a menú de inicio.</a>
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
