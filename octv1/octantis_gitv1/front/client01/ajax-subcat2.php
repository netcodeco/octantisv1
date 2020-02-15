<?php
if(isset($_POST['id'])):
	require "../../lib/funcs_db/conexion2.php";
  $varbusqueda2=$_POST['id'];

  try{
    $base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $base->exec("SET CHARACTER SET utf8");
    $sql="SELECT id,nombre FROM subcat2 WHERE subcat1_id=$varbusqueda2";
    //$u=buscar("subcat1","categorias_id=".$_POST['id']);
    //variable $resultado que almacena PDO statement
    $stmt=$base->prepare($sql);
    //ejecutar la funcion
    $result=$stmt->execute();
    $rows=$stmt->fetchAll(\PDO::FETCH_OBJ);
    $html="";
    foreach ($rows as $value){
      $html.="<option value='".$value->id."'>".$value->nombre."</option>";

  }
echo $html;
  $stmt->closecursor();
  }catch(exception $e)
      {
          die('Error: ' . $e->GetMessage());
      }finally{
          $base=null;
  }
else:
  //No estan llegando datos mediante $_POST.
  echo "No se estan cargando datos desde el origen. Codigo error: AX_SC2_001";
endif;
/*
  $user=new ApptivaDB();
	$u=$user->buscar("subcat2","subcat1_id=".$_POST['id']);
  $u=buscar("subcat2","subcat1_id=".$_POST['id']);
	$html="";
	foreach ($u as $value)
		$html.="<option value='".$value['id']."'>".$value['nombre']."</option>";
	echo $html;
endif;
*/
?>
