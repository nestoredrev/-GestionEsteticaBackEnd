<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents('php://input');
	$data 		= json_decode($formData);
	$idEmpleado = $data->idEmpli;


	$resultado = $misqli->query("SELECT id_empleado,
										nombre,
	                    				apellido1,
	                    				apellido2,
	                    				telefono FROM empleados WHERE id_empleado='".$idEmpleado."'");

	$set = array();

	//Obtener una fila de resultado como un array asociativo
	while ($fila = $resultado->fetch_assoc())
	{
	   $set = $fila;
	}

	echo json_encode($set);

	/* free result set */
	$resultado->close();

	/* close connection */
	$misqli->close();


 ?>