<?php 
	
	include('../Conexion.php');
	$formData 	= file_get_contents('php://input');
	$data 		= json_decode($formData);
	
	$id 	= $data->idSeccion;
	$nombre = $data->nombreServicio;

	$id 	= $misqli->real_escape_string($id);
	$nombre = $misqli->real_escape_string($nombre);

	$resultado = $misqli->query("SELECT ec.id_elemento,ec.nombreElemento,ec.precioVenta FROM elementos_comerciales AS ec, servicios AS s WHERE ec.id_elemento = s.idElemento AND nombreServicio = '".$nombre."' AND idSeccion = '".$id."' AND ec.eliminado = 0;");

	$set = array();

	while($fila = $resultado->fetch_assoc())
	{
		$set[] = $fila;
	}

	//JSON_NUMERIC_CHECK es necesario a la hora de codificar los numeros
	//si no los devuelve en el cliente como undefined
	echo json_encode($set,JSON_NUMERIC_CHECK);

	$resultado->close();

	$misqli->close();

 ?>