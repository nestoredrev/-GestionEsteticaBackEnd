<?php 

include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
	$data 		= json_decode( $formData );
	$idUser		= $data->idUser;
	$idUser		= $misqli->real_escape_string($idUser);

	$resultado = $misqli->query("SELECT usuario, email, nombreEmpresa, urlFoto 
								 FROM usuarios 
								 WHERE id_usuario = '".$idUser."' AND eliminado = 0;");

	$numero_filas = $resultado->num_rows;
	if($numero_filas > 0)
	{
		$set = array();

		while($fila = $resultado->fetch_assoc())
		{
			$set = $fila;
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