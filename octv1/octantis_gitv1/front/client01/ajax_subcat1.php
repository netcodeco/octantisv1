<?php
if(isset($_POST['id'])){
  require "../../lib/funcs_db/conexion2.php";
  $varbusqueda=$_POST['id'];
	//$user=new ApptivaDB();//lo mreemplaza el siguiente codigo en PDO:
    try{
      $base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $base->exec("SET CHARACTER SET utf8");
      $sql="SELECT id,nombre, categorias_id FROM subcat1 WHERE categorias_id=$varbusqueda";
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
    }catch(exception $e) {
      die('Error: ' . $e->GetMessage());
    }finally{
      $base=null;
    }
  }else{
    //No estan llegando datos mediante $_POST.
    echo "No se estan cargando datos desde el origen. Codigo error: AX_SC1_001";
  }
?>
