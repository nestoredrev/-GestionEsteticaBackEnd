<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
	$data 		= json_decode( $formData );
	$idSeccion = $data->idSeccion;


	$resultado = $misqli->query("SELECT idElemento FROM servicios WHERE idSeccion='".$idSeccion."';");

	$numero_filas = $resultado->num_rows;
	if($numero_filas > 0)
	{
		echo -1;
		$resultado->close();
	}
	else
	{
		$misqli->query("UPDATE secciones SET eliminado = 1 WHERE id_seccion='".$idSeccion."';");
	}

    $misqli->close();

 ?>