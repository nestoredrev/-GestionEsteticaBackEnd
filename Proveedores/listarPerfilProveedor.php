<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents('php://input');
	$data 		= json_decode($formData);

	$idProoveedor = $data->idProoveedor;
	$idProoveedor = $misqli->real_escape_string($idProoveedor);

	/* ----------------- Obtener informacion perfil del Proveedor ------------------- */
	$resultado = $misqli->query("SELECT nombre, telefono, email, localidad, direccion 
								 FROM proveedores 
								 WHERE id_proveedor = '".$idProoveedor."';");

	$set = array();

	while($fila = $resultado->fetch_assoc())
	{
		$set[] = $fila;
	}
	/* ----------------------------------------------------------------------------- */

	$resultado2 = $misqli->query("SELECT m.id_marca, m.nombre as nombreMarca
								  FROM marcas AS m, asignar_marca AS am 
								  WHERE idProveedor = '".$idProoveedor."' AND am.idMarca = m.id_marca AND m.eliminado = 0;");

	$set2 = array();

	while($fila2 = $resultado2->fetch_assoc())
	{
		$set2[] = $fila2;
	}

	// array de objetos
	$resultadoFinal = (array) array_merge($set,$set2);

	echo json_encode($resultadoFinal);

	/* liberar resultado obtenido */
	$resultado->close();
	$resultado2->close();

	$misqli->close();
 ?>