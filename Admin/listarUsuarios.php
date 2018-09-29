<?php 

	include('../Conexion.php');

	$resultado = $misqli->query("SELECT id_usuario,
					usuario,
					email,
					eliminado,
					urlFoto FROM usuarios WHERE tipoUsuario=11 ORDER BY usuario");

	$numero_filas = $resultado->num_rows;
	if($numero_filas > 0)
	{
		$set = array();
		//Obtener una fila de resultado como un array asociativo
		while ($fila = $resultado->fetch_assoc())
		{
		   $set[] = $fila;
		}

		echo json_encode($set);

		/* free result set */
		$resultado->close();
	}
	else
	{
		//No hay resultados de la consulta
		echo -1;
		/* free result set */
		$resultado->close();
	}

	$misqli->close();

 ?>