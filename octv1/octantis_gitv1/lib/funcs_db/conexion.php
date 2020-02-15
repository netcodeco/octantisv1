<?php
	$mysqli=new mysqli("localhost","usfinocta01","5Y50ct@nT1Sk0Ns3#*","octantisv1");

	if(mysqli_connect_errno()){
		echo 'Conexion Fallida : ', mysqli_connect_error();
		exit();
	}
?>
