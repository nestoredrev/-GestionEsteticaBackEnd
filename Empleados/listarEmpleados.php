<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
    $data 		= json_decode( $formData );
    $idUser     = $data->idUser;
    $idUser     = $misqli->real_escape_string($idUser);


	$resultado = $misqli->query("SELECT id_empleado,
										nombre,
										apellido1,
										apellido2,
										telefono FROM empleados WHERE idUsuario = '".$idUser."' AND eliminado = 0 ORDER BY nombre");
	
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