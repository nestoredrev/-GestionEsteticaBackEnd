<?php 
	
	include('../Conexion.php');
	$formData 	= file_get_contents('php://input');
	$data 		= json_decode($formData);

	$idSeccion	= $data->idSeccion;
	$idSeccion  = $misqli->real_escape_string($idSeccion);

	$resultado = $misqli->query("SELECT s.nombreServicio 
								FROM servicios AS s, elementos_comerciales  AS ec 
								WHERE idSeccion = '".$idSeccion."' AND ec.eliminado = 0 AND ec.id_elemento = s.idElemento 
								GROUP BY s.nombreServicio");

	$numero_filas = $resultado->num_rows;
	if($numero_filas > 0)
	{
		$set = array();

		while($fila = $resultado->fetch_assoc())
		{
			$set[] = $fila;
		}

		echo json_encode($set);

		$resultado->close();
	}
	else
	{
		//No hay resultados de la consulta
		echo -1;
		$resultado->close();
	}

	$misqli->close();

 ?>