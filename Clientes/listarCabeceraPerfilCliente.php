<?php 

include('../Conexion.php');

	$formData 	= file_get_contents('php://input');
	$data 		= json_decode($formData);
	$idCliente = $data->idCli;


	$resultado = $misqli->query("SELECT id_cliente,
										nombre,
	                    				apellido1,
	                    				apellido2,
	                    				telefono,
	                    				email,
	                    				urlFoto, 
	                    				idTipo FROM clientes WHERE id_cliente='".$idCliente."'");

	$set = array();

	//Obtener una fila de resultado como un array asociativo
	while ($fila = $resultado->fetch_assoc())
	{
	   $set[] = $fila;
	}

	echo json_encode($set);

	/* free result set */
	$resultado->close();

	$misqli->close(); 

?>