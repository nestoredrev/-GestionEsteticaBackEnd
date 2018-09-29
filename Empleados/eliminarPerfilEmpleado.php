<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
	$data 		= json_decode( $formData );
	$idEmpleado = $data->idEmpli;

    $misqli->query("UPDATE empleados SET eliminado = 1 WHERE id_empleado='".$idEmpleado."' ");

    $misqli->close();

 ?>