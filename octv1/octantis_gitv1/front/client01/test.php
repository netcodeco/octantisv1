 <?php
require "../../lib/funcs_db/conexion2.php";

?>

<!DOCTYPE html>
<html>
  <head>

    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>

  </head>
    <body>
      <select name="categorias" id="categorias" class="select_1">
        <?php
        try{
            //include("conexion2.php");
            $base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $base->exec("SET CHARACTER SET utf8");
            $sql="SELECT id,nombre FROM categorias";
            //variable $resultado que almacena PDO statement
            $stmt=$base->prepare($sql);
            //ejecutar la funcion
            $result=$stmt->execute();
            $rows=$stmt->fetchAll(\PDO::FETCH_OBJ);
            foreach($rows as $rowa){
        ?>
        <option value="<?php print($rowa->id);?>"><?php $describetasa=print($rowa->nombre);?></option>
        <?php
            }
            $stmt->closecursor();
            }catch(exception $e)
                {
                    die('Error: ' . $e->GetMessage());
                }finally{
                    $base=null;
            }
        ?>
      </select>
      <select name="subcat1" id="subcat1" class="select_1"></select>
      <select name="subcat2" id="subcat2" class="select_1"></select>

      <script>
       $(document).ready(function(e){
        $("#categorias").change(function(){
          var parametros= "id="+$("#categorias").val();
          $.ajax({
              data:  parametros,
              url:   'ajax_subcat1.php',
              type:  'post',
              beforeSend: function () { },
              success:  function (response) {
                  $("#subcat1").html(response);
              },
              error:function(){
                alert("error")
              }
          });
        })

        $("#subcat1").change(function(){
          //var parametros= "id="+$("#subcat1").val();CUANDO SE REQUIERE QUE LOS EVENTOS VAYAN EN CASCADA COMO EN EL CASO DE PAIS-CIUDAD-DEPARTAMENTO SI SE PODRÍA USAR DE ESTA MANERA, PERO COMO EN LAS TABLAS SE REQUIERE ASOCIAR LA TAREA DE ACUERDO A LA TABLA PADRE POR ESO SE REQUIERE USAR DOS VECES LA MISMA VARIABLE #CATEGORIAS.
          var parametros= "id="+$("#categorias").val();
          $.ajax({
                    data:  parametros,
                    url:   'ajax-subcat2.php',
                    type:  'post',
                    beforeSend: function () { },
                    success:  function (response) {
                        $("#subcat2").html(response);
                    },
                    error:function(){
                      alert("error")
                    }
                });

        })

      })

      </script>
    </body>
</html>


<!-- asociado al jandler.js comentareado util para select doble. NO triple<!DOCTYPE html>
<html>
  <body>
    <form id="form1">
      <select id="select1" onclick="cambiaForm2(this.value)">
        <option value = "0" selected> </option>
        <option value = "A">A</option>
        <option value = "B">B</option>
        <option value = "C">C</option>
      </select>
    </form>

    <form id="form2">
      <select id="select2"></select>
    </form>
    <form id="form3">
      <select id="select3"></select>
    </form>
    <script src="handler.js"></script>
  </body>
</html>-->
